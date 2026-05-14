@extends('layouts.app')
@section('title', 'EventSphere — Real Events, Any City')

@section('content')

{{-- ═══════════ FIXED BG: Aurora blobs + Particle canvas ═══════════ --}}
<div id="es-aurora" aria-hidden="true" style="position:fixed;inset:0;z-index:-2;pointer-events:none;overflow:hidden;">
    {{-- Rose top-left primary --}}
    <div style="position:absolute;width:780px;height:780px;top:-200px;left:8%;border-radius:50%;filter:blur(80px);background:radial-gradient(circle,rgba(225,29,72,0.18),transparent 65%);animation:esBlob1 22s ease-in-out infinite;"></div>
    {{-- Violet right mid --}}
    <div style="position:absolute;width:620px;height:620px;top:25%;right:-120px;border-radius:50%;filter:blur(75px);background:radial-gradient(circle,rgba(124,58,237,0.16),transparent 65%);animation:esBlob2 28s ease-in-out infinite;"></div>
    {{-- Rose bottom-left --}}
    <div style="position:absolute;width:500px;height:500px;bottom:5%;left:-80px;border-radius:50%;filter:blur(95px);background:radial-gradient(circle,rgba(225,29,72,0.12),transparent 65%);animation:esBlob3 20s ease-in-out infinite;"></div>
    {{-- Violet center-lower --}}
    <div style="position:absolute;width:420px;height:420px;top:65%;left:38%;border-radius:50%;filter:blur(100px);background:radial-gradient(circle,rgba(124,58,237,0.10),transparent 65%);animation:esBlob1 33s ease-in-out infinite reverse;"></div>
    {{-- Rose-violet mix center accent --}}
    <div style="position:absolute;width:320px;height:320px;top:28%;left:44%;border-radius:50%;filter:blur(90px);background:radial-gradient(circle,rgba(200,40,180,0.09),transparent 65%);animation:esBlob2 25s ease-in-out infinite reverse;"></div>
</div>
<canvas id="es-canvas" aria-hidden="true" style="position:fixed;inset:0;width:100vw;height:100vh;z-index:-1;pointer-events:none;"></canvas>

