@props(['id' => null, 'maxWidth' => null, 'submit'])

<x-jet-modal :id="$id" :maxWidth="$maxWidth" {{ $attributes }}>
    <div class="px-6 py-4">
        <div class="text-lg">
            {{ $title }}
        </div>
    </div>

    <form wire:submit.prevent="{{ $submit }}">
        <div class="px-6 py-4">
            {{ $content }}
        </div>

        <div class="flex items-center justify-end px-6 py-4 bg-gray-100">
            {{ $footer }}
        </div>
    </form>

</x-jet-modal>