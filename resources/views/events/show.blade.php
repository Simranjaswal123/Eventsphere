@extends('layouts.app')
@section('title', $event->title)
@section('meta_description', Str::limit($event->description, 160))

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-10">
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">

        {{-- LEFT: Main Content --}}
        <div class="lg:col-span-2 space-y-6">

            {{-- Hero Image --}}
            <div class="relative rounded-2xl overflow-hidden bg-white/5 border border-white/10 h-72 sm:h-96">
                <img src="{{ $event->image_url }}" alt="{{ $event->title }}"
                     class="w-full h-full object-cover"
                     onerror="this.src='https://picsum.photos/seed/{{ $event->_id }}/800/400'">
                <div class="absolute inset-0 bg-gradient-to-t from-black/70 via-transparent to-transparent"></div>
                <div class="absolute bottom-5 left-5 flex gap-2">
                    @if($event->is_free)
                        <span class="px-3 py-1 bg-emerald-500/90 backdrop-blur text-white text-sm font-bold rounded-full">Free</span>
                    @else
                        <span class="px-3 py-1 bg-white/20 backdrop-blur text-white text-sm font-semibold rounded-full">${{ number_format($event->price, 2) }}</span>
                    @endif
                    @if($event->category)
                        <span class="px-3 py-1 bg-black/40 backdrop-blur text-slate-200 text-sm rounded-full border border-white/10">{{ $event->category->name }}</span>
                    @endif
                </div>
            </div>

            {{-- Title & Meta --}}
            <div class="bg-white/5 backdrop-blur border border-white/10 rounded-2xl p-6">
                <h1 class="font-righteous text-3xl text-white mb-4">{{ $event->title }}</h1>

                <div class="grid grid-cols-1 sm:grid-cols-3 gap-4 mb-6">
                    <div class="flex items-start gap-3">
                        <div class="w-9 h-9 bg-rose-500/10 rounded-lg flex items-center justify-center shrink-0">
                            <svg class="w-4 h-4 text-rose-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                        </div>
                        <div>
                            <p class="text-xs text-slate-500 mb-0.5">Date & Time</p>
                            <p class="text-sm text-white font-medium">{{ $event->start_date?->format('D, M j, Y') }}</p>
                            <p class="text-xs text-slate-400">{{ $event->start_date?->format('g:i A') }}{{ $event->end_date ? ' – '.$event->end_date->format('g:i A') : '' }}</p>
                        </div>
                    </div>
                    <div class="flex items-start gap-3">
                        <div class="w-9 h-9 bg-violet-500/10 rounded-lg flex items-center justify-center shrink-0">
                            <svg class="w-4 h-4 text-violet-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                        </div>
                        <div>
                            <p class="text-xs text-slate-500 mb-0.5">Location</p>
                            <p class="text-sm text-white font-medium">{{ $event->location }}</p>
                            <p class="text-xs text-slate-400">{{ $event->city }}, {{ $event->country }}</p>
                        </div>
                    </div>
                    <div class="flex items-start gap-3">
                        <div class="w-9 h-9 bg-blue-500/10 rounded-lg flex items-center justify-center shrink-0">
                            <svg class="w-4 h-4 text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                        </div>
                        <div>
                            <p class="text-xs text-slate-500 mb-0.5">Attendees</p>
                            <p class="text-sm text-white font-medium">{{ $rsvpCount }} going</p>
                            @if($event->max_attendees)
                                <p class="text-xs text-slate-400">{{ $event->spotsLeft() }} spots left</p>
                            @else
                                <p class="text-xs text-slate-400">Unlimited</p>
                            @endif
                        </div>
                    </div>
                </div>

                {{-- Tags --}}
                @if($event->tags && count($event->tags))
                    <div class="flex flex-wrap gap-2 mb-4">
                        @foreach($event->tags as $tag)
                            <a href="{{ route('search') }}?q={{ $tag }}" class="px-3 py-1 bg-white/5 hover:bg-white/10 border border-white/10 text-slate-400 hover:text-white text-xs rounded-full transition-colors cursor-pointer">#{{ $tag }}</a>
                        @endforeach
                    </div>
                @endif

                {{-- Description --}}
                <div class="prose prose-invert prose-sm max-w-none text-slate-300 leading-relaxed">
                    {!! nl2br(e($event->description)) !!}
                </div>
            </div>

            {{-- Comments Section --}}
            <div class="bg-white/5 backdrop-blur border border-white/10 rounded-2xl p-6">
                <h2 class="font-semibold text-white text-lg mb-6 flex items-center gap-2">
                    <svg class="w-5 h-5 text-rose-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"/></svg>
                    Comments ({{ $comments->count() }})
                </h2>

                {{-- Add Comment --}}
                @auth
                    <form action="{{ route('comments.store', $event->slug) }}" method="POST" class="mb-8">
                        @csrf
                        <div class="flex gap-3">
                            <img src="{{ Auth::user()->avatar_url }}" alt="{{ Auth::user()->name }}" class="w-9 h-9 rounded-full object-cover shrink-0">
                            <div class="flex-1">
                                <textarea name="body" rows="3" placeholder="Share your thoughts about this event..."
                                    class="w-full px-4 py-3 bg-white/5 border border-white/10 rounded-xl text-white text-sm placeholder-slate-500 focus:outline-none focus:border-rose-500 focus:ring-1 focus:ring-rose-500/30 resize-none transition-all">{{ old('body') }}</textarea>
                                @error('body')<p class="text-xs text-rose-400 mt-1">{{ $message }}</p>@enderror
                                <div class="flex justify-end mt-2">
                                    <button type="submit" class="px-5 py-2 bg-gradient-to-r from-rose-500 to-violet-600 text-white text-sm font-semibold rounded-lg hover:opacity-90 active:scale-[0.98] transition-all cursor-pointer">Post Comment</button>
                                </div>
                            </div>
                        </div>
                    </form>
                @else
                    <div class="p-4 bg-white/3 border border-white/10 rounded-xl text-center mb-6">
                        <p class="text-slate-400 text-sm"><a href="{{ route('login') }}" class="text-rose-400 hover:underline cursor-pointer">Sign in</a> to leave a comment.</p>
                    </div>
                @endauth

                {{-- Comments List --}}
                <div class="space-y-5">
                    @forelse($comments as $comment)
                        <div class="flex gap-3" id="comment-{{ $comment->_id }}">
                            <img src="{{ $comment->user?->avatar_url }}" alt="{{ $comment->user?->name }}" class="w-8 h-8 rounded-full object-cover shrink-0 mt-0.5">
                            <div class="flex-1">
                                <div class="bg-white/5 border border-white/10 rounded-xl px-4 py-3">
                                    <div class="flex items-center justify-between mb-1.5">
                                        <span class="text-sm font-semibold text-white">{{ $comment->user?->name }}</span>
                                        <span class="text-xs text-slate-500">{{ $comment->created_at?->diffForHumans() }}</span>
                                    </div>
                                    <p class="text-sm text-slate-300 leading-relaxed">{{ $comment->body }}</p>
                                </div>
                                @auth
                                    @if((string) $comment->user_id === (string) Auth::id() || Auth::user()->isAdmin())
                                        <form action="{{ route('comments.destroy', $comment->_id) }}" method="POST" class="inline">
                                            @csrf @method('DELETE')
                                            <button type="submit" class="text-xs text-slate-500 hover:text-rose-400 mt-1 ml-1 transition-colors cursor-pointer" onclick="return confirm('Delete comment?')">Delete</button>
                                        </form>
                                    @endif
                                @endauth

                                {{-- Replies --}}
                                @foreach($comment->replies as $reply)
                                    <div class="flex gap-3 mt-3 ml-4">
                                        <img src="{{ $reply->user?->avatar_url }}" alt="{{ $reply->user?->name }}" class="w-7 h-7 rounded-full object-cover shrink-0 mt-0.5">
                                        <div class="bg-white/3 border border-white/8 rounded-xl px-3 py-2.5 flex-1">
                                            <div class="flex items-center justify-between mb-1">
                                                <span class="text-xs font-semibold text-white">{{ $reply->user?->name }}</span>
                                                <span class="text-xs text-slate-600">{{ $reply->created_at?->diffForHumans() }}</span>
                                            </div>
                                            <p class="text-xs text-slate-400">{{ $reply->body }}</p>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    @empty
                        <div class="text-center py-8 text-slate-500 text-sm">No comments yet. Be the first!</div>
                    @endforelse
                </div>
            </div>
        </div>

        {{-- RIGHT: Sidebar --}}
        <div class="space-y-5">

            {{-- RSVP Card --}}
            <div class="bg-white/5 backdrop-blur border border-white/10 rounded-2xl p-6 sticky top-20">
                <div class="text-center mb-5">
                    @if($event->is_free)
                        <p class="text-3xl font-bold text-emerald-400 mb-1">Free</p>
                    @else
                        <p class="text-3xl font-bold text-white mb-1">${{ number_format($event->price, 2) }}</p>
                    @endif
                    <p class="text-slate-400 text-sm">per person</p>
                </div>

                @auth
                    <form action="{{ route('rsvp.toggle', $event->slug) }}" method="POST" class="mb-3" id="rsvp-form">
                        @csrf
                        @if($event->isFull() && !$hasRsvped)
                            <button type="button" disabled class="w-full py-3.5 bg-white/5 border border-white/10 text-slate-500 font-semibold rounded-xl cursor-not-allowed text-sm">
                                Event Full
                            </button>
                        @else
                            <button type="submit"
                                class="w-full py-3.5 font-semibold rounded-xl transition-all duration-200 cursor-pointer text-sm {{ $hasRsvped ? 'bg-white/10 border border-white/20 text-white hover:bg-rose-500/10 hover:border-rose-500/30 hover:text-rose-400' : 'bg-gradient-to-r from-rose-500 to-violet-600 text-white hover:opacity-90 active:scale-[0.98]' }}">
                                {{ $hasRsvped ? 'Cancel RSVP' : 'RSVP — I\'m Going!' }}
                            </button>
                        @endif
                    </form>

                    {{-- Like Button --}}
                    <form action="{{ route('like.toggle', $event->slug) }}" method="POST">
                        @csrf
                        <button type="submit" class="w-full py-3 border border-white/10 hover:border-rose-500/30 text-sm font-medium rounded-xl transition-all duration-200 cursor-pointer flex items-center justify-center gap-2 {{ $hasLiked ? 'text-rose-400 bg-rose-500/10' : 'text-slate-400 hover:text-rose-400' }}">
                            <svg class="w-4 h-4" fill="{{ $hasLiked ? 'currentColor' : 'none' }}" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"/></svg>
                            {{ $hasLiked ? 'Unlike' : 'Like' }} ({{ $event->likes_count ?? 0 }})
                        </button>
                    </form>
                @else
                    <a href="{{ route('login') }}" class="block w-full py-3.5 bg-gradient-to-r from-rose-500 to-violet-600 text-white text-sm font-semibold rounded-xl text-center hover:opacity-90 transition-opacity cursor-pointer">
                        Sign in to RSVP
                    </a>
                @endauth

                {{-- Stats --}}
                <div class="grid grid-cols-3 gap-3 mt-5 pt-5 border-t border-white/10">
                    <div class="text-center">
                        <p class="text-lg font-bold text-white">{{ $rsvpCount }}</p>
                        <p class="text-xs text-slate-500 mt-0.5">Going</p>
                    </div>
                    <div class="text-center">
                        <p class="text-lg font-bold text-white">{{ $event->likes_count ?? 0 }}</p>
                        <p class="text-xs text-slate-500 mt-0.5">Likes</p>
                    </div>
                    <div class="text-center">
                        <p class="text-lg font-bold text-white">{{ $event->views_count ?? 0 }}</p>
                        <p class="text-xs text-slate-500 mt-0.5">Views</p>
                    </div>
                </div>
            </div>

            {{-- Organizer --}}
            @if($event->user)
            <div class="bg-white/5 backdrop-blur border border-white/10 rounded-2xl p-5">
                <h3 class="text-sm font-semibold text-slate-400 uppercase tracking-wider mb-4">Organizer</h3>
                <div class="flex items-center gap-3">
                    <img src="{{ $event->user->avatar_url }}" alt="{{ $event->user->name }}" class="w-11 h-11 rounded-full object-cover border-2 border-rose-500/30">
                    <div>
                        <p class="text-sm font-semibold text-white">{{ $event->user->name }}</p>
                        <p class="text-xs text-slate-400">@{{ $event->user->username ?? 'organizer' }}</p>
                    </div>
                </div>
                @if($event->user->bio)
                    <p class="text-xs text-slate-400 mt-3 leading-relaxed">{{ Str::limit($event->user->bio, 100) }}</p>
                @endif
                <a href="{{ route('profile.show', $event->user->username ?? $event->user->_id) }}" class="block mt-3 text-center py-2 border border-white/10 hover:border-white/20 text-sm text-slate-300 hover:text-white rounded-lg transition-all cursor-pointer">
                    View Profile
                </a>
            </div>
            @endif

            {{-- Edit / Delete (owner only) --}}
            @auth
                @if((string) $event->user_id === (string) Auth::id() || Auth::user()->isAdmin())
                <div class="bg-white/5 backdrop-blur border border-white/10 rounded-2xl p-5 space-y-2">
                    <h3 class="text-sm font-semibold text-slate-400 mb-3">Manage Event</h3>
                    <a href="{{ route('events.edit', $event->slug) }}" class="flex items-center gap-2 w-full py-2.5 px-4 bg-blue-500/10 border border-blue-500/20 text-blue-400 hover:bg-blue-500/20 text-sm font-medium rounded-lg transition-all cursor-pointer">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>
                        Edit Event
                    </a>
                    <form action="{{ route('events.destroy', $event->slug) }}" method="POST" onsubmit="return confirm('Delete this event? This cannot be undone.')">
                        @csrf @method('DELETE')
                        <button type="submit" class="flex items-center gap-2 w-full py-2.5 px-4 bg-rose-500/10 border border-rose-500/20 text-rose-400 hover:bg-rose-500/20 text-sm font-medium rounded-lg transition-all cursor-pointer">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                            Delete Event
                        </button>
                    </form>
                </div>
                @endif
            @endauth

            {{-- Related Events --}}
            @if($relatedEvents->isNotEmpty())
            <div class="bg-white/5 backdrop-blur border border-white/10 rounded-2xl p-5">
                <h3 class="text-sm font-semibold text-slate-400 uppercase tracking-wider mb-4">Related Events</h3>
                <div class="space-y-3">
                    @foreach($relatedEvents as $related)
                        @include('components.event-card-sm', ['event' => $related])
                    @endforeach
                </div>
            </div>
            @endif
        </div>
    </div>
</div>
@endsection
