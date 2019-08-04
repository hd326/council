<?php

namespace Tests\Unit;

use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\DatabaseMigrations;

class ThreadTest extends TestCase
{
    use DatabaseMigrations;
    //this was added when we added a user can add a reply function
    protected $thread;

    public function setUp(){
        parent::setUp();
        $this->thread = factory('App\Thread')->create();
    }

    /** @test */
    public function a_thread_can_make_a_string_path()
    {
        $thread = factory('App\Thread')->create();

        // $this->assertEquals('/threads/'. $thread->channel->slug . '/' . $thread->id, $thread->path());
        // given we have a thread, the threads path must be ...
        $this->assertEquals("/threads/{$thread->channel->slug}/{$thread->slug}", $thread->path());
    }
    
    /** @test */
    public function a_thread_has_replies()
    {
        //first way
        //$thread = factory('App\Thread')->create();
        $this->assertInstanceOf('Illuminate\Database\Eloquent\Collection', $this->thread->replies); // hasMany(Reply::class);
        //$this->assertInstanceOf('App\Thread', $thread->replies);
    }

    /** @test */
    public function a_thread_has_a_creator()
    {
        //first way
        //$thread = factory('App\Thread')->create();
        $this->assertInstanceOf('App\User', $this->thread->creator); // belongsTo(User::class, 'user_id')
    }

    /** @test */
    public function a_thread_can_add_a_reply()
    {
        $this->thread->addReply([
            'body' => 'Foobar',
            'user_id' => 1
        ]);

        // addReply($reply)
        // $this->replies()->create($reply);

        $this->assertCount(1, $this->thread->replies);
    }

    /** @test */
    public function a_thread_belongs_to_a_channel()
    {
        $thread = factory('App\Thread')->create();

        $this->assertInstanceOf('App\Channel', $thread->channel);
    }

    /** @test */
    function a_thread_can_be_subscribed_to()
    {
        $thread = create('App\Thread');

        //$this->signIn(); 

        //$user->subscribeToThread();
        //$user->subscriptions;
        
        $thread->subscribe($userId = 1);

        $this->assertEquals(
            1, 
            $thread->subscriptions()->where('user_id', $userId)->count()
        );
    }

    /** @test */
    function a_thread_can_be_unsubscribed_from()
    {
        $thread = create('App\Thread');

        $thread->subscribe($userId = 1);

        $thread->unsubscribe($userId);

        $this->assertCount(0, $thread->subscriptions);
    }

    /** @test */
    function it_knows_if_the_authenticated_user_is_subscribed_to_it()
    {
        
        $thread = create('App\Thread');

        $this->signIn();

        $this->assertFalse($thread->isSubscribedTo);

        $thread->subscribe();

        $this->assertTrue($thread->isSubscribedTo);

    }
}
