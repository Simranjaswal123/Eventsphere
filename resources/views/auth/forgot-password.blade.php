@extends('layouts.app')
@section('title', 'Reset Password — EventSphere')

@section('content')
<div class="min-h-screen flex items-center justify-center px-4 py-20">
    <div class="w-full max-w-md">

        {{-- Logo --}}
        <div class="text-center mb-8">
            <a href="{{ route('home') }}" class="inline-flex items-center gap-2 mb-6">
                <div class="w-9 h-9 rounded-lg flex items-center justify-center font-black text-white text-sm"
                     style="background: #E11D48; box-shadow: 0 0 20px rgba(225,29,72,0.4);">ES</div>
                <span class="font-bold text-white text-lg">EventSphere</span>
            </a>
            <h1 class="text-2xl font-bold text-white mb-2">Forgot your password?</h1>
            <p class="text-[#666] text-sm">Enter your email and we'll send a reset link.</p>
        </div>

        <div class="rounded-2xl p-8" style="background: #161616; border: 1px solid rgba(255,255,255,0.08);">

            @if(session('status'))
                <div class="mb-6 p-4 rounded-xl text-sm font-semibold"
                     style="background: rgba(34,197,94,0.1); border: 1px solid rgba(34,197,94,0.25); color: #4ade80;">
                    {{ session('status') }}
                </div>
            @endif

            @if($errors->has('token'))
                <div class="mb-6 p-4 rounded-xl text-sm font-semibold"
                     style="background: rgba(225,29,72,0.1); border: 1px solid rgba(225,29,72,0.25); color: #f87171;">
                    {{ $errors->first('token') }}
                </div>
            @endif

            <form method="POST" action="{{ route('password.email') }}" class="space-y-5">
                @csrf

                <div>
                    <label class="block text-sm font-semibold text-[#999] mb-2">Email Address</label>
                    <input type="email" name="email" value="{{ old('email') }}" required autofocus
                           placeholder="you@example.com"
                           class="es-input w-full h-12 px-4">
                    @error('email')
                        <p class="mt-1.5 text-sm" style="color: #f87171;">{{ $message }}</p>
                    @enderror
                </div>

                <button type="submit" class="es-btn w-full py-3 text-sm font-bold justify-center">
                    Send Reset Link →
                </button>
            </form>

            <div class="mt-6 text-center">
                <a href="{{ route('login') }}" class="text-sm text-[#555] hover:text-white transition-colors">
                    ← Back to sign in
                </a>
            </div>
        </div>
    </div>
</div>
@endsection
