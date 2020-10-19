<div>
    <x-slot name="header">
        <x-forum-header />
    </x-slot>

    <div class="flex flex-col lg:flex-row py-12">
        <div class="lg:flex-1 sm:px-6 lg:px-8">
            <div class="bg-white p-6 sm:px-20 overflow-hidden shadow-xl sm:rounded-lg">

                @livewire('manage-thread', ['thread' => $thread])

                @livewire('show-replies', ['thread' => $thread])

                @if (auth()->check())

                @livewire('create-reply', ['thread' => $thread])

                @else
                <h4 class="mt-6 text-xl text-gray-500">
                    <a href="{{ route('login') }}">Please sign in...</a>
                </h4>
                @endif

            </div>
        </div>

        <div class="order-first lg:order-none lg:flex-1 lg:max-w-xl mb-6 lg:mt-0 sm:px-6 lg:px-8">
            <div class="bg-white p-6 sm:px-20 overflow-hidden shadow-xl sm:rounded-lg">

                @livewire('thread-sidebar', ['thread' => $thread])

            </div>
        </div>
    </div>
</div>