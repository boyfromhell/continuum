@php
$classes = 'px-1 pt-1 border-b-2 border-transparent text-sm font-medium leading-5 text-gray-500
hover:text-gray-700 hover:border-gray-300 focus:outline-none focus:text-gray-700 focus:border-gray-300 transition
duration-150 ease-in-out';
@endphp

<div class="flex">
    <div class="flex-1 inline-flex">
        <button type="button" class="mr-4 {{$classes}}">
            <a href="{{ route('forum') }}">{{ __('Forum') }}</a>
        </button>

        <x-jet-dropdown align="left" width="48">
            <x-slot name="trigger">
                <button type="button" class="{{$classes}}">
                    {{ __('Channels') }}
                </button>
            </x-slot>

            <x-slot name="content">

                @foreach ($channels as $channel)
                <x-jet-dropdown-link href="/forum/{{$channel->slug}}">
                    {{ $channel->name }}
                </x-jet-dropdown-link>
                <div class="border-t border-gray-100"></div>
                @endforeach

            </x-slot>
        </x-jet-dropdown>
    </div>

    <div>
        @guest
        @if (Route::has('login'))
        <button type="button" class="{{$classes}}">
            <a href="{{ route('login') }}">{{ __('Login') }}</a>
        </button>
        @if (Route::has('register'))
        <button type="button" class="{{$classes}} ml-4">
            <a href="{{ route('register') }}">{{ __('Register') }}</a>
        </button>
        @endif
        @endif
        @endguest
    </div>
</div>