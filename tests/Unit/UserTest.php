<?php

namespace Tests\Unit;

use Illuminate\Auth\Events\Registered;
use Illuminate\Auth\Notifications\VerifyEmail;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Facades\Event;
use Illuminate\Support\Facades\Notification;
use Tests\TestCase;

class UserTest extends TestCase
{
    use WithFaker, RefreshDatabase;
    
    /** @test */
    public function a_user_can_make_a_string_path()
    {
        $user = create('User');

        $this->assertEquals($user->path(), '/user/' . $user->username);
    }

    /** @test */
    public function an_authenticated_user_can_check_if_he_read_all_replies_to_a_thread()
    {
        $this->signIn();

        $thread = create('Thread');

        tap(auth()->user(), function ($user) use ($thread) {
            $this->assertTrue($user->hasSeenUpdatesFor($thread));

            $user->read($thread);

            $this->assertFalse($user->hasSeenUpdatesFor($thread));
        });
    }

    /** @test */
    public function a_user_can_fetch_their_most_recent_reply()
    {
        $user = create('User');

        $reply = create('Reply', ['user_id' => $user->id]);

        $this->assertEquals($reply->id, $user->lastCreated('reply')->id);
    }
    
    /** @test */
    public function a_user_can_fetch_their_most_recent_thread()
    {
        $user = create('User');

        $thread = create('Thread', ['user_id' => $user->id]);

        $this->assertEquals($thread->id, $user->lastCreated('thread')->id);
    }

    /** @test */
    public function an_event_is_dispatched_upon_registration()
    {
        Event::fake();
        // Notification::fake();

        $this->post(route('register'), [
            'name' => 'John',
            'username' => 'johny',
            'email' => 'johndoe@test.com',
            'password' => 'passwordtest',
            'password_confirmation' => 'passwordtest'
        ]);

        // $user = \App\Models\User::first();

        Event::assertDispatched(Registered::class);
        // Notification::assertSentTo($user, VerifyEmail::class);

        //notifications on registering not working, working on resending an email
    }
}
