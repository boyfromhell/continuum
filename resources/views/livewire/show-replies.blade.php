<div>

    @if ($replies->total())
    <h3 class="mt-4 mb-2 text-lg text-gray-500">
        {{ __('Replies') }}
    </h3>

    @foreach ($replies as $reply)

    @livewire('manage-reply', ['reply' => $reply], key($reply->id))

    @endforeach

    {{ $replies->links() }}
    @endif

</div>