<?php

namespace App\Http\Livewire;

use App\Models\Thread;
use App\Rules\Spamfree;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Livewire\Component;

class CreateThread extends Component
{
    use AuthorizesRequests;

    public $channel_id;

    public $title;

    public $body;

    public $classes;

    public $confirmingThreadCreation = false;

    public function create()
    {
        if (auth()->guest()) {
            return redirect('login');
        } elseif (! auth()->user()->hasVerifiedEmail()) {
            return redirect('email/verify');
        } elseif (\Gate::denies('create-throttle', 'Thread')) {
            return $this->dispatchBrowserEvent('flash', ['message' => 'posting too frequently', 'color' => 'red']);
        }

        $this->validate([
            'title' => ['required', new Spamfree],
            'body' => ['required', new Spamfree],
            'channel_id' => 'required|exists:channels,id',
        ]);

        $thread = Thread::create(
            [
                'user_id' => auth()->id(),
                'channel_id' => $this->channel_id,
                'slug' => $this->title,
                'title' => $this->title,
                'body' => $this->body,
            ]
        );

        return redirect($thread->path());
    }

    public function render()
    {
        return view('livewire.create-thread');
    }
}
