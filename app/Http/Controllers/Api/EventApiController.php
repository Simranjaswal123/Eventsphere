<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Event;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class EventApiController extends Controller
{
    public function index(Request $request): JsonResponse
    {
        $query = Event::published()->upcoming()->with('user:_id,name,username', 'category:_id,name,slug');

        if ($request->filled('category')) {
            $cat = Category::where('slug', $request->category)->first();
            if ($cat) $query->where('category_id', (string) $cat->_id);
        }

        if ($request->filled('city')) {
            $query->where('city', 'like', '%' . $request->city . '%');
        }

        if ($request->filled('search')) {
            $s = $request->search;
            $query->where(function ($q) use ($s) {
                $q->where('title', 'like', "%{$s}%")->orWhere('city', 'like', "%{$s}%");
            });
        }

        $events = $query->orderBy('start_date', 'asc')->paginate(12);

        return response()->json([
            'success' => true,
            'data'    => $events->items(),
            'meta'    => [
                'total'        => $events->total(),
                'per_page'     => $events->perPage(),
                'current_page' => $events->currentPage(),
                'last_page'    => $events->lastPage(),
            ],
        ]);
    }

    public function show(string $slug): JsonResponse
    {
        $event = Event::where('slug', $slug)->where('status', 'published')->with('user', 'category')->first();

        if (!$event) {
            return response()->json(['success' => false, 'message' => 'Event not found'], 404);
        }

        return response()->json(['success' => true, 'data' => $event]);
    }

    public function categories(): JsonResponse
    {
        $categories = Category::where('is_active', true)->get(['_id', 'name', 'slug', 'icon', 'color']);
        return response()->json(['success' => true, 'data' => $categories]);
    }

    public function featured(): JsonResponse
    {
        $events = Event::published()->featured()->upcoming()
            ->with('user:_id,name,username', 'category:_id,name,slug')
            ->limit(6)->get();

        return response()->json(['success' => true, 'data' => $events]);
    }

    public function stats(): JsonResponse
    {
        return response()->json([
            'success' => true,
            'data' => [
                'total_events'     => Event::published()->count(),
                'upcoming_events'  => Event::published()->upcoming()->count(),
                'total_categories' => Category::where('is_active', true)->count(),
            ],
        ]);
    }
}
