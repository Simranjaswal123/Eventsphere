@extends('layouts.app')
@section('title', 'Create Event')

@section('content')
<div class="max-w-3xl mx-auto px-4 sm:px-6 lg:px-8 py-10">

    {{-- Header --}}
    <div class="mb-8">
        <a href="{{ route('events.index') }}" class="flex items-center gap-2 text-slate-400 hover:text-white text-sm transition-colors cursor-pointer mb-4">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/></svg>
            Back to Events
        </a>
        <h1 class="font-righteous text-3xl text-white">Create New Event</h1>
        <p class="text-slate-400 mt-1">Share your event with the community</p>
    </div>

    <form action="{{ route('events.store') }}" method="POST" enctype="multipart/form-data" class="space-y-6">
        @csrf

        {{-- Basic Info --}}
        <div class="bg-white/5 backdrop-blur border border-white/10 rounded-2xl p-6 space-y-5">
            <h2 class="text-white font-semibold text-base border-b border-white/10 pb-3">Basic Information</h2>

            {{-- Title --}}
            <div>
                <label class="block text-sm font-medium text-slate-300 mb-1.5">Event Title <span class="text-rose-400">*</span></label>
                <input type="text" name="title" value="{{ old('title') }}"
                    class="w-full px-4 py-3 bg-white/5 border {{ $errors->has('title') ? 'border-rose-500' : 'border-white/10' }} rounded-xl text-white placeholder-slate-500 focus:outline-none focus:border-rose-500 focus:ring-1 focus:ring-rose-500/30 transition-all"
                    placeholder="e.g. Annual Tech Meetup 2025">
                @error('title')<p class="mt-1.5 text-sm text-rose-400">{{ $message }}</p>@enderror
            </div>

            {{-- Category + Free/Paid Row --}}
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-5">
                <div>
                    <label class="block text-sm font-medium text-slate-300 mb-1.5">Category <span class="text-rose-400">*</span></label>
                    <select name="category_id" class="w-full px-4 py-3 bg-white/5 border {{ $errors->has('category_id') ? 'border-rose-500' : 'border-white/10' }} rounded-xl text-white focus:outline-none focus:border-rose-500 transition-all cursor-pointer appearance-none">
                        <option value="" class="bg-[#1A1A2E]">Select category</option>
                        @foreach($categories as $cat)
                            <option value="{{ $cat->_id }}" {{ old('category_id') == $cat->_id ? 'selected' : '' }} class="bg-[#1A1A2E]">{{ $cat->name }}</option>
                        @endforeach
                    </select>
                    @error('category_id')<p class="mt-1.5 text-sm text-rose-400">{{ $message }}</p>@enderror
                </div>
                <div>
                    <label class="block text-sm font-medium text-slate-300 mb-1.5">Pricing</label>
                    <div class="flex gap-3">
                        <label class="flex-1 flex items-center gap-2 px-4 py-3 bg-white/5 border border-white/10 rounded-xl cursor-pointer hover:border-emerald-500/30 transition-all has-[:checked]:border-emerald-500 has-[:checked]:bg-emerald-500/10">
                            <input type="radio" name="is_free" value="1" {{ old('is_free', '1') == '1' ? 'checked' : '' }} class="text-emerald-500">
                            <span class="text-sm text-slate-300">Free</span>
                        </label>
                        <label class="flex-1 flex items-center gap-2 px-4 py-3 bg-white/5 border border-white/10 rounded-xl cursor-pointer hover:border-rose-500/30 transition-all has-[:checked]:border-rose-500 has-[:checked]:bg-rose-500/10">
                            <input type="radio" name="is_free" value="0" {{ old('is_free') == '0' ? 'checked' : '' }} class="text-rose-500">
                            <span class="text-sm text-slate-300">Paid</span>
                        </label>
                    </div>
                </div>
            </div>

            {{-- Price (shown if paid) --}}
            <div id="price-field" class="{{ old('is_free', '1') == '0' ? '' : 'hidden' }}">
                <label class="block text-sm font-medium text-slate-300 mb-1.5">Ticket Price (USD) <span class="text-rose-400">*</span></label>
                <div class="relative">
                    <span class="absolute left-4 top-1/2 -translate-y-1/2 text-slate-400 font-medium">$</span>
                    <input type="number" name="price" value="{{ old('price') }}" min="0" step="0.01"
                        class="w-full pl-8 pr-4 py-3 bg-white/5 border {{ $errors->has('price') ? 'border-rose-500' : 'border-white/10' }} rounded-xl text-white placeholder-slate-500 focus:outline-none focus:border-rose-500 transition-all"
                        placeholder="9.99">
                </div>
                @error('price')<p class="mt-1.5 text-sm text-rose-400">{{ $message }}</p>@enderror
            </div>

            {{-- Description --}}
            <div>
                <label class="block text-sm font-medium text-slate-300 mb-1.5">Description <span class="text-rose-400">*</span></label>
                <textarea name="description" rows="5"
                    class="w-full px-4 py-3 bg-white/5 border {{ $errors->has('description') ? 'border-rose-500' : 'border-white/10' }} rounded-xl text-white placeholder-slate-500 focus:outline-none focus:border-rose-500 focus:ring-1 focus:ring-rose-500/30 resize-none transition-all"
                    placeholder="Describe your event — what to expect, who it's for, what to bring...">{{ old('description') }}</textarea>
                @error('description')<p class="mt-1.5 text-sm text-rose-400">{{ $message }}</p>@enderror
            </div>
        </div>

        {{-- Date & Time --}}
        <div class="bg-white/5 backdrop-blur border border-white/10 rounded-2xl p-6 space-y-5">
            <h2 class="text-white font-semibold text-base border-b border-white/10 pb-3">Date & Time</h2>
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-5">
                <div>
                    <label class="block text-sm font-medium text-slate-300 mb-1.5">Start Date & Time <span class="text-rose-400">*</span></label>
                    <input type="datetime-local" name="start_date" value="{{ old('start_date') }}"
                        class="w-full px-4 py-3 bg-white/5 border {{ $errors->has('start_date') ? 'border-rose-500' : 'border-white/10' }} rounded-xl text-white focus:outline-none focus:border-rose-500 transition-all [color-scheme:dark]">
                    @error('start_date')<p class="mt-1.5 text-sm text-rose-400">{{ $message }}</p>@enderror
                </div>
                <div>
                    <label class="block text-sm font-medium text-slate-300 mb-1.5">End Date & Time</label>
                    <input type="datetime-local" name="end_date" value="{{ old('end_date') }}"
                        class="w-full px-4 py-3 bg-white/5 border border-white/10 rounded-xl text-white focus:outline-none focus:border-rose-500 transition-all [color-scheme:dark]">
                    @error('end_date')<p class="mt-1.5 text-sm text-rose-400">{{ $message }}</p>@enderror
                </div>
            </div>
        </div>

        {{-- Location --}}
        <div class="bg-white/5 backdrop-blur border border-white/10 rounded-2xl p-6 space-y-5">
            <h2 class="text-white font-semibold text-base border-b border-white/10 pb-3">Location</h2>
            <div>
                <label class="block text-sm font-medium text-slate-300 mb-1.5">Venue Name <span class="text-rose-400">*</span></label>
                <input type="text" name="location" value="{{ old('location') }}"
                    class="w-full px-4 py-3 bg-white/5 border {{ $errors->has('location') ? 'border-rose-500' : 'border-white/10' }} rounded-xl text-white placeholder-slate-500 focus:outline-none focus:border-rose-500 transition-all"
                    placeholder="e.g. Central Park, Convention Center">
                @error('location')<p class="mt-1.5 text-sm text-rose-400">{{ $message }}</p>@enderror
            </div>
            <div>
                <label class="block text-sm font-medium text-slate-300 mb-1.5">Street Address <span class="text-rose-400">*</span></label>
                <input type="text" name="address" value="{{ old('address') }}"
                    class="w-full px-4 py-3 bg-white/5 border {{ $errors->has('address') ? 'border-rose-500' : 'border-white/10' }} rounded-xl text-white placeholder-slate-500 focus:outline-none focus:border-rose-500 transition-all"
                    placeholder="123 Main Street">
                @error('address')<p class="mt-1.5 text-sm text-rose-400">{{ $message }}</p>@enderror
            </div>
            <div class="grid grid-cols-2 sm:grid-cols-3 gap-4">
                <div>
                    <label class="block text-sm font-medium text-slate-300 mb-1.5">City <span class="text-rose-400">*</span></label>
                    <input type="text" name="city" value="{{ old('city') }}"
                        class="w-full px-4 py-3 bg-white/5 border {{ $errors->has('city') ? 'border-rose-500' : 'border-white/10' }} rounded-xl text-white placeholder-slate-500 focus:outline-none focus:border-rose-500 transition-all"
                        placeholder="New York">
                    @error('city')<p class="mt-1.5 text-sm text-rose-400">{{ $message }}</p>@enderror
                </div>
                <div>
                    <label class="block text-sm font-medium text-slate-300 mb-1.5">State</label>
                    <input type="text" name="state" value="{{ old('state') }}"
                        class="w-full px-4 py-3 bg-white/5 border border-white/10 rounded-xl text-white placeholder-slate-500 focus:outline-none focus:border-rose-500 transition-all"
                        placeholder="NY">
                </div>
                <div>
                    <label class="block text-sm font-medium text-slate-300 mb-1.5">Country <span class="text-rose-400">*</span></label>
                    <input type="text" name="country" value="{{ old('country', 'India') }}"
                        class="w-full px-4 py-3 bg-white/5 border {{ $errors->has('country') ? 'border-rose-500' : 'border-white/10' }} rounded-xl text-white placeholder-slate-500 focus:outline-none focus:border-rose-500 transition-all"
                        placeholder="India">
                    @error('country')<p class="mt-1.5 text-sm text-rose-400">{{ $message }}</p>@enderror
                </div>
            </div>
        </div>

        {{-- Extra Details --}}
        <div class="bg-white/5 backdrop-blur border border-white/10 rounded-2xl p-6 space-y-5">
            <h2 class="text-white font-semibold text-base border-b border-white/10 pb-3">Additional Details</h2>
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-5">
                <div>
                    <label class="block text-sm font-medium text-slate-300 mb-1.5">Max Attendees</label>
                    <input type="number" name="max_attendees" value="{{ old('max_attendees') }}" min="1"
                        class="w-full px-4 py-3 bg-white/5 border border-white/10 rounded-xl text-white placeholder-slate-500 focus:outline-none focus:border-rose-500 transition-all"
                        placeholder="Leave blank for unlimited">
                </div>
                <div>
                    <label class="block text-sm font-medium text-slate-300 mb-1.5">Tags <span class="text-slate-500 text-xs">(comma separated)</span></label>
                    <input type="text" name="tags" value="{{ old('tags') }}"
                        class="w-full px-4 py-3 bg-white/5 border border-white/10 rounded-xl text-white placeholder-slate-500 focus:outline-none focus:border-rose-500 transition-all"
                        placeholder="music, outdoor, networking">
                </div>
            </div>

            {{-- Image Upload --}}
            <div>
                <label class="block text-sm font-medium text-slate-300 mb-1.5">Event Cover Image</label>
                <div class="relative border-2 border-dashed border-white/10 hover:border-rose-500/40 rounded-xl p-8 text-center transition-all cursor-pointer"
                     onclick="document.getElementById('image').click()">
                    <svg class="w-10 h-10 text-slate-500 mx-auto mb-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                    <p class="text-slate-400 text-sm mb-1">Click to upload image</p>
                    <p class="text-slate-600 text-xs">JPG, PNG, WEBP up to 4MB</p>
                    <input type="file" id="image" name="image" accept="image/*" class="hidden" onchange="previewImage(this)">
                </div>
                <div id="image-preview" class="hidden mt-3 relative">
                    <img id="preview-img" src="" alt="Preview" class="w-full h-48 object-cover rounded-xl border border-white/10">
                    <button type="button" onclick="clearImage()" class="absolute top-2 right-2 w-7 h-7 bg-black/60 text-white rounded-full flex items-center justify-center hover:bg-rose-500/80 transition-colors cursor-pointer">
                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                    </button>
                </div>
                @error('image')<p class="mt-1.5 text-sm text-rose-400">{{ $message }}</p>@enderror
            </div>
        </div>

        {{-- Submit --}}
        <div class="flex gap-4">
            <a href="{{ route('events.index') }}" class="px-6 py-3.5 bg-white/5 border border-white/10 text-slate-300 hover:text-white font-semibold rounded-xl transition-all cursor-pointer">Cancel</a>
            <button type="submit" class="flex-1 py-3.5 bg-gradient-to-r from-rose-500 to-violet-600 text-white font-semibold rounded-xl hover:opacity-90 active:scale-[0.98] transition-all cursor-pointer">
                Publish Event
            </button>
        </div>
    </form>
</div>
@endsection

@push('scripts')
<script>
// Toggle price field
document.querySelectorAll('input[name="is_free"]').forEach(radio => {
    radio.addEventListener('change', () => {
        document.getElementById('price-field').classList.toggle('hidden', radio.value === '1');
    });
});

function previewImage(input) {
    if (input.files && input.files[0]) {
        const reader = new FileReader();
        reader.onload = e => {
            document.getElementById('preview-img').src = e.target.result;
            document.getElementById('image-preview').classList.remove('hidden');
        };
        reader.readAsDataURL(input.files[0]);
    }
}

function clearImage() {
    document.getElementById('image').value = '';
    document.getElementById('image-preview').classList.add('hidden');
}
</script>
@endpush
