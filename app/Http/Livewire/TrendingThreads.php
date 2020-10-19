<?php

namespace App\Http\Livewire;

use App\Models\Trending;
use Livewire\Component;

class TrendingThreads extends Component
{
    protected $trending;

    public function mount(Trending $trending)
    {
        $this->trending = $trending;
    }

    public function render()
    {
        return view('livewire.trending-threads', [
            'trending' => $this->trending->get(),
        ]);
    }
}
