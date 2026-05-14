<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <style>
        body { font-family: 'Poppins', Arial, sans-serif; background: #0A0A0F; color: #e2e8f0; margin: 0; padding: 0; }
        .container { max-width: 600px; margin: 40px auto; background: #13131A; border-radius: 16px; overflow: hidden; border: 1px solid rgba(225,29,72,0.3); }
        .header { background: linear-gradient(135deg, #E11D48, #7C3AED); padding: 40px; text-align: center; }
        .header h1 { color: #fff; margin: 0; font-size: 28px; }
        .body { padding: 40px; }
        .body h2 { color: #f8fafc; }
        .body p { color: #94a3b8; line-height: 1.8; }
        .btn { display: inline-block; background: linear-gradient(135deg, #E11D48, #7C3AED); color: #fff; padding: 14px 32px; border-radius: 8px; text-decoration: none; font-weight: 600; margin: 20px 0; }
        .footer { text-align: center; padding: 24px; color: #475569; font-size: 13px; border-top: 1px solid rgba(255,255,255,0.1); }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h1>🎉 Welcome to EventSphere</h1>
        </div>
        <div class="body">
            <h2>Hey {{ $user->name }}!</h2>
            <p>You've successfully joined <strong>EventSphere</strong> — the social platform for discovering and sharing local events in your community.</p>
            <p>Here's what you can do:</p>
            <ul style="color: #94a3b8; line-height: 2;">
                <li>Discover events near you</li>
                <li>Create and promote your own events</li>
                <li>RSVP and connect with attendees</li>
                <li>Follow your favorite event organizers</li>
            </ul>
            <a href="{{ config('app.url') }}" class="btn">Explore Events Now</a>
        </div>
        <div class="footer">
            &copy; {{ date('Y') }} EventSphere. All rights reserved.
        </div>
    </div>
</body>
</html>
