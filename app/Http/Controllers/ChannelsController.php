<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Channel;
use Illuminate\Validation\Rule;
use App\Http\Controllers\Controller;

class ChannelsController extends Controller
{
    public function index(Channel $channel)
    {
        $channels = Channel::withArchived()->with('threads')->get();
        return view('admin.channels.index', compact('channels'));
    }

    public function store(Channel $channel)
    {
        $channel = Channel::create(
            request()->validate([
                'name' => 'required|unique:channels',
                'description' => 'required',
            ])
        );
        cache()->forget('channels');
        if (request()->wantsJson()) {
            return response($channel, 201);
        }
        return redirect(route('admin.channels.index'))
            ->with('flash', 'Your channel has been created!');
    }

    public function create(Channel $channel)
    {
        return view('admin.channels.create', ['channel' => new Channel]);
    }

    public function edit(Channel $channel)
    {
        return view('admin.channels.edit', compact('channel'));
    }

    public function update(Channel $channel)
    {
        $channel->update(
            request()->validate([
                'name' => ['required', Rule::unique('channels')->ignore($channel->id)],
                'description' => 'required',
                'archived' => 'required|boolean'
            ])
        );
        cache()->forget('channels');
        if (request()->wantsJson()) {
            return response($channel, 200);
        }
        return redirect(route('admin.channels.index'))
            ->with('flash', 'Your channel has been updated!');
    }
}
