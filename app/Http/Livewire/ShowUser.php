<?php

namespace App\Http\Livewire;

use App\Models\Activity;
use App\Models\User;
use Livewire\Component;

class ShowUser extends Component
{
    protected $user;

    public function mount(User $user)
    {
        $this->user = $user;
    }

    public function render()
    {
        return view('livewire.show-user', [
            'user' => $this->user,
            'activities' => Activity::feed($this->user)
        ]);
    }
}