{{-- ═══════════ HERO ═══════════ --}}
<section class="relative flex flex-col justify-center overflow-hidden" style="min-height: 88vh;">

    {{-- Layered background --}}
    <div class="absolute inset-0 pointer-events-none" aria-hidden="true" style="z-index:0;">
        <div class="absolute top-0 left-1/2 -translate-x-1/2 w-[1100px] h-[600px] rounded-full opacity-[0.09]"
             style="background: radial-gradient(ellipse at center, #E11D48 0%, transparent 65%);"></div>
        <div class="absolute top-1/3 right-0 w-[500px] h-[500px] rounded-full opacity-[0.04]"
             style="background: radial-gradient(ellipse, #FFD600, transparent 70%);"></div>
        <div class="absolute bottom-0 left-0 w-[400px] h-[400px] rounded-full opacity-[0.03]"
             style="background: radial-gradient(ellipse, #7C3AED, transparent 70%);"></div>
    </div>

    <div class="relative max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 w-full pt-24 pb-16" style="z-index:1;">
        <div class="grid lg:grid-cols-2 gap-12 items-center">

            {{-- Left: copy + search --}}
            <div>
                <div class="inline-flex items-center gap-2 px-3 py-1.5 rounded-full text-xs font-bold uppercase tracking-widest mb-6"
                     style="background: rgba(225,29,72,0.12); border: 1px solid rgba(225,29,72,0.25); color: #E11D48;">
                    <span class="w-1.5 h-1.5 rounded-full bg-[#E11D48] animate-pulse inline-block"></span>
                    Live Events Worldwide
                </div>

                <h1 class="heading-xl mb-5 leading-[1.05]">
                    Discover<br>
                    <span style="background: linear-gradient(135deg, #fff 0%, rgba(255,255,255,0.6) 100%); -webkit-background-clip: text; -webkit-text-fill-color: transparent; background-clip: text;">the World's</span><br>
                    <span style="color: #E11D48;">Best Events.</span>
                </h1>

                <p class="text-[#666] text-lg leading-relaxed mb-10 max-w-lg">
                    Concerts, sports, arts, tech — every city, every country. Real-time data from 3 global sources.
                </p>

                {{-- Search --}}
                <form action="{{ route('events.index') }}" method="GET" id="hero-form"
                      class="relative flex flex-col sm:flex-row gap-2 max-w-xl mb-4">
                    <div class="flex-1 relative">
                        <svg class="absolute left-4 top-1/2 -translate-y-1/2 w-4 h-4 pointer-events-none" style="color:#555;" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/>
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/>
                        </svg>
                        <input id="city-input" type="text" name="city"
                               value="" autocomplete="off"
                               placeholder="New York, Mumbai, London…"
                               class="es-input text-base w-full"
                               style="height: 52px; border-radius: 12px; padding-left: 44px;">
                    </div>
                    <button type="submit" class="es-btn h-13 px-8 text-base font-bold" style="height:52px; border-radius:12px; min-width:140px;">
                        Find Events
                    </button>
                </form>

                {{-- Live preview --}}
                <div id="live-preview" class="hidden max-w-xl rounded-xl overflow-hidden mb-5"
                     style="background: #181818; border: 1px solid rgba(255,255,255,0.1); box-shadow: 0 20px 60px rgba(0,0,0,0.7);">
                    <div id="live-preview-content"></div>
                </div>

                {{-- City chips --}}
                <div class="flex flex-wrap gap-2 items-center">
                    <span class="text-xs font-bold text-[#333] uppercase tracking-widest">Try:</span>
                    @foreach(['Mumbai','Delhi','Bangalore','New York','London','Tokyo'] as $c)
                        <a href="{{ route('events.index') }}?city={{ urlencode($c) }}"
                           class="es-tag text-xs cursor-pointer">{{ $c }}</a>
                    @endforeach
                </div>
            </div>

            {{-- Right: Live departure board --}}
            <div class="hidden lg:block">
                <div class="rounded-2xl overflow-hidden" style="background:#0e0e0e; border:1px solid rgba(255,255,255,0.08);">

                    {{-- Board header --}}
                    <div class="flex items-center justify-between px-5 py-3.5" style="border-bottom:1px solid rgba(255,255,255,0.06);">
                        <div class="flex items-center gap-2">
                            <span class="w-2 h-2 rounded-full bg-[#E11D48]" style="animation:pulse 1.8s ease-in-out infinite;"></span>
                            <span style="font-size:0.68rem;font-weight:800;letter-spacing:.12em;text-transform:uppercase;color:#E11D48;">Live Now</span>
                        </div>
                        <span style="font-size:0.68rem;color:#333;font-family:monospace;">{{ count($worldEvents) }} events</span>
                    </div>

                    {{-- Scrolling rows --}}
                    <div style="height:340px;overflow:hidden;position:relative;">
                        {{-- Top fade --}}
                        <div style="position:absolute;top:0;left:0;right:0;height:40px;z-index:2;pointer-events:none;background:linear-gradient(to bottom,#0e0e0e,transparent);"></div>
                        {{-- Bottom fade --}}
                        <div style="position:absolute;bottom:0;left:0;right:0;height:40px;z-index:2;pointer-events:none;background:linear-gradient(to top,#0e0e0e,transparent);"></div>

                        <div id="board-track" style="display:flex;flex-direction:column;will-change:transform;">
                            @php
                                $boardEvents = !empty($boardEvents) ? $boardEvents : ($upcomingEvents ?? []);
                            @endphp
                            @foreach($boardEvents as $ev)
                            @php
                                $evIsArr  = is_array($ev);
                                $evSlug   = $evIsArr ? $ev['slug'] : $ev->slug;
                                $evTitle  = $evIsArr ? $ev['title'] : $ev->title;
                                $evCity   = $evIsArr ? ($ev['city'] ?? 'Worldwide') : ($ev->city ?? 'Worldwide');
                                $evDate   = $evIsArr ? ($ev['start_date'] ?? null) : ($ev->start_date ?? null);
                                $evSrc    = $evIsArr ? ($ev['source'] ?? 'live') : 'local';
                                $evImg    = $evIsArr ? ($ev['image_url'] ?? '') : ($ev->image ? asset('storage/events/'.$ev->image) : '');
                                $evGenre  = $evIsArr ? ($ev['genre'] ?? $ev['category'] ?? '') : ($ev->category->name ?? '');
                                try { $evFmt = $evDate ? (is_string($evDate) ? \Carbon\Carbon::parse($evDate) : $evDate)->format('d M') : ''; } catch(\Throwable $e) { $evFmt=''; }
                                $dotColor = $evSrc === 'google' ? '#4285F4' : ($evSrc === 'eventbrite' ? '#ff6600' : '#E11D48');
                            @endphp
                            <a href="{{ route('events.show', $evSlug) }}"
                               class="board-row flex items-center gap-3 px-4 py-3 group"
                               style="border-bottom:1px solid rgba(255,255,255,0.04);text-decoration:none;transition:background 0.15s;"
                               onmouseover="this.style.background='rgba(255,255,255,0.04)'"
                               onmouseout="this.style.background='transparent'">
                                {{-- Thumbnail --}}
                                <div class="shrink-0 rounded-lg overflow-hidden" style="width:38px;height:38px;">
                                    <img src="{{ $evImg ?: 'https://picsum.photos/seed/'.$evSlug.'/80/80' }}"
                                         alt="" class="w-full h-full object-cover" loading="lazy"
                                         onerror="this.style.display='none';this.parentElement.style.background='{{ $dotColor }}33'">
                                </div>
                                {{-- Info --}}
                                <div style="flex:1;min-width:0;">
                                    <p style="color:#e0e0e0;font-size:0.8rem;font-weight:600;white-space:nowrap;overflow:hidden;text-overflow:ellipsis;line-height:1.2;">{{ $evTitle }}</p>
                                    <p style="color:#444;font-size:0.7rem;margin-top:2px;white-space:nowrap;overflow:hidden;text-overflow:ellipsis;">{{ $evCity }}@if($evGenre) &middot; {{ $evGenre }}@endif</p>
                                </div>
                                {{-- Date --}}
                                <div style="text-align:right;flex-shrink:0;">
                                    @if($evFmt)<p style="color:#666;font-size:0.7rem;font-family:monospace;white-space:nowrap;">{{ $evFmt }}</p>@endif
                                    <span style="display:inline-block;width:6px;height:6px;border-radius:50%;background:{{ $dotColor }};margin-top:4px;"></span>
                                </div>
                            </a>
                            @endforeach
                        </div>
                    </div>
                </div>
            </div>

        </div>
    </div>

