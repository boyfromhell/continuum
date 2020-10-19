<?php

namespace App\Traits;

use App\Models\Thread;
use App\Models\User;

trait ThreadQueries
{
    /**
     * Filter the query by a given search string.
     *
     * @return Builder
     */
    protected function search($source, $search)
    {
        return $source->where(function ($query) use ($search) {
            $query->where('body', 'like', '%'.$search.'%')
                  ->orWhere('title', 'like', '%'.$search.'%');
        });
    }

    /**
     * Filter the query by a given username.
     *
     * @param  string $username
     * @return Builder
     */
    protected function by($username)
    {
        $user = User::where('username', $username)->firstOrFail();

        return Thread::where('user_id', $user->id);
    }

    /**
     * Filter the query according to most popular threads.
     *
     * @return $this
     */
    protected function popular($blank = null)
    {
        return Thread::orderBy('replies_count', 'desc');
    }

    /**
     * Filter the query according to unanswered threads.
     *
     * @return $this
     */
    protected function unanswered($blank = null)
    {
        return Thread::doesntHave('replies');
    }

    /**
     * Filter the query according to latest threads.
     *
     * @return $this
     */
    protected function latest()
    {
        return Thread::latest();
    }
}
