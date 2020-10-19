<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class UserPageTest extends TestCase
{
    use WithFaker, RefreshDatabase;
    
    /** @test */
    public function only_a_signed_in_user_can_view_users_page()
    {
        $user = create('User');

        $this->get($user->path())
            ->assertStatus(302); // Redirect to login page

        $userOther = create('User');
        $this->signIn($userOther);

        $this->get($user->path())
            ->assertStatus(200);
    }

    /** @test */
    public function a_user_has_a_page()
    {
        $user = create('User');
        $this->signIn($user);

        $this->get($user->path())
                ->assertSee($user->name);
    }

    /** @test */
    public function users_page_shows_all_threads_associated_with_a_user()
    {
        $this->signIn();

        $thread = create('Thread', ['user_id' => auth()->id()]);

        $this->get(auth()->user()->path())
               ->assertSee($thread->title);
    }
}
