<?php

namespace Tests\Unit;

use App\Models\Reply;
use App\Models\User;
use App\Notifications\ReplyWasCreated;
use Carbon\Carbon;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Facades\Notification;
use Tests\TestCase;

class ReplyTest extends TestCase
{
    use WithFaker, RefreshDatabase;

    /** @test */
    public function has_an_owner()
    {
        $reply = create('Reply');

        $this->assertInstanceOf(User::class, $reply->owner);
    }

    /** @test */
    public function a_reply_has_favorites()
    {
        $reply = create('Reply');

        $this->assertInstanceOf('Illuminate\Database\Eloquent\Collection', $reply->favorites);
    }

    /** @test */
    public function a_reply_can_be_favorited()
    {
        $this->signIn();

        $reply = create('Reply');
        
        $reply->favorite();

        $this->assertCount(1, $reply->favorites);
    }

    /** @test */
    public function a_reply_creation_notifies_a_user_that_subscribed_to_a_given_thread()
    {
        Notification::fake();

        $this->signIn();

        $thread = create('Thread');

        $thread->subscribe();

        create('Reply', ['thread_id' => $thread->id]);

        Notification::assertSentTo(auth()->user(), ReplyWasCreated::class);
    }

    /** @test */
    public function it_knows_if_it_was_just_published()
    {
        $reply = create('Reply');
    
        $this->assertTrue($reply->wasJustCreated());
    
        $reply->created_at = Carbon::now()->subMonth();

        $this->assertFalse($reply->wasJustCreated());
    }

    /** @test */
    public function it_can_detect_all_mentioned_users_in_the_body()
    {
        $reply = new Reply([
            'body' => '@Jane-Doe wants to talk to @JohnDoe.'
        ]);

        $this->assertEquals(['Jane-Doe', 'JohnDoe'], $reply->mentionedUsers());
    }

    /** @test */
    public function a_reply_may_be_best_in_a_given_thread()
    {
        $reply = create('Reply');

        $this->assertFalse($reply->isBest());

        $reply->thread->update([
            'best_reply_id' => $reply->id
        ]);

        $this->assertTrue($reply->fresh()->isBest());
    }
}
