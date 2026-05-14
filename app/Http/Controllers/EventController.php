<?php

namespace App\Http\Controllers;

use App\Http\Requests\EventRequest;
use App\Models\Category;
use App\Models\Event;
use App\Models\Rsvp;
use App\Services\EventbriteService;
use App\Services\GoogleEventsService;
use App\Services\TicketmasterService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class EventController extends Controller
{
    public function __construct(
        private TicketmasterService  $ticketmaster,
        private EventbriteService    $eventbrite,
        private GoogleEventsService  $google,
    ) {}

    public function index(Request $request)
    {
        $city     = $request->get('city', '');
        $keyword  = $request->get('search', $request->get('q', ''));
        $category = $request->get('category', '');
        $price    = $request->get('price', '');
        $types    = array_values(array_filter((array) $request->get('type', [])));
        $page     = max(1, (int)$request->get('page', 1));

        $events       = [];
        $total        = 0;
        $totalPages   = 1;
        $usingRealData = $this->ticketmaster->isConfigured() || $this->eventbrite->isConfigured() || $this->google->isConfigured();

        if ($usingRealData) {
            $tmPage  = max(0, $page - 1);
            $params  = array_filter([
                'keyword' => $keyword ?: null,
                'city'    => $city    ?: null,
                'size'    => 12,
            ]);

            if (!empty($types)) {
                $params['classificationName'] = $this->mapType($types[0]);
            } elseif ($category) {
                $params['classificationName'] = $this->mapCategory($category);
            }

            $tmResult = $this->ticketmaster->isConfigured()
                ? $this->ticketmaster->searchEvents($params, $tmPage)
                : ['events' => [], 'total' => 0, 'pages' => 1];

            $ebParams = array_filter([
                'keyword' => $keyword ?: null,
                'city'    => $city    ?: null,
                'size'    => 12,
                'is_free' => $price === 'free' ?: null,
            ]);

            $ebResult = $this->eventbrite->isConfigured()
                ? $this->eventbrite->searchEvents($ebParams, $page)
                : ['events' => [], 'total' => 0, 'pages' => 1];

            $geResult = $this->google->isConfigured()
                ? $this->google->searchEvents([
                    'keyword' => $keyword ?: null,
                    'city'    => $city    ?: null,
                    'size'    => 12,
                  ], $page)
                : ['events' => [], 'total' => 0, 'pages' => 1];

            // Merge + sort by date
            $all = array_merge($tmResult['events'], $ebResult['events'], $geResult['events']);
            usort($all, function ($a, $b) {
                $da = $a['start_date'] ?? null;
                $db = $b['start_date'] ?? null;
                if (!$da && !$db) return 0;
                if (!$da) return 1;
                if (!$db) return -1;
                return $da <=> $db;
            });

            // PHP-side type filtering: catches EB/GE events the API couldn't filter
            if (!empty($types)) {
                $all = array_values(array_filter($all, function ($event) use ($types) {
                    foreach ($types as $t) {
                        if ($this->typeMatchesEvent($t, $event)) return true;
                    }
                    return false;
                }));
            }

            $events     = $all;
            $total      = ($tmResult['total'] ?? 0) + ($ebResult['total'] ?? 0) + ($geResult['total'] ?? 0);
            $totalPages = $total > 0 ? (int) ceil($total / 12) : 1;
            // hasNextPage based on actual returned events — avoids showing wrong "X of Y" for API data
            $hasNextPage = count($events) >= 12;
        } else {
            try {
                $query = Event::published()->upcoming()->with('user', 'category');
                if ($keyword) {
                    $query->where(function ($q) use ($keyword) {
                        $q->where('title', 'like', "%{$keyword}%")
                          ->orWhere('description', 'like', "%{$keyword}%")
                          ->orWhere('city', 'like', "%{$keyword}%");
                    });
                }
                if ($city)     $query->where('city', 'like', "%{$city}%");
                if ($category) {
                    $cat = Category::where('slug', $category)->first();
                    if ($cat) $query->where('category_id', (string)$cat->_id);
                }
                if ($price === 'free') $query->where('is_free', true);

                $paginated  = $query->orderBy('start_date')->paginate(12);
                $events     = $paginated->items();
                $total      = $paginated->total();
                $totalPages = $paginated->lastPage();
            } catch (\Throwable $e) {
                \Illuminate\Support\Facades\Log::warning('MongoDB events index fallback failed: ' . $e->getMessage());
            }
        }

        if ($city) cookie()->queue('last_city', $city, 60 * 24 * 7);

        try {
            $categories = Category::where('is_active', true)->get();
        } catch (\Throwable $e) {
            $categories = collect();
        }

        $currentPage = $page;
        if (!isset($hasNextPage)) {
            $hasNextPage = $currentPage < $totalPages;
        }

        return view('events.index', compact(
            'events', 'categories', 'total', 'totalPages',
            'currentPage', 'city', 'keyword', 'category', 'price', 'types',
            'usingRealData', 'hasNextPage'
        ));
    }

    public function show(string $slug)
    {
        // Ticketmaster event
        if (str_starts_with($slug, 'tm-')) {
            $tmId  = substr($slug, 3);
            $event = $this->ticketmaster->getEvent($tmId);
            if (!$event) abort(404);

            $hasRsvped = Auth::check()
                ? Rsvp::where('user_id', (string)Auth::id())->where('event_id', $slug)->exists()
                : false;

            return view('events.show-tm', compact('event', 'hasRsvped'));
        }

        // Eventbrite event
        if (str_starts_with($slug, 'eb-')) {
            $ebId  = substr($slug, 3);
            $event = $this->eventbrite->getEvent($ebId);
            if (!$event) abort(404);

            $hasRsvped = Auth::check()
                ? Rsvp::where('user_id', (string)Auth::id())->where('event_id', $slug)->exists()
                : false;

            return view('events.show-eb', compact('event', 'hasRsvped'));
        }

        // Google Events
        if (str_starts_with($slug, 'ge-')) {
            $geId  = substr($slug, 3);
            $event = $this->google->getEvent($geId);
            if (!$event) abort(404);

            $hasRsvped = Auth::check()
                ? Rsvp::where('user_id', (string)Auth::id())->where('event_id', $slug)->exists()
                : false;

            return view('events.show-ge', compact('event', 'hasRsvped'));
        }

        // MongoDB user-created event
        $event = Event::where('slug', $slug)->where('status', 'published')->firstOrFail();
        $event->increment('views_count');
        $event->load('user', 'category', 'comments.user');

        $comments  = $event->comments()->whereNull('parent_id')->with('user', 'replies.user')->latest()->get();
        $rsvpCount = $event->rsvp_count ?? 0;
        $hasRsvped = Auth::check() ? Auth::user()->hasRsvped((string)$event->_id) : false;
        $hasLiked  = Auth::check() ? Auth::user()->hasLiked((string)$event->_id) : false;

        $relatedEvents = Event::published()
            ->where('category_id', $event->category_id)
            ->where('_id', '!=', $event->_id)
            ->upcoming()
            ->limit(3)
            ->get();

        return view('events.show', compact(
            'event', 'comments', 'rsvpCount', 'hasRsvped', 'hasLiked', 'relatedEvents'
        ));
    }

    public function create()
    {
        $categories = Category::where('is_active', true)->get();
        return view('events.create', compact('categories'));
    }

    public function store(EventRequest $request)
    {
        $data             = $request->validated();
        $data['user_id']  = (string)Auth::id();
        $data['slug']     = Str::slug($data['title']) . '-' . Str::random(6);
        $data['status']   = 'published';
        $data['views_count'] = 0;
        $data['likes_count'] = 0;
        $data['rsvp_count']  = 0;
        $data['tags']     = $request->filled('tags')
            ? array_map('trim', explode(',', $request->tags))
            : [];

        if ($request->hasFile('image')) {
            $file     = $request->file('image');
            $filename = Str::uuid() . '.' . $file->getClientOriginalExtension();
            $file->storeAs('public/events', $filename);
            $data['image'] = $filename;
        }

        $event = Event::create($data);

        return redirect()->route('events.show', $event->slug)
            ->with('success', 'Event created successfully!');
    }

    public function edit(string $slug)
    {
        $event = Event::where('slug', $slug)->firstOrFail();
        if ((string)$event->user_id !== (string)Auth::id() && !Auth::user()->isAdmin()) abort(403);

        $categories = Category::where('is_active', true)->get();
        return view('events.edit', compact('event', 'categories'));
    }

    public function update(EventRequest $request, string $slug)
    {
        $event = Event::where('slug', $slug)->firstOrFail();
        if ((string)$event->user_id !== (string)Auth::id() && !Auth::user()->isAdmin()) abort(403);

        $data         = $request->validated();
        $data['tags'] = $request->filled('tags')
            ? array_map('trim', explode(',', $request->tags))
            : [];

        if ($request->hasFile('image')) {
            if ($event->image) Storage::delete('public/events/' . $event->image);
            $file     = $request->file('image');
            $filename = Str::uuid() . '.' . $file->getClientOriginalExtension();
            $file->storeAs('public/events', $filename);
            $data['image'] = $filename;
        }

        $event->update($data);

        return redirect()->route('events.show', $event->slug)->with('success', 'Event updated!');
    }

    public function destroy(string $slug)
    {
        $event = Event::where('slug', $slug)->firstOrFail();
        if ((string)$event->user_id !== (string)Auth::id() && !Auth::user()->isAdmin()) abort(403);

        if ($event->image) Storage::delete('public/events/' . $event->image);
        $event->delete();

        return redirect()->route('home')->with('success', 'Event deleted.');
    }

    public function myEvents()
    {
        $events = Event::where('user_id', (string)Auth::id())
            ->orderBy('created_at', 'desc')
            ->paginate(10);

        return view('events.my-events', compact('events'));
    }

    private function typeMatchesEvent(string $type, array $event): bool
    {
        $cat   = strtolower($event['category'] ?? '');
        $genre = strtolower($event['genre'] ?? '');
        $title = strtolower($event['title'] ?? '');

        // Events with no category metadata pass through (can't filter what we can't read)
        if (empty($cat) && empty($genre)) return true;

        return match($type) {
            'concerts'     => $cat === 'music',
            'festivals'    => $cat === 'music' || str_contains($genre, 'festival') || str_contains($title, 'festival'),
            'sports'       => $cat === 'sports',
            'comedy'       => str_contains($genre, 'comedy') || str_contains($title, 'comedy'),
            'theatre'      => str_contains($genre, 'theatre') || str_contains($genre, 'theater'),
            'performances' => str_contains($cat, 'arts') || str_contains($cat, 'theatre'),
            'cultural'     => str_contains($cat, 'arts') || str_contains($title, 'cultural') || str_contains($title, 'heritage'),
            'family'       => $cat === 'family' || str_contains($genre, 'family') || str_contains($title, 'family') || str_contains($title, 'kids'),
            'conferences'  => str_contains($title, 'conference') || str_contains($title, 'summit') || $cat === 'miscellaneous',
            'workshops'    => str_contains($title, 'workshop') || str_contains($title, 'seminar') || str_contains($title, 'masterclass'),
            'exhibitions'  => str_contains($title, 'exhibit') || str_contains($title, 'expo') || str_contains($title, 'fair'),
            default        => true,
        };
    }

    private function mapType(string $type): string
    {
        return match($type) {
            'concerts'    => 'Music',
            'festivals'   => 'Music',
            'sports'      => 'Sports',
            'comedy'      => 'Comedy',
            'theatre'     => 'Theatre',
            'performances'=> 'Arts & Theatre',
            'cultural'    => 'Arts & Theatre',
            'family'      => 'Family',
            'conferences' => 'Miscellaneous',
            'workshops'   => 'Miscellaneous',
            'exhibitions' => 'Arts & Theatre',
            default       => ucfirst($type),
        };
    }

    private function mapCategory(string $slug): string
    {
        return match($slug) {
            'music'      => 'Music',
            'sports'     => 'Sports',
            'arts'       => 'Arts & Theatre',
            'comedy'     => 'Arts & Theatre',
            'technology' => 'Miscellaneous',
            'food-drink' => 'Miscellaneous',
            'health'     => 'Miscellaneous',
            'outdoors'   => 'Miscellaneous',
            'networking' => 'Miscellaneous',
            'community'  => 'Miscellaneous',
            'business'   => 'Miscellaneous',
            default      => ucfirst($slug),
        };
    }
}
