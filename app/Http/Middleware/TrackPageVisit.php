<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class TrackPageVisit
{
    public function handle(Request $request, Closure $next): Response
    {
        $response = $next($request);

        // Track visit count in session
        $visitCount = session('visit_count', 0) + 1;
        session(['visit_count' => $visitCount]);
        session(['last_visited_url' => $request->path()]);

        return $response;
    }
}
