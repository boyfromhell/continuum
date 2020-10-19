<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Support\Facades\Redis;

class Trending
{
    public function get()
    {
        $threads = array_map('json_decode', Redis::zrevrange($this->cacheKey(), 0, -1));

        foreach ($threads as $thread) {
            if ($thread->date > Carbon::now()->subDay()) {
                $this->pushWithoutDate($thread);
            } else {
                $this->destroyKnowingDate($thread);
            }
        }

        $trending = array_map('json_decode', Redis::zrevrange($this->cacheKeyWithoutDate(), 0, 4));

        $this->resetWithoutDate();

        return $trending;
    }

    protected function pushWithoutDate($thread)
    {
        Redis::zincrby($this->cacheKeyWithoutDate(), 1, json_encode([
            'title' => $thread->title,
            'path' => $thread->path,
        ]));
    }

    public function push($thread)
    {
        Redis::zincrby($this->cacheKey(), 1, json_encode([
            'title' => $thread->title,
            'path' => $thread->path(),
            'date' => Carbon::now(),
        ]));
    }

    public function destroyKnowingDate($thread)
    {
        Redis::zrem($this->cacheKey(), json_encode([
            'title' => $thread->title,
            'path' => $thread->path,
            'date' => $thread->date,
        ]));
    }

    public function destroy($deletedThread)
    {
        $threads = array_map('json_decode', Redis::zrevrange($this->cacheKey(), 0, -1));

        foreach ($threads as $thread) {
            if ($thread->path == $deletedThread->path()) {
                Redis::zrem($this->cacheKey(), json_encode([
                    'title' => $thread->title,
                    'path' => $thread->path,
                    'date' => $thread->date,
                ]));
            }
        }
    }

    public function resetWithoutDate()
    {
        Redis::del($this->cacheKeyWithoutDate());
    }

    public function reset()
    {
        Redis::del($this->cacheKey());
    }

    protected function cacheKey()
    {
        return app()->environment('testing') ? 'tests_trending_threads' : 'trending_threads';
    }

    protected function cacheKeyWithoutDate()
    {
        return app()->environment('testing') ? 'tests_trending_threads_without_date' : 'trending_threads_without_date';
    }
}
