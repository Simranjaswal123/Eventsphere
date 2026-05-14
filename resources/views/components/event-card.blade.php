@php
    $isTm   = is_array($event);
    $slug   = $isTm ? $event['slug']      : $event->slug;
    $title  = $isTm ? $event['title']     : $event->title;
    $img    = $isTm ? $event['image_url'] : $event->image_url;
    $city   = $isTm ? $event['city']      : $event->city;
    $venue  = $isTm ? $event['location']  : ($event->location ?? '');
    $isFree = $isTm ? $event['is_free']   : $event->is_free;
    $price  = $isTm ? $event['price']     : ($event->price ?? null);
    $extUrl = $isTm ? $event['external_url'] : null;

    if ($isTm && $event['start_date']) {
        $dateStr = $event['start_date']->format('D, M j');
        $timeStr = !($event['tbd'] ?? false) ? $event['start_date']->format('g:i A') : 'Time TBD';
    } elseif (!$isTm && $event->start_date) {
        $dateStr = $event->start_date->format('D, M j');
        $timeStr = $event->start_date->format('g:i A');
    } else {
        $dateStr = 'TBD'; $timeStr = '';
    }

    $category = $isTm ? ($event['genre'] ?? $event['category'] ?? null) : ($event->category->name ?? null);
    $source   = $isTm ? 'ticketmaster' : 'user';
@endphp

<div class="es-card group flex flex-col h-full">
    {{-- Image --}}
    <a href="{{ route('events.show', $slug) }}" class="block relative overflow-hidden" style="height: 200px;">
        <img src="{{ $img }}" alt="{{ $title }}" loading="lazy"
             class="w-full h-full object-cover transition-transform duration-500 group-hover:scale-105"
             onerror="this.src='https://picsum.photos/seed/{{ $slug }}/600/400'">

        {{-- Gradient overlay --}}
        <div class="absolute inset-0" style="background: linear-gradient(to top, rgba(8,8,8,0.8) 0%, rgba(8,8,8,0.1) 60%, transparent 100%);"></div>

        {{-- Badges --}}
        <div class="absolute top-3 left-3 flex gap-1.5">
            @if($isFree)
                <span class="es-badge" style="background: rgba(255,214,0,0.18); color: #FFD600; border: 1px solid rgba(255,214,0,0.3);">Free</span>
            @elseif($price)
                <span class="es-badge" style="background: rgba(8,8,8,0.7); color: #ccc; border: 1px solid rgba(255,255,255,0.1);">${{ number_format($price, 0) }}+</span>
            @endif
            @if($source === 'ticketmaster')
                <span class="es-badge" style="background: rgba(225,29,72,0.2); color: #E11D48; border: 1px solid rgba(225,29,72,0.3);">Live</span>
            @endif
        </div>

        {{-- Category bottom-left --}}
        @if($category)
            <p class="absolute bottom-3 left-3 text-xs font-semibold text-white/60 uppercase tracking-wide">{{ $category }}</p>
        @endif
    </a>

    <div class="p-4 flex flex-col flex-1">
        {{-- Date --}}
        <p class="text-xs font-semibold text-[#E11D48] uppercase tracking-wider mb-2">
            {{ $dateStr }}@if($timeStr) · {{ $timeStr }}@endif
        </p>

        {{-- Title --}}
        <a href="{{ route('events.show', $slug) }}" class="block mb-3 group/title">
            <h3 class="font-semibold text-white text-sm leading-snug line-clamp-2 group-hover/title:text-[#E11D48] transition-colors">{{ $title }}</h3>
        </a>

        {{-- Location --}}
        <div class="flex items-center gap-1.5 text-[#555] text-xs mb-4">
            <svg class="w-3.5 h-3.5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
            <span class="truncate">{{ $venue ? $venue . ', ' : '' }}{{ $city }}</span>
        </div>

        {{-- Footer --}}
        <div class="mt-auto pt-3 flex items-center justify-between" style="border-top: 1px solid rgba(255,255,255,0.07);">
            @if($source === 'ticketmaster' && $extUrl && $extUrl !== '#')
                <a href="{{ $extUrl }}" target="_blank" rel="noopener"
                   class="text-xs font-semibold text-[#555] hover:text-[#E11D48] transition-colors uppercase tracking-wider flex items-center gap-1 cursor-pointer">
                    Tickets
                    <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"/></svg>
                </a>
            @else
                <span class="text-xs text-[#333] uppercase tracking-wider">Community</span>
            @endif
            <a href="{{ route('events.show', $slug) }}"
               class="text-xs font-semibold text-white/60 hover:text-[#E11D48] transition-colors cursor-pointer">
                View →
            </a>
        </div>
    </div>
</div>
