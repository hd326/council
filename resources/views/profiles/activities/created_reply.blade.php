<div class="panel panel-default">
    <div class="panel-heading">
        <div class="level">
            <span class="flex">
                {{--@if ($activity->type == 'created_thread')
                    {{ $profileUser->name }} published a thread
                @endif--}}
                {{--@if ($activity->type == 'created_reply')
                    {{ $profileUser->name }} replied to thread
                @endif--}}
                {{ $profileUser->name }} replied to
                <a href="{{ $activity->activitiable->thread->path() }}">"{{ $activity->activitiable->thread->title }}"</a>
                {{--<a href="{{ route('profile', $thread->creator )}}">{{ $thread->creator->name }}</a> posted: --}}
                {{--<a href="{{ $thread->path() }}">{{ $thread->title }}</a> --}}
            </span>
            <span>
                {{-- $thread->created_at->diffForHumans() --}}
                {{ $activity->created_at->diffForHumans() }}
            </span>
        </div>
    </div>
    <div class="panel-body">
        <div class="body">
            {{-- $thread->body --}}
            
            {{ $activity->activitiable->body }}
        </div>
    </div>
</div>