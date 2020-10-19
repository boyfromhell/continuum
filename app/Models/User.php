<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Fortify\TwoFactorAuthenticatable;
use Laravel\Jetstream\HasProfilePhoto;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    use HasApiTokens;
    use HasFactory;
    use HasProfilePhoto;
    use Notifiable;
    use TwoFactorAuthenticatable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name',
        'username',
        'email',
        'password',
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password',
        'remember_token',
        'two_factor_recovery_codes',
        'two_factor_secret',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    /**
     * The accessors to append to the model's array form.
     *
     * @var array
     */
    protected $appends = [
        'profile_photo_url',
    ];

    public function getRouteKeyName()
    {
        return 'username';
    }

    public function path()
    {
        return '/user/' . $this->username;
    }

    public function threads()
    {
        return $this->hasMany(Thread::class)->latest();
    }

    public function activity()
    {
        return $this->hasMany(Activity::class);
    }

    public function read(Thread $thread)
    {
        cache()->forever(
            $this->visitedCacheKey($thread),
            Carbon::now()
        );
    }

    public function hasSeenUpdatesFor(Thread $thread)
    {
        return $thread->updated_at > cache($this->visitedCacheKey($thread));
    }

    public function visitedCacheKey(Thread $thread)
    {
        return sprintf('users.%s.visits.%s', $this->id, $thread->id);
    }

    public function lastCreated($model)
    {
        switch (strtolower($model)) {
            case "reply":
                return $this->lastReply;
                break;
            case "thread":
                return $this->lastThread;
                break;
            default:
                return;
        }
    }

    public function lastReply()
    {
        return $this->hasOne(Reply::class)->latest();
    }

    public function lastThread()
    {
        return $this->hasOne(Thread::class)->latest();
    }

    public function isAdmin()
    {
        return in_array($this->username, ['adrian'], true);
    }
}
