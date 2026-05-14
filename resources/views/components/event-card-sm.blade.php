@php
    $isTm   = is_array($event);
    $slug   = $isTm ? $event['slug']    : $event->slug;
    $title  = $isTm ? $event['title']   : $event->title;
    $city   = $isTm ? $event['city']    : $event->city;
    $img    = $isTm ? ($event['image_url'] ?? null) : ($event->image_url ?? null);
    $isFree = $isTm ? $event['is_free'] : $event->is_free;
    $price  = $isTm ? $event['price']   : ($event->price ?? null);

    if ($isTm && $event['start_date']) {
        $mon = $event['start_date']->format('M');
        $day = $event['start_date']->format('d');
    } elseif (!$isTm && $event->start_date) {
        $mon = $event->start_date->format('M');
        $day = $event->start_date->format('d');
    } else {
        $mon = '—'; $day = '?';
    }
@endphp

<a href="{{ route('events.show', $slug) }}"
   class="group flex gap-3 p-3.5 rounded-xl transition-all cursor-pointer"
   style="background: rgba(255,255,255,0.025); border: 1px solid rgba(255,255,255,0.07);"
   onmouseover="this.style.borderColor='rgba(225,29,72,0.35)';this.style.background='rgba(225,29,72,0.04)'"
   onmouseout="this.style.borderColor='rgba(255,255,255,0.07)';this.style.background='rgba(255,255,255,0.025)'">

    {{-- Date box --}}
    <div class="shrink-0 w-11 flex flex-col items-center justify-center rounded-lg text-center pt-0.5 pb-0.5"
         style="background: rgba(225,29,72,0.12); border: 1px solid rgba(225,29,72,0.2);">
        <span class="text-[10px] font-bold text-[#E11D48] uppercase leading-none">{{ $mon }}</span>
        <span class="text-xl font-black text-white leading-tight">{{ $day }}</span>
    </div>

    {{-- Info --}}
    <div class="flex-1 min-w-0">
        <h4 class="text-sm font-semibold text-white line-clamp-2 group-hover:text-[#E11D48] transition-colors leading-snug mb-1">{{ $title }}</h4>
        <p class="text-xs text-[#555] flex items-center gap-1 truncate">
            <svg class="w-3 h-3 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/></svg>
            {{ $city }}
        </p>
        <div class="mt-1.5">
            @if($isFree)
                <span class="text-xs font-bold" style="color: #FFD600;">Free</span>
            @elseif($price)
                <span class="text-xs text-[#555]">from ${{ number_format($price, 0) }}</span>
            @endif
        </div>
    </div>
</a>
