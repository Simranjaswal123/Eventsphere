<?php

namespace App\Models;

use Illuminate\Auth\Authenticatable;
use Illuminate\Contracts\Auth\Authenticatable as AuthenticatableContract;
use Illuminate\Notifications\Notifiable;
use MongoDB\Laravel\Eloquent\Model;

class User extends Model implements AuthenticatableContract
{
    use Authenticatable, Notifiable;

    protected $connection = 'mongodb';
    protected $collection = 'users';

    protected $fillable = [
        'name', 'email', 'password', 'username', 'bio', 'avatar',
        'location', 'website', 'role', 'is_active',
        'email_verified_at', 'remember_token',
    ];

    protected $hidden = ['password', 'remember_token'];

    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
            'is_active' => 'boolean',
        ];
    }

    public function events()
    {
        return $this->hasMany(Event::class, 'user_id');
    }

    public function rsvps()
    {
        return $this->hasMany(Rsvp::class, 'user_id');
    }

    public function comments()
    {
        return $this->hasMany(Comment::class, 'user_id');
    }

    public function likes()
    {
        return $this->hasMany(Like::class, 'user_id');
    }

    public function isAdmin(): bool
    {
        return $this->role === 'admin';
    }

    public function getAvatarUrlAttribute(): string
    {
        if ($this->avatar) {
            return asset('storage/avatars/' . $this->avatar);
        }
        return 'https://ui-avatars.com/api/?name=' . urlencode($this->name) . '&background=E11D48&color=fff&bold=true';
    }

    public function hasLiked(string $eventId): bool
    {
        return Like::where('user_id', (string) $this->_id)
            ->where('event_id', $eventId)
            ->exists();
    }

    public function hasRsvped(string $eventId): bool
    {
        return Rsvp::where('user_id', (string) $this->_id)
            ->where('event_id', $eventId)
            ->exists();
    }
}
