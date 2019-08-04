<?php

namespace App\Http\Controllers\Api;

use App\User;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class RegisterConfirmationController extends Controller
{
    public function index()
    {
        try {
            $user = User::where('confirmation_token', request('token'))
            ->firstOrFail();
            //->update(['confirmed' => true]);
            $user->confirmed = true;
            $user->save();
        } catch (\Exception $e) {
            return redirect(route('threads'))
            ->with('flash', 'Unknown token.');
        }
        return redirect(route('threads'))
            ->with('flash', 'Your account is now confirmed! You may post to the forum.');
    }

    //instead of try catch (same)
    //public function index()
    //{
    //    $user = User::where('confirmation_token', request('token'))->first();
    //    if (! $user) {
    //        return redirect(route('/threads'))
    //        ->with('flash', 'Unknown token.');
    //    }
    //    //->update(['confirmed' => true]);
    //    $user->confirmed = true;
    //    $user->save();
    //    return redirect(route('/threads'))
    //        ->with('flash', 'Your account is now confirmed! You may post to the forum.');
    //}
}
