<?php

namespace App\Services;

use Carbon\Carbon;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class TicketmasterService
{
    private string $apiKey;
    private string $baseUrl = 'https://app.ticketmaster.com/discovery/v2';

    public function __construct()
    {
        $this->apiKey = config('services.ticketmaster.key', '');
    }

    public function isConfigured(): bool
    {
        return !empty($this->apiKey);
    }

    /**
     * Search events by city / keyword / classification.
     */
    public function searchEvents(array $params = [], int $page = 0): array
    {
        if (!$this->isConfigured()) {
            return ['events' => [], 'total' => 0, 'pages' => 0];
        }

        $cacheKey = 'tm_search_' . md5(json_encode([$params, $page]));

        return Cache::remember($cacheKey, 1800, function () use ($params, $page) {
            try {
                $response = Http::timeout(10)->get("{$this->baseUrl}/events.json", array_merge([
                    'apikey' => $this->apiKey,
                    'size'   => 12,
                    'page'   => $page,
                    'sort'   => 'date,asc',
                ], $params));

                if (!$response->successful()) {
                    Log::warning('Ticketmaster API error', ['status' => $response->status(), 'body' => $response->body()]);
                    return ['events' => [], 'total' => 0, 'pages' => 0];
                }

                $data   = $response->json();
                $events = $data['_embedded']['events'] ?? [];

                return [
                    'events' => array_map([$this, 'normalize'], $events),
                    'total'  => $data['page']['totalElements'] ?? 0,
                    'pages'  => $data['page']['totalPages'] ?? 1,
                    'page'   => $data['page']['number'] ?? 0,
                ];
            } catch (\Throwable $e) {
                Log::error('Ticketmaster request failed', ['error' => $e->getMessage()]);
                return ['events' => [], 'total' => 0, 'pages' => 0];
            }
        });
    }

    /**
     * Get single event by Ticketmaster ID.
     */
    public function getEvent(string $id): ?array
    {
        if (!$this->isConfigured()) return null;

        $cacheKey = 'tm_event_' . $id;

        return Cache::remember($cacheKey, 3600, function () use ($id) {
            try {
                $response = Http::timeout(10)->get("{$this->baseUrl}/events/{$id}.json", [
                    'apikey' => $this->apiKey,
                ]);

                if (!$response->successful()) return null;

                return $this->normalize($response->json());
            } catch (\Throwable $e) {
                Log::error('Ticketmaster getEvent failed', ['id' => $id, 'error' => $e->getMessage()]);
                return null;
            }
        });
    }

    /**
     * Get featured events for homepage.
     */
    public function getFeaturedEvents(string $city = '', int $size = 6): array
    {
        $params = ['size' => $size, 'classificationName' => 'Music,Sports,Arts & Theatre'];
        if ($city) {
            $params['city'] = $city;
        }

        $result = $this->searchEvents($params);
        return $result['events'];
    }

    /**
     * Get events by city for homepage upcoming section.
     */
    public function getUpcomingByCity(string $city, int $size = 8): array
    {
        $result = $this->searchEvents([
            'city' => $city,
            'size' => $size,
            'sort' => 'date,asc',
        ]);
        return $result['events'];
    }

    /**
     * Normalize Ticketmaster event to a consistent array shape.
     */
    public function normalize(array $event): array
    {
        $venue = $event['_embedded']['venues'][0] ?? [];
        $images = $event['images'] ?? [];
        usort($images, fn($a, $b) => ($b['width'] ?? 0) - ($a['width'] ?? 0));
        $image = $images[0] ?? [];

        $price    = $event['priceRanges'][0] ?? null;
        $classif  = $event['classifications'][0] ?? [];
        $segment  = $classif['segment']['name'] ?? 'Event';
        $genre    = $classif['genre']['name'] ?? null;

        $startDateTime = null;
        if (!empty($event['dates']['start']['dateTime'])) {
            $startDateTime = Carbon::parse($event['dates']['start']['dateTime']);
        } elseif (!empty($event['dates']['start']['localDate'])) {
            $d = $event['dates']['start']['localDate'];
            $t = $event['dates']['start']['localTime'] ?? '00:00:00';
            $startDateTime = Carbon::parse("{$d} {$t}");
        }

        $cityName  = $venue['city']['name'] ?? ($event['_embedded']['venues'][0]['city']['name'] ?? 'TBD');
        $stateName = $venue['state']['stateCode'] ?? '';
        $country   = $venue['country']['countryCode'] ?? 'US';
        $venueName = $venue['name'] ?? 'Venue TBD';
        $address   = trim(($venue['address']['line1'] ?? '') . ', ' . $cityName);

        return [
            'id'              => $event['id'],
            'slug'            => 'tm-' . $event['id'],
            'title'           => $event['name'],
            'description'     => $event['info'] ?? ($event['pleaseNote'] ?? 'Visit the event page for full details about this event.'),
            'image_url'       => $image['url'] ?? "https://picsum.photos/seed/{$event['id']}/800/400",
            'start_date'      => $startDateTime,
            'start_date_raw'  => $event['dates']['start']['localDate'] ?? null,
            'start_time_raw'  => $event['dates']['start']['localTime'] ?? null,
            'tbd'             => $event['dates']['start']['timeTBA'] ?? false,
            'city'            => $cityName,
            'state'           => $stateName,
            'country'         => $country,
            'location'        => $venueName,
            'address'         => $address,
            'external_url'    => $event['url'] ?? '#',
            'is_free'         => $price === null,
            'price'           => $price ? (float)($price['min'] ?? 0) : null,
            'price_max'       => $price ? (float)($price['max'] ?? 0) : null,
            'currency'        => $price['currency'] ?? 'USD',
            'category'        => $segment,
            'genre'           => $genre,
            'source'          => 'ticketmaster',
            'is_featured'     => false,
            'likes_count'     => 0,
            'rsvp_count'      => 0,
            'views_count'     => 0,
            'status'          => 'published',
            'max_attendees'   => null,
            'tags'            => array_filter([$genre, $cityName, strtolower($segment)]),
            'user'            => null,
        ];
    }
}
