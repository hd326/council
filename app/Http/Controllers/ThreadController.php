<?php

namespace App\Http\Controllers;

//use Redis;
use App\Filters\ThreadFilters;
use App\Rules\Recaptcha;
use App\Channel;
use App\Thread;
use App\Reply;
use App\Trending;
use Illuminate\Http\Request;
use Zttp\Zttp;

class ThreadController extends Controller
{

    public function __construct()
    {
        //$this->middleware('auth')->only(['create', 'store']);
        $this->middleware('auth')->except(['index', 'show']);
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Channel $channel/*$channelSlug = null*/, ThreadFilters $filters, Trending $trending)
    {
        //$this->validate(request(), [
        //    'title' => 'required'
        //]);

        //if ($channelSlug) {
        //    //Channel::whereSlug($channelSlug);
        //    //$channelId = Channel::where('slug', $channelSlug)->first();
        //    $channelId = Channel::where('slug', $channelSlug)->first()->id;
        //    $threads = Thread::where('channel_id', $channelId)->latest()->get();
        //    get the id with the slug from the Channel, then get the Channel_id from the slug
        //} else {
        //    $threads = Thread::latest()->get();
        //}

        //added myself
        //$channels = Channel::all();
        //$threads = $this->getThreads($channel);

        //$threads = Thread::filter($filters)->get();


        $threads = $this->getThreads($channel, $filters);

        // interchangeable

        //if ($channel->exists) {
        //    //$threads = $channel->threads()->latest()->get();
        //    $threads = $channel->threads()->latest();
        //} else {
        //    //$threads = Thread::latest()->get();
        //    $threads = Thread::latest();
        //}

            // if request('by'), we should filter by given usename
//        if ($username = request('by')) {
//            $user = \App\User::where('name', $username)->firstOrFail();
//            $threads->where('user_id', $user->id);
//        }

        if (request()->wantsJson()) {
            return $threads;
        }

        //$trending = collect(Redis::zrevrange('trending_threads', 0, -1))->map(function ($thread) {
        //    return json_decode($thread);
        //});

        //$trending = array_map('json_decode', Redis::zrevrange('trending_threads', 0, 4));

        // return view('threads.index', compact('threads', 'trending'));
        return view('threads.index', [
            'threads' => $threads,
            'trending' => $trending->get()
            ]);
    }



    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('threads.create', [
            'channels' => Channel::orderBy('name', 'asc')->get()
        ]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request, Recaptcha $recaptcha)
    {

        //dd(request()->all());

        request()->validate([
            'title' => 'required',
            'body' => 'required',
            'channel_id' => 'required|exists:channels,id',
            'g-recaptcha-response' => ['required', $recaptcha]
        ]);

        $thread = Thread::create([
            'user_id' => auth()->id(),
            'channel_id' => request('channel_id'),
            'title' => request('title'),
            'body' => request('body'),
            //'slug' => request('title')
            //'slug' => str_slug(request('title'))
        ]);

        if (request()->wantsJson()) {
            return response($thread, 201);
        }
        
        return redirect($thread->path())
            ->with('flash', 'Your thread has been published!');
        //dd($thread->path());
    }

    /**
     * Display the specified resource.
     *
     * @param @channelId
     * @param  \App\Thread  $thread
     * @return \Illuminate\Http\Response
     */
    public function show($channelId, Thread $thread, Trending $trending)
    {
        // return $thread;
        // Route Model Binding
        // return $thread->load('replies');
        // dd($thread);

        // Redis::zincrby('trending_threads', 1, $thread->id);
        // we can use JSON request to never make database query
        // $thread->id would be
        // json_encode(['title => $thread->title, 'path' => $thread->path])

        //Redis::zincrby('trending_threads', 1, json_encode([
        //    'title' => $thread->title,
        //    'path' => $thread->path()
        //]));
        $trending->push($thread);

        //$thread->recordVisit();
        //$thread->visits()->record();
        $thread->increment('visits');

        return view('threads.show', [
            'thread' => $thread,
            'replies' => $thread->replies()
        ]);
        //return view('threads.show', compact('thread'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Thread  $thread
     * @return \Illuminate\Http\Response
     */
    public function edit(Thread $thread)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Thread  $thread
     * @return \Illuminate\Http\Response
     */
    public function update($channel, Request $request, Thread $thread)
    {
        //authorization
        $this->authorize('update', $thread);
        //validation
        //update the thread
        $thread->update(request()->validate([
            'title' => 'required',
            'body' => 'required'
        ]));

        return $thread; 
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Thread  $thread
     * @return \Illuminate\Http\Response
     */
    public function destroy($channel, Thread $thread)
    {
        //this portion is updated in lieu of another method
        //$thread->replies()->delete();
        //if ($thread->user_id != auth()->id()){
        //    //abort(403);
        //    //if (request()->wantsJson()) {
        //    //    return response(['status' => 'Permission Denied'], 403);
        //    //}
        //    //return redirect('/login');
        //    abort(403, 'You do not have permission to do this.');
        //}
        $this->authorize('update', $thread);
        //authorize -> policy -> update -> $thread

        //Reply::where('thread_id', $thread->id)->delete();
        $thread->delete();

        if (request()->wantsJson()) {
            return response([], 204);
        }
    
        return redirect('/threads');
    }

    //protected function getThreads(Channel $channel)
    //{
    //    if ($channel->exists) {
    //        //$threads = $channel->threads()->latest()->get();
    //        $threads = $channel->threads()->latest();
    //    } else {
    //        //$threads = Thread::latest()->get();
    //        $threads = Thread::latest();
    //    }
    //    // if request('by'), we should filter by given usename
    //    if ($username = request('by')) {
    //        $user = \App\User::where('name', $username)->firstOrFail();
    //        $threads->where('user_id', $user->id);
    //    }
    //    $threads = $threads->get();
    //    return $threads;
    //}
    protected function getThreads(Channel $channel, ThreadFilters $filters)
    {
        $threads = Thread::orderBy('pinned', 'DESC')
            ->filter($filters)->latest();

        if($channel->exists) {
            $threads->where('channel_id', $channel->id);
        }
        //$threads = $threads->filter($filters)->get();
        //dd($threads->toSql());
        return $threads->paginate(5);
    }
}
