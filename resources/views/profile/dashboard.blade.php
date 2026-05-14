@extends('layouts.app')
@section('title', __('messages.dashboard_title'))

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-10">

    {{-- ── Hero Header ──────────────────────────────────────────────── --}}
    <div class="relative rounded-3xl overflow-hidden mb-8 p-6 sm:p-8"
         style="background: linear-gradient(135deg, rgba(225,29,72,0.08) 0%, rgba(10,10,10,0) 40%, rgba(124,58,237,0.07) 100%);
                border: 1px solid rgba(255,255,255,0.08);
                backdrop-filter: blur(16px);">

        {{-- Glow accents --}}
        <div class="absolute -top-16 -left-16 w-56 h-56 rounded-full pointer-events-none"
             style="background: radial-gradient(circle, rgba(225,29,72,0.12), transparent 70%);"></div>
        <div class="absolute -bottom-12 -right-8 w-48 h-48 rounded-full pointer-events-none"
             style="background: radial-gradient(circle, rgba(124,58,237,0.1), transparent 70%);"></div>

        <div class="relative flex flex-col sm:flex-row items-start sm:items-center justify-between gap-5">
            <div class="flex items-center gap-4">
                {{-- Avatar with ring --}}
                <div class="relative shrink-0">
                    <div class="absolute inset-0 rounded-2xl" style="background: linear-gradient(135deg,#E11D48,#7C3AED); padding:2px; border-radius:18px;">
                        <div style="background:#0A0A0A; border-radius:16px; width:100%; height:100%;"></div>
                    </div>
                    <img src="{{ $user->avatar_url }}" alt="{{ $user->name }}"
                         class="relative w-16 h-16 rounded-2xl object-cover"
                         style="border: 2px solid transparent; background: linear-gradient(#0A0A0A,#0A0A0A) padding-box, linear-gradient(135deg,#E11D48,#7C3AED) border-box;">
                </div>

                <div>
                    <p class="text-xs font-bold uppercase tracking-widest mb-1" style="color: #E11D48;">
                        {{ __('messages.nav_dashboard') }}
                    </p>
                    <h1 class="text-2xl sm:text-3xl font-black text-white leading-tight">
                        {{ __('messages.dashboard_welcome', ['name' => $user->name]) }}
                    </h1>
                    <p class="text-sm mt-0.5" style="color:#555;">{{ __('messages.dashboard_subtitle') }}</p>
                </div>
            </div>

            <a href="{{ route('events.create') }}"
               class="shrink-0 flex items-center gap-2 px-5 py-3 font-bold text-sm text-white rounded-xl cursor-pointer transition-all"
               style="background: linear-gradient(135deg,#E11D48,#7C3AED); box-shadow: 0 4px 24px rgba(225,29,72,0.3);"
               onmouseover="this.style.boxShadow='0 6px 32px rgba(225,29,72,0.5)';this.style.transform='translateY(-1px)'"
               onmouseout="this.style.boxShadow='0 4px 24px rgba(225,29,72,0.3)';this.style.transform='translateY(0)'">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 4v16m8-8H4"/></svg>
                {{ __('messages.create_event') }}
            </a>
        </div>
    </div>

    {{-- ── Stats Grid ────────────────────────────────────────────────── --}}
    @php
    $statCards = [
        [
            'label'  => __('messages.dashboard_events_created'),
            'value'  => $stats['total_events'],
            'icon'   => 'M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z',
            'color'  => '#E11D48',
            'glow'   => 'rgba(225,29,72,0.15)',
            'border' => 'rgba(225,29,72,0.2)',
        ],
        [
            'label'  => __('messages.dashboard_total_rsvps'),
            'value'  => $stats['total_rsvps'],
            'icon'   => 'M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2',
            'color'  => '#7C3AED',
            'glow'   => 'rgba(124,58,237,0.15)',
            'border' => 'rgba(124,58,237,0.2)',
        ],
        [
            'label'  => __('messages.dashboard_total_views'),
            'value'  => number_format($stats['total_views']),
            'icon'   => 'M15 12a3 3 0 11-6 0 3 3 0 016 0z M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z',
            'color'  => '#0EA5E9',
            'glow'   => 'rgba(14,165,233,0.12)',
            'border' => 'rgba(14,165,233,0.18)',
        ],
        [
            'label'  => __('messages.dashboard_total_likes'),
            'value'  => $stats['total_likes'],
            'icon'   => 'M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z',
            'color'  => '#10B981',
            'glow'   => 'rgba(16,185,129,0.12)',
            'border' => 'rgba(16,185,129,0.18)',
        ],
    ];
    @endphp

    <div class="grid grid-cols-2 lg:grid-cols-4 gap-4 mb-8">
        @foreach($statCards as $card)
        <div class="relative rounded-2xl p-5 overflow-hidden transition-transform duration-200 cursor-default"
             style="background: rgba(255,255,255,0.03); border: 1px solid {{ $card['border'] }}; backdrop-filter: blur(12px);"
             onmouseover="this.style.transform='translateY(-3px)'"
             onmouseout="this.style.transform='translateY(0)'">

            {{-- Glow bg --}}
            <div class="absolute inset-0 pointer-events-none"
                 style="background: radial-gradient(ellipse at top right, {{ $card['glow'] }}, transparent 70%);"></div>

            <div class="relative">
                <div class="w-9 h-9 rounded-xl flex items-center justify-center mb-4"
                     style="background: {{ $card['glow'] }}; border: 1px solid {{ $card['border'] }};">
                    <svg class="w-4.5 h-4.5" style="width:18px;height:18px;color:{{ $card['color'] }};" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="{{ $card['icon'] }}"/>
                    </svg>
                </div>
                <p class="text-3xl font-black text-white leading-none mb-1"
                   style="text-shadow: 0 0 20px {{ $card['glow'] }};">{{ $card['value'] }}</p>
                <p class="text-xs font-medium" style="color:#555;">{{ $card['label'] }}</p>
            </div>
        </div>
        @endforeach
    </div>

    {{-- ── Two-column panels ────────────────────────────────────────── --}}
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-6">

        {{-- Recent Events --}}
        <div class="rounded-2xl overflow-hidden" style="background:rgba(255,255,255,0.025); border:1px solid rgba(255,255,255,0.08); backdrop-filter:blur(12px);">
            <div class="flex items-center justify-between px-5 py-4" style="border-bottom:1px solid rgba(255,255,255,0.06);">
                <div class="flex items-center gap-2">
                    <div class="w-1 h-4 rounded-full" style="background:linear-gradient(to bottom,#E11D48,#7C3AED);"></div>
                    <h2 class="font-bold text-sm text-white">{{ __('messages.dashboard_recent_events') }}</h2>
                </div>
                <a href="{{ route('events.mine') }}"
                   class="text-xs font-semibold transition-colors cursor-pointer"
                   style="color:#555;" onmouseover="this.style.color='#E11D48'" onmouseout="this.style.color='#555'">
                   {{ __('messages.dashboard_view_all') }} →
                </a>
            </div>

            @if($myEvents->isEmpty())
                <div class="flex flex-col items-center justify-center py-14 gap-3">
                    <div class="w-12 h-12 rounded-2xl flex items-center justify-center" style="background:rgba(255,255,255,0.04);border:1px solid rgba(255,255,255,0.08);">
                        <svg class="w-5 h-5" style="color:#333;" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                    </div>
                    <p class="text-sm" style="color:#444;">{{ __('messages.dashboard_no_events') }}
                        <a href="{{ route('events.create') }}" class="cursor-pointer" style="color:#E11D48;" onmouseover="this.style.textDecoration='underline'" onmouseout="this.style.textDecoration='none'">
                            {{ __('messages.dashboard_create_one') }}
                        </a>
                    </p>
                </div>
            @else
                <div class="divide-y" style="--tw-divide-opacity:1;border-color:rgba(255,255,255,0.04);">
                    @foreach($myEvents as $event)
                    <a href="{{ route('events.show', $event->slug) }}"
                       class="flex items-center gap-3 px-5 py-3.5 cursor-pointer group transition-all"
                       style="text-decoration:none;"
                       onmouseover="this.style.background='rgba(255,255,255,0.03)'"
                       onmouseout="this.style.background='transparent'">
                        <div class="w-10 h-10 rounded-xl overflow-hidden shrink-0 ring-1 ring-white/[0.06]">
                            <img src="{{ $event->image_url }}" alt="{{ $event->title }}"
                                 class="w-full h-full object-cover"
                                 onerror="this.src='https://picsum.photos/seed/{{ $event->_id }}/80/80'">
                        </div>
                        <div class="flex-1 min-w-0">
                            <p class="text-sm font-semibold text-white truncate transition-colors"
                               style="" onmouseover="this.style.color='#E11D48'" onmouseout="this.style.color='#fff'">
                               {{ $event->title }}</p>
                            <p class="text-xs mt-0.5" style="color:#444;">
                                {{ $event->start_date?->format('M j, Y') ?? 'TBD' }}
                                <span style="color:#2a2a2a;"> · </span>
                                {{ $event->rsvp_count ?? 0 }} RSVPs
                            </p>
                        </div>
                        <span class="shrink-0 text-xs font-bold px-2.5 py-1 rounded-full"
                              style="background:rgba(225,29,72,0.1);color:#E11D48;border:1px solid rgba(225,29,72,0.2);">
                              {{ $event->status ?? 'Live' }}
                        </span>
                    </a>
                    @endforeach
                </div>
            @endif
        </div>

        {{-- Upcoming RSVPs --}}
        <div class="rounded-2xl overflow-hidden" style="background:rgba(255,255,255,0.025); border:1px solid rgba(255,255,255,0.08); backdrop-filter:blur(12px);">
            <div class="flex items-center justify-between px-5 py-4" style="border-bottom:1px solid rgba(255,255,255,0.06);">
                <div class="flex items-center gap-2">
                    <div class="w-1 h-4 rounded-full" style="background:linear-gradient(to bottom,#7C3AED,#0EA5E9);"></div>
                    <h2 class="font-bold text-sm text-white">{{ __('messages.dashboard_upcoming_rsvps') }}</h2>
                </div>
                <a href="{{ route('profile.rsvps') }}"
                   class="text-xs font-semibold transition-colors cursor-pointer"
                   style="color:#555;" onmouseover="this.style.color='#7C3AED'" onmouseout="this.style.color='#555'">
                   {{ __('messages.dashboard_view_all') }} →
                </a>
            </div>

            @if($myRsvps->isEmpty())
                <div class="flex flex-col items-center justify-center py-14 gap-3">
                    <div class="w-12 h-12 rounded-2xl flex items-center justify-center" style="background:rgba(255,255,255,0.04);border:1px solid rgba(255,255,255,0.08);">
                        <svg class="w-5 h-5" style="color:#333;" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M5 13l4 4L19 7"/></svg>
                    </div>
                    <p class="text-sm" style="color:#444;">{{ __('messages.dashboard_no_rsvps') }}
                        <a href="{{ route('events.index') }}" class="cursor-pointer" style="color:#7C3AED;" onmouseover="this.style.textDecoration='underline'" onmouseout="this.style.textDecoration='none'">
                            {{ __('messages.dashboard_browse') }}
                        </a>
                    </p>
                </div>
            @else
                <div class="divide-y" style="border-color:rgba(255,255,255,0.04);">
                    @foreach($myRsvps as $rsvp)
                    @if($rsvp->event)
                    <a href="{{ route('events.show', $rsvp->event->slug) }}"
                       class="flex items-center gap-3 px-5 py-3.5 cursor-pointer transition-all"
                       style="text-decoration:none;"
                       onmouseover="this.style.background='rgba(255,255,255,0.03)'"
                       onmouseout="this.style.background='transparent'">
                        <div class="w-10 h-10 rounded-xl overflow-hidden shrink-0 ring-1 ring-white/[0.06]">
                            <img src="{{ $rsvp->event->image_url }}" alt="{{ $rsvp->event->title }}"
                                 class="w-full h-full object-cover"
                                 onerror="this.src='https://picsum.photos/seed/{{ $rsvp->event->_id }}/80/80'">
                        </div>
                        <div class="flex-1 min-w-0">
                            <p class="text-sm font-semibold text-white truncate">{{ $rsvp->event->title }}</p>
                            <p class="text-xs mt-0.5" style="color:#444;">
                                {{ $rsvp->event->start_date?->format('M j, Y') ?? 'TBD' }}
                                @if($rsvp->event->city)<span style="color:#2a2a2a;"> · </span>{{ $rsvp->event->city }}@endif
                            </p>
                        </div>
                        <span class="shrink-0 text-xs font-bold px-2.5 py-1 rounded-full"
                              style="background:rgba(16,185,129,0.1);color:#10B981;border:1px solid rgba(16,185,129,0.2);">
                              {{ __('messages.dashboard_confirmed') }}
                        </span>
                    </a>
                    @endif
                    @endforeach
                </div>
            @endif
        </div>
    </div>

    {{-- ── Quick Actions ────────────────────────────────────────────── --}}
    <div>
        <p class="text-xs font-bold uppercase tracking-widest mb-4" style="color:#333;">{{ __('messages.dashboard_quick_links') }}</p>
        <div class="grid grid-cols-2 sm:grid-cols-4 gap-3">
            @foreach([
                ['href' => route('profile.edit'),  'icon' => 'M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z M15 12a3 3 0 11-6 0 3 3 0 016 0z', 'label' => __('messages.profile_edit_btn'), 'color' => '#E11D48'],
                ['href' => route('events.create'), 'icon' => 'M12 4v16m8-8H4', 'label' => __('messages.create_event'), 'color' => '#7C3AED'],
                ['href' => route('events.index'),  'icon' => 'M9 20l-5.447-2.724A1 1 0 013 16.382V5.618a1 1 0 011.447-.894L9 7m0 13l6-3m-6 3V7m6 10l4.553 2.276A1 1 0 0021 18.382V7.618a1 1 0 00-.553-.894L15 4m0 13V4m0 0L9 7', 'label' => __('messages.nav_browse'), 'color' => '#0EA5E9'],
                ['href' => route('profile.rsvps'), 'icon' => 'M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2', 'label' => __('messages.nav_my_rsvps'), 'color' => '#10B981'],
            ] as $link)
            <a href="{{ $link['href'] }}"
               class="group flex flex-col items-center gap-3 p-4 rounded-2xl cursor-pointer transition-all duration-200"
               style="background:rgba(255,255,255,0.025);border:1px solid rgba(255,255,255,0.07);"
               onmouseover="this.style.background='rgba(255,255,255,0.05)';this.style.borderColor='{{ $link['color'] }}33';this.style.transform='translateY(-2px)'"
               onmouseout="this.style.background='rgba(255,255,255,0.025)';this.style.borderColor='rgba(255,255,255,0.07)';this.style.transform='translateY(0)'">
                <div class="w-10 h-10 rounded-xl flex items-center justify-center transition-all"
                     style="background:{{ $link['color'] }}15;border:1px solid {{ $link['color'] }}25;">
                    <svg class="w-4 h-4" style="color:{{ $link['color'] }};" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="{{ $link['icon'] }}"/>
                    </svg>
                </div>
                <span class="text-xs font-semibold text-center leading-tight" style="color:#555;">{{ $link['label'] }}</span>
            </a>
            @endforeach
        </div>
    </div>

</div>
@endsection
