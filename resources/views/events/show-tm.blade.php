@extends('layouts.app')
@section('title', $event['title'])

@section('content')

{{-- Header image strip --}}
<div class="relative h-72 sm:h-96 overflow-hidden border-b-2 border-[#2A2A2A]">
    <img src="{{ $event['image_url'] }}" alt="{{ $event['title'] }}"
         class="w-full h-full object-cover"
         onerror="this.src='https://picsum.photos/seed/{{ $event['id'] }}/1200/500'">
    <div class="absolute inset-0 bg-gradient-to-t from-[#0A0A0A] via-[#0A0A0A]/40 to-transparent"></div>

    {{-- Live badge --}}
    <div class="absolute top-4 left-4">
        <span class="bg-[#E11D48] text-white text-xs font-black px-3 py-1 uppercase tracking-widest border border-[#E11D48] shadow-[2px_2px_0_#FFD600]">
            Live Event
        </span>
    </div>
</div>

<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-10">
    <div class="flex flex-col lg:flex-row gap-10">

        {{-- Main content --}}
        <div class="flex-1 min-w-0">

            {{-- Category / genre --}}
            @if($event['genre'] ?? $event['category'])
                <p class="text-xs font-black uppercase tracking-widest text-[#E11D48] mb-3">
                    {{ $event['genre'] ?? $event['category'] }}
                </p>
            @endif

            {{-- Title --}}
            <h1 class="font-bebas text-4xl sm:text-5xl lg:text-6xl text-[#F0F0F0] leading-none mb-6">
                {{ $event['title'] }}
            </h1>

            {{-- Meta strip --}}
            <div class="grid grid-cols-1 sm:grid-cols-3 gap-0 border-2 border-[#2A2A2A] mb-8">
                <div class="p-5 border-b-2 sm:border-b-0 sm:border-r-2 border-[#2A2A2A]">
                    <p class="text-xs font-black uppercase tracking-wider text-[#555] mb-1">Date & Time</p>
                    @if($event['start_date'])
                        <p class="font-bold text-[#F0F0F0]">{{ $event['start_date']->format('D, M j, Y') }}</p>
                        @if(!($event['tbd'] ?? false))
                            <p class="text-sm text-[#888]">{{ $event['start_date']->format('g:i A') }}</p>
                        @else
                            <p class="text-sm text-[#888]">Time TBD</p>
                        @endif
                    @else
                        <p class="font-bold text-[#888]">Date TBD</p>
                    @endif
                </div>
                <div class="p-5 border-b-2 sm:border-b-0 sm:border-r-2 border-[#2A2A2A]">
                    <p class="text-xs font-black uppercase tracking-wider text-[#555] mb-1">Venue</p>
                    <p class="font-bold text-[#F0F0F0]">{{ $event['location'] }}</p>
                    <p class="text-sm text-[#888]">{{ $event['city'] }}{{ $event['state'] ? ', ' . $event['state'] : '' }}</p>
                </div>
                <div class="p-5">
                    <p class="text-xs font-black uppercase tracking-wider text-[#555] mb-1">Price</p>
                    @if($event['is_free'])
                        <p class="font-black text-[#FFD600] text-lg uppercase">Free</p>
                    @elseif($event['price'])
                        <p class="font-bold text-[#F0F0F0] text-lg">${{ number_format($event['price'], 0) }}
                            @if($event['price_max'] && $event['price_max'] > $event['price'])
                                – ${{ number_format($event['price_max'], 0) }}
                            @endif
                        </p>
                        <p class="text-xs text-[#555]">{{ $event['currency'] }}</p>
                    @else
                        <p class="font-bold text-[#888]">See tickets</p>
                    @endif
                </div>
            </div>

            {{-- Description --}}
            <div class="mb-8">
                <div class="section-label mb-3">About This Event</div>
                <p class="text-[#888] leading-relaxed">{{ $event['description'] }}</p>
            </div>

            {{-- Address --}}
            @if($event['address'])
                <div class="border-2 border-[#2A2A2A] p-5 mb-8">
                    <div class="section-label mb-3">Location</div>
                    <div class="flex items-start gap-3">
                        <svg class="w-5 h-5 text-[#E11D48] shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                        <div>
                            <p class="font-bold text-[#F0F0F0]">{{ $event['location'] }}</p>
                            <p class="text-sm text-[#888]">{{ $event['address'] }}</p>
                        </div>
                    </div>
                </div>
            @endif

            {{-- Tags --}}
            @if(!empty($event['tags']))
                <div class="flex flex-wrap gap-2">
                    @foreach(array_filter($event['tags']) as $tag)
                        <span class="nb-tag">{{ $tag }}</span>
                    @endforeach
                </div>
            @endif
        </div>

        {{-- Sidebar --}}
        <div class="lg:w-80 shrink-0">
            <div class="bg-[#141414] border-2 border-[#2A2A2A] shadow-[4px_4px_0_#E11D48] sticky top-20">
                <div class="p-5 border-b-2 border-[#2A2A2A]">
                    <p class="text-xs font-black uppercase tracking-wider text-[#555] mb-1">
                        {{ $event['is_free'] ? 'Free Event' : 'Starting From' }}
                    </p>
                    @if($event['is_free'])
                        <p class="font-black text-[#FFD600] text-3xl uppercase">Free</p>
                    @elseif($event['price'])
                        <p class="font-black text-[#F0F0F0] text-3xl">${{ number_format($event['price'], 0) }}</p>
                    @else
                        <p class="font-bold text-[#888] text-xl">Price TBD</p>
                    @endif
                </div>

                <div class="p-5 space-y-3">
                    {{-- Buy tickets link --}}
                    @if($event['external_url'] && $event['external_url'] !== '#')
                        <a href="{{ $event['external_url'] }}" target="_blank" rel="noopener"
                           class="nb-btn w-full text-center py-3 text-sm block">
                            Get Tickets →
                        </a>
                    @endif

                    {{-- RSVP (our platform) --}}
                    @auth
                        <form method="POST" action="{{ route('rsvp.toggle', $event['slug']) }}">
                            @csrf
                            <button type="submit"
                                    class="{{ $hasRsvped ? 'nb-btn-ghost' : 'nb-btn-yellow' }} w-full text-sm py-3 text-center cursor-pointer">
                                {{ $hasRsvped ? 'Cancel Attendance' : '✓ Mark Attending' }}
                            </button>
                        </form>
                    @else
                        <a href="{{ route('login') }}" class="nb-btn-ghost w-full text-sm py-3 text-center block">
                            Sign in to RSVP
                        </a>
                    @endauth

                    <hr class="nb-divider">

                    {{-- Meta --}}
                    <div class="space-y-3 pt-1">
                        @if($event['start_date'])
                            <div class="flex items-center gap-3 text-sm">
                                <svg class="w-4 h-4 text-[#E11D48] shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                                <span class="text-[#888]">{{ $event['start_date']->format('D, M j, Y') }}</span>
                            </div>
                        @endif
                        <div class="flex items-center gap-3 text-sm">
                            <svg class="w-4 h-4 text-[#E11D48] shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                            <span class="text-[#888]">{{ $event['city'] }}{{ $event['state'] ? ', ' . $event['state'] : '' }}</span>
                        </div>
                        <div class="flex items-center gap-3 text-sm">
                            <svg class="w-4 h-4 text-[#E11D48] shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/></svg>
                            <span class="text-[#888]">{{ $event['location'] }}</span>
                        </div>
                    </div>

                    <hr class="nb-divider">
                    <p class="text-xs text-[#333] text-center">Powered by Ticketmaster</p>
                </div>
            </div>
        </div>

    </div>
</div>

@endsection
