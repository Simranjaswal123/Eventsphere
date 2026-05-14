<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\ForgotPasswordController;
use App\Http\Controllers\CommentController;
use App\Http\Controllers\EventController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\LikeController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\RsvpController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes — EventSphere Social Events Platform
|--------------------------------------------------------------------------
*/

// Public routes with visit tracking middleware
Route::middleware(['web', \App\Http\Middleware\TrackPageVisit::class])->group(function () {

    // Home
    Route::get('/', [HomeController::class, 'index'])->name('home');
    Route::get('/about', [HomeController::class, 'about'])->name('about');
    Route::get('/search', [HomeController::class, 'search'])->name('search');
    Route::get('/api/live-search', [HomeController::class, 'liveSearch'])->name('live.search');

    // Events — specific auth-only paths FIRST (before wildcard)
    Route::prefix('events')->name('events.')->group(function () {
        Route::get('/', [EventController::class, 'index'])->name('index');

        // Auth-required specific paths — must come before /{slug} wildcard
        Route::middleware('auth')->group(function () {
            Route::get('/my/list', [EventController::class, 'myEvents'])->name('mine');
            Route::get('/create', [EventController::class, 'create'])->name('create');
            Route::post('/store', [EventController::class, 'store'])->name('store');
            Route::get('/{slug}/edit', [EventController::class, 'edit'])->name('edit');
            Route::put('/{slug}', [EventController::class, 'update'])->name('update');
            Route::delete('/{slug}', [EventController::class, 'destroy'])->name('destroy');
        });

        // Wildcard public show — LAST so specific routes win
        Route::get('/{slug}', [EventController::class, 'show'])->name('show');
    });

    // Public profile
    Route::get('/user/{username}', [ProfileController::class, 'show'])->name('profile.show');

    // Auth routes (guests only)
    Route::middleware('guest')->group(function () {
        Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
        Route::post('/login', [AuthController::class, 'login'])->name('login.post');
        Route::get('/register', [AuthController::class, 'showRegister'])->name('register');
        Route::post('/register', [AuthController::class, 'register'])->name('register.post');

        // Forgot / reset password
        Route::get('/forgot-password', [ForgotPasswordController::class, 'showForm'])->name('password.request');
        Route::post('/forgot-password', [ForgotPasswordController::class, 'sendLink'])->name('password.email');
        Route::get('/reset-password/{token}', [ForgotPasswordController::class, 'showReset'])->name('password.reset');
        Route::post('/reset-password/{token}', [ForgotPasswordController::class, 'update'])->name('password.update');
    });

    // Authenticated routes
    Route::middleware('auth')->group(function () {

        // Logout
        Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

        // Dashboard
        Route::get('/dashboard', [ProfileController::class, 'dashboard'])->name('dashboard');

        // Profile management
        Route::prefix('profile')->name('profile.')->group(function () {
            Route::get('/edit', [ProfileController::class, 'edit'])->name('edit');
            Route::put('/update', [ProfileController::class, 'update'])->name('update');
            Route::put('/password', [ProfileController::class, 'changePassword'])->name('password');
            Route::get('/rsvps', [RsvpController::class, 'myRsvps'])->name('rsvps');
        });

        // RSVP
        Route::post('/events/{slug}/rsvp', [RsvpController::class, 'toggle'])->name('rsvp.toggle');

        // Likes
        Route::post('/events/{slug}/like', [LikeController::class, 'toggle'])->name('like.toggle');

        // Comments
        Route::post('/events/{slug}/comments', [CommentController::class, 'store'])->name('comments.store');
        Route::delete('/comments/{id}', [CommentController::class, 'destroy'])->name('comments.destroy');
    });
});

// Locale switching (demonstrates Laravel Localization — Unit IV)
Route::get('/lang/{locale}', function (string $locale) {
    if (!in_array($locale, ['en', 'hi', 'fr'])) {
        abort(400);
    }
    session(['locale' => $locale]);
    app()->setLocale($locale);
    return redirect()->back()->with('success', 'Language changed.');
})->name('lang.switch');
