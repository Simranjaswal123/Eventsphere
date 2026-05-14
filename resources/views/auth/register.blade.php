@extends('layouts.app')
@section('title', 'Create Account')

@section('content')
<div class="min-h-screen flex">
    {{-- Left panel --}}
    <div class="hidden lg:flex lg:w-1/2 flex-col justify-between p-12 relative overflow-hidden"
         style="background: #0a0a0a; border-right: 1px solid rgba(255,255,255,0.06);">
        <div class="absolute top-0 right-0 w-72 h-72 rounded-full pointer-events-none opacity-[0.06]" style="background: radial-gradient(circle, #E11D48, transparent 70%);"></div>

        <div class="relative">
            <a href="{{ route('home') }}" class="flex items-center gap-2.5 cursor-pointer">
                <span class="w-8 h-8 rounded-lg flex items-center justify-center" style="background: #E11D48;">
                    <svg class="w-4 h-4 text-white" fill="currentColor" viewBox="0 0 24 24"><path d="M12 2C8.13 2 5 5.13 5 9c0 5.25 7 13 7 13s7-7.75 7-13c0-3.87-3.13-7-7-7zm0 9.5c-1.38 0-2.5-1.12-2.5-2.5s1.12-2.5 2.5-2.5 2.5 1.12 2.5 2.5-1.12 2.5-2.5 2.5z"/></svg>
                </span>
                <span class="font-bebas text-2xl text-white tracking-wider">EventSphere</span>
            </a>
        </div>

        <div class="relative">
            <h2 class="heading-lg text-white mb-4">Host Events.<br><span style="color:#E11D48;">Free.</span></h2>
            <p class="text-[#555] text-lg">Create events, grow your audience, manage RSVPs.</p>
        </div>

        <div class="relative space-y-3">
            @foreach(['Real event data from any city','Create & host your own events','RSVP, like, comment on events'] as $feat)
                <div class="flex items-center gap-3">
                    <span class="w-5 h-5 rounded-full flex items-center justify-center shrink-0" style="background: rgba(225,29,72,0.15); border: 1px solid rgba(225,29,72,0.3);">
                        <svg class="w-2.5 h-2.5 text-[#E11D48]" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 13l4 4L19 7"/></svg>
                    </span>
                    <span class="text-[#666] text-sm">{{ $feat }}</span>
                </div>
            @endforeach
        </div>
    </div>

    {{-- Right panel --}}
    <div class="w-full lg:w-1/2 flex items-center justify-center px-6 py-16" style="background: #080808;">
        <div class="w-full max-w-md">

            <div class="mb-8">
                <div class="es-label mb-3">Get started</div>
                <h1 class="heading-md text-white mb-1.5">Create Account</h1>
                <p class="text-[#555] text-sm">Already joined? <a href="{{ route('login') }}" class="text-[#E11D48] font-semibold hover:underline cursor-pointer">Sign in</a></p>
            </div>

            <form action="{{ route('register.post') }}" method="POST" class="space-y-4">
                @csrf

                <div>
                    <label for="name" class="block text-xs font-semibold uppercase tracking-wider text-[#555] mb-2">Full Name</label>
                    <input type="text" id="name" name="name" value="{{ old('name') }}"
                           class="es-input {{ $errors->has('name') ? 'border-[#E11D48]' : '' }}"
                           placeholder="John Doe">
                    @error('name')<p class="mt-1.5 text-xs text-[#E11D48]">{{ $message }}</p>@enderror
                </div>

                <div>
                    <label for="username" class="block text-xs font-semibold uppercase tracking-wider text-[#555] mb-2">
                        Username <span class="text-[#333] normal-case font-normal">(optional)</span>
                    </label>
                    <input type="text" id="username" name="username" value="{{ old('username') }}"
                           class="es-input {{ $errors->has('username') ? 'border-[#E11D48]' : '' }}"
                           placeholder="johndoe">
                    @error('username')<p class="mt-1.5 text-xs text-[#E11D48]">{{ $message }}</p>@enderror
                </div>

                <div>
                    <label for="email" class="block text-xs font-semibold uppercase tracking-wider text-[#555] mb-2">Email</label>
                    <input type="email" id="email" name="email" value="{{ old('email') }}"
                           class="es-input {{ $errors->has('email') ? 'border-[#E11D48]' : '' }}"
                           placeholder="you@example.com">
                    @error('email')<p class="mt-1.5 text-xs text-[#E11D48]">{{ $message }}</p>@enderror
                </div>

                <div>
                    <label for="password" class="block text-xs font-semibold uppercase tracking-wider text-[#555] mb-2">Password</label>
                    <div class="relative">
                        <input type="password" id="password" name="password"
                               class="es-input pr-12 {{ $errors->has('password') ? 'border-[#E11D48]' : '' }}"
                               placeholder="Min 8 characters">
                        <button type="button" onclick="togglePwd('password')"
                                class="absolute right-3 top-1/2 -translate-y-1/2 text-[#555] hover:text-white cursor-pointer transition-colors">
                            <svg class="w-4.5 h-4.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/></svg>
                        </button>
                    </div>
                    @error('password')<p class="mt-1.5 text-xs text-[#E11D48]">{{ $message }}</p>@enderror
                </div>

                <div>
                    <label for="password_confirmation" class="block text-xs font-semibold uppercase tracking-wider text-[#555] mb-2">Confirm Password</label>
                    <input type="password" id="password_confirmation" name="password_confirmation"
                           class="es-input" placeholder="Repeat password">
                </div>

                <button type="submit" class="es-btn w-full py-3 text-base">Create Account →</button>
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
