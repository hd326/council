<?php

namespace App\Http\Controllers;

use App\Thread;
use App\Reply;
use Illuminate\Http\Request;

class ReplyController extends Controller
{
    //this is for protection
    public function __construct()
    {
        $this->middleware('auth', ['except' => 'index']);
    }

    public function index($channelId, Thread $thread)
    {
        return $thread->replies()->paginate(20);
    }

    public function store($channelId, Thread $thread)
    {
        if ($thread->locked) {
            return reponse('Thread is locked', 422);
        }
        
        $this->validate(request(), [
            'body' => 'required'
        ]);

        $reply = $thread->addReply([
            'body' => request('body'),
            'user_id' => auth()->id()
        ]);

        if (request()->expectsJson()) {
            return $reply->load('owner');
        }

        return back()->with('flash', 'Your reply has been left!');
    }

    public function destroy(Reply $reply)
    {
        //if($reply->user_id != auth()->id()) {
        //    return response([], 403);
        //}
        $this->authorize('update', $reply);

        $reply->delete();

        if (request()->expectsJson()) {
             return response(['status' => 'Reply deleted']);
        }
        return back();
    }

    public function update(Reply $reply)
    {
        $this->authorize('update', $reply);
        
        $reply->update(request()->validate(['body' => 'required']));
    }
}
