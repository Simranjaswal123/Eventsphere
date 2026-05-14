@extends('layouts.app')
@section('title', 'Browse Events' . ($city ? ' in ' . $city : ''))

@section('content')

{{-- Header --}}
<div class="border-b-2 border-[#2A2A2A]">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-10">
        <div class="section-label">{{ $usingRealData ? 'Live Data' : 'All Events' }}</div>
        <h1 class="heading-lg text-[#F0F0F0]">
            {{ $city ? 'Events in ' . $city : 'Browse Events' }}
        </h1>
        @if($total > 0)
            <p class="text-[#555] font-semibold mt-1">{{ number_format($total) }} events found</p>
        @endif
    </div>
</div>

<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
    <div class="flex flex-col lg:flex-row gap-8">

        {{-- Sidebar --}}
        <aside class="lg:w-64 shrink-0">
            <form method="GET" action="{{ route('events.index') }}">
                <div class="bg-[#141414] border-2 border-[#2A2A2A]">
                    <div class="px-4 py-3 border-b-2 border-[#2A2A2A] flex items-center justify-between">
                        <span class="text-xs font-black uppercase tracking-wider text-[#F0F0F0]">Filters</span>
                        <a href="{{ route('events.index') }}" class="text-xs text-[#E11D48] font-bold uppercase cursor-pointer">Reset</a>
                    </div>
                    <div class="p-4 space-y-5">

                        <div>
                            <label class="block text-xs font-black uppercase tracking-wider text-[#555] mb-2">City</label>
                            <input type="text" name="city" value="{{ $city }}" placeholder="Type any city..."
                                   class="nb-input text-sm">
                        </div>

                        <div>
                            <label class="block text-xs font-black uppercase tracking-wider text-[#555] mb-2">Keyword</label>
                            <input type="text" name="search" value="{{ $keyword }}" placeholder="Artist, event name..."
                                   class="nb-input text-sm">
                        </div>

                        {{-- Event Type — multi-select chips --}}
                        <div>
                            <div class="flex items-center justify-between mb-2">
                                <label class="text-xs font-black uppercase tracking-wider text-[#555]">Event Type</label>
                                @if(!empty($types))
                                    <button type="button" onclick="clearTypes()" class="text-xs text-[#E11D48] font-bold uppercase cursor-pointer">Clear</button>
                                @endif
                            </div>
                            @php
                            $eventTypeOptions = [
                                'concerts'    => 'Concerts',
                                'festivals'   => 'Festivals',
                                'sports'      => 'Sports',
                                'comedy'      => 'Comedy',
                                'theatre'     => 'Theatre',
                                'performances'=> 'Performances',
                                'cultural'    => 'Cultural',
                                'family'      => 'Family',
                                'conferences' => 'Conferences',
                                'workshops'   => 'Workshops',
                                'exhibitions' => 'Exhibitions',
                            ];
                            @endphp
                            <div class="flex flex-wrap gap-1.5">
                                @foreach($eventTypeOptions as $typeSlug => $typeLabel)
                                    @php $isActive = in_array($typeSlug, $types); @endphp
                                    <label class="cursor-pointer type-chip-label">
                                        <input type="checkbox" name="type[]" value="{{ $typeSlug }}"
                                               {{ $isActive ? 'checked' : '' }}
                                               class="sr-only type-checkbox">
                                        <span class="type-chip inline-block px-2.5 py-1 text-xs font-bold uppercase tracking-wide border-2 cursor-pointer select-none transition-all
                                                     {{ $isActive ? 'border-[#E11D48] text-[#F0F0F0] bg-[#E11D48]/10' : 'border-[#2A2A2A] text-[#555]' }}">
                                            {{ $typeLabel }}
                                        </span>
                                    </label>
                                @endforeach
                            </div>
                            @if(!empty($types))
                                <p class="text-xs mt-1.5" style="color:#555;">{{ count($types) }} type{{ count($types) > 1 ? 's' : '' }} selected</p>
                            @endif
                        </div>

                        <div>
                            <label class="block text-xs font-black uppercase tracking-wider text-[#555] mb-2">Category</label>
                            <div class="space-y-1">
                                <label class="flex items-center gap-2 cursor-pointer group py-1">
                                    <input type="radio" name="category" value="" {{ !$category ? 'checked' : '' }} class="accent-[#E11D48]">
                                    <span class="text-sm text-[#888] group-hover:text-[#F0F0F0] transition-colors">All</span>
                                </label>
                                @foreach($categories as $cat)
                                    <label class="flex items-center gap-2 cursor-pointer group py-1">
                                        <input type="radio" name="category" value="{{ $cat->slug }}" {{ $category === $cat->slug ? 'checked' : '' }} class="accent-[#E11D48]">
                                        <span class="text-sm text-[#888] group-hover:text-[#F0F0F0] transition-colors">{{ $cat->name }}</span>
                                    </label>
                                @endforeach
                            </div>
                        </div>

                        <div>
                            <label class="block text-xs font-black uppercase tracking-wider text-[#555] mb-2">Price</label>
                            <div class="space-y-1">
                                <label class="flex items-center gap-2 cursor-pointer group py-1">
                                    <input type="radio" name="price" value="" {{ !$price ? 'checked' : '' }} class="accent-[#E11D48]">
                                    <span class="text-sm text-[#888] group-hover:text-[#F0F0F0] transition-colors">All</span>
                                </label>
                                <label class="flex items-center gap-2 cursor-pointer group py-1">
                                    <input type="radio" name="price" value="free" {{ $price === 'free' ? 'checked' : '' }} class="accent-[#E11D48]">
                                    <span class="text-sm text-[#888] group-hover:text-[#F0F0F0] transition-colors">Free only</span>
                                </label>
                            </div>
                        </div>

                        <button type="submit" class="nb-btn w-full text-sm py-2.5 text-center">Apply Filters</button>
                    </div>
                </div>
            </form>

            <div class="mt-4 bg-[#141414] border-2 border-[#2A2A2A] p-4">
                <p class="text-xs font-black uppercase tracking-wider text-[#555] mb-3">Quick Cities</p>
                <div class="flex flex-wrap gap-1.5">
                    @foreach(['New York','London','Los Angeles','Chicago','Toronto','Sydney','Paris','Berlin'] as $qc)
                        <a href="{{ route('events.index') }}?city={{ urlencode($qc) }}"
                           class="nb-tag cursor-pointer hover:border-[#E11D48] hover:text-[#F0F0F0] transition-colors {{ $city === $qc ? 'border-[#E11D48] text-[#F0F0F0]' : '' }}">{{ $qc }}</a>
                    @endforeach
                </div>
            </div>

            @auth
                <div class="mt-4">
                    <a href="{{ route('events.create') }}" class="nb-btn-yellow w-full text-sm py-3 text-center block">+ Create Your Event</a>
                </div>
            @endauth
        </aside>

        {{-- Main grid --}}
        <div class="flex-1 min-w-0">
            @if(!empty($events) && count($events) > 0)
                <div class="grid grid-cols-1 sm:grid-cols-2 xl:grid-cols-3 gap-5">
                    @foreach($events as $event)
                        @include('components.event-card', ['event' => $event])
                    @endforeach
                </div>

                {{-- Pagination --}}
                @if($currentPage > 1 || $hasNextPage || (!$usingRealData && $totalPages > 1))
                    <div class="mt-10 flex items-center justify-center gap-2">
                        @if($currentPage > 1)
                            <a href="{{ request()->fullUrlWithQuery(['page' => $currentPage - 1]) }}" class="nb-btn-ghost text-sm py-2 px-5">← Prev</a>
                        @endif
                        <span class="px-5 py-2 border-2 border-[#E11D48] text-sm font-bold bg-[#141414]">
                            @if($usingRealData)
                                Page {{ $currentPage }}
                            @else
                                Page {{ $currentPage }} of {{ $totalPages }}
                            @endif
                        </span>
                        @if($hasNextPage)
                            <a href="{{ request()->fullUrlWithQuery(['page' => $currentPage + 1]) }}" class="nb-btn-ghost text-sm py-2 px-5">Next →</a>
                        @endif
                    </div>
                @endif

            @else
                <div class="border-2 border-dashed border-[#2A2A2A] p-20 text-center">
                    <svg class="w-12 h-12 text-[#2A2A2A] mx-auto mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
                    <p class="font-black uppercase tracking-wider text-[#555] mb-2">No Events Found</p>
                    <p class="text-sm text-[#333] mb-6">
                        @if(!$usingRealData)
                            Set <code class="text-[#E11D48] font-mono">TICKETMASTER_API_KEY</code> in <code class="text-[#FFD600] font-mono">.env</code> to see real events.
                        @else
                            Try a different city or keyword.
                        @endif
                    </p>
                    @auth
                        <a href="{{ route('events.create') }}" class="nb-btn text-sm">Create an Event</a>
                    @endauth
                </div>
            @endif
        </div>

    </div>
</div>

@endsection

@push('scripts')
<script>
// Toggle chip visual state on checkbox change
document.querySelectorAll('.type-checkbox').forEach(function(cb) {
    cb.addEventListener('change', function() {
        var chip = this.nextElementSibling;
        if (this.checked) {
            chip.style.borderColor = '#E11D48';
            chip.style.color = '#F0F0F0';
            chip.style.background = 'rgba(225,29,72,0.10)';
        } else {
            chip.style.borderColor = '#2A2A2A';
            chip.style.color = '#555';
            chip.style.background = 'transparent';
        }
    });
});

function clearTypes() {
    document.querySelectorAll('.type-checkbox').forEach(function(cb) {
        cb.checked = false;
        var chip = cb.nextElementSibling;
        chip.style.borderColor = '#2A2A2A';
        chip.style.color = '#555';
        chip.style.background = 'transparent';
    });
}
</script>
@endpush
