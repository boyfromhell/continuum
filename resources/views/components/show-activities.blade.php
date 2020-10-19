@props(['record', 'user'])

@php
switch ($record->type) {
    case 'created_favorite':
        $activityText = ' favorited a ';
        $activityPath = $record->subject->favorited->path();
        $activitySubject = 'reply';
        $activitySlot = $record->subject->favorited->body;
        break;
    case 'created_reply':
        $activityText = ' replied to ';
        $activityPath = $record->subject->thread->path();
        $activitySubject = $record->subject->thread->title;
        $activitySlot = $record->subject->body;
        break;
    case 'created_thread':
        $activityText = ' published ';
        $activityPath = $record->subject->path();
        $activitySubject = $record->subject->title;
        $activitySlot = $record->subject->body;
        break;
    default:
        # code...
        break;
}
@endphp

<article>

    <div class="flex items-center mt-2">

        <h5 class="text-gray-500">
            {{ $user->name }}
            {{ $activityText }}
            <a href="{{ $activityPath }}">
                {{ $activitySubject }}
            </a>
        </h5>

    </div>

    <div class="mt-4 text-sm text-gray-500">

        {!! $activitySlot !!}

    </div>

</article>