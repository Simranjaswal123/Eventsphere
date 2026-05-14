<?php

namespace App\Http\Controllers;

use App\Models\Comment;
use App\Models\Event;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CommentController extends Controller
{
    public function store(Request $request, string $slug)
    {
        $request->validate([
            'body'      => 'required|string|min:2|max:1000',
            'parent_id' => 'nullable|string',
        ]);

        $event = Event::where('slug', $slug)->firstOrFail();

        Comment::create([
            'event_id'    => (string) $event->_id,
            'user_id'     => (string) Auth::id(),
            'body'        => $request->body,
            'parent_id'   => $request->parent_id ?: null,
            'is_approved' => true,
        ]);

        return back()->with('success', 'Comment added!');
    }

    public function destroy(string $id)
    {
        $comment = Comment::findOrFail($id);

        if ((string) $comment->user_id !== (string) Auth::id() && !Auth::user()->isAdmin()) {
            abort(403);
        }

        $comment->delete();

        return back()->with('success', 'Comment deleted.');
    }
}
