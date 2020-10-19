<?php

namespace App\Http\Livewire;

use App\Models\Thread;
use Livewire\Component;
use Livewire\WithPagination;

class ShowReplies extends Component
{
    use WithPagination;

    public $thread;

    protected $listeners = ['refresh' => '$refresh', 'lock' => '$refresh', 'unlock' => '$refresh'];

    public function mount(Thread $thread)
    {
        $this->thread = $thread;
    }

    public function render()
    {
        return view('livewire.show-replies', [
            'replies' => $this->thread->replies()->paginate(5)
        ]);
    }
}