</section>


{{-- ═══════════ WORLD EVENTS CAROUSEL ═══════════ --}}
@if(!empty($worldEvents) && count($worldEvents) > 0)
<section class="py-16">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">

        <div class="flex items-center justify-between mb-8">
            <div>
                <div class="es-label mb-1">Global</div>
                <h2 class="heading-md text-white">Trending Worldwide</h2>
            </div>
            <a href="{{ route('events.index') }}" class="es-btn-ghost text-sm py-2 px-4">See all →</a>
        </div>

        {{-- Marquee outer: overflow hidden, edge fade masks --}}
        <div id="marquee-outer" style="position:relative;overflow:hidden;">
            {{-- Left fade --}}
            <div style="position:absolute;left:0;top:0;bottom:0;width:80px;z-index:2;pointer-events:none;background:linear-gradient(to right,#080808,transparent);"></div>
            {{-- Right fade --}}
            <div style="position:absolute;right:0;top:0;bottom:0;width:80px;z-index:2;pointer-events:none;background:linear-gradient(to left,#080808,transparent);"></div>

            {{-- Marquee track --}}
            <div id="marquee-track" style="display:flex;gap:20px;width:max-content;will-change:transform;">
                @foreach($worldEvents as $event)
                @php
                    $isArr     = is_array($event);
                    $slug      = $isArr ? $event['slug'] : $event->slug;
                    $title     = $isArr ? $event['title'] : $event->title;
                    $imageUrl  = $isArr ? ($event['image_url'] ?? '') : ($event->image ? asset('storage/events/'.$event->image) : '');
                    $city      = $isArr ? ($event['city'] ?? '') : ($event->city ?? '');
                    $category  = $isArr ? ($event['genre'] ?? $event['category'] ?? 'Event') : ($event->category->name ?? 'Event');
                    $isFree    = $isArr ? ($event['is_free'] ?? false) : ($event->is_free ?? false);
                    $price     = $isArr ? ($event['price'] ?? null) : ($event->price ?? null);
                    $startDate = $isArr ? ($event['start_date'] ?? null) : ($event->start_date ?? null);
                    $source    = $isArr ? ($event['source'] ?? 'ticketmaster') : 'local';
                    $currency  = $isArr ? ($event['currency'] ?? 'USD') : 'USD';
                @endphp
                <a href="{{ route('events.show', $slug) }}"
                   class="marquee-card flex-none rounded-2xl overflow-hidden cursor-pointer block"
                   style="width:360px;background:#161616;border:1px solid rgba(255,255,255,0.08);transition:transform 0.3s,box-shadow 0.3s;"
                   onmouseover="marqueeStop();this.style.transform='translateY(-8px)';this.style.boxShadow='0 24px 64px rgba(0,0,0,0.7)';"
                   onmouseout="marqueeStart();this.style.transform='translateY(0)';this.style.boxShadow='none';">

                    {{-- Image --}}
                    <div class="relative overflow-hidden" style="height:210px;">
                        <img src="{{ $imageUrl ?: 'https://picsum.photos/seed/'.$slug.'/720/420' }}"
                             alt="{{ $title }}"
                             class="w-full h-full object-cover"
                             style="transition:transform 0.6s;"
                             onmouseover="this.style.transform='scale(1.07)'"
                             onmouseout="this.style.transform='scale(1)'"
                             loading="lazy"
                             onerror="this.src='https://picsum.photos/seed/ev{{ $loop->index }}/720/420'">
                        <div class="absolute inset-0" style="background:linear-gradient(to top,#161616 0%,rgba(0,0,0,0.25) 50%,transparent 100%);"></div>

                        {{-- Source badge --}}
                        <div class="absolute top-3 left-3">
                            @if($source === 'google')
                                <span style="font-size:0.7rem;font-weight:800;padding:3px 10px;border-radius:20px;background:rgba(66,133,244,0.92);color:#fff;letter-spacing:.03em;">Google</span>
                            @elseif($source === 'eventbrite')
                                <span style="font-size:0.7rem;font-weight:800;padding:3px 10px;border-radius:20px;background:rgba(255,102,0,0.92);color:#fff;letter-spacing:.03em;">Eventbrite</span>
                            @else
                                <span style="font-size:0.7rem;font-weight:800;padding:3px 10px;border-radius:20px;background:rgba(225,29,72,0.92);color:#fff;letter-spacing:.03em;">Live</span>
                            @endif
                        </div>

                        {{-- Price badge --}}
                        @if($isFree)
                        <div class="absolute top-3 right-3">
                            <span style="font-size:0.7rem;font-weight:800;padding:3px 10px;border-radius:20px;background:rgba(16,185,129,0.92);color:#fff;letter-spacing:.03em;">FREE</span>
                        </div>
                        @elseif($price)
                        <div class="absolute top-3 right-3">
                            <span style="font-size:0.7rem;font-weight:700;padding:3px 10px;border-radius:20px;background:rgba(0,0,0,0.8);color:#fff;border:1px solid rgba(255,255,255,0.12);">{{ $currency === 'INR' ? '₹' : '$' }}{{ number_format($price, 0) }}</span>
                        </div>
                        @endif
                    </div>

                    {{-- Content --}}
                    <div class="p-5">
                        <p style="font-size:0.68rem;font-weight:800;letter-spacing:.1em;text-transform:uppercase;color:#E11D48;margin-bottom:6px;white-space:nowrap;overflow:hidden;text-overflow:ellipsis;">{{ $category }}</p>
                        <p style="color:#fff;font-weight:700;font-size:1rem;line-height:1.35;margin-bottom:10px;display:-webkit-box;-webkit-line-clamp:2;-webkit-box-orient:vertical;overflow:hidden;min-height:2.7rem;">{{ $title }}</p>
                        <div style="display:flex;align-items:center;gap:6px;font-size:0.75rem;color:#555;">
                            <svg style="width:12px;height:12px;flex-shrink:0;" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                            <span style="max-width:140px;white-space:nowrap;overflow:hidden;text-overflow:ellipsis;">{{ $city ?: 'Worldwide' }}</span>
                            @if($startDate)
                            @php try { $fmt = (is_string($startDate) ? \Carbon\Carbon::parse($startDate) : $startDate)->format('M j, Y'); } catch(\Throwable $e) { $fmt=''; } @endphp
                            @if($fmt)<span style="color:#333;">·</span><span>{{ $fmt }}</span>@endif
                            @endif
                        </div>
                    </div>
                </a>
                @endforeach
            </div>
        </div>
    </div>
