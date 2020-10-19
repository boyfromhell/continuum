<?php

namespace Tests\Feature;

use App\Models\Trending;
use Carbon\Carbon;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Facades\Redis;
use Tests\TestCase;

class TrendingThreadsTest extends TestCase
{
    use RefreshDatabase, WithFaker;

    protected function setUp(): void
    {
        parent::setUp();

        $this->trending = new Trending;

        $this->trending->reset();

        $this->trending->resetWithoutDate();
    }

    /** @test */
    public function it_increments_a_threads_score_each_time_it_is_visited()
    {
        $this->assertCount(0, $this->trending->get());

        $thread = create('Thread');

        $this->get($thread->path());

        $trending = $this->trending->get();

        $this->assertCount(1, $trending);

        $this->assertEquals($thread->title, $trending[0]->title);
    }

    /** @test */
    public function it_can_remove_trending_thread_on_thread_deletion()
    {
        $this->signIn();

        $thread = create('Thread', ['user_id' => auth()->id()]);
        
        $this->get($thread->path());

        $trending = $this->trending->get();

        $this->assertCount(1, $trending);

        $this->assertEquals($thread->title, $trending[0]->title);

        $thread->delete();

        $trendingEmpty = $this->trending->get();

        $this->assertCount(0, $trendingEmpty);
    }

    /** @test */
    public function it_shows_trending_threads_only_from_the_past_day()
    {
        $this->assertCount(0, $this->trending->get());

        $thread = create('Thread');

        Redis::zincrby('tests_trending_threads', 1, json_encode([
            'title' => $thread->title,
            'path' => $thread->path(),
            'date' => Carbon::now()->subWeek(),
        ]));

        $trending = $this->trending->get();

        $this->assertCount(0, $trending);
    }
}
