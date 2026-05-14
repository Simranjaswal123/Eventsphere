<?php

use App\Http\Controllers\Api\EventApiController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes — EventSphere REST API (Unit VI)
|--------------------------------------------------------------------------
| All routes return JSON responses with consistent structure.
*/

Route::prefix('v1')->name('api.v1.')->group(function () {

    // Public API endpoints
    Route::get('/events', [EventApiController::class, 'index'])->name('events.index');
    Route::get('/events/featured', [EventApiController::class, 'featured'])->name('events.featured');
    Route::get('/events/{slug}', [EventApiController::class, 'show'])->name('events.show');
    Route::get('/categories', [EventApiController::class, 'categories'])->name('categories');
    Route::get('/stats', [EventApiController::class, 'stats'])->name('stats');
});
