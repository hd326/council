<?php

namespace App\Listeners;

use App\Events\ThreadReceivedNewReply;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;

class NotifySubscribers
{
    /**
     * Handle the event.
     *
     * @param  ThreadReceivedNewReply  $event
     * @return void
     */
    public function handle(ThreadReceivedNewReply $event)
    {
        $thread = $event->reply->thread;
        // in Thread we use addReply which takes $reply
        // so if we want to use get the thread we use eloquent relationship
        $thread->subscriptions
        ->where('user_id', '!=', $event->reply->user_id)
        ->each
        ->notify($event->reply);
    }
}
