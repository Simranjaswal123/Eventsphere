@extends('layouts.app')
@section('title', 'About EventSphere')

@section('content')
<div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 py-16 text-center">
    <div class="inline-flex items-center gap-2 px-4 py-2 bg-rose-500/10 border border-rose-500/20 rounded-full text-rose-400 text-sm font-medium mb-6">Our Story</div>
    <h1 class="font-righteous text-4xl sm:text-5xl text-white mb-6">About <span class="bg-gradient-to-r from-rose-400 to-violet-400 bg-clip-text text-transparent">EventSphere</span></h1>
    <p class="text-slate-400 text-lg leading-relaxed max-w-2xl mx-auto mb-12">
        EventSphere is a community-driven platform built to help people discover and connect through local events.
        Whether it's a tech meetup, music festival, art exhibition or charity run — we make it easy to find
        and share events that matter to you.
    </p>
    <div class="grid grid-cols-1 sm:grid-cols-3 gap-6 mb-12">
        @foreach([
            ['icon' => 'M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z M15 11a3 3 0 11-6 0 3 3 0 016 0z', 'title' => 'Local First', 'desc' => 'We focus on bringing communities together through hyper-local, meaningful events.'],
            ['icon' => 'M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z', 'title' => 'Community Driven', 'desc' => 'Anyone can create and share events — from large concerts to small neighbourhood gatherings.'],
            ['icon' => 'M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z', 'title' => 'Always Free', 'desc' => 'Creating and browsing events on EventSphere is always completely free for everyone.'],
        ] as $item)
            <div class="p-6 bg-white/5 border border-white/10 rounded-2xl text-center">
                <div class="w-12 h-12 bg-gradient-to-br from-rose-500/20 to-violet-600/20 rounded-xl flex items-center justify-center mx-auto mb-4">
                    <svg class="w-6 h-6 text-rose-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="{{ $item['icon'] }}"/></svg>
                </div>
                <h3 class="text-white font-semibold mb-2">{{ $item['title'] }}</h3>
                <p class="text-slate-400 text-sm leading-relaxed">{{ $item['desc'] }}</p>
            </div>
        @endforeach
    </div>
    <div class="bg-white/5 border border-white/10 rounded-2xl p-8">
        <h2 class="font-righteous text-2xl text-white mb-3">Built with Modern Technology</h2>
        <p class="text-slate-400 text-sm mb-6">EventSphere is built on a rock-solid stack designed for performance and scale.</p>
        <div class="flex flex-wrap justify-center gap-3">
            @foreach(['Laravel 12', 'MongoDB', 'Tailwind CSS v4', 'Blade Templates', 'REST API', 'Vite'] as $tech)
                <span class="px-4 py-2 bg-white/5 border border-white/10 text-slate-300 text-sm rounded-full">{{ $tech }}</span>
            @endforeach
        </div>
    </div>
    <div class="mt-10">
        <a href="{{ route('events.index') }}" class="px-8 py-4 bg-gradient-to-r from-rose-500 to-violet-600 text-white font-semibold rounded-xl hover:opacity-90 transition-opacity cursor-pointer">Explore Events</a>
    </div>
</div>
@endsection
