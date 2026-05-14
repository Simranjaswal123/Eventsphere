<?php

namespace App\Services;

use Carbon\Carbon;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class GoogleEventsService
{
    private string $apiKey;
    private string $baseUrl = 'https://serpapi.com/search.json';

    public function __construct()
    {
        $this->apiKey = config('services.serpapi.key', '');
    }

    public function isConfigured(): bool
    {
        return !empty($this->apiKey);
    }

    public function searchEvents(array $params = [], int $page = 1): array
    {
        if (!$this->isConfigured()) {
            return ['events' => [], 'total' => 0, 'pages' => 1];
        }

        $cacheKey = 'ge_search_' . md5(json_encode([$params, $page]));

        return Cache::remember($cacheKey, 1800, function () use ($params, $page) {
            try {
                $city    = $params['city'] ?? '';
                $keyword = $params['keyword'] ?? '';
                $size    = $params['size'] ?? 12;

                $q = $keyword
                    ? "{$keyword} events" . ($city ? " in {$city}" : '')
                    : ($city ? "events in {$city}" : 'upcoming events');

                $query = [
                    'engine'   => 'google_events',
                    'q'        => $q,
                    'api_key'  => $this->apiKey,
                    'hl'       => 'en',
                    'gl'       => 'in',  // India locale for better local results
                    'start'    => ($page - 1) * 10,
                ];

                $response = Http::timeout(12)->get($this->baseUrl, $query);

                if (!$response->successful()) {
                    Log::warning('GoogleEvents API error', [
                        'status' => $response->status(),
                        'body'   => substr($response->body(), 0, 500),
                    ]);
                    return ['events' => [], 'total' => 0, 'pages' => 1];
                }

                $data   = $response->json();
                $raw    = $data['events_results'] ?? [];

                $events = array_values(array_filter(array_map(
                    fn($e) => $this->normalize($e, $city),
                    array_slice($raw, 0, $size)
                )));

                // Cache each event individually for show page lookups
                foreach ($events as $event) {
                    Cache::put('ge_event_' . $event['id'], $event, 1800);
                }

                $total = count($raw) >= 10 ? 100 : count($raw); // Google Events doesn't expose total
                $pages = $total > 10 ? 5 : 1;

                return ['events' => $events, 'total' => $total, 'pages' => $pages];
            } catch (\Throwable $e) {
                Log::error('GoogleEvents request failed', ['error' => $e->getMessage()]);
                return ['events' => [], 'total' => 0, 'pages' => 1];
            }
        });
    }

    public function getEvent(string $id): ?array
    {
        // Events are cached during search; retrieve from cache
        $cached = Cache::get('ge_event_' . $id);
        if ($cached) return $cached;

        // Cache miss — do a generic search and hope the event appears
        return null;
    }

    public function getFeaturedEvents(string $city = '', int $size = 6): array
    {
        $params = ['size' => $size];
        if ($city) $params['city'] = $city;
        return $this->searchEvents($params)['events'];
    }

    private function normalize(array $event, string $searchCity = ''): ?array
    {
        $title = $event['title'] ?? null;
        if (!$title) return null;

        // Stable ID: hash of the event link (most unique field)
        $link = $event['link'] ?? '';
        $id   = substr(md5($link ?: $title), 0, 16);

        // Date parsing
        $startDateTime = null;
        $when = $event['date']['when'] ?? ($event['date']['start_date'] ?? '');
        if ($when) {
            try {
                // "Jan 15, 7:30 PM" or "Saturday, January 15" or "Jan 15 – 16"
                $clean = preg_replace('/\s*–.*$/', '', $when); // strip end-range
                $startDateTime = Carbon::parse($clean);
                // If parsed year is in the past, bump to next year
                if ($startDateTime->isPast() && !str_contains($when, (string)date('Y'))) {
                    $startDateTime->addYear();
                }
            } catch (\Throwable $e) {
                $startDateTime = null;
            }
        }

        // Address
        $addressParts = $event['address'] ?? [];
        $venueName    = $addressParts[0] ?? 'Venue TBD';
        $cityLine     = $addressParts[1] ?? $searchCity;

        // Parse "Mumbai, Maharashtra 400001, India"
        $city  = $searchCity;
        $state = '';
        if ($cityLine) {
            $parts = array_map('trim', explode(',', $cityLine));
            $city  = $parts[0] ?? $searchCity;
            $state = $parts[1] ?? '';
        }

        $fullAddress = implode(', ', array_filter($addressParts));

        // Ticket / price info
        $ticketInfo  = $event['ticket_info'] ?? [];
        $externalUrl = $link;
        if (!$externalUrl && !empty($ticketInfo)) {
            $externalUrl = $ticketInfo[0]['link'] ?? '#';
        }
        $externalUrl = $externalUrl ?: '#';

        // Image
        $imageUrl = $event['image']
            ?? ($event['thumbnail']
            ?? "https://picsum.photos/seed/ge{$id}/800/400");

        // Description
        $desc = $event['description'] ?? 'Visit the event page for more details.';

        $tags = array_values(array_filter([$city, $state, 'google-events']));

        return [
            'id'             => $id,
            'slug'           => 'ge-' . $id,
            'title'          => $title,
            'description'    => $desc,
            'image_url'      => $imageUrl,
            'start_date'     => $startDateTime,
            'start_date_raw' => $when,
            'start_time_raw' => $startDateTime ? $startDateTime->format('H:i:s') : null,
            'tbd'            => false,
            'city'           => $city,
            'state'          => $state,
            'country'        => 'IN',
            'location'       => $venueName,
            'address'        => $fullAddress,
            'external_url'   => $externalUrl,
            'is_free'        => false,
            'price'          => null,
            'price_max'      => null,
            'currency'       => 'INR',
            'category'       => 'Event',
            'genre'          => null,
            'source'         => 'google',
            'is_featured'    => false,
            'likes_count'    => 0,
            'rsvp_count'     => 0,
            'views_count'    => 0,
            'status'         => 'published',
            'max_attendees'  => null,
            'tags'           => $tags,
            'ticket_info'    => $ticketInfo,
            'user'           => null,
        ];
    }
}
