<?php

namespace App\Http\Controllers;

use App\Models\Event;
use App\Models\Rsvp;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Illuminate\Validation\Rules\Password;

class ProfileController extends Controller
{
    public function show(string $username)
    {
        $user = User::where('username', $username)->firstOrFail();
        $events = Event::where('user_id', (string) $user->_id)
            ->published()
            ->orderBy('created_at', 'desc')
            ->paginate(6);
        $totalEvents = Event::where('user_id', (string) $user->_id)->count();
        $totalRsvps = Rsvp::where('user_id', (string) $user->_id)->count();

        return view('profile.show', compact('user', 'events', 'totalEvents', 'totalRsvps'));
    }

    public function edit()
    {
        return view('profile.edit', ['user' => Auth::user()]);
    }

    public function update(Request $request)
    {
        $user = Auth::user();

        $request->validate([
            'name'     => 'required|string|max:100',
            'username' => 'required|string|max:50|alpha_dash|unique:mongodb.users,username,' . $user->_id . ',_id',
            'bio'      => 'nullable|string|max:500',
            'location' => 'nullable|string|max:100',
            'website'  => 'nullable|url|max:255',
        ]);

        $data = $request->only('name', 'username', 'bio', 'location', 'website');

        // Handle avatar removal
        if ($request->input('remove_avatar') === '1' && $user->avatar) {
            Storage::delete('public/avatars/' . $user->avatar);
            $data['avatar'] = null;
        }

        // Handle avatar upload (overrides removal if both somehow sent)
        if ($request->hasFile('avatar')) {
            $request->validate(['avatar' => 'image|mimes:jpg,jpeg,png,webp|max:2048']);

            if ($user->avatar) {
                Storage::delete('public/avatars/' . $user->avatar);
            }
            $filename = Str::uuid() . '.' . $request->file('avatar')->getClientOriginalExtension();
            $request->file('avatar')->storeAs('public/avatars', $filename);
            $data['avatar'] = $filename;
        }

        $user->update($data);

        return redirect()->route('profile.show', $user->username)
            ->with('success', 'Profile updated!');
    }

    public function dashboard()
    {
        $user = Auth::user();
        $myEvents = Event::where('user_id', (string) $user->_id)
            ->orderBy('created_at', 'desc')
            ->limit(5)
            ->get();
        $myRsvps = Rsvp::where('user_id', (string) $user->_id)
            ->with('event')
            ->orderBy('created_at', 'desc')
            ->limit(5)
            ->get();
        $stats = [
            'total_events' => Event::where('user_id', (string) $user->_id)->count(),
            'total_rsvps'  => Rsvp::where('user_id', (string) $user->_id)->count(),
            'total_views'  => Event::where('user_id', (string) $user->_id)->sum('views_count'),
            'total_likes'  => Event::where('user_id', (string) $user->_id)->sum('likes_count'),
        ];

        return view('profile.dashboard', compact('user', 'myEvents', 'myRsvps', 'stats'));
    }

    public function changePassword(Request $request)
    {
        $key = 'pwd-change:' . Auth::id();

        if (RateLimiter::tooManyAttempts($key, 5)) {
            $seconds = RateLimiter::availableIn($key);
            return back()->withErrors(['current_password' => "Too many attempts. Try again in {$seconds}s."]);
        }

        $request->validate([
            'current_password' => 'required',
            'password' => [
                'required',
                'confirmed',
                Password::min(12)
                    ->letters()
                    ->mixedCase()
                    ->numbers()
                    ->symbols(),
            ],
        ]);

        $user = Auth::user();

        if (!Hash::check($request->current_password, $user->password)) {
            RateLimiter::hit($key, 900);
            return back()->withErrors(['current_password' => 'Current password is incorrect.']);
        }

        RateLimiter::clear($key);
        $user->update(['password' => Hash::make($request->password)]);

        return back()->with('success', 'Password updated successfully!');
    }
}
