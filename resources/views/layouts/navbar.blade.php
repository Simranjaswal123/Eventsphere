<nav class="fixed top-0 left-0 right-0 z-50" style="background: rgba(8,8,8,0.82); backdrop-filter: blur(20px); -webkit-backdrop-filter: blur(20px); border-bottom: 1px solid rgba(255,255,255,0.07);">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 flex items-center justify-between h-16">

        {{-- Logo --}}
        <a href="{{ route('home') }}" class="flex items-center gap-2.5 cursor-pointer group shrink-0">
            <span class="w-8 h-8 rounded-lg flex items-center justify-center" style="background: #E11D48; box-shadow: 0 0 16px rgba(225,29,72,0.4);">
                <svg class="w-4 h-4 text-white" fill="currentColor" viewBox="0 0 24 24"><path d="M12 2C8.13 2 5 5.13 5 9c0 5.25 7 13 7 13s7-7.75 7-13c0-3.87-3.13-7-7-7zm0 9.5c-1.38 0-2.5-1.12-2.5-2.5s1.12-2.5 2.5-2.5 2.5 1.12 2.5 2.5-1.12 2.5-2.5 2.5z"/></svg>
            </span>
            <span class="font-bebas text-2xl text-white tracking-wider">EventSphere</span>
        </a>

        {{-- Desktop nav --}}
        <div class="hidden md:flex items-center gap-1">
            <a href="{{ route('home') }}" class="px-4 py-2 rounded-lg text-sm font-semibold transition-colors cursor-pointer {{ request()->routeIs('home') ? 'text-white bg-white/[0.08]' : 'text-[#888] hover:text-white hover:bg-white/[0.05]' }}">{{ __('messages.nav_home') }}</a>
            <a href="{{ route('events.index') }}" class="px-4 py-2 rounded-lg text-sm font-semibold transition-colors cursor-pointer {{ request()->routeIs('events.*') ? 'text-white bg-white/[0.08]' : 'text-[#888] hover:text-white hover:bg-white/[0.05]' }}">{{ __('messages.nav_browse') }}</a>
            <a href="{{ route('search') }}" class="px-4 py-2 rounded-lg text-sm font-semibold text-[#888] hover:text-white hover:bg-white/[0.05] transition-colors cursor-pointer">{{ __('messages.nav_search') }}</a>
        </div>

        {{-- Right side --}}
        <div class="flex items-center gap-2">
            @auth
                <a href="{{ route('events.create') }}"
                   class="hidden sm:inline-flex items-center gap-1.5 text-sm font-bold py-2 px-4 rounded-lg transition-all cursor-pointer"
                   style="background: rgba(225,29,72,0.15); border: 1px solid rgba(225,29,72,0.4); color: #E11D48;"
                   onmouseover="this.style.background='#E11D48';this.style.color='#fff'"
                   onmouseout="this.style.background='rgba(225,29,72,0.15)';this.style.color='#E11D48'">
                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 4v16m8-8H4"/></svg>
                    {{ __('messages.nav_host_event') }}
                </a>

                {{-- User dropdown --}}
                <div class="relative" x-data="{ open: false }">
                    <button @click="open=!open" class="flex items-center gap-2 cursor-pointer rounded-lg p-1 hover:bg-white/[0.06] transition-colors">
                        <img src="{{ Auth::user()->avatar_url }}" alt="{{ Auth::user()->name }}"
                             class="w-7 h-7 rounded-full object-cover ring-1 ring-white/10">
                        <svg class="w-3.5 h-3.5 text-[#555]" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/></svg>
                    </button>
                    <div x-show="open" @click.away="open=false" x-transition:enter="transition ease-out duration-100" x-transition:enter-start="opacity-0 scale-95" x-transition:enter-end="opacity-100 scale-100"
                         class="absolute right-0 top-full mt-2 w-52 rounded-xl py-1 z-50"
                         style="background: #181818; border: 1px solid rgba(255,255,255,0.1); box-shadow: 0 16px 40px rgba(0,0,0,0.6);">
                        <div class="px-4 py-3 border-b border-white/[0.07]">
                            <p class="text-sm font-semibold text-white truncate">{{ Auth::user()->name }}</p>
                            <p class="text-xs text-[#555] truncate">{{ Auth::user()->email }}</p>
                        </div>
                        <a href="{{ route('dashboard') }}" class="flex items-center gap-2.5 px-4 py-2.5 text-sm text-[#888] hover:text-white hover:bg-white/[0.05] transition-colors cursor-pointer">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/></svg>
                            {{ __('messages.nav_dashboard') }}
                        </a>
                        <a href="{{ route('profile.show', Auth::user()->username ?? Auth::user()->_id) }}" class="flex items-center gap-2.5 px-4 py-2.5 text-sm text-[#888] hover:text-white hover:bg-white/[0.05] transition-colors cursor-pointer">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/></svg>
                            {{ __('messages.nav_profile') }}
                        </a>
                        <a href="{{ route('events.mine') }}" class="flex items-center gap-2.5 px-4 py-2.5 text-sm text-[#888] hover:text-white hover:bg-white/[0.05] transition-colors cursor-pointer">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                            {{ __('messages.nav_my_events') }}
                        </a>
                        <a href="{{ route('profile.rsvps') }}" class="flex items-center gap-2.5 px-4 py-2.5 text-sm text-[#888] hover:text-white hover:bg-white/[0.05] transition-colors cursor-pointer">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                            {{ __('messages.nav_my_rsvps') }}
                        </a>
                        <div class="border-t border-white/[0.07] mt-1 pt-1">
                            <form method="POST" action="{{ route('logout') }}">
                                @csrf
                                <button type="submit" class="w-full flex items-center gap-2.5 px-4 py-2.5 text-sm text-[#E11D48] hover:bg-white/[0.05] transition-colors cursor-pointer">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"/></svg>
                                    {{ __('messages.nav_sign_out') }}
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
            @else
                <a href="{{ route('login') }}" class="text-sm font-semibold text-[#888] hover:text-white px-3 py-2 rounded-lg hover:bg-white/[0.05] transition-colors cursor-pointer">{{ __('messages.nav_sign_in') }}</a>
                <a href="{{ route('register') }}" class="es-btn text-sm py-2 px-4">{{ __('messages.nav_join_free') }}</a>
            @endauth

            <button class="md:hidden p-2 rounded-lg hover:bg-white/[0.06] transition-colors cursor-pointer" onclick="document.getElementById('mobile-menu').classList.toggle('hidden')">
                <svg class="w-5 h-5 text-[#888]" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"/></svg>
            </button>
        </div>
    </div>

    {{-- Mobile nav --}}
    <div id="mobile-menu" class="hidden md:hidden border-t border-white/[0.07]" style="background: rgba(8,8,8,0.95);">
        <div class="px-4 py-3 flex flex-col gap-1">
            <a href="{{ route('home') }}" class="py-2.5 px-3 rounded-lg text-sm font-semibold text-[#888] hover:text-white hover:bg-white/[0.05] transition-colors cursor-pointer">{{ __('messages.nav_home') }}</a>
            <a href="{{ route('events.index') }}" class="py-2.5 px-3 rounded-lg text-sm font-semibold text-[#888] hover:text-white hover:bg-white/[0.05] transition-colors cursor-pointer">{{ __('messages.nav_browse') }}</a>
            <a href="{{ route('search') }}" class="py-2.5 px-3 rounded-lg text-sm font-semibold text-[#888] hover:text-white hover:bg-white/[0.05] transition-colors cursor-pointer">{{ __('messages.nav_search') }}</a>
            @auth
                <a href="{{ route('events.create') }}" class="py-2.5 px-3 rounded-lg text-sm font-semibold text-[#E11D48] hover:bg-white/[0.05] transition-colors cursor-pointer">+ {{ __('messages.create_event') }}</a>
            @else
                <a href="{{ route('login') }}" class="py-2.5 px-3 rounded-lg text-sm font-semibold text-[#888] hover:text-white hover:bg-white/[0.05] transition-colors cursor-pointer">{{ __('messages.nav_sign_in') }}</a>
                <a href="{{ route('register') }}" class="py-2.5 px-3 rounded-lg text-sm font-semibold text-[#E11D48] cursor-pointer">{{ __('messages.nav_join_free') }}</a>
            @endauth
        </div>
    </div>
</nav>

<script defer src="https://unpkg.com/alpinejs@3.x.x/dist/cdn.min.js"></script>
