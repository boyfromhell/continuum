<div>
    <p class="text-gray-500">This thread was published {{ $thread->created_at->diffForHumans() }} by <a
            href="{{ $thread->creator->path() }}">{{ $thread->creator->name }}</a>,
        and currently has {{ $thread->replies_count }}
        {{ Str::plural('comment', $thread->replies_count) }}.</p>

    @auth
    <div class="flex mt-3">
        @if (! $thread->locked)
        <x-state-button :state="$subscribedState" wire:click="subscribe">
            {{ __('Subscribe') }}
        </x-state-button>
        @endif

        @if (auth()->user()->isAdmin())
        <x-state-button :state="$lockedState" class="ml-auto" wire:click="lock">
            <span class="fa fa-lock"></span>
        </x-state-button>
        @endif
    </div>
    @endauth
</div>