<?php

namespace Tests\Feature;

use Tests\TestCase;
//use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\DatabaseMigrations;

class ThreadsTest extends TestCase
{
    use DatabaseMigrations;

    public function setUp()
    {
        //because we extend TestCase, we call parent::setUp
        parent::setUp();
        $this->thread = factory('App\Thread')->create();
    }

    /** @test */

    //public function test_a_user_can_browse_threads()
    public function a_user_can_view_all_threads()
    {
        //test 1
        //this is commented out for the setUp
        //$thread = factory('App\Thread')->create();
        //first way
        //$response = $this->get('/threads');
        //$response->assertSee($this->thread->title);
        //second way
        $this->get('/threads')->assertSee($this->thread->title);

        //$response->assertStatus(200);
    }

    /** @test */

    public function a_user_can_read_a_single_thread()
    {
        //test 2
        //this means we should see the title when test runs
        //this is commented out for the setUp
        //$thread = factory('App\Thread')->create();

        // first way
        //$response = $this->get('/threads/' . $this->thread->id);
        //$response->assertSee($this->thread->title);

        // second way
        $this->get(/*'/threads/' . $this->thread->id*/$this->thread->path())->assertSee($this->thread->title);

    }

    /** @test */

    //public function a_user_can_read_replies_that_are_associated_with_a_thread()
    //{
    //    // Given we have a thread
    //    // And that thread includes replies
    //    $reply = factory('App\Reply')->create(['thread_id' => $this->thread->id]);
    //    //this generates one reply
    //    // When we visit a thread page
    //    //first way
    //    //$response = $this->get('/threads/' . $this->thread->id);
    //    //$response->assertSee($reply->body);
    //    //second way 
    //    //$this->get(/*'/threads/' . $this->thread->id*/$this->thread->path())->assertSee($reply->body);
    //    $this->assertDatabaseHas('replies', ['body' => $reply->body]);
    //    // Then we should see the replies
    //}

    /** @test */

    public function a_user_can_filter_threads_according_to_a_channel()
    {
        $channel = factory('App\Channel')->create();
        $threadInChannel = factory('App\Thread')->create(['channel_id' => $channel->id]);
        $threadNotInChannel = factory('App\Thread')->create();

        $this->get('/threads/' . $channel->slug)
            ->assertSee($threadInChannel->title)
            ->assertDontSee($threadNotInChannel->title);
    }

    /** @test */

    function a_user_can_filter_threads_by_any_username()
    {
        $this->signIn(create('App\User', ['name' => 'JohnDoe']));

        $threadByJohn = create('App\Thread', ['user_id' => auth()->id()]);
        $threadNotByJohn = create('App\Thread');
        //$threadByRichard = factory('App\Thread')->create(['user_id' => auth()->id()]);
        //$threadNotByRichard = factory('App\Thread')->create();
        
        $this->get('threads?by=JohnDoe')
            ->assertSee($threadByJohn->title)
            ->assertDontSee($threadNotByJohn->title);
    }

    /** @test */
    function a_user_can_filter_threads_by_popularity()
    {
        $threadWithTwoReplies = create('App\Thread');
        create('App\Reply', ['thread_id' => $threadWithTwoReplies->id], 2);

        $threadWithThreeReplies = create('App\Thread');
        create('App\Reply', ['thread_id' => $threadWithThreeReplies->id], 3);

        $threadWithNoReplies = $this->thread;

        $response = $this->getJson('threads?popular=1')->json();
        $this->assertEquals([3,2,0], array_column($response['data'], 'replies_count'));
    }

    /** @test */
    function a_user_can_filter_threads_by_those_that_are_unanswered()
    {
        $thread = create('App\Thread');
        create('App\Reply', ['thread_id' => $thread->id]);

        $response = $this->getJson('threads?unanswered=1')->json();
        $this->assertCount(1, $response['data']);
    }

    /** @test */

    function a_user_can_request_all_replies_for_a_given_thread()
    {
        $thread = create('App\Thread');
        create('App\Reply', ['thread_id' => $thread->id], 2);

        $response = $this->getJson($thread->path() . '/replies')->json();

        //dd($response);
        $this->assertCount(2, $response['data']);
        $this->assertEquals(2, $response['total']);
    }
}
