<?php

namespace Tests\Unit;

use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\DatabaseMigrations;

class SubscribeToThreadsTest extends TestCase
{
    use DatabaseMigrations;

    /** @test */
    public function a_user_can_subscribe_to_threads()
    {
        $this->signIn();
        $thread = create('App\Thread');
        $this->post($thread->path() . '/subscriptions');

        $this->assertCount(1, $thread->fresh()->subscriptions); 

        //$this->assertCount(1, $thread->subscriptions);
        //// each time a new reply is left
        //// notification should be prepared for the user
        //$this->assertCount(0, auth()->user()->notifications);
        //$thread->addReply([
        //    'user_id' => auth()->id(),
        //    'body' => 'Some reply here'
        //]);
        //$this->assertCount(1, auth()->user()->fresh()->notifications);
        //// $this->assertCount(1, auth()->user()->notifications);
    }

    /** @test */
    public function a_user_can_unsubscribe_from_threads()
    {
        $this->signIn();
        $thread = create('App\Thread');
        $thread->subscribe();
        $this->delete($thread->path() . '/subscriptions');
        $this->assertCount(0, $thread->subscriptions);
    }
}
