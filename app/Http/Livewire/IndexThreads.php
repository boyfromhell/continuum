<?php

namespace App\Http\Livewire;

use App\Models\Channel;
use App\Traits\ThreadQueries;
use Livewire\Component;
use Livewire\WithPagination;

class IndexThreads extends Component
{
    use WithPagination;
    use ThreadQueries;

    protected $threads;

    public $channel;

    public $popular = 0;

    public $unanswered = 0;

    public $by = '';

    public $search = '';

    public $page = 1;

    protected $filters = ['by' => '', 'popular' => 0, 'unanswered' => 0];

    protected $queryString = [
        'search' => ['except' => ''],
        'by' => ['except' => ''],
        'popular' => ['except' => 0],
        'unanswered' => ['except' => 0],
        'page' => ['except' => 1],
    ];

    public function mount(Channel $channel)
    {
        $this->fill(request()->only('search', 'by', 'popular', 'unanswered', 'page'));

        $this->channel = $channel;
    }

    public function query($method, $value)
    {
        $this->resetQueries();

        foreach (array_keys($this->filters) as $filter) {
            if ($filter == $method) {
                $this->$filter = $value;
                break;
            }
        }
    }

    protected function resetQueries()
    {
        $this->search = '';
        $this->by = '';
        $this->popular = 0;
        $this->unanswered = 0;
        $this->page = 1;
    }

    public function resetPage()
    {
        $this->page = 1;
    }

    protected function filterThreads()
    {
        foreach ($this->filters as $filter => $default) {
            if ($this->$filter != $default) {
                $this->threads = $this->$filter($this->$filter);
                break;
            }
        }

        if ($this->threads === null) {
            $this->threads = $this->latest();
        }

        if ($this->channel->exists) {
            $this->threads->where('channel_id', $this->channel->id);
        }

        if ($this->search != '') {
            $this->search($this->threads, $this->search);
        }

        return $this->threads;
    }

    public function render()
    {
        $this->threads = $this->filterThreads();

        return view('livewire.index-threads', [
            'threads' => $this->threads->paginate(10),
        ]);
    }
}
