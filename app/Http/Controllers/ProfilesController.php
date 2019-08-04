<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\User; 
use App\Thread;

class ProfilesController extends Controller
{
    public function show(User $user)
    {
        $activities = $user->activity()->latest()->with('activitiable')->take(50)->get();
        return view('profiles.show', [
            'profileUser' => $user,
            //'threads' => $user->threads()->paginate(1)
            'activities' => $activities,
            //we want something like activity()
        ]);
    }
}
