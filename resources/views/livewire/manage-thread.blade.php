<div>
    <article x-data="changing()">
        <div class="flex flex-col sm:flex-row sm:items-center">
            <div class="flex-1 flex-col">
                <div x-show="isClose()">
                    <h4 class="font-medium text-lg text-gray-500">{{ $thread->title }}</h4>
                    <h5 class="mt-1 text-xs text-gray-500">
                        {{ 'created by ' }}
                        <a href="{{ $thread->creator->path() }}">
                            {{ $thread->creator->name }}
                        </a>
                        {{ ' ' . $thread->created_at->diffForHumans()  }}
                    </h5>
                </div>
                <div x-show="isOpen()">
                </div>
            </div>

            @if (! $thread->locked)
            <div class="inline-flex items-center mt-3 sm:mt-0">
                @can('update', $thread)
                <div class="">
                    <x-jet-secondary-button x-on:click="show = !show" x-cloak>
                        <span class="fa fa-chevron-down" aria-hidden="true" x-show="isClose()"></span>
                        <span class="fa fa-chevron-up" aria-hidden="true" x-show="isOpen()"></span>
                    </x-jet-secondary-button>
                </div>
                @endcan
            </div>
            @endif
        </div>
        <div class="mt-4 text-gray-500" x-cloak>
            <div x-show="isClose()">
                <p class="mt-3">{{ $thread->body }}</p>
            </div>

            <div x-show="isOpen()">
                <form wire:submit.prevent="update">
                    <x-jet-input type="text" class="mt-6 block w-full" wire:model.defer="title" required />
                    <x-jet-input-error for="title" class="mt-2" />

                    <textarea class="form-textarea rounded-md shadow-sm mt-3 mb-2 block w-full" wire:model.defer="body"
                        required></textarea>
                    <x-jet-input-error for="body" class="mt-2" />
                    <div class="flex items-center">
                        <x-jet-button x-on:click="close">
                            {{ __('Update') }}
                        </x-jet-button>
                        <x-jet-secondary-button class="ml-3" x-on:click="close" wire:click="return">
                            {{ __('Cancel') }}
                        </x-jet-secondary-button>
                        @can('delete', $thread)
                        <div class="ml-auto">
                            <x-jet-danger-button wire:click="$toggle('confirmingThreadDeletion')">
                                <span class="fa fa-trash-o" aria-hidden="true"></span>
                            </x-jet-danger-button>
                        </div>
                        @endcan
                    </div>
                </form>
            </div>
        </div>
    </article>

    <x-jet-confirmation-modal wire:model="confirmingThreadDeletion">
        <x-slot name="title">
            {{ __('Delete Thread') }}
        </x-slot>

        <x-slot name="content">
            {{ __('Are you sure you would like to delete this thread?') }}
        </x-slot>

        <x-slot name="footer">
            <div class="flex items-center justify-end">
                <x-jet-secondary-button wire:click="$toggle('confirmingThreadDeletion')" wire:loading.attr="disabled">
                    {{ __('Nevermind') }}
                </x-jet-secondary-button>

                <x-jet-danger-button wire:click="delete" class="ml-2" wire:loading.attr="disabled">
                    {{ __('Delete') }}
                </x-jet-danger-button>
            </div>
        </x-slot>
    </x-jet-confirmation-modal>
</div>