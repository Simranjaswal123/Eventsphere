@extends('layouts.app')
@section('title', $query ? 'Search: '.$query : 'Search Events')

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-10">
    <div class="max-w-2xl mx-auto text-center mb-10">
        <h1 class="font-righteous text-3xl text-white mb-4">Search Events</h1>
        <form action="{{ route('search') }}" method="GET" class="flex gap-3">
            <div class="flex-1 relative">
                <svg class="absolute left-4 top-1/2 -translate-y-1/2 w-5 h-5 text-slate-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
                <input type="text" name="q" value="{{ $query }}" placeholder="Search events, cities, categories..."
                    class="w-full pl-12 pr-4 py-3.5 bg-white/5 border border-white/10 rounded-xl text-white placeholder-slate-500 focus:outline-none focus:border-rose-500 focus:ring-1 focus:ring-rose-500/30 transition-all text-base"
                    autofocus>
            </div>
            <button type="submit" class="px-6 py-3.5 bg-gradient-to-r from-rose-500 to-violet-600 text-white font-semibold rounded-xl hover:opacity-90 transition-opacity cursor-pointer">Search</button>
        </form>
    </div>

    @if($query)
        <div class="mb-6">
            <p class="text-slate-400 text-sm text-center">
                @if($events instanceof \Illuminate\Pagination\LengthAwarePaginator)
                    Found <span class="text-white font-medium">{{ $events->total() }}</span> results for "<span class="text-rose-400">{{ $query }}</span>"
                @else
                    Showing results for "<span class="text-rose-400">{{ $query }}</span>"
                @endif
            </p>
        </div>

        @if(empty($events) || (is_object($events) && $events->isEmpty()))
            <div class="text-center py-16 bg-white/5 border border-white/10 rounded-2xl max-w-lg mx-auto">
                <div class="w-16 h-16 bg-white/5 rounded-full flex items-center justify-center mx-auto mb-4">
                    <svg class="w-8 h-8 text-slate-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.172 16.172a4 4 0 015.656 0M9 10h.01M15 10h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                </div>
                <h3 class="text-white font-semibold text-lg mb-2">No events found</h3>
                <p class="text-slate-500 text-sm">Try a different search term or <a href="{{ route('events.index') }}" class="text-rose-400 hover:underline">browse all events</a>.</p>
            </div>
        @else
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6 mb-8">
                @foreach($events as $event)
                    @include('components.event-card', ['event' => $event])
                @endforeach
            </div>
            @if($events instanceof \Illuminate\Pagination\LengthAwarePaginator)
                {{ $events->links('components.pagination') }}
            @endif
        @endif
    @else
        <div class="text-center py-16 text-slate-500">
            <p>Type something above to search for events.</p>
        </div>
    @endif
</div>
@endsection
