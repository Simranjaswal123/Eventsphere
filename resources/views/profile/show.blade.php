@extends('layouts.app')
@section('title', $user->name)

@section('content')
<div class="max-w-5xl mx-auto px-4 sm:px-6 lg:px-8 py-10">

    {{-- ── Profile Card ──────────────────────────────────────────────── --}}
    <div class="rounded-3xl overflow-hidden mb-10"
         style="background:rgba(255,255,255,0.025);border:1px solid rgba(255,255,255,0.08);backdrop-filter:blur(16px);">

        {{-- Cover banner --}}
        <div class="relative overflow-hidden" style="height:200px;background:#080808;">

            {{-- Animated gradient blobs --}}
            <div class="absolute" style="width:650px;height:320px;top:-100px;left:-80px;border-radius:50%;filter:blur(55px);background:radial-gradient(circle,rgba(225,29,72,0.55) 0%,transparent 65%);animation:cBlob1 14s ease-in-out infinite;will-change:transform;"></div>
            <div class="absolute" style="width:520px;height:280px;top:-80px;right:-100px;border-radius:50%;filter:blur(65px);background:radial-gradient(circle,rgba(124,58,237,0.5) 0%,transparent 65%);animation:cBlob2 18s ease-in-out infinite;will-change:transform;"></div>
            <div class="absolute" style="width:360px;height:240px;bottom:-80px;left:35%;border-radius:50%;filter:blur(75px);background:radial-gradient(circle,rgba(14,165,233,0.35) 0%,transparent 65%);animation:cBlob1 22s ease-in-out infinite reverse;will-change:transform;"></div>
            <div class="absolute" style="width:280px;height:180px;top:20px;left:55%;border-radius:50%;filter:blur(60px);background:radial-gradient(circle,rgba(225,29,72,0.25) 0%,transparent 65%);animation:cBlob2 10s ease-in-out infinite 3s;will-change:transform;"></div>

            {{-- Grid overlay --}}
            <div class="absolute inset-0" style="background-image:linear-gradient(rgba(255,255,255,0.05) 1px,transparent 1px),linear-gradient(90deg,rgba(255,255,255,0.05) 1px,transparent 1px);background-size:44px 44px;"></div>

            {{-- Bottom fade into card body --}}
            <div class="absolute bottom-0 left-0 right-0 h-20" style="background:linear-gradient(to bottom,transparent,rgba(8,8,8,0.55));"></div>
        </div>

        <div class="px-6 sm:px-8 pb-8">
            {{-- Avatar + actions row --}}
            <div class="flex flex-col sm:flex-row items-start sm:items-end justify-between gap-4 -mt-14 mb-6">
                {{-- Avatar --}}
                <div class="shrink-0 relative">
                    {{-- Glow ring --}}
                    <div class="absolute -inset-1 rounded-2xl opacity-60" style="background:linear-gradient(135deg,#E11D48,#7C3AED,#0EA5E9);filter:blur(6px);border-radius:20px;"></div>
                    <img src="{{ $user->avatar_url }}" alt="{{ $user->name }}"
                         class="relative w-24 h-24 rounded-2xl object-cover"
                         style="border:2px solid transparent;background:linear-gradient(#0A0A0A,#0A0A0A) padding-box,linear-gradient(135deg,#E11D48,#7C3AED,#0EA5E9) border-box;">
                </div>

                {{-- Actions --}}
                <div class="flex items-center gap-2">
                    @auth
                        @if((string) Auth::id() === (string) $user->_id)
                            <a href="{{ route('profile.edit') }}"
                               class="flex items-center gap-2 px-4 py-2.5 text-sm font-semibold rounded-xl cursor-pointer transition-all"
                               style="background:rgba(225,29,72,0.08);border:1px solid rgba(225,29,72,0.25);color:#E11D48;"
                               onmouseover="this.style.background='rgba(225,29,72,0.15)';this.style.borderColor='rgba(225,29,72,0.5)'"
                               onmouseout="this.style.background='rgba(225,29,72,0.08)';this.style.borderColor='rgba(225,29,72,0.25)'">
                                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>
                                {{ __('messages.profile_edit_btn') }}
                            </a>
                        @endif
                    @endauth
                </div>
            </div>

            {{-- Name + username --}}
            <div class="mb-4">
                <h1 class="text-2xl font-black text-white leading-tight tracking-tight">{{ $user->name }}</h1>
                <p class="text-sm mt-1 font-mono" style="color:#3d3d3d;">{{ '@' . ($user->username ?? 'user') }}</p>
            </div>

            {{-- Bio --}}
            @if($user->bio)
                <p class="text-sm leading-relaxed mb-5 max-w-2xl" style="color:#777;">{{ $user->bio }}</p>
            @endif

            {{-- Meta row --}}
            <div class="flex flex-wrap gap-4 mb-6">
                @if($user->location)
                    <span class="flex items-center gap-1.5 text-xs font-semibold px-3 py-1.5 rounded-lg" style="background:rgba(255,255,255,0.03);border:1px solid rgba(255,255,255,0.07);color:#555;">
                        <svg class="w-3.5 h-3.5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                        {{ $user->location }}
                    </span>
                @endif
                @if($user->website)
                    <a href="{{ $user->website }}" target="_blank" rel="noopener"
                       class="flex items-center gap-1.5 text-xs font-semibold px-3 py-1.5 rounded-lg cursor-pointer transition-all"
                       style="background:rgba(255,255,255,0.03);border:1px solid rgba(255,255,255,0.07);color:#555;"
                       onmouseover="this.style.color='#E11D48';this.style.borderColor='rgba(225,29,72,0.3)'"
                       onmouseout="this.style.color='#555';this.style.borderColor='rgba(255,255,255,0.07)'">
                        <svg class="w-3.5 h-3.5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.828 10.172a4 4 0 00-5.656 0l-4 4a4 4 0 105.656 5.656l1.102-1.101m-.758-4.899a4 4 0 005.656 0l4-4a4 4 0 00-5.656-5.656l-1.1 1.1"/></svg>
                        {{ parse_url($user->website, PHP_URL_HOST) }}
                    </a>
                @endif
                <span class="flex items-center gap-1.5 text-xs font-semibold px-3 py-1.5 rounded-lg" style="background:rgba(255,255,255,0.03);border:1px solid rgba(255,255,255,0.07);color:#555;">
                    <svg class="w-3.5 h-3.5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                    {{ __('messages.profile_joined', ['date' => $user->created_at?->format('M Y')]) }}
                </span>
            </div>

            {{-- Stats --}}
            <div class="flex gap-3 flex-wrap">
                <div class="group flex items-center gap-3 px-5 py-3 rounded-2xl cursor-default transition-all"
                     style="background:rgba(225,29,72,0.07);border:1px solid rgba(225,29,72,0.2);"
                     onmouseover="this.style.background='rgba(225,29,72,0.12)';this.style.borderColor='rgba(225,29,72,0.35)'"
                     onmouseout="this.style.background='rgba(225,29,72,0.07)';this.style.borderColor='rgba(225,29,72,0.2)'">
                    <svg class="w-4 h-4 shrink-0" style="color:#E11D48;" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                    <div>
                        <p class="text-xl font-black leading-none" style="color:#E11D48;text-shadow:0 0 20px rgba(225,29,72,0.4);">{{ $totalEvents }}</p>
                        <p class="text-xs font-semibold mt-0.5" style="color:#555;">{{ __('messages.profile_events_count') }}</p>
                    </div>
                </div>
                <div class="flex items-center gap-3 px-5 py-3 rounded-2xl cursor-default transition-all"
                     style="background:rgba(124,58,237,0.07);border:1px solid rgba(124,58,237,0.2);"
                     onmouseover="this.style.background='rgba(124,58,237,0.12)';this.style.borderColor='rgba(124,58,237,0.35)'"
                     onmouseout="this.style.background='rgba(124,58,237,0.07)';this.style.borderColor='rgba(124,58,237,0.2)'">
                    <svg class="w-4 h-4 shrink-0" style="color:#7C3AED;" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                    <div>
                        <p class="text-xl font-black leading-none" style="color:#7C3AED;text-shadow:0 0 20px rgba(124,58,237,0.4);">{{ $totalRsvps }}</p>
                        <p class="text-xs font-semibold mt-0.5" style="color:#555;">{{ __('messages.profile_rsvps_count') }}</p>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- ── Events Grid ──────────────────────────────────────────────── --}}
    <div>
        <div class="flex items-center gap-3 mb-6">
            <div class="w-1 h-5 rounded-full" style="background:linear-gradient(to bottom,#E11D48,#7C3AED);"></div>
            <h2 class="font-black text-lg text-white">{{ __('messages.profile_events_by', ['name' => $user->name]) }}</h2>
        </div>

        @if($events->isEmpty())
            <div class="flex flex-col items-center justify-center py-20 rounded-2xl"
                 style="background:rgba(255,255,255,0.015);border:1px dashed rgba(255,255,255,0.07);">
                <div class="w-14 h-14 rounded-2xl flex items-center justify-center mb-4"
                     style="background:rgba(255,255,255,0.03);border:1px solid rgba(255,255,255,0.07);">
                    <svg class="w-6 h-6" style="color:#2a2a2a;" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                </div>
                <p class="text-sm font-semibold" style="color:#333;">{{ __('messages.profile_no_events') }}</p>
            </div>
        @else
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-5">
                @foreach($events as $event)
                    @include('components.event-card', ['event' => $event])
                @endforeach
            </div>
            <div class="mt-8">{{ $events->links('components.pagination') }}</div>
        @endif
    </div>
</div>

@push('styles')
<style>
@keyframes cBlob1 {
    0%,100% { transform: translate(0,0) scale(1); }
    33%     { transform: translate(50px,-35px) scale(1.12); }
    66%     { transform: translate(-30px,25px) scale(0.93); }
}
@keyframes cBlob2 {
    0%,100% { transform: translate(0,0) scale(1); }
    40%     { transform: translate(-55px,20px) scale(1.08); }
    75%     { transform: translate(35px,-20px) scale(0.9); }
}
@media (prefers-reduced-motion: reduce) {
    [style*="cBlob"] { animation: none !important; }
}
</style>
@endpush
@endsection
