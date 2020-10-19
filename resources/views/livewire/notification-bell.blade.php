<div>
    <x-jet-dropdown align="right" width="64">
        <x-slot name="trigger">
            <span class="relative mr-3">
                <button type="button" class="text-gray-500">
                    <span class="fa fa-bell-o"></span>
                </button>
                <span x-data="{ shown: {{ (int) $notificationsState }} }" x-show="shown" class="flex absolute top-0 right-0 h-2 w-2" x-cloak>
                    <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-pink-400 opacity-75"></span>
                    <span class="relative inline-flex rounded-full h-2 w-2 bg-pink-500"></span>
                </span>
            </span>
        </x-slot>

        <x-slot name="content">

            @forelse ($notifications as $notification)
            <x-jet-dropdown-link href="{{ $notification->data['link'] }}"
                wire:click.prevent="confirm({{$notification}})">
                {{ $notification->data['message'] }}
            </x-jet-dropdown-link>

            @if ( $loop->last )
            @else
            <div class="border-t border-gray-100"></div>
            @endif

            @empty
            <p
                class="block px-4 py-2 text-sm leading-5 text-gray-700 hover:bg-gray-100 focus:outline-none focus:bg-gray-100 transition duration-150 ease-in-out">
                There's no unread notifications...
            </p>

            @endforelse

        </x-slot>
    </x-jet-dropdown>
</div>