<?php

namespace App\Models;

use App\Events\ThreadReceivedNewReply;
use App\Notifications\YouWereMentioned;
use App\Traits\Favorable;
use App\Traits\NotifySubscriber;
use App\Traits\RecordActivity;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Notification;
use Stevebauman\Purify\Facades\Purify;

class Reply extends Model
{
    use HasFactory;
    use Favorable;
    use RecordActivity;
    use NotifySubscriber;

    protected $guarded = [];

    protected $with = ['owner', 'favorites'];

    protected $withCount = ['favorites'];

    protected $appends = ['isFavorited', 'isBest'];

    protected static function boot()
    {
        parent::boot();

        static::created(function ($reply) {
            $reply->thread->touch();
            $reply->owner->read($reply->thread);
            event(new ThreadReceivedNewReply($reply));
        });

        static::updated(function ($reply) {
            event(new ThreadReceivedNewReply($reply));
        });

        static::deleted(function ($reply) {
            if ($reply->isBest()) {
                $reply->thread->update(['best_reply_id' => null]);
            }
        });
    }

    public function owner()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function thread()
    {
        return $this->belongsTo(Thread::class);
    }

    public function path()
    {
        return $this->thread->path();
    }

    public function wasJustCreated()
    {
        return $this->created_at > Carbon::now()->subSeconds(5);
    }

    public function mentionedUsers()
    {
        preg_match_all('/@([\w\-]+)/', $this->body, $matches);

        return $matches[1];
    }

    public function notifyMentionedUsers(): void
    {
        $users = User::whereIn('username', $this->mentionedUsers())->get();
        Notification::send($users, new YouWereMentioned($this));
    }

    public function displayMentionedUsers()
    {
        $users = User::whereIn('username', $this->mentionedUsers())->get();
        
        $body = $this->body;

        foreach ($users as $user) {
            $username = ($user->toArray())["username"];
            $body = str_ireplace(
                "@" . $username,
                "<a href=\"{$user->path()}\">@{$username}</a>",
                $body
            );
        }

        return $body;
    }

    public function getBodyAttribute($body)
    {
        return Purify::clean($body);
    }

    public function isBest()
    {
        return $this->id == $this->thread->best_reply_id;
    }

    public function getIsBestAttribute()
    {
        return $this->isBest();
    }
}
