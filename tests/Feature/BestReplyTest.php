<?php

namespace Tests\Feature;

use App\Http\Livewire\ManageReply;
use App\Models\Reply;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Livewire\Livewire;
use Tests\TestCase;

class BestReplyTest extends TestCase
{
    use WithFaker, RefreshDatabase;

    /** @test */
    public function a_reply_may_not_be_marked_as_best_by_a_diffrent_user()
    {
        $this->signIn();

        $thread = create('Thread');

        create('Reply', ['thread_id' => $thread->id], 2);

        $reply = Reply::first();

        $this->assertFalse($reply->fresh()->isBest());

        Livewire::test(ManageReply::class, ['reply' => $reply])
            ->call('best')
            ->assertForbidden();

        $this->assertFalse($reply->fresh()->isBest());
    }

    /** @test */
    public function a_reply_may_be_marked_as_best_by_a_thread_creator()
    {
        $this->signIn();

        $thread = create('Thread', ['user_id' => auth()->id()]);

        create('Reply', ['thread_id' => $thread->id], 2);

        $reply = Reply::first();

        $this->assertFalse($reply->fresh()->isBest());

        Livewire::test(ManageReply::class, ['reply' => $reply])
            ->call('best');

        $this->assertTrue($reply->fresh()->isBest());
    }

    /** @test */
    public function best_reply_deletion_marks_thread_best_reply_as_null()
    {
        $this->signIn();

        $reply = create('Reply', ['user_id' => auth()->id()]);

        $reply->thread->update([
            'best_reply_id' => $reply->id
        ]);

        $this->assertEquals($reply->thread->best_reply_id, $reply->id);
       
        $reply->delete();

        $this->assertNull($reply->thread->best_reply_id);
    }
}