</section>
@endif


{{-- ═══════════ CATEGORIES ═══════════ --}}
@if($categories->count())
<section class="py-16">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex items-center justify-between mb-8">
            <div>
                <div class="es-label mb-1">Explore</div>
                <h2 class="heading-md text-white">Browse by Category</h2>
            </div>
            <a href="{{ route('events.index') }}" class="es-btn-ghost text-sm py-2 px-4">All events →</a>
        </div>

        <div class="grid grid-cols-3 sm:grid-cols-4 md:grid-cols-6 gap-3">
            @php
            $categoryIcons = [
                'music'      => '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19V6l12-3v13M9 19c0 1.105-1.343 2-3 2s-3-.895-3-2 1.343-2 3-2 3 .895 3 2zm12-3c0 1.105-1.343 2-3 2s-3-.895-3-2 1.343-2 3-2 3 .895 3 2zM9 10l12-3"/>',
                'sports'     => '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3.055 11H5a2 2 0 012 2v1a2 2 0 002 2 2 2 0 012 2v2.945M8 3.935V5.5A2.5 2.5 0 0010.5 8h.5a2 2 0 012 2 2 2 0 104 0 2 2 0 012-2h1.064M15 20.488V18a2 2 0 012-2h3.064M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>',
                'arts'       => '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 21a4 4 0 01-4-4V5a2 2 0 012-2h4a2 2 0 012 2v12a4 4 0 01-4 4zm0 0h12a2 2 0 002-2v-4a2 2 0 00-2-2h-2.343M11 7.343l1.657-1.657a2 2 0 012.828 0l2.829 2.829a2 2 0 010 2.828l-8.486 8.485M7 17h.01"/>',
                'technology' => '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.75 17L9 20l-1 1h8l-1-1-.75-3M3 13h18M5 17h14a2 2 0 002-2V5a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>',
                'food'       => '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6V4m0 2a2 2 0 100 4m0-4a2 2 0 110 4m-6 8a2 2 0 100-4m0 4a2 2 0 110-4m0 4v2m0-6V4m6 6v10m6-2a2 2 0 100-4m0 4a2 2 0 110-4m0 4v2m0-6V4"/>',
                'default'    => '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>',
            ];
            @endphp
            @foreach($categories as $cat)
            @php
                $iconKey = strtolower($cat->slug ?? '');
                $iconPath = $categoryIcons[$iconKey] ?? $categoryIcons['default'];
                $color = $cat->color ?? '#E11D48';
            @endphp
            <a href="{{ route('events.index') }}?category={{ $cat->slug }}"
               class="group flex flex-col items-center gap-3 p-4 rounded-2xl transition-all duration-200 cursor-pointer"
               style="background: rgba(255,255,255,0.03); border: 1px solid rgba(255,255,255,0.07);"
               onmouseover="this.style.borderColor='{{ $color }}55';this.style.background='{{ $color }}0D';this.style.transform='translateY(-2px)'"
               onmouseout="this.style.borderColor='rgba(255,255,255,0.07)';this.style.background='rgba(255,255,255,0.03)';this.style.transform='translateY(0)'">
                <div class="w-10 h-10 rounded-xl flex items-center justify-center transition-all"
                     style="background: {{ $color }}18; border: 1px solid {{ $color }}30;">
                    <svg class="w-5 h-5" style="color: {{ $color }};" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        {!! $iconPath !!}
                    </svg>
                </div>
                <span class="text-xs font-semibold text-[#666] group-hover:text-white transition-colors text-center leading-tight">{{ $cat->name }}</span>
            </a>
            @endforeach
        </div>
    </div>
