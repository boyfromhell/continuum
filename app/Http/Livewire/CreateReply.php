<?php

namespace App\Http\Livewire;

use App\Models\Thread;
use App\Rules\Spamfree;
use Livewire\Component;

class CreateReply extends Component
{
    public $thread;

    public $body;

    protected $listeners = ['lock' => '$refresh', 'unlock' => '$refresh'];

    public function mount(Thread $thread)
    {
        $this->thread = $thread;
    }

    public function create()
    {
        if ($this->thread->locked) {
            return $this->dispatchBrowserEvent('flash', ['message' => 'thread is locked', 'color' => 'red']);
        } elseif (auth()->guest()) {
            return redirect('login');
        } elseif (\Gate::denies('create-throttle', 'Reply')) {
            return $this->dispatchBrowserEvent('flash', ['message' => 'posting too frequently', 'color' => 'red']);
        }

        $this->validate([
            'body' => ['required', new Spamfree],
        ]);

        $this->thread->replies()->create([
            'body' => $this->body,
            'user_id' => auth()->id(),
        ]);

        $this->emit('refresh');

        $this->dispatchBrowserEvent('flash', ['message' => 'created a reply', 'color' => 'green']);

        $this->body = '';
    }

    public function render()
    {
        return view('livewire.create-reply');
    }
}
