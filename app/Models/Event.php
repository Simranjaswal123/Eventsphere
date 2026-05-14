<?php

namespace App\Models;

use MongoDB\Laravel\Eloquent\Model;

class Event extends Model
{
    protected $connection = 'mongodb';
    protected $collection = 'events';

    protected $fillable = [
        'title', 'slug', 'description', 'category_id', 'user_id',
        'image', 'location', 'address', 'city', 'state', 'country',
        'latitude', 'longitude', 'start_date', 'end_date',
        'is_free', 'price', 'max_attendees', 'tags',
        'status', 'is_featured', 'views_count', 'likes_count', 'rsvp_count',
    ];

    protected function casts(): array
    {
        return [
            'start_date' => 'datetime',
            'end_date' => 'datetime',
            'is_free' => 'boolean',
            'is_featured' => 'boolean',
            'price' => 'float',
            'tags' => 'array',
            'views_count' => 'integer',
            'likes_count' => 'integer',
            'rsvp_count' => 'integer',
        ];
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function category()
    {
        return $this->belongsTo(Category::class, 'category_id');
    }

    public function comments()
    {
        return $this->hasMany(Comment::class, 'event_id');
    }

    public function likes()
    {
        return $this->hasMany(Like::class, 'event_id');
    }

    public function rsvps()
    {
        return $this->hasMany(Rsvp::class, 'event_id');
    }

    public function getImageUrlAttribute(): string
    {
        if ($this->image) {
            return asset('storage/events/' . $this->image);
        }
        return asset('images/default-event.jpg');
    }

    public function isUpcoming(): bool
    {
        return $this->start_date && $this->start_date->isFuture();
    }

    public function isPast(): bool
    {
        return $this->start_date && $this->start_date->isPast();
    }

    public function spotsLeft(): ?int
    {
        if (!$this->max_attendees) return null;
        return max(0, $this->max_attendees - ($this->rsvp_count ?? 0));
    }

    public function isFull(): bool
    {
        if (!$this->max_attendees) return false;
        return ($this->rsvp_count ?? 0) >= $this->max_attendees;
    }

    public function scopePublished($query)
    {
        return $query->where('status', 'published');
    }

    public function scopeFeatured($query)
    {
        return $query->where('is_featured', true);
    }

    public function scopeUpcoming($query)
    {
        return $query->where('start_date', '>=', now());
    }

    public function scopeByCity($query, string $city)
    {
        return $query->where('city', 'like', "%{$city}%");
    }
}
