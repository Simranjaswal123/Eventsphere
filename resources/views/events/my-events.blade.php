@extends('layouts.app')
@section('title', 'My Events')

@section('content')
<div class="max-w-5xl mx-auto px-4 sm:px-6 lg:px-8 py-10">
    <div class="flex items-center justify-between mb-8">
        <div>
            <h1 class="font-righteous text-3xl text-white">My Events</h1>
            <p class="text-slate-400 mt-1">Events you've created</p>
        </div>
        <a href="{{ route('events.create') }}" class="flex items-center gap-2 px-5 py-2.5 bg-gradient-to-r from-rose-500 to-violet-600 text-white text-sm font-semibold rounded-xl hover:opacity-90 transition-opacity cursor-pointer">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
            New Event
        </a>
    </div>

    @if($events->isEmpty())
        <div class="text-center py-20 bg-white/5 border border-white/10 rounded-2xl">
            <div class="w-16 h-16 bg-white/5 rounded-full flex items-center justify-center mx-auto mb-5">
                <svg class="w-8 h-8 text-slate-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
            </div>
            <h3 class="text-white font-semibold text-lg mb-2">No events yet</h3>
            <p class="text-slate-500 text-sm mb-6">Create your first event and share it with the community.</p>
            <a href="{{ route('events.create') }}" class="inline-flex items-center gap-2 px-6 py-3 bg-gradient-to-r from-rose-500 to-violet-600 text-white font-semibold rounded-xl hover:opacity-90 transition-opacity cursor-pointer">Create Event</a>
        </div>
    @else
        <div class="space-y-4">
            @foreach($events as $event)
                <div class="flex flex-col sm:flex-row items-start sm:items-center gap-4 p-5 bg-white/5 border border-white/10 rounded-2xl hover:border-white/20 transition-all">
                    <div class="w-16 h-16 rounded-xl overflow-hidden shrink-0">
                        <img src="{{ $event->image_url }}" alt="{{ $event->title }}" class="w-full h-full object-cover"
                             onerror="this.src='https://picsum.photos/seed/{{ $event->_id }}/200/200'">
                    </div>
                    <div class="flex-1 min-w-0">
                        <h3 class="text-white font-semibold text-base mb-1 truncate">{{ $event->title }}</h3>
                        <div class="flex flex-wrap items-center gap-3 text-xs text-slate-400">
                            <span class="flex items-center gap-1">
                                <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                                {{ $event->start_date?->format('M j, Y') ?? 'TBD' }}
                            </span>
                            <span class="flex items-center gap-1">
                                <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                                {{ $event->rsvp_count ?? 0 }} RSVPs
                            </span>
                            <span class="flex items-center gap-1">
                                <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/></svg>
                                {{ $event->views_count ?? 0 }} views
                            </span>
                        </div>
                    </div>
                    <div class="flex items-center gap-2 shrink-0">
                        <a href="{{ route('events.show', $event->slug) }}" class="px-3 py-2 bg-white/5 border border-white/10 text-slate-300 hover:text-white text-xs font-medium rounded-lg transition-all cursor-pointer">View</a>
                        <a href="{{ route('events.edit', $event->slug) }}" class="px-3 py-2 bg-blue-500/10 border border-blue-500/20 text-blue-400 hover:bg-blue-500/20 text-xs font-medium rounded-lg transition-all cursor-pointer">Edit</a>
                        <form action="{{ route('events.destroy', $event->slug) }}" method="POST" onsubmit="return confirm('Delete?')">
                            @csrf @method('DELETE')
                            <button type="submit" class="px-3 py-2 bg-rose-500/10 border border-rose-500/20 text-rose-400 hover:bg-rose-500/20 text-xs font-medium rounded-lg transition-all cursor-pointer">Delete</button>
                        </form>
                    </div>
                </div>
            @endforeach
        </div>
        <div class="mt-8">{{ $events->links('components.pagination') }}</div>
    @endif
</div>
@endsection
