@extends('layouts.app')
@section('title', 'Reset Password — EventSphere')

@section('content')
<div class="min-h-screen flex items-center justify-center px-4 py-20">
    <div class="w-full max-w-md">

        <div class="text-center mb-8">
            <a href="{{ route('home') }}" class="inline-flex items-center gap-2 mb-6">
                <div class="w-9 h-9 rounded-lg flex items-center justify-center font-black text-white text-sm"
                     style="background: #E11D48; box-shadow: 0 0 20px rgba(225,29,72,0.4);">ES</div>
                <span class="font-bold text-white text-lg">EventSphere</span>
            </a>
            <h1 class="text-2xl font-bold text-white mb-2">Choose a new password</h1>
            <p class="text-[#666] text-sm">Make it strong and memorable.</p>
        </div>

        <div class="rounded-2xl p-8" style="background: #161616; border: 1px solid rgba(255,255,255,0.08);">

            @if($errors->has('token'))
                <div class="mb-6 p-4 rounded-xl text-sm font-semibold"
                     style="background: rgba(225,29,72,0.1); border: 1px solid rgba(225,29,72,0.25); color: #f87171;">
                    {{ $errors->first('token') }}
                </div>
            @endif

            <form method="POST" action="{{ route('password.update', $token) }}" class="space-y-5">
                @csrf

                <div>
                    <label class="block text-sm font-semibold text-[#999] mb-2">New Password</label>
                    <input type="password" name="password" required autofocus
                           placeholder="Minimum 8 characters"
                           class="es-input w-full h-12 px-4">
                    @error('password')
                        <p class="mt-1.5 text-sm" style="color: #f87171;">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label class="block text-sm font-semibold text-[#999] mb-2">Confirm Password</label>
                    <input type="password" name="password_confirmation" required
                           placeholder="Type it again"
                           class="es-input w-full h-12 px-4">
                </div>

                <button type="submit" class="es-btn w-full py-3 text-sm font-bold justify-center">
                    Reset Password →
                </button>
            </form>
        </div>
    </div>
</div>
@endsection
