<?php

namespace Tests\Unit;

use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use App\Activity;

class ActivityTest extends TestCase
{
    use DatabaseMigrations;

    /** @test */

    public function it_records_activity_when_a_thread_is_create()
    {
        $this->signIn();

        $thread = create('App\Thread');
        
        $this->assertDatabaseHas('activities', [
            'type' => 'created_thread',
            'user_id' => auth()->id(),
            'activitiable_id' => $thread->id,
            'activitiable_type' => 'App\Thread'
        ]);

        $activity = Activity::first(); 
        // immediate relationship between activity and subject
        $this->assertEquals($activity->activitiable->id, $thread->id);
    }

    /** @test */
    
    public function it_records_activity_when_a_reply_is_created()
    {
        $this->signIn();

        $reply = create('App\Reply');

        $this->assertEquals(2, Activity::count());
        // why 2?
    }
}
