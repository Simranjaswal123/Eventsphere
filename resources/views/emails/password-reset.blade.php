<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <style>
        body { font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', sans-serif; background: #f4f4f5; margin: 0; padding: 40px 20px; }
        .card { background: #fff; max-width: 520px; margin: 0 auto; border-radius: 12px; overflow: hidden; box-shadow: 0 4px 20px rgba(0,0,0,0.08); }
        .header { background: #E11D48; padding: 32px 40px; text-align: center; }
        .header h1 { color: #fff; font-size: 22px; font-weight: 700; margin: 0; }
        .body { padding: 40px; }
        .body p { color: #374151; font-size: 15px; line-height: 1.7; margin: 0 0 16px; }
        .btn { display: inline-block; background: #E11D48; color: #fff !important; text-decoration: none; font-weight: 700; font-size: 15px; padding: 14px 32px; border-radius: 8px; margin: 8px 0 24px; }
        .note { color: #9ca3af; font-size: 13px; }
        .url { word-break: break-all; color: #6b7280; font-size: 12px; }
        .footer { background: #f9fafb; padding: 20px 40px; text-align: center; color: #9ca3af; font-size: 12px; border-top: 1px solid #e5e7eb; }
    </style>
</head>
<body>
    <div class="card">
        <div class="header">
            <h1>EventSphere</h1>
        </div>
        <div class="body">
            <p>Hi {{ $user->name }},</p>
            <p>We received a request to reset your password. Click the button below to choose a new one. This link expires in <strong>1 hour</strong>.</p>
            <a href="{{ $resetUrl }}" class="btn">Reset Password</a>
            <p class="note">If you didn't request a password reset, you can safely ignore this email. Your password won't change.</p>
            <p class="url">Or copy this link: {{ $resetUrl }}</p>
        </div>
        <div class="footer">
            &copy; {{ date('Y') }} EventSphere. All rights reserved.
        </div>
    </div>
</body>
</html>
