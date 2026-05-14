<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta name="description" content="@yield('meta_description', 'EventSphere — Discover real events happening in any city, powered by live data.')">

    <title>@yield('title', 'EventSphere') — Real Events, Any City</title>

    {{-- Fonts: Bebas Neue + Space Grotesk --}}
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Bebas+Neue&family=Righteous&family=Space+Grotesk:wght@300;400;500;600;700&display=swap" rel="stylesheet">

    @vite(['resources/css/app.css', 'resources/js/app.js'])
    @stack('styles')
</head>
<body class="bg-[#0A0A0A] text-[#F0F0F0] font-grotesk min-h-screen">

    @include('layouts.navbar')

    {{-- Flash messages --}}
    @if(session('success'))
        <div id="flash-success" class="fixed top-20 right-4 z-50 max-w-sm flex items-center gap-3 px-4 py-3.5 rounded-xl animate-slide-in"
             style="background: rgba(16,185,129,0.12); border: 1px solid rgba(16,185,129,0.3); box-shadow: 0 8px 32px rgba(0,0,0,0.5); backdrop-filter: blur(12px); transition: opacity 0.3s, transform 0.3s;">
            <svg class="w-4 h-4 shrink-0 text-emerald-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"/></svg>
            <span class="text-sm font-semibold text-white">{{ session('success') }}</span>
            <button onclick="this.parentElement.remove()" class="ml-auto cursor-pointer text-white/40 hover:text-white transition-colors">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
            </button>
        </div>
    @endif
    @if(session('error'))
        <div id="flash-error" class="fixed top-20 right-4 z-50 max-w-sm flex items-center gap-3 px-4 py-3.5 rounded-xl animate-slide-in"
             style="background: rgba(225,29,72,0.12); border: 1px solid rgba(225,29,72,0.3); box-shadow: 0 8px 32px rgba(0,0,0,0.5); backdrop-filter: blur(12px); transition: opacity 0.3s, transform 0.3s;">
            <svg class="w-4 h-4 shrink-0 text-[#E11D48]" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
            <span class="text-sm font-semibold text-white">{{ session('error') }}</span>
            <button onclick="this.parentElement.remove()" class="ml-auto cursor-pointer text-white/40 hover:text-white transition-colors">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
            </button>
        </div>
    @endif

    <main class="pt-16">
        @yield('content')
    </main>

    @include('layouts.footer')

    <script>
        setTimeout(() => {
            ['flash-success','flash-error'].forEach(id => {
                const el = document.getElementById(id);
                if (el) { el.style.opacity='0'; el.style.transform='translateX(110%)'; setTimeout(()=>el.remove(),300); }
            });
        }, 4000);
    </script>
    @stack('scripts')
</body>
</html>
