@extends('layouts.app')
@section('title', '404 — Page Not Found')

@section('content')
<div class="min-h-[70vh] flex items-center justify-center px-4">
    <div class="text-center max-w-md">
        <div class="font-righteous text-8xl sm:text-9xl bg-gradient-to-r from-rose-400 to-violet-400 bg-clip-text text-transparent mb-4">404</div>
        <h1 class="font-righteous text-2xl text-white mb-3">Page not found</h1>
        <p class="text-slate-400 mb-8">The event or page you're looking for doesn't exist or may have been removed.</p>
        <div class="flex flex-col sm:flex-row gap-3 justify-center">
            <a href="{{ route('home') }}" class="px-6 py-3 bg-gradient-to-r from-rose-500 to-violet-600 text-white font-semibold rounded-xl hover:opacity-90 transition-opacity cursor-pointer">Back to Home</a>
            <a href="{{ route('events.index') }}" class="px-6 py-3 bg-white/5 border border-white/10 text-white font-semibold rounded-xl hover:bg-white/10 transition-colors cursor-pointer">Browse Events</a>
        </div>
    </div>
</div>
@endsection
