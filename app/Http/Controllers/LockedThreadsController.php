<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Thread;

class LockedThreadsController extends Controller
{
    public function store()
    {
        //if (request()->has('locked')) {
        //    if (! auth()->user()->isAdmin()) {
        //        return response('', 403);
        //    }
        //}
        $thread->update(['locked' => true]);
    }
    public function destroy()
    {
        $thread->update(['locked' => false]);
    }
}
