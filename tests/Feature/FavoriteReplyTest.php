<?php

namespace Tests\Feature;

use App\Http\Livewire\ManageReply;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Livewire\Livewire;
use Tests\TestCase;

class FavoriteReplyTest extends TestCase
{
    use WithFaker, RefreshDatabase;

    /** @test  */
    public function reply_favoriting_containing_livewire_component_on_thread_page()
    {
        $thread = create('Thread');

        create('Reply', ['thread_id' => $thread->id]);

        $this->get($thread->path())->assertSeeLivewire('manage-reply');
    }

    /** @test */
    public function a_guest_cannot_favorite_anything()
    {
        $reply = create('Reply');

        Livewire::test(ManageReply::class, ['reply' => $reply])
            ->call('favorite')
            ->assertRedirect('login');
    }

    /** @test */
    public function an_authenticated_user_can_favorite_any_reply()
    {
        $this->signIn();

        $reply = create('Reply');

        Livewire::test(ManageReply::class, ['reply' => $reply])
            ->call('favorite');

        $this->assertCount(1, $reply->fresh()->favorites);
    }

    /** @test */
    public function an_authenticated_user_can_unfavorite_a_reply()
    {
        $this->signIn();

        $reply = create('Reply');
    
        Livewire::test(ManageReply::class, ['reply' => $reply])
            ->call('favorite');

        $this->assertCount(1, $reply->fresh()->favorites);

        Livewire::test(ManageReply::class, ['reply' => $reply])
            ->call('favorite');

        $this->assertCount(0, $reply->fresh()->favorites);
    }
}
