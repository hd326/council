<div class="panel panel-default">
    <div class="panel-heading">
        <div class="level">
            <span class="flex">
                {{ $profileUser->name }} replied to
                <a
                    href="{{ $activity->activitiable->thread->path() }}">"{{ $activity->activitiable->thread->title }}"</a>
            </span>
            <span>
                {{ $activity->created_at->diffForHumans() }}
            </span>
        </div>
    </div>
    <div class="panel-body">
        
        <div class="body">
            {{ $activity->activitiable->body }}
        </div>
    </div>
</div>