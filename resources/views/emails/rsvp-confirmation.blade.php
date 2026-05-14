<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <style>
        body { font-family: Arial, sans-serif; background: #0A0A0F; color: #e2e8f0; margin: 0; padding: 0; }
        .container { max-width: 600px; margin: 40px auto; background: #13131A; border-radius: 16px; overflow: hidden; border: 1px solid rgba(37,99,235,0.3); }
        .header { background: linear-gradient(135deg, #2563EB, #7C3AED); padding: 40px; text-align: center; }
        .header h1 { color: #fff; margin: 0; font-size: 24px; }
        .body { padding: 40px; }
        .event-card { background: rgba(255,255,255,0.05); border-radius: 12px; padding: 24px; margin: 20px 0; border: 1px solid rgba(255,255,255,0.1); }
        .event-card h3 { color: #f8fafc; margin: 0 0 12px; }
        .event-card p { color: #94a3b8; margin: 6px 0; }
        .btn { display: inline-block; background: linear-gradient(135deg, #2563EB, #7C3AED); color: #fff; padding: 14px 32px; border-radius: 8px; text-decoration: none; font-weight: 600; margin: 20px 0; }
        .footer { text-align: center; padding: 24px; color: #475569; font-size: 13px; border-top: 1px solid rgba(255,255,255,0.1); }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h1>✅ RSVP Confirmed!</h1>
        </div>
        <div class="body">
            <p style="color: #94a3b8;">Hi <strong style="color: #f8fafc;">{{ $user->name }}</strong>, your RSVP has been confirmed.</p>
            <div class="event-card">
                <h3>{{ $event->title }}</h3>
                <p>📅 {{ $event->start_date?->format('D, M j, Y \a\t g:i A') }}</p>
                <p>📍 {{ $event->location }}, {{ $event->city }}</p>
                @if(!$event->is_free)
                    <p>💰 ${{ number_format($event->price, 2) }}</p>
                @else
                    <p>🎟️ Free Event</p>
                @endif
            </div>
            <a href="{{ config('app.url') }}/events/{{ $event->slug }}" class="btn">View Event Details</a>
            <p style="color: #64748b; font-size: 14px;">If you can no longer attend, you can cancel your RSVP from the event page.</p>
        </div>
        <div class="footer">
            &copy; {{ date('Y') }} EventSphere. All rights reserved.
        </div>
    </div>
</body>
</html>
