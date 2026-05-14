<?php

namespace App\Http\Controllers;

use App\Mail\RsvpConfirmationMail;
use App\Models\Event;
use App\Models\Rsvp;
use App\Services\TicketmasterService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;

class RsvpController extends Controller
{
    public function __construct(private TicketmasterService $ticketmaster) {}

    public function toggle(string $slug)
    {
        $userId    = (string)Auth::id();
        $isExternal = str_starts_with($slug, 'tm-') || str_starts_with($slug, 'eb-') || str_starts_with($slug, 'ge-');
        $message   = '';

        if ($isExternal) {
            // Ticketmaster / Eventbrite event — use slug as event_id directly
            $existing = Rsvp::where('user_id', $userId)->where('event_id', $slug)->first();

            if ($existing) {
                $existing->delete();
                $message = 'Attendance cancelled.';
            } else {
                Rsvp::create([
                    'event_id' => $slug,
                    'user_id'  => $userId,
                    'status'   => 'confirmed',
                    'attended' => false,
                ]);

                try {
                    Mail::to(Auth::user()->email)->send(new \App\Mail\ExternalRsvpMail(Auth::user(), $slug));
                } catch (\Throwable $e) {
                    \Illuminate\Support\Facades\Log::error('External RSVP mail failed: ' . $e->getMessage());
                }

                $message = 'Marked as attending!';
            }

            return back()->with('success', $message);
        }

        // MongoDB event
        $event   = Event::where('slug', $slug)->firstOrFail();
        $eventId = (string)$event->_id;
        $existing = Rsvp::where('user_id', $userId)->where('event_id', $eventId)->first();

        if ($existing) {
            $existing->delete();
            $event->decrement('rsvp_count');
            $message = 'RSVP cancelled.';
        } else {
            if ($event->isFull()) {
                return back()->with('error', 'This event is fully booked.');
            }

            Rsvp::create([
                'event_id' => $eventId,
                'user_id'  => $userId,
                'status'   => 'confirmed',
                'attended' => false,
            ]);
            $event->increment('rsvp_count');

            try {
                Mail::to(Auth::user()->email)->send(new RsvpConfirmationMail($event, Auth::user()));
            } catch (\Throwable $e) {
                \Illuminate\Support\Facades\Log::error('RSVP mail failed: ' . $e->getMessage());
            }

            $message = 'RSVP confirmed!';
        }

        if (request()->wantsJson()) {
            return response()->json([
                'rsvped'     => !$existing,
                'rsvp_count' => $event->fresh()->rsvp_count ?? 0,
                'message'    => $message,
            ]);
        }

        return back()->with('success', $message);
    }

    public function myRsvps()
    {
        $rsvps = Rsvp::where('user_id', (string)Auth::id())
            ->with('event')
            ->orderBy('created_at', 'desc')
            ->paginate(10);

        return view('profile.rsvps', compact('rsvps'));
    }
}
