<?php

namespace App\Http\Livewire;

use App\Models\Reply;
use App\Rules\Spamfree;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Livewire\Component;

class ManageReply extends Component
{
    use AuthorizesRequests;

    public $reply;

    public $body;

    public $bodyCache;

    public $favoriteCount;

    public $favoriteState;

    protected $listeners = ['lock' => '$refresh', 'unlock' => '$refresh'];

    public function mount(Reply $reply)
    {
        $this->reply = $reply;

        $this->body = $reply->body;

        $this->bodyCache = $reply->body;

        $this->favoriteCount = $reply->favorites_count;

        $this->favoriteState = $reply->isFavorited;
    }

    public function update()
    {
        if ($this->reply->thread->locked) {
            return $this->dispatchBrowserEvent('flash', ['message' => 'thread is locked', 'color' => 'red']);
        } elseif (auth()->guest()) {
            return redirect('login');
        }
        
        $this->authorize('update', $this->reply);
        
        $this->validate([
            'body' => ['required', new Spamfree],
        ]);

        $this->reply->update([
            'body' => $this->body
        ]);

        $this->dispatchBrowserEvent('flash', ['message' => 'updated a reply', 'color' => 'green']);

        $this->bodyCache = $this->body;
    }

    public function delete()
    {
        if ($this->reply->thread->locked) {
            return $this->dispatchBrowserEvent('flash', ['message' => 'thread is locked', 'color' => 'red']);
        } elseif (auth()->guest()) {
            return redirect('login');
        }

        $this->authorize('delete', $this->reply);

        $this->reply->delete();

        $this->emit('refresh');

        $this->dispatchBrowserEvent('flash', ['message' => 'deleted a reply', 'color' => 'red']);
    }

    public function favorite()
    {
        if ($this->reply->thread->locked) {
            return $this->dispatchBrowserEvent('flash', ['message' => 'thread is locked', 'color' => 'red']);
        } elseif (auth()->guest()) {
            return redirect('login');
        }

        if ($this->reply->isFavorited) {
            $this->reply->unfavorite();
            
            $this->dispatchBrowserEvent('flash', ['message' => 'unliked a reply', 'color' => 'red']);

            $this->favoriteCount = $this->reply->favorites_count;
                        
            $this->favoriteState = false;

            $this->favoriteCount--;
        } else {
            $this->reply->favorite();
            
            $this->dispatchBrowserEvent('flash', ['message' => 'liked a reply', 'color' => 'green']);

            $this->favoriteCount = $this->reply->favorites_count;

            $this->favoriteState = true;

            $this->favoriteCount++;
        }
    }

    public function best()
    {
        if ($this->reply->thread->locked) {
            return $this->dispatchBrowserEvent('flash', ['message' => 'thread is locked', 'color' => 'red']);
        } elseif (auth()->guest()) {
            return redirect('login');
        }

        $this->authorize('update', $this->reply->thread);

        if ($this->reply->thread->best_reply_id == $this->reply->id) {
            $this->reply->thread->update([
                'best_reply_id' => null
            ]);

            $this->dispatchBrowserEvent('flash', ['message' => 'unmarked the best reply', 'color' => 'red']);
        } else {
            $this->reply->thread->update([
                'best_reply_id' => $this->reply->id
            ]);

            $this->dispatchBrowserEvent('flash', ['message' => 'marked the best reply', 'color' => 'green']);
        }
    }

    public function return()
    {
        $this->body = $this->bodyCache;
    }

    public function render()
    {
        return view('livewire.manage-reply');
    }
}
