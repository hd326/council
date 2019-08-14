{{-- Editing the question. --}}

<div class="panel panel-default" v-if="editing">
        <div class="panel-heading">
            <div class="level">
                <input type="text" class="form-control" v-model="form.title">
            </div>
        </div>
        <div class="panel-body">
            <div class="form-group">
                {{--<textarea class="form-control" rows="10" v-model="form.body"></textarea>--}}
                <wysiwyg v-model="form.body" :value="form.body"></wysiwyg>
        </div>
        </div>
        <div class="panel-footer">
            <div class="level">
                    <button class="btn btn-xs level-item" @click="editing = true" v-show="! editing">Edit</button>
                    <button class="btn btn-primary btn-xs level-item" @click="update">Update</button>
                    <button class="btn btn-xs level-item" @click="resetForm">Cancel</button>
                @can('update', $thread)
                <form action="{{ $thread->path() }}" method="POST" class="ml-a">
                    {{ csrf_field() }}
                    {{ method_field('DELETE') }}
                    <button type="submit" class="btn btn-link">Delete Thread</button>
                </form>
                @endcan
            </div>

        </div>
    </div>
    
    

{{-- Viewing the question. --}}
<div class="panel panel-default" v-else>
    <div class="panel-heading">
        <div class="level">
            {{--@if($thread->creator->avatar_path)--}}
            <img src="/storage/{{ $thread->creator->avatar() }}" width="25" height="25" class="mr-1">
            {{--@endif--}}
            <span class="flex"><a
                    href="{{ route('profile', $thread->creator->name) }}">{{ $thread->creator->name }} ({{ $thread->creator->reputation }} XP)</a>
                posted:
            <span v-text="title"></span>    
            </span>
        </div>
    </div>
    <div class="panel-body" v-html="body"></div>
    <div class="panel-footer" v-if="authorize('owns', thread)">
        <button class="btn btn-xs" @click="editing = true">Edit</button>
    </div>
</div>

