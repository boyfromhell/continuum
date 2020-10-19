<div>

    <x-slot name="header">
        <x-forum-header />
    </x-slot>

    <div class="py-12">
        <div class="flex flex-col lg:flex-row">
            <div class="lg:flex-1 sm:px-6 lg:px-8">
                @if(auth()->check() && auth()->user()->hasVerifiedEmail())
                <div class="mb-3 bg-white p-6 sm:px-20 shadow-xl sm:rounded-lg">
                    @livewire('create-thread')
                </div>
                @endif

                <div class="bg-white p-6 sm:px-20 shadow-xl sm:rounded-lg">

                    <x-show-threads :threads="$threads" />

                </div>
            </div>

            <div class="order-first lg:order-none lg:flex-col lg:flex-1 lg:max-w-xl">
                <div class="mb-3">
                    <div class="sm:px-6 lg:px-8">
                        <div class="flex justify-center bg-white p-6 sm:px-20 shadow-xl sm:rounded-lg">
                            <x-jet-input type="text" class="min-w-full" wire:model="search" wire:keydown="resetPage"
                                placeholder=" Search posts..." />
                        </div>
                    </div>
                </div>
                <div class="hidden lg:contents ">
                    <div class="sm:px-6 lg:px-8 ">
                        <div class="bg-white p-6 sm:px-20 shadow-xl sm:rounded-lg ">

                            @livewire('trending-threads')

                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

</div>