<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class NotifyTest extends TestCase
{
    use WithFaker, RefreshDatabase;

    /** @test */
    public function reply_created_by_other_user_in_a_subscribed_thread_creates_a_notification()
    {
        $user = create('User');
        $this->signIn($user);

        $thread = create('Thread');

        $thread->subscribe();

        $this->assertCount(0, $user->notifications);

        create('Reply', ['thread_id' => $thread->id]);

        $this->assertCount(1, $user->fresh()->notifications);
    }

    /** @test */
    public function reply_created_by_current_user_in_a_subscribed_thread_doesnt_create_a_notification()
    {
        $user = create('User');
        $this->signIn($user);

        $thread = create('Thread');

        $thread->subscribe();

        $this->assertCount(0, $user->notifications);

        create('Reply', ['user_id' => $user->id, 'thread_id' => $thread->id]);

        $this->assertCount(0, $user->fresh()->notifications);
    }
}
