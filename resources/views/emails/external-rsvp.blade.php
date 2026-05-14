<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <style>
        body { font-family: Arial, sans-serif; background: #0A0A0F; color: #e2e8f0; margin: 0; padding: 0; }
        .container { max-width: 600px; margin: 40px auto; background: #13131A; border-radius: 16px; overflow: hidden; border: 1px solid rgba(225,29,72,0.3); }
        .header { background: linear-gradient(135deg, #E11D48, #7C3AED); padding: 40px; text-align: center; }
        .header h1 { color: #fff; margin: 0; font-size: 24px; }
        .body { padding: 40px; }
        .info-box { background: rgba(255,255,255,0.05); border-radius: 12px; padding: 24px; margin: 20px 0; border: 1px solid rgba(255,255,255,0.1); }
        .btn { display: inline-block; background: linear-gradient(135deg, #E11D48, #7C3AED); color: #fff; padding: 14px 32px; border-radius: 8px; text-decoration: none; font-weight: 600; margin: 20px 0; }
        .footer { text-align: center; padding: 24px; color: #475569; font-size: 13px; border-top: 1px solid rgba(255,255,255,0.1); }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h1>You're Attending!</h1>
        </div>
        <div class="body">
            <p style="color: #94a3b8;">Hi <strong style="color: #f8fafc;">{{ $user->name }}</strong>,</p>
            <p style="color: #94a3b8;">Your attendance has been confirmed on EventSphere.</p>
            <div class="info-box">
                <p style="color: #f8fafc; margin: 0 0 8px; font-weight: 600;">What's next?</p>
                <p style="color: #94a3b8; margin: 0;">Visit the event page for full details, venue info, and any updates from the organizer.</p>
            </div>
            <a href="{{ config('app.url') }}/events/{{ $eventSlug }}" class="btn">View Event</a>
            <p style="color: #64748b; font-size: 14px;">Changed your mind? You can cancel attendance from the event page.</p>
        </div>
        <div class="footer">
            &copy; {{ date('Y') }} EventSphere. All rights reserved.
        </div>
    </div>
</body>
</html>
