@extends('layouts.app')
@section('title', 'Edit Event')

@section('content')
<div class="max-w-3xl mx-auto px-4 sm:px-6 lg:px-8 py-10">
    <div class="mb-8">
        <a href="{{ route('events.show', $event->slug) }}" class="flex items-center gap-2 text-slate-400 hover:text-white text-sm transition-colors cursor-pointer mb-4">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/></svg>
            Back to Event
        </a>
        <h1 class="font-righteous text-3xl text-white">Edit Event</h1>
        <p class="text-slate-400 mt-1">Update your event details</p>
    </div>

    <form action="{{ route('events.update', $event->slug) }}" method="POST" enctype="multipart/form-data" class="space-y-6">
        @csrf @method('PUT')

        <div class="bg-white/5 backdrop-blur border border-white/10 rounded-2xl p-6 space-y-5">
            <h2 class="text-white font-semibold text-base border-b border-white/10 pb-3">Basic Information</h2>
            <div>
                <label class="block text-sm font-medium text-slate-300 mb-1.5">Event Title <span class="text-rose-400">*</span></label>
                <input type="text" name="title" value="{{ old('title', $event->title) }}"
                    class="w-full px-4 py-3 bg-white/5 border {{ $errors->has('title') ? 'border-rose-500' : 'border-white/10' }} rounded-xl text-white placeholder-slate-500 focus:outline-none focus:border-rose-500 transition-all">
                @error('title')<p class="mt-1.5 text-sm text-rose-400">{{ $message }}</p>@enderror
            </div>
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-5">
                <div>
                    <label class="block text-sm font-medium text-slate-300 mb-1.5">Category <span class="text-rose-400">*</span></label>
                    <select name="category_id" class="w-full px-4 py-3 bg-white/5 border border-white/10 rounded-xl text-white focus:outline-none focus:border-rose-500 transition-all cursor-pointer appearance-none">
                        @foreach($categories as $cat)
                            <option value="{{ $cat->_id }}" {{ old('category_id', $event->category_id) == $cat->_id ? 'selected' : '' }} class="bg-[#1A1A2E]">{{ $cat->name }}</option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <label class="block text-sm font-medium text-slate-300 mb-1.5">Pricing</label>
                    <div class="flex gap-3">
                        <label class="flex-1 flex items-center gap-2 px-4 py-3 bg-white/5 border border-white/10 rounded-xl cursor-pointer hover:border-emerald-500/30 transition-all has-[:checked]:border-emerald-500 has-[:checked]:bg-emerald-500/10">
                            <input type="radio" name="is_free" value="1" {{ old('is_free', $event->is_free ? '1' : '0') == '1' ? 'checked' : '' }}>
                            <span class="text-sm text-slate-300">Free</span>
                        </label>
                        <label class="flex-1 flex items-center gap-2 px-4 py-3 bg-white/5 border border-white/10 rounded-xl cursor-pointer hover:border-rose-500/30 transition-all has-[:checked]:border-rose-500 has-[:checked]:bg-rose-500/10">
                            <input type="radio" name="is_free" value="0" {{ old('is_free', $event->is_free ? '1' : '0') == '0' ? 'checked' : '' }}>
                            <span class="text-sm text-slate-300">Paid</span>
                        </label>
                    </div>
                </div>
            </div>
            <div id="price-field" class="{{ old('is_free', $event->is_free ? '1' : '0') == '0' ? '' : 'hidden' }}">
                <label class="block text-sm font-medium text-slate-300 mb-1.5">Ticket Price (USD)</label>
                <div class="relative">
                    <span class="absolute left-4 top-1/2 -translate-y-1/2 text-slate-400">$</span>
                    <input type="number" name="price" value="{{ old('price', $event->price) }}" min="0" step="0.01"
                        class="w-full pl-8 pr-4 py-3 bg-white/5 border border-white/10 rounded-xl text-white focus:outline-none focus:border-rose-500 transition-all">
                </div>
            </div>
            <div>
                <label class="block text-sm font-medium text-slate-300 mb-1.5">Description <span class="text-rose-400">*</span></label>
                <textarea name="description" rows="5"
                    class="w-full px-4 py-3 bg-white/5 border {{ $errors->has('description') ? 'border-rose-500' : 'border-white/10' }} rounded-xl text-white placeholder-slate-500 focus:outline-none focus:border-rose-500 resize-none transition-all">{{ old('description', $event->description) }}</textarea>
                @error('description')<p class="mt-1.5 text-sm text-rose-400">{{ $message }}</p>@enderror
            </div>
        </div>

        <div class="bg-white/5 backdrop-blur border border-white/10 rounded-2xl p-6 space-y-5">
            <h2 class="text-white font-semibold text-base border-b border-white/10 pb-3">Date & Time</h2>
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-5">
                <div>
                    <label class="block text-sm font-medium text-slate-300 mb-1.5">Start Date & Time <span class="text-rose-400">*</span></label>
                    <input type="datetime-local" name="start_date"
                        value="{{ old('start_date', $event->start_date?->format('Y-m-d\TH:i')) }}"
                        class="w-full px-4 py-3 bg-white/5 border border-white/10 rounded-xl text-white focus:outline-none focus:border-rose-500 transition-all [color-scheme:dark]">
                </div>
                <div>
                    <label class="block text-sm font-medium text-slate-300 mb-1.5">End Date & Time</label>
                    <input type="datetime-local" name="end_date"
                        value="{{ old('end_date', $event->end_date?->format('Y-m-d\TH:i')) }}"
                        class="w-full px-4 py-3 bg-white/5 border border-white/10 rounded-xl text-white focus:outline-none focus:border-rose-500 transition-all [color-scheme:dark]">
                </div>
            </div>
        </div>

        <div class="bg-white/5 backdrop-blur border border-white/10 rounded-2xl p-6 space-y-5">
            <h2 class="text-white font-semibold text-base border-b border-white/10 pb-3">Location</h2>
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-5">
                <div>
                    <label class="block text-sm font-medium text-slate-300 mb-1.5">Venue Name</label>
                    <input type="text" name="location" value="{{ old('location', $event->location) }}"
                        class="w-full px-4 py-3 bg-white/5 border border-white/10 rounded-xl text-white placeholder-slate-500 focus:outline-none focus:border-rose-500 transition-all">
                </div>
                <div>
                    <label class="block text-sm font-medium text-slate-300 mb-1.5">Street Address</label>
                    <input type="text" name="address" value="{{ old('address', $event->address) }}"
                        class="w-full px-4 py-3 bg-white/5 border border-white/10 rounded-xl text-white placeholder-slate-500 focus:outline-none focus:border-rose-500 transition-all">
                </div>
                <div>
                    <label class="block text-sm font-medium text-slate-300 mb-1.5">City</label>
                    <input type="text" name="city" value="{{ old('city', $event->city) }}"
                        class="w-full px-4 py-3 bg-white/5 border border-white/10 rounded-xl text-white placeholder-slate-500 focus:outline-none focus:border-rose-500 transition-all">
                </div>
                <div>
                    <label class="block text-sm font-medium text-slate-300 mb-1.5">Country</label>
                    <input type="text" name="country" value="{{ old('country', $event->country) }}"
                        class="w-full px-4 py-3 bg-white/5 border border-white/10 rounded-xl text-white placeholder-slate-500 focus:outline-none focus:border-rose-500 transition-all">
                </div>
            </div>
        </div>

        <div class="bg-white/5 backdrop-blur border border-white/10 rounded-2xl p-6 space-y-5">
            <h2 class="text-white font-semibold text-base border-b border-white/10 pb-3">Media & Extra</h2>
            <div>
                <label class="block text-sm font-medium text-slate-300 mb-1.5">Tags (comma separated)</label>
                <input type="text" name="tags" value="{{ old('tags', is_array($event->tags) ? implode(', ', $event->tags) : $event->tags) }}"
                    class="w-full px-4 py-3 bg-white/5 border border-white/10 rounded-xl text-white placeholder-slate-500 focus:outline-none focus:border-rose-500 transition-all"
                    placeholder="music, outdoor, networking">
            </div>
            @if($event->image)
                <div>
                    <p class="text-sm text-slate-400 mb-2">Current image:</p>
                    <img src="{{ $event->image_url }}" alt="Current" class="h-32 object-cover rounded-xl border border-white/10">
                </div>
            @endif
            <div>
                <label class="block text-sm font-medium text-slate-300 mb-1.5">{{ $event->image ? 'Replace Image' : 'Event Cover Image' }}</label>
                <div class="border-2 border-dashed border-white/10 hover:border-rose-500/40 rounded-xl p-6 text-center transition-all cursor-pointer" onclick="document.getElementById('image').click()">
                    <p class="text-slate-400 text-sm">Click to upload new image</p>
                    <p class="text-slate-600 text-xs mt-1">JPG, PNG, WEBP up to 4MB</p>
                    <input type="file" id="image" name="image" accept="image/*" class="hidden" onchange="previewImage(this)">
                </div>
                <div id="image-preview" class="hidden mt-3">
                    <img id="preview-img" src="" alt="Preview" class="w-full h-40 object-cover rounded-xl border border-white/10">
                </div>
            </div>
        </div>

        <div class="flex gap-4">
            <a href="{{ route('events.show', $event->slug) }}" class="px-6 py-3.5 bg-white/5 border border-white/10 text-slate-300 hover:text-white font-semibold rounded-xl transition-all cursor-pointer">Cancel</a>
            <button type="submit" class="flex-1 py-3.5 bg-gradient-to-r from-rose-500 to-violet-600 text-white font-semibold rounded-xl hover:opacity-90 active:scale-[0.98] transition-all cursor-pointer">Save Changes</button>
        </div>
    </form>
</div>
@endsection

@push('scripts')
<script>
document.querySelectorAll('input[name="is_free"]').forEach(r => {
    r.addEventListener('change', () => document.getElementById('price-field').classList.toggle('hidden', r.value === '1'));
});
function previewImage(input) {
    if (input.files?.[0]) {
        const reader = new FileReader();
        reader.onload = e => { document.getElementById('preview-img').src = e.target.result; document.getElementById('image-preview').classList.remove('hidden'); };
        reader.readAsDataURL(input.files[0]);
    }
}
</script>
@endpush