</section>
@endif


{{-- ═══════════ CITY / UPCOMING EVENTS ═══════════ --}}
<section class="py-16">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex items-center justify-between mb-8">
            <div>
                <div class="es-label mb-1">{{ $lastCity ? 'Near You' : 'Coming Up' }}</div>
                <h2 class="heading-md text-white">
                    {{ $lastCity ? 'Events in '.$lastCity : 'Upcoming Events' }}
                </h2>
            </div>
            <a href="{{ route('events.index') }}{{ $lastCity ? '?city='.urlencode($lastCity) : '' }}"
               class="es-btn-ghost text-sm py-2 px-4">More →</a>
        </div>

        @if(!empty($upcomingEvents) && count($upcomingEvents) > 0)
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
                @foreach($upcomingEvents as $event)
                    @include('components.event-card-sm', ['event' => $event])
                @endforeach
            </div>
        @else
            <div class="py-20 flex flex-col items-center justify-center rounded-2xl text-center"
                 style="background: rgba(255,255,255,0.02); border: 1px dashed rgba(255,255,255,0.08);">
                <svg class="w-12 h-12 text-[#333] mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                </svg>
                <p class="text-[#555] font-semibold mb-1">No events found</p>
                <p class="text-sm text-[#333]">Search a city above to find live events.</p>
            </div>
        @endif
    </div>
</section>


