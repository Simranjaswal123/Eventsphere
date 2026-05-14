<?php

namespace App\Providers;

use App\Models\Category;
use Illuminate\Pagination\Paginator;
use Illuminate\Support\Facades\View;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    public function register(): void {}

    public function boot(): void
    {
        // Use our custom glassmorphism pagination view
        Paginator::defaultView('components.pagination');

        // Share categories globally with all views
        View::share('globalCategories', fn() => Category::where('is_active', true)->get());

        // Share current locale with all views
        View::composer('*', function ($view) {
            $view->with('currentLocale', app()->getLocale());
        });
    }
}
