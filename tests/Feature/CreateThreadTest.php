<?php

namespace Tests\Feature;

use App\Http\Livewire\CreateThread;
use App\Http\Livewire\ManageThread;
use App\Models\Activity;
use App\Models\Favorite;
use App\Models\Thread;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Livewire\Livewire;
use Tests\TestCase;

class CreateThreadTest extends TestCase
{
    use WithFaker, RefreshDatabase;

    /** @test  */
    public function guests_and_not_verified_users_cannot_see_thread_creation_modal_containing_livewire_component_on_main_page()
    {
        $this->get('forum')->assertDontSeeLivewire('create-thread');

        $user = create('User', ['email_verified_at' => null]);
        $this->actingAs($user);

        $this->get('forum')->assertDontSeeLivewire('create-thread');
    }

    /** @test  */
    public function verified_user_sees_thread_creation_modal_containing_livewire_component_on_main_page()
    {
        $this->signIn();

        $this->get('forum')->assertSeeLivewire('create-thread');
    }

    /** @test */
    public function a_guest_cannot_create_new_forum_thread()
    {
        Livewire::test(CreateThread::class)
            ->call('create')
            ->assertRedirect('login');
    }

    /** @test */
    public function new_users_must_first_confirm_their_email_address_before_creating_threads()
    {
        $user = create('User', ['email_verified_at' => null]);
        $this->actingAs($user);

        Livewire::test(CreateThread::class)
            ->call('create')
            ->assertRedirect('email/verify');
    }

    /** @test */
    public function an_authenticated_user_can_create_new_forum_thread()
    {
        $this->signIn();

        $channel = create('Channel');

        Livewire::test(CreateThread::class)
            ->set('title', 'foo')
            ->set('body', 'bar')
            ->set('channel_id', $channel->id)
            ->call('create')
            ->assertRedirect("forum/{$channel->slug}/foo" . '-' . time());

        $this->assertTrue(Thread::whereTitle('foo')->exists());
    }

    /** @test */
    public function an_unauthorized_user_cannot_delete_a_forum_thread()
    {
        $thread = create('Thread');

        Livewire::test(ManageThread::class, ['thread' => $thread])
            ->call('delete')
            ->assertRedirect('login');

        $this->signIn();

        Livewire::test(ManageThread::class, ['thread' => $thread])
            ->call('delete')
            ->assertForbidden();
    }

    /** @test */
    public function an_authorized_user_can_delete_a_forum_thread()
    {
        $this->signIn();

        $thread = create('Thread', ['user_id' => auth()->id()]);

        $reply = create('Reply', ['thread_id' => $thread->id]);

        $reply->favorite();

        Livewire::test(ManageThread::class, ['thread' => $thread])
            ->call('delete');

        $this->assertDatabaseMissing('threads', $thread->only('id'))
                ->assertDatabaseMissing('replies', $reply->only('id'));

        $this->assertEquals(0, Favorite::count());

        $this->assertEquals(0, Activity::count());
    }

    /** @test */
    public function an_unauthorized_user_cannot_update_a_forum_thread()
    {
        $thread = create('Thread');

        Livewire::test(ManageThread::class, ['thread' => $thread])
            ->call('update')
            ->assertRedirect('login');

        $this->signIn();

        Livewire::test(ManageThread::class, ['thread' => $thread])
            ->call('update')
            ->assertForbidden();
    }

    /** @test */
    public function an_authorized_user_can_update_a_forum_thread()
    {
        $this->signIn();

        $thread = create('Thread', ['user_id' => auth()->id()]);

        $updatedTitle = 'Changed Title.';
        $updatedBody = 'Changed Body.';

        Livewire::test(ManageThread::class, ['thread' => $thread])
            ->set('title', $updatedTitle)
            ->set('body', $updatedBody)
            ->call('update');

        $this->assertDatabaseHas('threads', ['id' => $thread->id, 'title' => $updatedTitle, 'body' => $updatedBody]);
    }

    /** @test */
    public function users_may_only_create_a_thread_a_maximum_of_once_per_minute()
    {
        $this->withoutExceptionHandling();

        $this->signIn();
    
        $channel = create('Channel');

        Livewire::test(CreateThread::class)
            ->set('title', 'foo')
            ->set('body', 'bar')
            ->set('channel_id', $channel->id)
            ->call('create')
            ->assertRedirect("forum/{$channel->slug}/foo" . '-' . time());

        $this->assertTrue(Thread::whereTitle('foo')->exists());

        Livewire::test(CreateThread::class)
            ->set('title', 'foofoo')
            ->set('body', 'barbar')
            ->set('channel_id', $channel->id)
            ->call('create');

        $this->assertFalse(Thread::whereTitle('foofoo')->exists());
    }

    /** @test */
    public function a_thread_requires_a_title()
    {
        $this->signIn();
    
        $channel = create('Channel');

        Livewire::test(CreateThread::class)
            ->set('title', '')
            ->set('body', 'bar')
            ->set('channel_id', $channel->id)
            ->call('create')
            ->assertHasErrors('title');
    }

    /** @test */
    public function a_thread_requires_a_body()
    {
        $this->signIn();
    
        $channel = create('Channel');

        Livewire::test(CreateThread::class)
            ->set('title', 'foo')
            ->set('body', '')
            ->set('channel_id', $channel->id)
            ->call('create')
            ->assertHasErrors('body');
    }

    /** @test */
    public function a_thread_requires_a_valid_channel()
    {
        create('Channel', [], 2);

        $this->signIn();
    
        Livewire::test(CreateThread::class)
            ->set('title', '')
            ->set('body', 'bar')
            ->set('channel_id', null)
            ->call('create')
            ->assertHasErrors('channel_id');

        Livewire::test(CreateThread::class)
            ->set('title', '')
            ->set('body', 'bar')
            ->set('channel_id', 99)
            ->call('create')
            ->assertHasErrors('channel_id');
    }
}
