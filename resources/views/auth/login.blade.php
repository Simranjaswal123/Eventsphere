@extends('layouts.app')
@section('title', 'Sign In')

@section('content')
<div class="min-h-screen flex">
    {{-- Left panel --}}
    <div class="hidden lg:flex lg:w-1/2 flex-col justify-between p-12 relative overflow-hidden"
         style="background: linear-gradient(135deg, #0f0f0f 0%, #080808 100%); border-right: 1px solid rgba(255,255,255,0.06);">
        {{-- Glow --}}
        <div class="absolute -top-20 -left-20 w-80 h-80 rounded-full pointer-events-none" style="background: radial-gradient(circle, rgba(225,29,72,0.12), transparent 70%);"></div>
        <div class="absolute bottom-0 right-0 w-64 h-64 rounded-full pointer-events-none" style="background: radial-gradient(circle, rgba(255,214,0,0.05), transparent 70%);"></div>

        <div class="relative">
            <a href="{{ route('home') }}" class="flex items-center gap-2.5 cursor-pointer">
                <span class="w-8 h-8 rounded-lg flex items-center justify-center" style="background: #E11D48; box-shadow: 0 0 16px rgba(225,29,72,0.4);">
                    <svg class="w-4 h-4 text-white" fill="currentColor" viewBox="0 0 24 24"><path d="M12 2C8.13 2 5 5.13 5 9c0 5.25 7 13 7 13s7-7.75 7-13c0-3.87-3.13-7-7-7zm0 9.5c-1.38 0-2.5-1.12-2.5-2.5s1.12-2.5 2.5-2.5 2.5 1.12 2.5 2.5-1.12 2.5-2.5 2.5z"/></svg>
                </span>
                <span class="font-bebas text-2xl text-white tracking-wider">EventSphere</span>
            </a>
        </div>

        <div class="relative">
            <h2 class="heading-lg text-white mb-4">Find Events<br>Everywhere.</h2>
            <p class="text-[#555] text-lg">Real concerts, sports, arts — any city in the world.</p>
        </div>

        <div class="relative grid grid-cols-3 gap-3">
            @foreach(['New York','London','Tokyo'] as $c)
                <div class="p-3 rounded-xl" style="background: rgba(255,255,255,0.04); border: 1px solid rgba(255,255,255,0.08);">
                    <p class="text-white font-semibold text-sm">{{ $c }}</p>
                    <p class="text-[#444] text-xs">Live events</p>
                </div>
            @endforeach
        </div>
    </div>

    {{-- Right panel --}}
    <div class="w-full lg:w-1/2 flex items-center justify-center px-6 py-16" style="background: #080808;">
        <div class="w-full max-w-md">

            <div class="mb-8">
                <div class="es-label mb-3">Welcome back</div>
                <h1 class="heading-md text-white mb-1.5">Sign In</h1>
                <p class="text-[#555] text-sm">Don't have an account? <a href="{{ route('register') }}" class="text-[#E11D48] font-semibold hover:underline cursor-pointer">Join free</a></p>
            </div>

            <form action="{{ route('login.post') }}" method="POST" class="space-y-4">
                @csrf

                <div>
                    <label for="email" class="block text-xs font-semibold uppercase tracking-wider text-[#555] mb-2">Email</label>
                    <input type="email" id="email" name="email"
                           value="{{ old('email', Cookie::get('last_email')) }}"
                           class="es-input {{ $errors->has('email') ? 'border-[#E11D48]' : '' }}"
                           placeholder="you@example.com" autocomplete="email">
                    @error('email')<p class="mt-1.5 text-xs text-[#E11D48]">{{ $message }}</p>@enderror
                </div>

                <div>
                    <label for="password" class="block text-xs font-semibold uppercase tracking-wider text-[#555] mb-2">Password</label>
                    <div class="relative">
                        <input type="password" id="password" name="password"
                               class="es-input pr-12 {{ $errors->has('password') ? 'border-[#E11D48]' : '' }}"
                               placeholder="••••••••" autocomplete="current-password">
                        <button type="button" onclick="togglePwd('password')"
                                class="absolute right-3 top-1/2 -translate-y-1/2 text-[#555] hover:text-white cursor-pointer transition-colors">
                            <svg class="w-4.5 h-4.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/></svg>
                        </button>
                    </div>
                    @error('password')<p class="mt-1.5 text-xs text-[#E11D48]">{{ $message }}</p>@enderror
                </div>

                <div class="flex items-center justify-between">
                    <label class="flex items-center gap-2 cursor-pointer">
                        <input type="checkbox" name="remember" class="accent-[#E11D48] w-4 h-4 rounded">
                        <span class="text-sm text-[#555]">Remember me</span>
                    </label>
                    <a href="{{ route('password.request') }}" class="text-sm text-[#555] hover:text-[#E11D48] transition-colors">
                        Forgot password?
                    </a>
                </div>

                <button type="submit" class="es-btn w-full py-3 text-base">Sign In →</button>
            </form>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
function togglePwd(id) {
    const el = document.getElementById(id);
    el.type = el.type === 'password' ? 'text' : 'password';
}
</script>
@endpush