{{-- ═══════════ HOST YOUR EVENT ═══════════ --}}
<section class="py-20">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="relative rounded-3xl overflow-hidden"
             style="background: linear-gradient(135deg, #0f0f0f 0%, #1a0a0f 50%, #0f0f0f 100%); border: 1px solid rgba(225,29,72,0.15);">

            {{-- Glows --}}
            <div class="absolute -top-24 -left-24 w-96 h-96 rounded-full pointer-events-none"
                 style="background: radial-gradient(circle, rgba(225,29,72,0.15), transparent 65%);"></div>
            <div class="absolute -bottom-24 -right-24 w-80 h-80 rounded-full pointer-events-none"
                 style="background: radial-gradient(circle, rgba(255,214,0,0.06), transparent 65%);"></div>

            <div class="relative z-10 px-8 py-14 md:px-16 md:py-16">
                <div class="flex flex-col lg:flex-row lg:items-center lg:justify-between gap-10">

                    {{-- Left: copy --}}
                    <div class="lg:max-w-lg">
                        <div class="es-label mb-4">For Organizers</div>
                        <h2 class="text-white font-black mb-4" style="font-size: clamp(2rem, 4vw, 3rem); line-height: 1.1;">
                            Ready to Host<br>Your Own Event?
                        </h2>
                        <p class="text-[#666] text-base leading-relaxed mb-6">
                            Create events, manage RSVPs, and grow your audience — all in one platform. Completely free.
                        </p>
                        <div class="grid grid-cols-2 gap-3">
                            @foreach(['Unlimited events', 'RSVP management', 'Real-time stats', 'Free forever'] as $feature)
                            <div class="flex items-center gap-2 text-sm text-[#888]">
                                <div class="w-4 h-4 rounded-full flex items-center justify-center shrink-0" style="background: rgba(225,29,72,0.2); border: 1px solid rgba(225,29,72,0.35);">
                                    <svg class="w-2.5 h-2.5" style="color:#E11D48;" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 13l4 4L19 7"/>
                                    </svg>
                                </div>
                                {{ $feature }}
                            </div>
                            @endforeach
                        </div>
                    </div>

                    {{-- Right: buttons --}}
                    <div class="flex flex-col gap-3" style="min-width: 240px; max-width: 280px;">
                        @auth
                            <a href="{{ route('events.create') }}"
                               class="es-btn text-base py-4 px-8 font-bold text-center block"
                               style="border-radius: 14px;">
                                + Create Your Event
                            </a>
                            <a href="{{ route('events.mine') }}" class="es-btn-ghost text-sm py-3 text-center block" style="border-radius:14px;">
                                Manage My Events →
                            </a>
                        @else
                            <a href="{{ route('register') }}"
                               class="es-btn text-base py-4 px-8 font-bold text-center block"
                               style="border-radius: 14px;">
                                Join Free &amp; Host Events
                            </a>
                            <a href="{{ route('login') }}" class="es-btn-ghost text-sm py-3 text-center block" style="border-radius:14px;">
                                Already have an account? Sign in →
                            </a>
                        @endauth
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

@endsection

@push('scripts')
<script>
// ── Live search preview ──────────────────────────────────────────────────
const cityInput = document.getElementById('city-input');
const preview   = document.getElementById('live-preview');
const content   = document.getElementById('live-preview-content');
let   debounce;

if (cityInput) {
    cityInput.addEventListener('input', () => {
        clearTimeout(debounce);
        const val = cityInput.value.trim();
        if (val.length < 2) { preview.classList.add('hidden'); return; }

        debounce = setTimeout(async () => {
            try {
                const res  = await fetch(`/api/live-search?city=${encodeURIComponent(val)}&_=${Date.now()}`);
                const data = await res.json();
                if (!data.events || data.events.length === 0) { preview.classList.add('hidden'); return; }

                content.innerHTML = data.events.slice(0, 5).map(e => {
                    const date  = e.start_date_raw ? new Date(e.start_date_raw).toLocaleDateString('en-US',{month:'short',day:'numeric'}) : 'TBD';
                    const price = e.is_free
                        ? '<span style="color:#E11D48;font-weight:800;font-size:0.65rem;letter-spacing:0.05em;">FREE</span>'
                        : (e.price ? `<span style="color:#666;font-size:0.75rem;">${e.currency==='INR'?'₹':'$'}${Math.floor(e.price)}</span>` : '');
                    return `<a href="/events/${e.slug}"
                               style="display:flex;align-items:center;gap:12px;padding:10px 14px;border-bottom:1px solid rgba(255,255,255,0.05);text-decoration:none;transition:background 0.12s;"
                               onmouseover="this.style.background='rgba(255,255,255,0.04)'"
                               onmouseout="this.style.background='transparent'">
                        <img src="${e.image_url}" style="width:44px;height:44px;object-fit:cover;border-radius:8px;flex-shrink:0;border:1px solid rgba(255,255,255,0.08);" alt="" loading="lazy" onerror="this.src='https://picsum.photos/seed/${e.slug}/44/44'">
                        <div style="flex:1;min-width:0;">
                            <p style="color:#F2F2F2;font-size:0.84rem;font-weight:600;white-space:nowrap;overflow:hidden;text-overflow:ellipsis;">${e.title}</p>
                            <p style="color:#555;font-size:0.72rem;margin-top:2px;">${date} · ${e.city || ''}</p>
                        </div>
                        ${price}
                    </a>`;
                }).join('');

                preview.classList.remove('hidden');
            } catch (_) {}
        }, 320);
    });

    document.addEventListener('click', e => {
        if (!cityInput.contains(e.target) && !preview.contains(e.target)) {
            preview.classList.add('hidden');
        }
    });
}

