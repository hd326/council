<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class UserAvatarController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function store()
    {
        $this->validate(request(), [
            'avatar' => ['required', 'image']
        ]);

        auth()->user()->update([
            'avatar_path' => request()->file('avatar')->store('/storage/avatars', 'public')
            //'avatar_path' => request()->file('avatar')->storeAs('avatars', 'avatars.jpg', 'public')
        ]);

        return back();

        //return response([], 204);
        // 204 means we're all good, message received, nothing to say, np
    }
}

