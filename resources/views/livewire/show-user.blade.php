<div>
    <x-slot name="header">
        <x-forum-header />
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white p-6 sm:px-20 overflow-hidden shadow-xl sm:rounded-lg">

                <div class="flex items-center mt-3">
                    <img class="h-8 w-8 rounded-full object-cover" src="{{ $user->profile_photo_url }}"
                        alt="{{ $user->username }}" />
                    <h4 class="ml-3 text-lg text-gray-500">
                        {{ $user->name . "'s page" }}
                    </h4>
                </div>
                <hr class="mt-3 mb-6">

                @forelse ($activities as $date => $activity)
                <h5 class="text-gray-500">
                    {{ $date }}
                </h5>

                @foreach ($activity as $record)

                <x-show-activities :record="$record" :user="$user" />

                @if ( $loop->last )
                <div class="my-6"></div>
                @else
                <hr class="my-3">
                @endif

                @endforeach

                @empty
                <p class="my-3 text-gray-500">
                    There's no activities...
                </p>
                @endforelse

            </div>
        </div>
    </div>
</div>