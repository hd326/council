@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row">
        <div class="col-md-8 col-md-offset-2">
            <div class="page-header">
                
            <avatar-form :user="{{ $profileUser }}"></avatar-form>
            {{--<div>
                <h1 v-text="user.name">
                    
                    <small>Since {{ $profileUser->created_at->diffForHumans() }}</small>
                </h1>
            
                <form v-if="canUpdate" method="POST" action="{{ route('avatar', $profileUser) }}" enctype="multipart/form-data">
                    {{ csrf_field() }}
                    <input type="file" name="avatar">
                    <button type="submit" class="btn btn-primary">Add Avatar</button>
                </form>
            
                <img src="/storage/{{ $profileUser->avatar() }}" width="50" height="50">
            </div>--}}
            </div>
            {{-- @foreach ($profileUser->threads as $thread) --}}
            {{-- @foreach ($threads as $thread) --}}

            @foreach ($activities as $activity)
            @if (view()->exists("profiles.activities.{$activity->type}"))
            @include ("profiles.activities.{$activity->type}")
            @else <p>There is no activity for this user yet.</p>
            @endif
            @endforeach
            {{-- $threads->links() --}}


        </div>
    </div>


</div>
@endsection