<?php

namespace Tests\Feature;

use App\Http\Livewire\CreateReply;
use App\Http\Livewire\ThreadSidebar;
use App\Models\Reply;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Livewire\Livewire;
use Tests\TestCase;

class LockThread extends TestCase
{
    use RefreshDatabase, WithFaker;

    /** @test */
    public function non_administrator_cannot_lock_a_thread()
    {
        $this->signIn();

        $thread = create('Thread', ['user_id' => auth()->id()]);

        Livewire::test(ThreadSidebar::class, ['thread' => $thread])
            ->call('lock');

        $this->assertFalse(!! $thread->fresh()->locked);
    }

    /** @test */
    public function an_administrator_can_lock_a_thread()
    {
        $this->signIn(\App\Models\User::factory()->administrator()->create());

        $thread = create('Thread', ['user_id' => auth()->id()]);

        Livewire::test(ThreadSidebar::class, ['thread' => $thread])
            ->call('lock');

        $this->assertTrue(!! $thread->fresh()->locked);
    }

    /** @test */
    public function an_administrator_can_unlock_a_thread()
    {
        $this->signIn(\App\Models\User::factory()->administrator()->create());

        $thread = create('Thread', ['user_id' => auth()->id()]);

        Livewire::test(ThreadSidebar::class, ['thread' => $thread])
            ->call('lock');

        $this->assertTrue(!! $thread->fresh()->locked);

        Livewire::test(ThreadSidebar::class, ['thread' => $thread])
            ->call('lock');

        $this->assertFalse(!! $thread->fresh()->locked);
    }

    /** @test */
    public function locked_thread_cannot_receive_new_replies()
    {
        $this->signIn();

        $thread = create('Thread');

        $thread->update([
            'locked' => true,
        ]);

        Livewire::test(CreateReply::class, ['thread' => $thread])
            ->set('body', 'foobar')
            ->call('create');

        $this->assertFalse(Reply::whereBody('foobar')->exists());
    }
}
