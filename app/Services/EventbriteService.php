<?php

namespace App\Services;

use Carbon\Carbon;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class EventbriteService
{
    private string $token;
    private string $baseUrl = 'https://www.eventbriteapi.com/v3';

    public function __construct()
    {
        $this->token = config('services.eventbrite.key', '');
    }

    public function isConfigured(): bool
    {
        return !empty($this->token);
    }

    /**
     * Search events by city / keyword.
     * Returns same array shape as TicketmasterService::searchEvents().
     */
    public function searchEvents(array $params = [], int $page = 1): array
    {
        if (!$this->isConfigured()) {
            return ['events' => [], 'total' => 0, 'pages' => 0];
        }

        $cacheKey = 'eb_search_' . md5(json_encode([$params, $page]));

        return Cache::remember($cacheKey, 1800, function () use ($params, $page) {
            try {
                $query = [
                    'expand'          => 'venue,category,ticket_classes',
                    'page'            => $page,
                    'page_size'       => $params['size'] ?? 12,
                    'sort_by'         => 'date',
                    'status'          => 'live',
                    'start_date.range_start' => now()->toIso8601String(),
                ];

                if (!empty($params['keyword'])) {
                    $query['q'] = $params['keyword'];
                }

                // Location — Eventbrite uses location.address or location.within + location.latitude/longitude
                if (!empty($params['city'])) {
                    $query['location.address'] = $params['city'];
                    $query['location.within']  = '50km';
                }

                if (!empty($params['is_free'])) {
                    $query['price'] = 'free';
                }

                $response = Http::withToken($this->token)
                    ->timeout(10)
                    ->get("{$this->baseUrl}/events/search/", $query);

                if (!$response->successful()) {
                    Log::warning('Eventbrite API error', [
                        'status' => $response->status(),
                        'body'   => $response->body(),
                    ]);
                    return ['events' => [], 'total' => 0, 'pages' => 0];
                }

                $data   = $response->json();
                $events = $data['events'] ?? [];

                return [
                    'events' => array_filter(array_map([$this, 'normalize'], $events)),
                    'total'  => $data['pagination']['object_count'] ?? 0,
                    'pages'  => $data['pagination']['page_count'] ?? 1,
                    'page'   => $data['pagination']['page_number'] ?? 1,
                ];
            } catch (\Throwable $e) {
                Log::error('Eventbrite request failed', ['error' => $e->getMessage()]);
                return ['events' => [], 'total' => 0, 'pages' => 0];
            }
        });
    }

    /**
     * Get single event by Eventbrite ID.
     */
    public function getEvent(string $id): ?array
    {
        if (!$this->isConfigured()) return null;

        $cacheKey = 'eb_event_' . $id;

        return Cache::remember($cacheKey, 3600, function () use ($id) {
            try {
                $response = Http::withToken($this->token)
                    ->timeout(10)
                    ->get("{$this->baseUrl}/events/{$id}/", [
                        'expand' => 'venue,category,ticket_classes',
                    ]);

                if (!$response->successful()) return null;

                return $this->normalize($response->json());
            } catch (\Throwable $e) {
                Log::error('Eventbrite getEvent failed', ['id' => $id, 'error' => $e->getMessage()]);
                return null;
            }
        });
    }

    /**
     * Get featured / upcoming events for a city.
     */
    public function getFeaturedEvents(string $city = '', int $size = 6): array
    {
        $params = ['size' => $size];
        if ($city) $params['city'] = $city;

        return $this->searchEvents($params)['events'];
    }

    /**
     * Normalize Eventbrite event to the shared flat array shape.
     */
    public function normalize(array $event): ?array
    {
        if (empty($event['id'])) return null;

        $venue    = $event['venue'] ?? [];
        $address  = $venue['address'] ?? [];
        $logo     = $event['logo'] ?? [];
        $tickets  = $event['ticket_classes'] ?? [];
        $category = $event['category'] ?? [];

        // Date
        $startDateTime = null;
        if (!empty($event['start']['local'])) {
            try { $startDateTime = Carbon::parse($event['start']['local']); } catch (\Throwable $e) {}
        }

        // Price
        $isFree  = $event['is_free'] ?? false;
        $price   = null;
        $priceMax = null;

        if (!$isFree && !empty($tickets)) {
            $costs = array_filter(array_map(fn($t) => isset($t['cost']['major_value']) ? (float)$t['cost']['major_value'] : null, $tickets));
            if ($costs) {
                $price    = min($costs);
                $priceMax = max($costs);
            }
        }

        $cityName  = $address['city'] ?? ($address['region'] ?? 'Online');
        $stateName = $address['region'] ?? '';
        $country   = $address['country'] ?? '';
        $venueName = $venue['name'] ?? 'Online / TBD';
        $fullAddr  = trim(($address['localized_address_display'] ?? $address['address_1'] ?? ''));

        // Image — prefer 800px+
        $imageUrl = null;
        if (!empty($logo['original']['url'])) {
            $imageUrl = $logo['original']['url'];
        } elseif (!empty($logo['url'])) {
            $imageUrl = $logo['url'];
        } else {
            $imageUrl = "https://picsum.photos/seed/eb{$event['id']}/800/400";
        }

        $title    = $event['name']['text'] ?? 'Untitled Event';
        $desc     = $event['description']['text'] ?? ($event['summary'] ?? 'Visit the event page for more details.');
        $genre    = $category['name'] ?? null;
        $tags     = array_filter([$genre, $cityName, 'eventbrite']);

        return [
            'id'             => $event['id'],
            'slug'           => 'eb-' . $event['id'],
            'title'          => $title,
            'description'    => $desc,
            'image_url'      => $imageUrl,
            'start_date'     => $startDateTime,
            'start_date_raw' => $event['start']['local'] ?? null,
            'start_time_raw' => $startDateTime ? $startDateTime->format('H:i:s') : null,
            'tbd'            => false,
            'city'           => $cityName,
            'state'          => $stateName,
            'country'        => $country,
            'location'       => $venueName,
            'address'        => $fullAddr,
            'external_url'   => $event['url'] ?? '#',
            'is_free'        => $isFree,
            'price'          => $price,
            'price_max'      => $priceMax,
            'currency'       => 'USD',
            'category'       => $genre ?? 'Event',
            'genre'          => $genre,
            'source'         => 'eventbrite',
            'is_featured'    => false,
            'likes_count'    => 0,
            'rsvp_count'     => 0,
            'views_count'    => 0,
            'status'         => 'published',
            'max_attendees'  => null,
            'tags'           => array_values($tags),
            'user'           => null,
        ];
    }
}
