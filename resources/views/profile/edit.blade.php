@extends('layouts.app')
@section('title', __('messages.profile_edit_title'))

@section('content')
<div class="max-w-3xl mx-auto px-4 sm:px-6 lg:px-8 py-10">

    {{-- Page header --}}
    <div class="mb-8">
        <p class="text-xs font-bold uppercase tracking-widest mb-1" style="color:#E11D48;">{{ __('messages.nav_profile') }}</p>
        <h1 class="text-3xl font-black text-white">{{ __('messages.profile_edit_title') }}</h1>
        <p class="text-sm mt-1" style="color:#555;">{{ __('messages.profile_edit_subtitle') }}</p>
    </div>

    {{-- ── Profile Form ─────────────────────────────────────────────── --}}
    <form action="{{ route('profile.update') }}" method="POST" enctype="multipart/form-data" class="space-y-5">
        @csrf @method('PUT')

        <div class="rounded-2xl overflow-hidden" style="background:rgba(255,255,255,0.025);border:1px solid rgba(255,255,255,0.08);backdrop-filter:blur(16px);">

            {{-- Section header --}}
            <div class="px-6 py-4" style="border-bottom:1px solid rgba(255,255,255,0.06);">
                <div class="flex items-center gap-2">
                    <div class="w-1 h-4 rounded-full" style="background:linear-gradient(to bottom,#E11D48,#7C3AED);"></div>
                    <h2 class="font-bold text-sm text-white">{{ __('messages.profile_section_info') }}</h2>
                </div>
            </div>

            <div class="p-6 space-y-5">

                {{-- Avatar --}}
                <div class="flex items-center gap-5">
                    <div class="relative shrink-0">
                        <div class="absolute inset-0 rounded-2xl pointer-events-none"
                             style="background:linear-gradient(135deg,#E11D48,#7C3AED);padding:2px;border-radius:18px;">
                            <div style="background:#0A0A0A;border-radius:16px;width:100%;height:100%;"></div>
                        </div>
                        <img id="avatar-preview" src="{{ $user->avatar_url }}" alt="{{ $user->name }}"
                             class="relative w-20 h-20 rounded-2xl object-cover"
                             style="border:2px solid transparent;background:linear-gradient(#0A0A0A,#0A0A0A) padding-box,linear-gradient(135deg,#E11D48,#7C3AED) border-box;">
                        <button type="button"
                                onclick="document.getElementById('avatar').click()"
                                class="absolute -bottom-1.5 -right-1.5 w-7 h-7 rounded-full flex items-center justify-center cursor-pointer transition-all"
                                style="background:#E11D48;box-shadow:0 2px 12px rgba(225,29,72,0.5);"
                                onmouseover="this.style.background='#c01538'" onmouseout="this.style.background='#E11D48'">
                            <svg class="w-3.5 h-3.5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 9a2 2 0 012-2h.93a2 2 0 001.664-.89l.812-1.22A2 2 0 0110.07 4h3.86a2 2 0 011.664.89l.812 1.22A2 2 0 0018.07 7H19a2 2 0 012 2v9a2 2 0 01-2 2H5a2 2 0 01-2-2V9z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 13a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                        </button>
                        <input type="file" id="avatar" name="avatar" accept="image/*" class="hidden" onchange="previewAvatar(this)">
                        <input type="hidden" name="remove_avatar" id="remove_avatar_flag" value="0">
                    </div>

                    <div>
                        <p class="text-sm font-semibold text-white mb-0.5">{{ __('messages.profile_photo') }}</p>
                        <p class="text-xs mb-2" style="color:#444;">{{ __('messages.profile_photo_hint') }}</p>
                        @if($user->avatar)
                            <button type="button" id="remove-avatar-btn"
                                    onclick="removeAvatar()"
                                    class="flex items-center gap-1.5 text-xs font-semibold cursor-pointer transition-colors"
                                    style="color:#555;"
                                    onmouseover="this.style.color='#E11D48'" onmouseout="this.style.color='#555'">
                                <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                                {{ __('messages.profile_remove_photo') }}
                            </button>
                        @endif
                        @error('avatar')<p class="text-xs mt-1" style="color:#E11D48;">{{ $message }}</p>@enderror
                    </div>
                </div>

                {{-- Name + Username --}}
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-xs font-bold uppercase tracking-wider mb-2" style="color:#555;">{{ __('messages.profile_full_name') }}</label>
                        <input type="text" name="name" value="{{ old('name', $user->name) }}"
                               class="w-full px-4 py-3 rounded-xl text-white text-sm placeholder-[#333] transition-all outline-none"
                               style="background:rgba(255,255,255,0.04);border:1px solid {{ $errors->has('name') ? '#E11D48' : 'rgba(255,255,255,0.08)' }};"
                               onfocus="this.style.borderColor='rgba(225,29,72,0.5)';this.style.boxShadow='0 0 0 3px rgba(225,29,72,0.08)'"
                               onblur="this.style.borderColor='rgba(255,255,255,0.08)';this.style.boxShadow='none'">
                        @error('name')<p class="text-xs mt-1.5" style="color:#E11D48;">{{ $message }}</p>@enderror
                    </div>
                    <div>
                        <label class="block text-xs font-bold uppercase tracking-wider mb-2" style="color:#555;">{{ __('messages.profile_username_label') }}</label>
                        <div class="relative">
                            <span class="absolute left-4 top-1/2 -translate-y-1/2 text-sm font-bold" style="color:#333;">@</span>
                            <input type="text" name="username" value="{{ old('username', $user->username) }}"
                                   class="w-full pl-8 pr-4 py-3 rounded-xl text-white text-sm placeholder-[#333] transition-all outline-none"
                                   style="background:rgba(255,255,255,0.04);border:1px solid {{ $errors->has('username') ? '#E11D48' : 'rgba(255,255,255,0.08)' }};"
                                   onfocus="this.style.borderColor='rgba(225,29,72,0.5)';this.style.boxShadow='0 0 0 3px rgba(225,29,72,0.08)'"
                                   onblur="this.style.borderColor='rgba(255,255,255,0.08)';this.style.boxShadow='none'">
                        </div>
                        @error('username')<p class="text-xs mt-1.5" style="color:#E11D48;">{{ $message }}</p>@enderror
                    </div>
                </div>

                {{-- Bio --}}
                <div>
                    <label class="block text-xs font-bold uppercase tracking-wider mb-2" style="color:#555;">{{ __('messages.profile_bio_label') }}</label>
                    <textarea name="bio" rows="3"
                              class="w-full px-4 py-3 rounded-xl text-white text-sm placeholder-[#333] resize-none transition-all outline-none"
                              style="background:rgba(255,255,255,0.04);border:1px solid rgba(255,255,255,0.08);"
                              placeholder="{{ __('messages.profile_bio_placeholder') }}"
                              onfocus="this.style.borderColor='rgba(225,29,72,0.5)';this.style.boxShadow='0 0 0 3px rgba(225,29,72,0.08)'"
                              onblur="this.style.borderColor='rgba(255,255,255,0.08)';this.style.boxShadow='none'">{{ old('bio', $user->bio) }}</textarea>
                    @error('bio')<p class="text-xs mt-1.5" style="color:#E11D48;">{{ $message }}</p>@enderror
                </div>

                {{-- Location + Website --}}
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-xs font-bold uppercase tracking-wider mb-2" style="color:#555;">{{ __('messages.profile_location_label') }}</label>
                        <input type="text" name="location" value="{{ old('location', $user->location) }}"
                               class="w-full px-4 py-3 rounded-xl text-white text-sm transition-all outline-none"
                               style="background:rgba(255,255,255,0.04);border:1px solid rgba(255,255,255,0.08);"
                               placeholder="{{ __('messages.profile_location_placeholder') }}"
                               onfocus="this.style.borderColor='rgba(225,29,72,0.5)';this.style.boxShadow='0 0 0 3px rgba(225,29,72,0.08)'"
                               onblur="this.style.borderColor='rgba(255,255,255,0.08)';this.style.boxShadow='none'">
                    </div>
                    <div>
                        <label class="block text-xs font-bold uppercase tracking-wider mb-2" style="color:#555;">{{ __('messages.profile_website_label') }}</label>
                        <input type="url" name="website" value="{{ old('website', $user->website) }}"
                               class="w-full px-4 py-3 rounded-xl text-white text-sm transition-all outline-none"
                               style="background:rgba(255,255,255,0.04);border:1px solid rgba(255,255,255,0.08);"
                               placeholder="https://yoursite.com"
                               onfocus="this.style.borderColor='rgba(225,29,72,0.5)';this.style.boxShadow='0 0 0 3px rgba(225,29,72,0.08)'"
                               onblur="this.style.borderColor='rgba(255,255,255,0.08)';this.style.boxShadow='none'">
                    </div>
                </div>

                {{-- Actions --}}
                <div class="flex gap-3 pt-2">
                    <button type="submit"
                            class="px-7 py-3 text-sm font-bold text-white rounded-xl cursor-pointer transition-all"
                            style="background:linear-gradient(135deg,#E11D48,#7C3AED);box-shadow:0 4px 20px rgba(225,29,72,0.25);"
                            onmouseover="this.style.boxShadow='0 6px 28px rgba(225,29,72,0.4)';this.style.transform='translateY(-1px)'"
                            onmouseout="this.style.boxShadow='0 4px 20px rgba(225,29,72,0.25)';this.style.transform='translateY(0)'">
                        {{ __('messages.profile_save') }}
                    </button>
                    <a href="{{ route('profile.show', $user->username ?? $user->_id) }}"
                       class="px-6 py-3 text-sm font-semibold rounded-xl cursor-pointer transition-all"
                       style="background:rgba(255,255,255,0.04);border:1px solid rgba(255,255,255,0.08);color:#666;"
                       onmouseover="this.style.color='#fff';this.style.borderColor='rgba(255,255,255,0.15)'"
                       onmouseout="this.style.color='#666';this.style.borderColor='rgba(255,255,255,0.08)'">
                        {{ __('messages.profile_cancel') }}
                    </a>
                </div>
            </div>
        </div>
    </form>

    {{-- ── Change Password ───────────────────────────────────────────── --}}
    <form action="{{ route('profile.password') }}" method="POST" class="mt-5">
        @csrf @method('PUT')

        <div class="rounded-2xl overflow-hidden" style="background:rgba(255,255,255,0.025);border:1px solid rgba(255,255,255,0.08);backdrop-filter:blur(16px);">
            <div class="px-6 py-4" style="border-bottom:1px solid rgba(255,255,255,0.06);">
                <div class="flex items-center justify-between">
                    <div class="flex items-center gap-2">
                        <div class="w-1 h-4 rounded-full" style="background:linear-gradient(to bottom,#7C3AED,#0EA5E9);"></div>
                        <h2 class="font-bold text-sm text-white">{{ __('messages.profile_change_password') }}</h2>
                    </div>
                    <span class="text-xs font-semibold px-2.5 py-1 rounded-lg" style="background:rgba(124,58,237,0.1);border:1px solid rgba(124,58,237,0.2);color:#7C3AED;">5 attempts max</span>
                </div>
            </div>

            <div class="p-6 space-y-4">

                {{-- Success / error flash --}}
                @if(session('success') && str_contains(session('success'), 'Password'))
                    <div class="flex items-center gap-2.5 px-4 py-3 rounded-xl text-sm font-semibold" style="background:rgba(34,197,94,0.08);border:1px solid rgba(34,197,94,0.2);color:#22c55e;">
                        <svg class="w-4 h-4 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                        {{ session('success') }}
                    </div>
                @endif

                {{-- Current password --}}
                <div>
                    <label class="block text-xs font-bold uppercase tracking-wider mb-2" style="color:#555;">{{ __('messages.profile_current_password') }}</label>
                    <div class="relative">
                        <input type="password" name="current_password" id="pwd-current" placeholder="••••••••"
                               class="w-full px-4 py-3 pr-11 rounded-xl text-white text-sm transition-all outline-none"
                               style="background:rgba(255,255,255,0.04);border:1px solid {{ $errors->has('current_password') ? '#E11D48' : 'rgba(255,255,255,0.08)' }};"
                               onfocus="this.style.borderColor='rgba(124,58,237,0.5)';this.style.boxShadow='0 0 0 3px rgba(124,58,237,0.08)'"
                               onblur="this.style.borderColor='{{ $errors->has('current_password') ? '#E11D48' : 'rgba(255,255,255,0.08)' }}';this.style.boxShadow='none'">
                        <button type="button" onclick="togglePwd('pwd-current',this)"
                                class="absolute right-3 top-1/2 -translate-y-1/2 p-1 cursor-pointer transition-colors"
                                style="color:#444;" onmouseover="this.style.color='#7C3AED'" onmouseout="this.style.color='#444'">
                            <svg class="w-4 h-4 eye-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/></svg>
                        </button>
                    </div>
                    @error('current_password')<p class="text-xs mt-1.5" style="color:#E11D48;">{{ $message }}</p>@enderror
                </div>

                {{-- New password + confirm --}}
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-xs font-bold uppercase tracking-wider mb-2" style="color:#555;">{{ __('messages.profile_new_password') }}</label>
                        <div class="relative">
                            <input type="password" name="password" id="pwd-new" placeholder="Min 12 characters"
                                   class="w-full px-4 py-3 pr-11 rounded-xl text-white text-sm transition-all outline-none"
                                   style="background:rgba(255,255,255,0.04);border:1px solid {{ $errors->has('password') ? '#E11D48' : 'rgba(255,255,255,0.08)' }};"
                                   onfocus="this.style.borderColor='rgba(124,58,237,0.5)';this.style.boxShadow='0 0 0 3px rgba(124,58,237,0.08)'"
                                   onblur="this.style.borderColor='{{ $errors->has('password') ? '#E11D48' : 'rgba(255,255,255,0.08)' }}';this.style.boxShadow='none'"
                                   oninput="checkStrength(this.value)">
                            <button type="button" onclick="togglePwd('pwd-new',this)"
                                    class="absolute right-3 top-1/2 -translate-y-1/2 p-1 cursor-pointer transition-colors"
                                    style="color:#444;" onmouseover="this.style.color='#7C3AED'" onmouseout="this.style.color='#444'">
                                <svg class="w-4 h-4 eye-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/></svg>
                            </button>
                        </div>

                        {{-- Strength meter --}}
                        <div id="strength-wrap" class="hidden mt-2.5 space-y-2">
                            <div class="flex gap-1">
                                <div id="s1" class="h-1 flex-1 rounded-full transition-all duration-300" style="background:#1a1a1a;"></div>
                                <div id="s2" class="h-1 flex-1 rounded-full transition-all duration-300" style="background:#1a1a1a;"></div>
                                <div id="s3" class="h-1 flex-1 rounded-full transition-all duration-300" style="background:#1a1a1a;"></div>
                                <div id="s4" class="h-1 flex-1 rounded-full transition-all duration-300" style="background:#1a1a1a;"></div>
                            </div>
                            <p id="strength-label" class="text-xs font-semibold" style="color:#555;"></p>
                            <div class="space-y-1 pt-0.5">
                                <p id="r-len"  class="text-xs flex items-center gap-1.5" style="color:#333;"><span class="req-dot w-1.5 h-1.5 rounded-full shrink-0" style="background:#333;"></span>12+ characters</p>
                                <p id="r-upper" class="text-xs flex items-center gap-1.5" style="color:#333;"><span class="req-dot w-1.5 h-1.5 rounded-full shrink-0" style="background:#333;"></span>Uppercase letter</p>
                                <p id="r-lower" class="text-xs flex items-center gap-1.5" style="color:#333;"><span class="req-dot w-1.5 h-1.5 rounded-full shrink-0" style="background:#333;"></span>Lowercase letter</p>
                                <p id="r-num"   class="text-xs flex items-center gap-1.5" style="color:#333;"><span class="req-dot w-1.5 h-1.5 rounded-full shrink-0" style="background:#333;"></span>Number</p>
                                <p id="r-sym"   class="text-xs flex items-center gap-1.5" style="color:#333;"><span class="req-dot w-1.5 h-1.5 rounded-full shrink-0" style="background:#333;"></span>Symbol (@$!%*#?&^_-)</p>
                            </div>
                        </div>

                        @error('password')<p class="text-xs mt-1.5" style="color:#E11D48;">{{ $message }}</p>@enderror
                    </div>

                    <div>
                        <label class="block text-xs font-bold uppercase tracking-wider mb-2" style="color:#555;">{{ __('messages.profile_confirm_password') }}</label>
                        <div class="relative">
                            <input type="password" name="password_confirmation" id="pwd-confirm" placeholder="••••••••"
                                   class="w-full px-4 py-3 pr-11 rounded-xl text-white text-sm transition-all outline-none"
                                   style="background:rgba(255,255,255,0.04);border:1px solid rgba(255,255,255,0.08);"
                                   onfocus="this.style.borderColor='rgba(124,58,237,0.5)';this.style.boxShadow='0 0 0 3px rgba(124,58,237,0.08)'"
                                   onblur="this.style.borderColor='rgba(255,255,255,0.08)';this.style.boxShadow='none'">
                            <button type="button" onclick="togglePwd('pwd-confirm',this)"
                                    class="absolute right-3 top-1/2 -translate-y-1/2 p-1 cursor-pointer transition-colors"
                                    style="color:#444;" onmouseover="this.style.color='#7C3AED'" onmouseout="this.style.color='#444'">
                                <svg class="w-4 h-4 eye-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/></svg>
                            </button>
                        </div>
                    </div>
                </div>

                <div class="pt-1">
                    <button type="submit"
                            class="px-7 py-3 text-sm font-bold text-white rounded-xl cursor-pointer transition-all"
                            style="background:rgba(124,58,237,0.15);border:1px solid rgba(124,58,237,0.3);color:#a78bfa;"
                            onmouseover="this.style.background='rgba(124,58,237,0.3)';this.style.borderColor='rgba(124,58,237,0.5)';this.style.transform='translateY(-1px)'"
                            onmouseout="this.style.background='rgba(124,58,237,0.15)';this.style.borderColor='rgba(124,58,237,0.3)';this.style.transform='translateY(0)'">
                        {{ __('messages.profile_update_password') }}
                    </button>
                </div>
            </div>
        </div>
    </form>
</div>
@endsection

@push('scripts')
<script>
function previewAvatar(input) {
    if (input.files?.[0]) {
        const reader = new FileReader();
        reader.onload = e => {
            document.getElementById('avatar-preview').src = e.target.result;
            document.getElementById('remove_avatar_flag').value = '0';
        };
        reader.readAsDataURL(input.files[0]);
    }
}

function removeAvatar() {
    const defaultAvatar = 'https://ui-avatars.com/api/?name={{ urlencode($user->name) }}&background=E11D48&color=fff&size=80';
    document.getElementById('avatar-preview').src = defaultAvatar;
    document.getElementById('remove_avatar_flag').value = '1';
    document.getElementById('avatar').value = '';
    const btn = document.getElementById('remove-avatar-btn');
    if (btn) btn.remove();
}

function togglePwd(id, btn) {
    const inp = document.getElementById(id);
    const showing = inp.type === 'text';
    inp.type = showing ? 'password' : 'text';
    const svg = btn.querySelector('svg');
    svg.innerHTML = showing
        ? '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>'
        : '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.88 9.88l-3.29-3.29m7.532 7.532l3.29 3.29M3 3l3.59 3.59m0 0A9.953 9.953 0 0112 5c4.478 0 8.268 2.943 9.543 7a10.025 10.025 0 01-4.132 5.411m0 0L21 21"/>';
}

function checkStrength(val) {
    const wrap  = document.getElementById('strength-wrap');
    if (!val) { wrap.classList.add('hidden'); return; }
    wrap.classList.remove('hidden');

    const checks = {
        len:   val.length >= 12,
        upper: /[A-Z]/.test(val),
        lower: /[a-z]/.test(val),
        num:   /[0-9]/.test(val),
        sym:   /[@$!%*#?&^_\-]/.test(val),
    };

    const score = Object.values(checks).filter(Boolean).length;
    const colors = ['#E11D48','#f97316','#eab308','#22c55e','#22c55e'];
    const labels = ['Very Weak','Weak','Fair','Good','Strong'];
    const segs = ['s1','s2','s3','s4'].map(id => document.getElementById(id));
    const fill = colors[score - 1] || '#1a1a1a';

    segs.forEach((s, i) => { s.style.background = i < score ? fill : '#1a1a1a'; });

    const lbl = document.getElementById('strength-label');
    lbl.textContent = labels[score - 1] || '';
    lbl.style.color = fill;

    const map = { 'r-len': checks.len, 'r-upper': checks.upper, 'r-lower': checks.lower, 'r-num': checks.num, 'r-sym': checks.sym };
    Object.entries(map).forEach(([id, ok]) => {
        const el = document.getElementById(id);
        el.style.color = ok ? '#22c55e' : '#555';
        el.querySelector('.req-dot').style.background = ok ? '#22c55e' : '#333';
    });
}
</script>
@endpush
