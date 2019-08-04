<?php

namespace Tests\Unit;

use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\DatabaseMigrations;

class ChannelTest extends TestCase
{
    use DatabaseMigrations;

    /** @test */
    public function a_channel_consists_of_threads()
    {
        $channel = factory('App\Channel')->create();
        $thread = factory('App\Thread')->create(['channel_id' => $channel->id]);
        // why isn't channel_id autopopulated when a thread is created?
        // we didn't include it in the ModelFactory
        
        // what i thought was correct
        //$this->get('/threads/' . $channel->slug)
        //    ->assertSee($thread->id);
        $this->assertTrue($channel->threads->contains($thread));
        // channel hasMany threads 
        // if we omit the the Thread channel_id... 
        // we do not get all components of thread
    }
}
