<?php

namespace App\Http\Livewire;

use App\Models\Thread;
use App\Models\Trending;
use Livewire\Component;

class ShowThread extends Component
{
    protected $thread;

    protected $trending;

    public function mount(Thread $thread, Trending $trending)
    {
        $this->thread = $thread;

        $this->trending = $trending;
    }

    public function render()
    {
        if (auth()->check()) {
            auth()->user()->read($this->thread);
        }

        $this->thread->increment('visits');

        $this->trending->push($this->thread);

        return view('livewire.show-thread', [
            'thread' => $this->thread,
        ]);
    }
}
