<footer class="border-t border-white/[0.07] mt-16" style="background: #080808;">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-14">
        <div class="grid grid-cols-1 md:grid-cols-4 gap-10">

            {{-- Brand --}}
            <div class="col-span-1 md:col-span-2">
                <div class="flex items-center gap-2.5 mb-4">
                    <span class="w-8 h-8 rounded-lg flex items-center justify-center shrink-0" style="background: #E11D48; box-shadow: 0 0 14px rgba(225,29,72,0.35);">
                        <svg class="w-4 h-4 text-white" fill="currentColor" viewBox="0 0 24 24"><path d="M12 2C8.13 2 5 5.13 5 9c0 5.25 7 13 7 13s7-7.75 7-13c0-3.87-3.13-7-7-7zm0 9.5c-1.38 0-2.5-1.12-2.5-2.5s1.12-2.5 2.5-2.5 2.5 1.12 2.5 2.5-1.12 2.5-2.5 2.5z"/></svg>
                    </span>
                    <span class="font-bebas text-2xl text-white tracking-wider">EventSphere</span>
                </div>
                <p class="text-[#555] text-sm leading-relaxed max-w-xs mb-6">
                    Real events, any city. Powered by live data from Ticketmaster. Host your own events free.
                </p>

                {{-- Language switcher --}}
                <div class="flex gap-2">
                    @foreach(['en' => 'EN', 'hi' => 'HI', 'fr' => 'FR'] as $code => $label)
                        <a href="{{ route('lang.switch', $code) }}"
                           class="text-xs px-3 py-1.5 rounded-md font-semibold transition-colors cursor-pointer
                                  {{ app()->getLocale() == $code ? 'bg-[#E11D48]/10 text-[#E11D48] ring-1 ring-[#E11D48]/30' : 'text-[#555] hover:text-[#888] bg-white/[0.03] hover:bg-white/[0.06]' }}">
                            {{ $label }}
                        </a>
                    @endforeach
                </div>
            </div>

            {{-- Explore --}}
            <div>
                <h4 class="text-xs font-bold uppercase tracking-widest text-[#888] mb-5">{{ __('messages.footer_explore') }}</h4>
                <ul class="space-y-3">
                    <li><a href="{{ route('events.index') }}" class="text-[#555] text-sm hover:text-white transition-colors cursor-pointer">{{ __('messages.footer_all_events') }}</a></li>
                    <li><a href="{{ route('events.index') }}?price=free" class="text-[#555] text-sm hover:text-white transition-colors cursor-pointer">{{ __('messages.footer_free_events') }}</a></li>
                    <li><a href="{{ route('events.index') }}?city=New+York" class="text-[#555] text-sm hover:text-white transition-colors cursor-pointer">New York</a></li>
                    <li><a href="{{ route('events.index') }}?city=London" class="text-[#555] text-sm hover:text-white transition-colors cursor-pointer">London</a></li>
                    <li><a href="{{ route('search') }}" class="text-[#555] text-sm hover:text-white transition-colors cursor-pointer">{{ __('messages.nav_search') }}</a></li>
                </ul>
            </div>

            {{-- Account --}}
            <div>
                <h4 class="text-xs font-bold uppercase tracking-widest text-[#888] mb-5">{{ __('messages.footer_account') }}</h4>
                <ul class="space-y-3">
                    @auth
                        <li><a href="{{ route('dashboard') }}" class="text-[#555] text-sm hover:text-white transition-colors cursor-pointer">{{ __('messages.nav_dashboard') }}</a></li>
                        <li><a href="{{ route('events.create') }}" class="text-[#555] text-sm hover:text-white transition-colors cursor-pointer">{{ __('messages.create_event') }}</a></li>
                        <li><a href="{{ route('profile.edit') }}" class="text-[#555] text-sm hover:text-white transition-colors cursor-pointer">{{ __('messages.footer_settings') }}</a></li>
                    @else
                        <li><a href="{{ route('login') }}" class="text-[#555] text-sm hover:text-white transition-colors cursor-pointer">{{ __('messages.nav_sign_in') }}</a></li>
                        <li><a href="{{ route('register') }}" class="text-[#555] text-sm hover:text-white transition-colors cursor-pointer">{{ __('messages.footer_register_free') }}</a></li>
                    @endauth
                </ul>
            </div>
        </div>

        <div class="mt-12 pt-6 flex flex-col sm:flex-row items-center justify-between gap-3" style="border-top: 1px solid rgba(255,255,255,0.06);">
            <p class="text-[#333] text-xs">&copy; {{ date('Y') }} EventSphere — Built with Laravel &amp; MongoDB</p>
            <p class="text-[#222] text-xs">{{ __('messages.visit_count', ['count' => session('visit_count', 1)]) }}</p>
        </div>
    </div>
</footer>
