<div>
    <div x-data="{ words: @entangle('body') }" x-cloak>
        @if (! $thread->locked)
        <form wire:submit.prevent="create">
            <div class="mt-4 shadow overflow-hidden shadow-md sm:rounded-md">
                <div class="px-4 py-5 sm:p-6">
                    <x-jet-label for="body" value="{{ __('Body') }}" />
                    <textarea name="body" id="body" rows="10" x-model="words"
                        class="form-textarea shadow-sm mt-1 block w-full mentionable" required></textarea>
                    <x-jet-input-error for="body" class="mt-2" />
                </div>
                <div class="flex items-center justify-end px-4 py-3 bg-gray-50 sm:px-6">
                    <x-jet-button>
                        <span wire:loading wire:target="create">
                            <svg class="animate-spin -ml-1 mr-3 h-4 w-4 text-white" xmlns="http://www.w3.org/2000/svg"
                                fill="none" viewBox="0 0 24 24">
                                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor"
                                    stroke-width="4">
                                </circle>
                                <path class="opacity-75" fill="currentColor"
                                    d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z">
                                </path>
                            </svg>
                        </span>
                        {{ __('Post') }}
                    </x-jet-button>
                </div>
            </div>
        </form>
        @else
        <div class="mt-3 text-xl text-gray-500">
            <p>{{ __('Thread has been locked...')}}</p>
        </div>
        @endif
    </div>
</div>