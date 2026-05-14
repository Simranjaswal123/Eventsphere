<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Event;
use App\Services\EventbriteService;
use App\Services\GoogleEventsService;
use App\Services\TicketmasterService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cookie;

class HomeController extends Controller
{
    public function __construct(
        private TicketmasterService  $ticketmaster,
        private EventbriteService    $eventbrite,
        private GoogleEventsService  $google,
    ) {}

    public function index(Request $request)
    {
        $lastCity       = Cookie::get('last_city', '');
        $worldEvents    = [];
        $boardEvents    = [];
        $upcomingEvents = [];
        $usingRealData  = $this->ticketmaster->isConfigured() || $this->eventbrite->isConfigured() || $this->google->isConfigured();

        if ($usingRealData) {
            // Carousel: featured/popular global events
            $worldEvents    = $this->mergeFeatured('', 12);
            // Board: upcoming chronological — different sort, offset to avoid overlap
            $boardEvents    = $this->mergeUpcoming('', 12);
            // City upcoming section
            $upcomingEvents = $this->mergeUpcoming($lastCity, 8);
        } else {
            try {
                $worldEvents    = Event::published()->featured()->upcoming()->with('user', 'category')->limit(12)->get();
                $boardEvents    = Event::published()->upcoming()->with('user', 'category')->orderBy('start_date')->skip(4)->limit(12)->get()->all();
                $upcomingEvents = Event::published()->upcoming()->with('user', 'category')->orderBy('start_date')->limit(8)->get();
            } catch (\Throwable $e) {
                \Illuminate\Support\Facades\Log::warning('MongoDB home fallback failed: ' . $e->getMessage());
            }
        }

        try {
            $categories = Category::where('is_active', true)->get();
        } catch (\Throwable $e) {
            $categories = collect();
        }

        return view('home', compact('worldEvents', 'boardEvents', 'upcomingEvents', 'categories', 'lastCity', 'usingRealData'));
    }

    public function about()
    {
        return view('about');
    }

    public function search(Request $request)
    {
        $query  = $request->get('q', '');
        $city   = $request->get('city', '');
        $events = [];
        $total  = 0;

        if ($query || $city) {
            if ($this->ticketmaster->isConfigured() || $this->eventbrite->isConfigured() || $this->google->isConfigured()) {
                $params = ['size' => 12, 'keyword' => $query, 'city' => $city];
                $page   = max(1, (int)$request->get('page', 1));
                $merged = $this->mergeSearch($params, $page);
                $events = $merged['events'];
                $total  = $merged['total'];
            } else {
                try {
                    $q = Event::published();
                    if ($query) {
                        $q->where(function ($qb) use ($query) {
                            $qb->where('title', 'like', "%{$query}%")
                               ->orWhere('description', 'like', "%{$query}%")
                               ->orWhere('city', 'like', "%{$query}%");
                        });
                    }
                    if ($city) $q->where('city', 'like', "%{$city}%");
                    $paginated = $q->with('user', 'category')->orderBy('start_date')->paginate(12);
                    $events    = $paginated->items();
                    $total     = $paginated->total();
                } catch (\Throwable $e) {}
            }
        }

        return view('search', compact('events', 'query', 'city', 'total'));
    }

    public function liveSearch(Request $request)
    {
        $city    = $request->get('city', '');
        $keyword = $request->get('q', '');

        if (!$city && !$keyword) {
            return response()->json(['events' => [], 'total' => 0]);
        }

        $params = ['size' => 5, 'keyword' => $keyword, 'city' => $city];
        $merged = $this->mergeSearch($params, 1);

        return response()->json(['events' => array_slice($merged['events'], 0, 5), 'total' => $merged['total']]);
    }

    // ─── Private helpers ─────────────────────────────────────────────────────

    private function mergeFeatured(string $city, int $size): array
    {
        $tm = $this->ticketmaster->isConfigured()
            ? $this->ticketmaster->getFeaturedEvents($city, $size)
            : [];

        $eb = $this->eventbrite->isConfigured()
            ? $this->eventbrite->getFeaturedEvents($city, $size)
            : [];

        $ge = $this->google->isConfigured()
            ? $this->google->getFeaturedEvents($city, $size)
            : [];

        return $this->blend($tm, $eb, $ge, $size);
    }

    private function mergeUpcoming(string $city, int $size): array
    {
        $params = ['size' => $size];
        if ($city) $params['city'] = $city;

        $tm = $this->ticketmaster->isConfigured()
            ? ($city
                ? $this->ticketmaster->getUpcomingByCity($city, $size)
                : $this->ticketmaster->searchEvents(['size' => $size, 'sort' => 'date,asc'])['events'])
            : [];

        $eb = $this->eventbrite->isConfigured()
            ? $this->eventbrite->searchEvents($params)['events']
            : [];

        $ge = $this->google->isConfigured()
            ? $this->google->searchEvents($params)['events']
            : [];

        return $this->blend($tm, $eb, $ge, $size);
    }

    private function mergeSearch(array $params, int $page): array
    {
        $tmPage = max(0, $page - 1);

        $tmResult = $this->ticketmaster->isConfigured()
            ? $this->ticketmaster->searchEvents(array_filter([
                'keyword' => $params['keyword'] ?? null,
                'city'    => $params['city'] ?? null,
                'size'    => $params['size'] ?? 12,
              ]), $tmPage)
            : ['events' => [], 'total' => 0, 'pages' => 0];

        $ebResult = $this->eventbrite->isConfigured()
            ? $this->eventbrite->searchEvents(array_filter([
                'keyword' => $params['keyword'] ?? null,
                'city'    => $params['city'] ?? null,
                'size'    => $params['size'] ?? 12,
              ]), $page)
            : ['events' => [], 'total' => 0, 'pages' => 0];

        $geResult = $this->google->isConfigured()
            ? $this->google->searchEvents([
                'keyword' => $params['keyword'] ?? null,
                'city'    => $params['city'] ?? null,
                'size'    => $params['size'] ?? 12,
              ], $page)
            : ['events' => [], 'total' => 0, 'pages' => 0];

        $size   = $params['size'] ?? 12;
        $merged = $this->blend($tmResult['events'], $ebResult['events'], $geResult['events'], $size * 3);

        return [
            'events' => $merged,
            'total'  => ($tmResult['total'] ?? 0) + ($ebResult['total'] ?? 0) + ($geResult['total'] ?? 0),
            'pages'  => max($tmResult['pages'] ?? 1, $ebResult['pages'] ?? 1, $geResult['pages'] ?? 1),
        ];
    }

    private function blend(array $tm, array $eb, array $ge, int $limit): array
    {
        $all = array_merge($tm, $eb, $ge);

        usort($all, function ($a, $b) {
            $da = $a['start_date'] ?? null;
            $db = $b['start_date'] ?? null;
            if (!$da && !$db) return 0;
            if (!$da) return 1;
            if (!$db) return -1;
            return $da <=> $db;
        });

        return array_slice($all, 0, $limit);
    }
}
