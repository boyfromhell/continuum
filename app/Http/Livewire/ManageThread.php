<?php

namespace App\Http\Livewire;

use App\Models\Thread;
use App\Models\Trending;
use App\Rules\Spamfree;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Livewire\Component;

class ManageThread extends Component
{
    use AuthorizesRequests;

    public $thread;

    public $body;

    public $title;

    public $bodyCache;

    public $titleCache;

    public $confirmingThreadDeletion = false;

    protected $listeners = ['lock' => '$refresh', 'unlock' => '$refresh'];

    public function mount(Thread $thread)
    {
        $this->thread = $thread;

        $this->body = $thread->body;

        $this->title = $thread->title;

        $this->bodyCache = $thread->body;

        $this->titleCache = $thread->title;
    }

    public function delete()
    {
        if ($this->thread->locked) {
            return $this->dispatchBrowserEvent('flash', ['message' => 'thread is locked', 'color' => 'red']);
        } elseif (auth()->guest()) {
            return redirect('login');
        }
        
        $this->authorize('delete', $this->thread);

        (new Trending)->destroy($this->thread);

        $this->thread->delete();
        
        return redirect()->route('forum');
    }

    public function update()
    {
        if ($this->thread->locked) {
            return $this->dispatchBrowserEvent('flash', ['message' => 'thread is locked', 'color' => 'red']);
        } elseif (auth()->guest()) {
            return redirect('login');
        }

        $this->authorize('update', $this->thread);
        
        $this->validate([
            'body' => ['required', new Spamfree],
            'title' => ['required', new Spamfree],
        ]);

        $this->thread->update([
            'body' => $this->body,
            'title' => $this->title,
        ]);

        $this->dispatchBrowserEvent('flash', ['message' => 'updated a thread', 'color' => 'green']);

        $this->bodyCache = $this->body;

        $this->titleCache = $this->title;
    }

    public function return()
    {
        $this->body = $this->bodyCache;

        $this->title = $this->titleCache;
    }

    public function render()
    {
        return view('livewire.manage-thread');
    }
}
