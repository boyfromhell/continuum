<?php

namespace App\Http\Livewire;

use App\Models\Thread;
use Livewire\Component;

class ThreadSidebar extends Component
{
    public $thread;

    public $subscribedState;

    public $lockedState;

    protected $listeners = ['refresh' => '$refresh'];

    public function mount(Thread $thread)
    {
        $this->thread = $thread;

        $this->subscribedState = $thread->isSubscribed;

        $this->lockedState = $thread->locked;
    }

    public function lock()
    {
        if (auth()->guest()) {
            return redirect('login');
        } elseif (! auth()->user()->isAdmin()) {
            return $this->dispatchBrowserEvent('flash', ['message' => 'not an administrator', 'color' => 'red']);
        }

        if ($this->thread->locked) {
            $this->thread->update([
                'locked' => false
            ]);

            $this->emit('unlock');

            $this->dispatchBrowserEvent('flash', ['message' => 'unlocked a thread', 'color' => 'green']);

            $this->lockedState = false;
        } else {
            $this->thread->update([
                'locked' => true
            ]);

            $this->emit('lock');

            $this->dispatchBrowserEvent('flash', ['message' => 'locked a thread', 'color' => 'red']);

            $this->lockedState = true;
        }
    }

    public function subscribe()
    {
        if ($this->thread->locked) {
            return $this->dispatchBrowserEvent('flash', ['message' => 'thread is locked', 'color' => 'red']);
        } elseif (auth()->guest()) {
            return redirect('login');
        }

        if ($this->thread->isSubscribed) {
            $this->thread->unsubscribe();

            $this->subscribedState = false;
    
            $this->dispatchBrowserEvent('flash', ['message' => 'unsubscribed to a thread', 'color' => 'red']);
        } else {
            $this->thread->subscribe();

            $this->subscribedState = true;

            $this->dispatchBrowserEvent('flash', ['message' => 'subscribed to a thread', 'color' => 'green']);
        }
    }

    public function render()
    {
        return view('livewire.thread-sidebar');
    }
}
