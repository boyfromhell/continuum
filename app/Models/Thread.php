<?php

namespace App\Models;

use App\Filters\ThreadFilter;
use App\Traits\RecordActivity;
use App\Traits\Subscribable;
use Carbon\Carbon;
use Illuminate\Support\Str;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Stevebauman\Purify\Facades\Purify;

class Thread extends Model
{
    use HasFactory;
    use RecordActivity;
    use Subscribable;

    protected $guarded = [];

    protected $with = ['creator', 'channel'];

    protected $withCount = ['replies'];

    protected $appends = ['isSubscribed'];

    protected static function boot()
    {
        parent::boot();

        static::deleting(function ($thread) {
            $thread->replies->each->delete();
            (new \App\Models\Trending)->destroy($thread);
        });

        static::updated(function ($thread) {
            $thread->creator->read($thread);
        });
    }

    public function getRouteKeyName()
    {
        return 'slug';
    }

    public function setSlugAttribute($slug)
    {
        $this->attributes['slug'] = Str::slug($slug) . '-' . time();
    }

    public function path()
    {
        return '/forum/' . $this->channel->slug . '/' . $this->slug;
    }

    public function creator()
    {
        return $this->belongsTo(User::class, 'user_id');
    }
    
    public function replies()
    {
        return $this->hasMany(Reply::class);
    }

    public function channel()
    {
        return $this->belongsTo(Channel::class);
    }

    /**
     * Apply all relevant thread filters.
     *
     * @param  Builder       $query
     * @param  ThreadFilters $filters
     * @return Builder
     */
    public function scopeFilter($query, ThreadFilter $filters)
    {
        return $filters->apply($query);
    }

    public function wasJustCreated()
    {
        return $this->created_at > Carbon::now()->subSeconds(5);
    }

    public function getBodyAttribute($body)
    {
        return Purify::clean($body);
    }
}
