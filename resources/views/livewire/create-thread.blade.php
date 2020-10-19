<div>
    <x-jet-input type="text" class="min-w-full" wire:click="$toggle('confirmingThreadCreation')"
        placeholder="Create post..." />

    <x-submit-modal submit="create" wire:model="confirmingThreadCreation">
        <x-slot name="title">
            {{ __('Create Thread') }}
        </x-slot>

        <x-slot name="content">
            <x-jet-label for="channel_id" value="{{ __('Choose a channel') }}" />
            <select name="channel_id" id="channel_id" wire:model.defer="channel_id"
                class="form-select rounded-md shadow-sm mt-1 block w-full" required>
                <option value="">Choose a channel...</option>
                @foreach ($channels as $channel)
                <option value="{{ $channel->id }}" {{ old('channel_id') == $channel->id ? 'selected' : ''}}>
                    {{ $channel->slug }}</option>
                @endforeach
            </select>
            <x-jet-input-error for="channel_id" class="mt-2" />

            <x-jet-label for="title" value="{{ __('Title') }}" class="mt-2" />
            <x-jet-input type="text" id="title" name="title" wire:model.defer="title" class="mt-1 block w-full"
                value="{{ old('title') }}" required />
            <x-jet-input-error for="title" class="mt-2" />

            <x-jet-label for="body" value="{{ __('Body') }}" class="mt-2" />
            <textarea name="body" id="body" rows="10" wire:model.defer="body"
                class="form-textarea rounded-md shadow-sm mt-1 block w-full" required>{{ old('body') }}</textarea>
            <x-jet-input-error for="body" class="mt-2" />

        </x-slot>

        <x-slot name="footer">
            <x-jet-secondary-button wire:click="$toggle('confirmingThreadCreation')" wire:loading.attr="disabled">
                {{ __('Nevermind') }}
            </x-jet-secondary-button>

            <x-jet-button class="ml-2" wire:loading.attr="disabled">
                <span wire:loading wire:target="create">
                    <svg class="animate-spin -ml-1 mr-3 h-4 w-4 text-white" xmlns="http://www.w3.org/2000/svg"
                        fill="none" viewBox="0 0 24 24">
                        <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4">
                        </circle>
                        <path class="opacity-75" fill="currentColor"
                            d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z">
                        </path>
                    </svg>
                </span>
                {{ __('Create') }}
            </x-jet-button>
        </x-slot>
    </x-submit-modal>
</div>