// ── Continuous marquee carousel ──────────────────────────────────────────
(function () {
    const track = document.getElementById('marquee-track');
    if (!track) return;

    // Clone all original cards to make seamless loop
    const origCards = Array.from(track.children);
    origCards.forEach(card => {
        const clone = card.cloneNode(true);
        clone.setAttribute('aria-hidden', 'true');
        track.appendChild(clone);
    });

    let pos       = 0;
    let stopped   = false;
    let rafId     = null;
    const speed   = 0.9; // px per frame ≈ 54px/s at 60fps

    // Original set width = half of total (since we cloned)
    function getHalfWidth() {
        return track.scrollWidth / 2;
    }

    function tick() {
        if (!stopped) {
            pos += speed;
            if (pos >= getHalfWidth()) pos = 0;
            track.style.transform = `translateX(-${pos}px)`;
        }
        rafId = requestAnimationFrame(tick);
    }

    window.marqueeStop  = () => { stopped = true; };
    window.marqueeStart = () => { stopped = false; };

    rafId = requestAnimationFrame(tick);
})();

// ── Departure board vertical scroll ──────────────────────────────────────
(function () {
    const board = document.getElementById('board-track');
    if (!board) return;

    // Clone rows for seamless vertical loop
    Array.from(board.children).forEach(row => {
        const clone = row.cloneNode(true);
        clone.setAttribute('aria-hidden', 'true');
        board.appendChild(clone);
    });

    let posY    = 0;
    let boardOn = true;
    const ROW_H = 57; // px per row (38px thumb + 2*3px pad + border ≈ 57)

    function boardTick() {
        if (boardOn) {
            posY += 0.45;
            const halfH = board.scrollHeight / 2;
            if (posY >= halfH) posY = 0;
            board.style.transform = `translateY(-${posY}px)`;
        }
        requestAnimationFrame(boardTick);
    }

    const outer = board.closest('[style*="height:340px"]');
    if (outer) {
        outer.addEventListener('mouseenter', () => boardOn = false);
        outer.addEventListener('mouseleave', () => boardOn = true);
    }

    requestAnimationFrame(boardTick);
})();

