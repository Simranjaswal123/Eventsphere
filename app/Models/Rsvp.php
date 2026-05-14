<?php

namespace App\Models;

use MongoDB\Laravel\Eloquent\Model;

class Rsvp extends Model
{
    protected $connection = 'mongodb';
    protected $collection = 'rsvps';

    protected $fillable = ['event_id', 'user_id', 'status', 'notes', 'attended'];

    protected function casts(): array
    {
        return ['attended' => 'boolean'];
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function event()
    {
        return $this->belongsTo(Event::class, 'event_id');
    }
}
