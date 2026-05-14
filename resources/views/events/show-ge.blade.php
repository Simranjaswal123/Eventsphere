@extends('layouts.app')
@section('title', $event['title'])

@section('content')

{{-- Header image --}}
<div class="relative h-72 sm:h-96 overflow-hidden" style="border-bottom: 1px solid rgba(255,255,255,0.07);">
    <img src="{{ $event['image_url'] }}" alt="{{ $event['title'] }}"
         class="w-full h-full object-cover"
         onerror="this.src='https://picsum.photos/seed/ge{{ $event['id'] }}/1200/500'">
    <div class="absolute inset-0" style="background: linear-gradient(to top, #080808 0%, rgba(8,8,8,0.3) 60%, transparent 100%);"></div>

    {{-- Source badge --}}
    <div class="absolute top-4 left-4">
        <span class="es-badge" style="background: rgba(66,133,244,0.2); color: #4285F4; border: 1px solid rgba(66,133,244,0.35); font-size: 0.7rem; padding: 0.25rem 0.75rem;">
            Google Events
        </span>
    </div>
</div>

<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-10">
    <div class="flex flex-col lg:flex-row gap-10">

        {{-- Main content --}}
        <div class="flex-1 min-w-0">

            @if($event['genre'] ?? $event['category'] ?? null)
                <p class="text-xs font-bold uppercase tracking-widest mb-3" style="color: #4285F4;">
                    {{ $event['genre'] ?? $event['category'] }}
                </p>
            @endif

            <h1 class="heading-lg text-white mb-6">{{ $event['title'] }}</h1>

            {{-- Meta strip --}}
            <div class="grid grid-cols-1 sm:grid-cols-3 gap-px mb-8 rounded-xl overflow-hidden"
                 style="background: rgba(255,255,255,0.06); border: 1px solid rgba(255,255,255,0.07);">
                <div class="p-5" style="background: #111;">
                    <p class="text-xs font-semibold uppercase tracking-wider text-[#555] mb-1">Date & Time</p>
                    @if($event['start_date'])
                        <p class="font-semibold text-white">{{ $event['start_date']->format('D, M j, Y') }}</p>
                        <p class="text-sm text-[#666]">{{ $event['start_date']->format('g:i A') }}</p>
                    @elseif($event['start_date_raw'])
                        <p class="font-semibold text-white">{{ $event['start_date_raw'] }}</p>
                    @else
                        <p class="font-semibold text-[#555]">Date TBD</p>
                    @endif
                </div>
                <div class="p-5" style="background: #111; border-left: 1px solid rgba(255,255,255,0.06); border-right: 1px solid rgba(255,255,255,0.06);">
                    <p class="text-xs font-semibold uppercase tracking-wider text-[#555] mb-1">Venue</p>
                    <p class="font-semibold text-white">{{ $event['location'] }}</p>
                    <p class="text-sm text-[#666]">{{ $event['city'] }}{{ $event['state'] ? ', '.$event['state'] : '' }}</p>
                </div>
                <div class="p-5" style="background: #111;">
                    <p class="text-xs font-semibold uppercase tracking-wider text-[#555] mb-1">Price</p>
                    @if($event['is_free'])
                        <p class="font-black text-lg uppercase" style="color: #FFD600;">Free</p>
                    @elseif($event['price'])
                        <p class="font-semibold text-white text-lg">{{ $event['currency'] === 'INR' ? '₹' : '$' }}{{ number_format($event['price'], 0) }}</p>
                    @else
                        <p class="font-semibold text-[#555]">See event page</p>
                    @endif
                </div>
            </div>

            {{-- Description --}}
            @if($event['description'])
            <div class="mb-8">
                <div class="es-label mb-3">About This Event</div>
                <p class="text-[#777] leading-relaxed">{{ $event['description'] }}</p>
            </div>
            @endif

            {{-- Address --}}
            @if($event['address'])
            <div class="p-5 rounded-xl mb-8" style="background: rgba(255,255,255,0.03); border: 1px solid rgba(255,255,255,0.07);">
                <div class="es-label mb-3">Location</div>
                <div class="flex items-start gap-3">
                    <svg class="w-5 h-5 shrink-0 mt-0.5" style="color: #4285F4;" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                    <div>
                        <p class="font-semibold text-white">{{ $event['location'] }}</p>
                        <p class="text-sm text-[#666]">{{ $event['address'] }}</p>
                    </div>
                </div>
            </div>
            @endif

            {{-- Ticket sources --}}
            @if(!empty($event['ticket_info']))
            <div class="mb-8">
                <div class="es-label mb-3">Get Tickets</div>
                <div class="flex flex-wrap gap-3">
                    @foreach($event['ticket_info'] as $ticket)
                        @if(!empty($ticket['link']))
                        <a href="{{ $ticket['link'] }}" target="_blank" rel="noopener"
                           class="es-btn-ghost py-2 px-4 text-sm rounded-lg">
                            {{ $ticket['source'] ?? 'Buy Tickets' }} →
                        </a>
                        @endif
                    @endforeach
                </div>
            </div>
            @endif

            {{-- Tags --}}
            @if(!empty($event['tags']))
            <div class="flex flex-wrap gap-2">
                @foreach(array_filter($event['tags']) as $tag)
                    <span class="es-tag">{{ $tag }}</span>
                @endforeach
            </div>
            @endif
        </div>

        {{-- Sidebar --}}
        <div class="lg:w-80 shrink-0">
            <div class="rounded-2xl overflow-hidden sticky top-20"
                 style="background: #161616; border: 1px solid rgba(255,255,255,0.08); box-shadow: 0 8px 40px rgba(0,0,0,0.4);">
                <div class="p-5" style="border-bottom: 1px solid rgba(255,255,255,0.07);">
                    <p class="text-xs font-semibold uppercase tracking-wider text-[#555] mb-1">
                        {{ $event['is_free'] ? 'Free Event' : 'Tickets Available' }}
                    </p>
                    @if($event['is_free'])
                        <p class="font-black text-3xl uppercase" style="color: #FFD600;">Free</p>
                    @elseif($event['price'])
                        <p class="font-black text-3xl text-white">{{ $event['currency'] === 'INR' ? '₹' : '$' }}{{ number_format($event['price'], 0) }}</p>
                    @else
                        <p class="font-semibold text-xl text-[#555]">See event page</p>
                    @endif
                </div>

                <div class="p-5 space-y-3">
                    {{-- Primary CTA --}}
                    @if(!empty($event['ticket_info']))
                        @foreach(array_slice($event['ticket_info'], 0, 1) as $ticket)
                            @if(!empty($ticket['link']))
                            <a href="{{ $ticket['link'] }}" target="_blank" rel="noopener"
                               class="es-btn w-full py-3 text-sm justify-center block text-center"
                               style="background: #4285F4;">
                                Get Tickets on {{ $ticket['source'] ?? 'Ticketing Site' }} →
                            </a>
                            @endif
                        @endforeach
                    @elseif($event['external_url'] && $event['external_url'] !== '#')
                        <a href="{{ $event['external_url'] }}" target="_blank" rel="noopener"
                           class="es-btn w-full py-3 text-sm justify-center block text-center"
                           style="background: #4285F4;">
                            View Event →
                        </a>
                    @endif

                    {{-- RSVP on our platform --}}
                    @auth
                        <form method="POST" action="{{ route('rsvp.toggle', $event['slug']) }}">
                            @csrf
                            <button type="submit"
                                    class="w-full py-3 text-sm font-semibold rounded-lg transition-all cursor-pointer
                                           {{ $hasRsvped ? 'es-btn-ghost' : 'es-btn-yellow' }}">
                                {{ $hasRsvped ? 'Cancel Attendance' : '✓ Mark Attending' }}
                            </button>
                        </form>
                    @else
                        <a href="{{ route('login') }}" class="es-btn-ghost w-full py-3 text-sm text-center block rounded-lg">
                            Sign in to RSVP
                        </a>
                    @endauth

                    <hr class="es-divider">

                    {{-- Meta --}}
                    <div class="space-y-3 pt-1">
                        @if($event['start_date'])
                        <div class="flex items-center gap-3 text-sm">
                            <svg class="w-4 h-4 shrink-0 text-[#555]" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                            <span class="text-[#666]">{{ $event['start_date']->format('D, M j, Y · g:i A') }}</span>
                        </div>
                        @elseif($event['start_date_raw'])
                        <div class="flex items-center gap-3 text-sm">
                            <svg class="w-4 h-4 shrink-0 text-[#555]" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                            <span class="text-[#666]">{{ $event['start_date_raw'] }}</span>
                        </div>
                        @endif
                        <div class="flex items-center gap-3 text-sm">
                            <svg class="w-4 h-4 shrink-0 text-[#555]" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                            <span class="text-[#666]">{{ $event['city'] }}{{ $event['state'] ? ', '.$event['state'] : '' }}</span>
                        </div>
                        <div class="flex items-center gap-3 text-sm">
                            <svg class="w-4 h-4 shrink-0 text-[#555]" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/></svg>
                            <span class="text-[#666]">{{ $event['location'] }}</span>
                        </div>
                    </div>

                    <hr class="es-divider">
                    <p class="text-xs text-[#333] text-center">Powered by Google Events</p>
                </div>
            </div>
        </div>

    </div>
</div>

@endsection
