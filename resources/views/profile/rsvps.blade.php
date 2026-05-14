@extends('layouts.app')
@section('title', 'My RSVPs')

@section('content')
<div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 py-10">
    <div class="mb-8">
        <h1 class="font-righteous text-3xl text-white">My RSVPs</h1>
        <p class="text-slate-400 mt-1">Events you're attending</p>
    </div>

    @if($rsvps->isEmpty())
        <div class="text-center py-20 bg-white/5 border border-white/10 rounded-2xl">
            <div class="w-16 h-16 bg-white/5 rounded-full flex items-center justify-center mx-auto mb-5">
                <svg class="w-8 h-8 text-slate-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/></svg>
            </div>
            <h3 class="text-white font-semibold text-lg mb-2">No RSVPs yet</h3>
            <p class="text-slate-500 text-sm mb-6">Find an event you like and RSVP to attend!</p>
            <a href="{{ route('events.index') }}" class="inline-flex items-center gap-2 px-6 py-3 bg-gradient-to-r from-rose-500 to-violet-600 text-white font-semibold rounded-xl hover:opacity-90 transition-opacity cursor-pointer">Browse Events</a>
        </div>
    @else
        <div class="space-y-4">
            @foreach($rsvps as $rsvp)
                @if($rsvp->event)
                    <div class="flex flex-col sm:flex-row items-start sm:items-center gap-4 p-5 bg-white/5 border border-white/10 rounded-2xl hover:border-white/20 transition-all">
                        <div class="w-16 h-16 rounded-xl overflow-hidden shrink-0">
                            <img src="{{ $rsvp->event->image_url }}" alt="{{ $rsvp->event->title }}" class="w-full h-full object-cover"
                                 onerror="this.src='https://picsum.photos/seed/{{ $rsvp->event->_id }}/200/200'">
                        </div>
                        <div class="flex-1 min-w-0">
                            <h3 class="text-white font-semibold text-base mb-1 truncate">{{ $rsvp->event->title }}</h3>
                            <div class="flex flex-wrap items-center gap-3 text-xs text-slate-400">
                                <span>{{ $rsvp->event->start_date?->format('D, M j, Y · g:i A') ?? 'TBD' }}</span>
                                <span class="text-slate-600">·</span>
                                <span>{{ $rsvp->event->location }}, {{ $rsvp->event->city }}</span>
                            </div>
                        </div>
                        <div class="flex items-center gap-2 shrink-0">
                            <span class="px-3 py-1.5 bg-emerald-500/10 border border-emerald-500/20 text-emerald-400 text-xs font-medium rounded-full">
                                {{ ucfirst($rsvp->status) }}
                            </span>
                            <a href="{{ route('events.show', $rsvp->event->slug) }}" class="px-3 py-2 bg-white/5 border border-white/10 text-slate-300 hover:text-white text-xs font-medium rounded-lg transition-all cursor-pointer">View</a>
                            <form action="{{ route('rsvp.toggle', $rsvp->event->slug) }}" method="POST">
                                @csrf
                                <button type="submit" class="px-3 py-2 bg-rose-500/10 border border-rose-500/20 text-rose-400 hover:bg-rose-500/20 text-xs font-medium rounded-lg transition-all cursor-pointer" onclick="return confirm('Cancel RSVP?')">Cancel</button>
                            </form>
                        </div>
                    </div>
                @endif
            @endforeach
        </div>
        <div class="mt-8">{{ $rsvps->links('components.pagination') }}</div>
    @endif
</div>
@endsection