// ── Aurora particle constellation (enhanced) ─────────────────────────────
(function () {
    const canvas = document.getElementById('es-canvas');
    if (!canvas) return;
    if (window.matchMedia('(prefers-reduced-motion: reduce)').matches) return;

    const ctx    = canvas.getContext('2d');
    const COUNT  = 85;
    const LINK   = 155;
    const REPEL  = 130;   // mouse repulsion radius
    const MAXSPD = 1.6;
    let W, H, pts, mouseX = -9999, mouseY = -9999;

    window.addEventListener('mousemove', e => { mouseX = e.clientX; mouseY = e.clientY; });

    function init() {
        W = canvas.width  = window.innerWidth;
        H = canvas.height = window.innerHeight;
        pts = Array.from({ length: COUNT }, () => {
            const star = Math.random() < 0.14;   // ~14% are star nodes
            return {
                x    : Math.random() * W,
                y    : Math.random() * H,
                vx   : (Math.random() - 0.5) * 0.28,
                vy   : (Math.random() - 0.5) * 0.28,
                r    : star ? Math.random() * 2.2 + 1.8 : Math.random() * 1.2 + 0.4,
                hue  : Math.random() < 0.58 ? 346 : 263,
                star,
                phase: Math.random() * Math.PI * 2,   // for pulse offset
            };
        });
    }

    function clampSpeed(p) {
        const spd = Math.sqrt(p.vx * p.vx + p.vy * p.vy);
        if (spd > MAXSPD) { p.vx = p.vx / spd * MAXSPD; p.vy = p.vy / spd * MAXSPD; }
    }

    let tick = 0;
    function frame() {
        tick++;
        ctx.clearRect(0, 0, W, H);

        // Draw connections first (below dots)
        for (let i = 0; i < COUNT; i++) {
            const p = pts[i];
            for (let j = i + 1; j < COUNT; j++) {
                const q  = pts[j];
                const dx = p.x - q.x, dy = p.y - q.y;
                const d  = Math.sqrt(dx * dx + dy * dy);
                if (d < LINK) {
                    const alpha = (1 - d / LINK) * 0.18;
                    const grad  = ctx.createLinearGradient(p.x, p.y, q.x, q.y);
                    grad.addColorStop(0, `hsla(${p.hue},75%,62%,${alpha})`);
                    grad.addColorStop(1, `hsla(${q.hue},75%,62%,${alpha})`);
                    ctx.beginPath();
                    ctx.moveTo(p.x, p.y);
                    ctx.lineTo(q.x, q.y);
                    ctx.strokeStyle = grad;
                    ctx.lineWidth   = p.star || q.star ? 0.9 : 0.55;
                    ctx.stroke();
                }
            }
        }

        // Draw + update particles
        for (let i = 0; i < COUNT; i++) {
            const p = pts[i];

            // Mouse repulsion
            const mdx = p.x - mouseX, mdy = p.y - mouseY;
            const md  = Math.sqrt(mdx * mdx + mdy * mdy);
            if (md < REPEL && md > 0) {
                const force = (REPEL - md) / REPEL * 0.018;
                p.vx += (mdx / md) * force;
                p.vy += (mdy / md) * force;
                clampSpeed(p);
            }

            p.x += p.vx; p.y += p.vy;
            if (p.x < 0 || p.x > W) p.vx *= -1;
            if (p.y < 0 || p.y > H) p.vy *= -1;

            // Pulsing radius for star nodes
            const pulse = p.star ? p.r + Math.sin(tick * 0.025 + p.phase) * 0.7 : p.r;
            const alpha = p.star ? 0.72 : 0.46;

            // Glow
            ctx.shadowBlur  = p.star ? 18 : 7;
            ctx.shadowColor = `hsla(${p.hue},90%,65%,0.7)`;

            ctx.beginPath();
            ctx.arc(p.x, p.y, pulse, 0, 6.283);
            ctx.fillStyle = `hsla(${p.hue},85%,68%,${alpha})`;
            ctx.fill();

            // Extra outer ring on star nodes
            if (p.star) {
                ctx.beginPath();
                ctx.arc(p.x, p.y, pulse + 3, 0, 6.283);
                ctx.strokeStyle = `hsla(${p.hue},80%,65%,0.18)`;
                ctx.lineWidth   = 1;
                ctx.stroke();
            }

            ctx.shadowBlur = 0;
        }

        requestAnimationFrame(frame);
    }

    init();
    window.addEventListener('resize', init);
    requestAnimationFrame(frame);
})();
</script>
<style>
#marquee-outer { cursor: default; }
.marquee-card  { text-decoration: none; }
.board-row     { cursor: pointer; }

@keyframes esBlob1 {
    0%   { transform: translate(0,0) scale(1) rotate(0deg); }
    20%  { transform: translate(55px,-45px) scale(1.08) rotate(6deg); }
    45%  { transform: translate(20px,-80px) scale(1.03) rotate(-3deg); }
    70%  { transform: translate(-50px,40px) scale(0.94) rotate(8deg); }
    100% { transform: translate(0,0) scale(1) rotate(0deg); }
}
@keyframes esBlob2 {
    0%   { transform: translate(0,0) scale(1) rotate(0deg); }
    25%  { transform: translate(-90px,50px) scale(1.11) rotate(-7deg); }
    55%  { transform: translate(-40px,90px) scale(0.97) rotate(4deg); }
    80%  { transform: translate(60px,-35px) scale(1.05) rotate(-5deg); }
    100% { transform: translate(0,0) scale(1) rotate(0deg); }
}
@keyframes esBlob3 {
    0%   { transform: translate(0,0) scale(1); }
    30%  { transform: translate(80px,20px) scale(1.12); }
    60%  { transform: translate(40px,70px) scale(0.92); }
    100% { transform: translate(0,0) scale(1); }
}
@media (prefers-reduced-motion: reduce) {
    #es-aurora > div { animation: none !important; }
    #es-canvas       { display: none; }
}
</style>
@endpush
