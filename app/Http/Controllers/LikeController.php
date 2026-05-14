<?php

namespace App\Http\Controllers;

use App\Models\Event;
use App\Models\Like;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class LikeController extends Controller
{
    public function toggle(string $slug)
    {
        $event = Event::where('slug', $slug)->firstOrFail();
        $userId = (string) Auth::id();
        $eventId = (string) $event->_id;

        $existing = Like::where('user_id', $userId)->where('event_id', $eventId)->first();

        if ($existing) {
            $existing->delete();
            $event->decrement('likes_count');
            $liked = false;
        } else {
            Like::create(['event_id' => $eventId, 'user_id' => $userId]);
            $event->increment('likes_count');
            $liked = true;
        }

        $count = $event->fresh()->likes_count ?? 0;

        if (request()->wantsJson()) {
            return response()->json(['liked' => $liked, 'likes_count' => $count]);
        }

        return back()->with('success', $liked ? 'Liked!' : 'Like removed.');
    }
}
