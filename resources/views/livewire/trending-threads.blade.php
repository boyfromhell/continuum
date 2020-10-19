<div>
    <p class="text-center text-gray-500">
        {{ __('Trending Threads') }}
    </p>
    <hr class="my-3 ">

    @forelse ($trending as $thread)

    <p class="text-sm text-gray-500">
        <a href="{{ $thread->path }}">{{ $thread->title }}</a>
    </p>

    @if ( $loop->last )
    @else
    <hr class="my-3">
    @endif

    @empty

    <p class="my-3 text-sm text-gray-500">
        {{ __('There\'s no trending threads in the past 24 hours...')}}
    </p>

    @endforelse
</div>