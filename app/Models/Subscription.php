<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Subscription extends Model
{
    use HasFactory;

    public $appends = ['subscriber'];

    protected $guarded = [];

    public function subscribed()
    {
        return $this->morphTo();
    }

    public function subscriber()
    {
        return User::where('id', $this->user_id)->first();
    }

    public function getSubscriberAttribute()
    {
        return $this->subscriber();
    }
}
