<?php

namespace App\Http\Controllers;

use App\Mail\PasswordResetMail;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;

class ForgotPasswordController extends Controller
{
    public function showForm()
    {
        return view('auth.forgot-password');
    }

    public function sendLink(Request $request)
    {
        $request->validate(['email' => 'required|email']);

        $user = User::where('email', $request->email)->first();

        if ($user) {
            $token = Str::random(64);
            Cache::put('pwd_reset_' . $token, $user->email, now()->addHour());

            try {
                Mail::to($user->email)->send(new PasswordResetMail($user, $token));
            } catch (\Throwable $e) {}
        }

        return back()->with('status', 'If that email is registered, a reset link has been sent. Check your inbox (or log file in dev).');
    }

    public function showReset(string $token)
    {
        if (!Cache::has('pwd_reset_' . $token)) {
            return redirect()->route('password.request')
                ->withErrors(['token' => 'This reset link is invalid or has expired.']);
        }
        return view('auth.reset-password', compact('token'));
    }

    public function update(Request $request, string $token)
    {
        $request->validate([
            'password' => 'required|min:8|confirmed',
        ]);

        $email = Cache::get('pwd_reset_' . $token);
        if (!$email) {
            return back()->withErrors(['token' => 'Invalid or expired reset link.']);
        }

        $user = User::where('email', $email)->first();
        if (!$user) {
            return back()->withErrors(['token' => 'No account found for this reset link.']);
        }

        $user->update(['password' => Hash::make($request->password)]);
        Cache::forget('pwd_reset_' . $token);

        return redirect()->route('login')
            ->with('success', 'Password reset successfully! You can now sign in.');
    }
}
