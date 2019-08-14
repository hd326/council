<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\DatabaseMigrations;

class CreateThreadsTest extends TestCase
{

    use DatabaseMigrations;

    /** @test */
    public function guests_may_not_create_threads()
    {
        //$this->expectException('Illuminate\Auth\AuthenticationException');
        //// this was available thru our models auth()
        //// $thread = factory('App\Thread')->make();
        //$thread = make('App\Thread');
        //$this->post('/threads', $thread->toArray());

        $this->withExceptionHandling();

        $this->get('/threads/create')
        ->assertRedirect('/login');
        
        $this->post('/threads')
        //cannot store
        ->assertRedirect('/login');

    }

    /** @test */

    //public function guests_cannot_see_the_create_thread_page()
    //{
    //    //$this->withExceptionHandling()
    //    //    ->get('/threads/create')
    //    //    ->assertRedirect('/login');
    //}

    /** @test */
    public function an_authenticated_user_can_create_new_forum_threads()
    {
        // Given we have a signed in user
        // $this->actingAs(factory('App\User')->create());
        $this->signIn();
        $thread = make('App\Thread', $overrides);
        // When we hit the endpoint to create a new thread
        return $this->post(route('threads'), $thread->toArray());

        // posting information to this endpoint with the data in store

        // Then, when we visit the thread page
        //$response = $this->get($thread->path());
        //// We should see a new thread
        //$response->assertSee($thread->title)
        //    ->assertSee($thread->body);

        //$this->get($thread->path())
        //    ->assertSee($thread->title)
        //    ->assertSee($thread->body);

        //dd($response->headers->get('Location'));

        $this->get($response->headers->get('Location'))
            ->assertSee($thread->title)
            ->assertSee($thread->body);
    }

    /** @test */
    
    function a_thread_requires_a_title()
    {
        //$this->withExceptionHandling()->signIn();
        //$thread = make('App\Thread', ['title' => null]);
        //$this->post('/threads', $thread->toArray())
        //    ->assertSessionHasErrors('title');
        $this->publishThread(['title' => null])
            ->assertSessionHasErrors('title');
            // it's null here, but we require it, so it will give us an error
    }

    /** @test */
    
    function a_thread_requires_a_body()
    {
        $this->publishThread(['body' => null])
            ->assertSessionHasErrors('body');
            // it's null here, but we require it, so it will give us an error
    }

    /** @test */

    function a_thread_requires_a_valid_channel()
    {
        factory('App\Channel', 2)->create();

        $this->publishThread(['channel_id' => null])
            ->assertSessionHasErrors('channel_id');
            // it's null here, but we require it, so it will give us an error
        $this->publishThread(['channel_id' => 999])
            ->assertSessionHasErrors('channel_id');
    }

    /** @test */
    public function guests_cannot_delete_threads()
    {
        $this->withExceptionHandling();
        $thread = create('App\Thread');
        //$response = $this->json('DELETE', $thread->path());
        $response = $this->delete($thread->path());
        $response->assertRedirect('/login');
    }

    /** @test */
    public function authorized_users_can_delete_threads()
    {
        $this->signIn();

        $thread = create('App\Thread', ['user_id' => auth()->id()]); 
        // just adding the user_id to the auth id to allow users to delete
        // i would have never thought of this
        $reply = create('App\Reply', ['thread_id' => $thread->id]);

        $response = $this->json('DELETE', $thread->path());

        $response->assertStatus(204);

        $this->assertDatabaseMissing('threads', ['id' => $thread->id]);
        $this->assertDatabaseMissing('replies', ['id' => $reply->id]);
        $this->assertDatabaseMissing('activities', [
            'activitiable_id' => $thread->id,
            //'activitiable_type' => get_class($thread)
            'activitiable_type' => 'App\Thread'
            ]);
        $this->assertDatabaseMissing('activities', [
            'activitiable_id' => $reply->id,
            //'activitiable_type' => get_class($reply)
            'activitiable_type' => 'App\Reply'
            ]);
        //$this->assertEquals(0, Activity::count());
    }

    ///** @test */
    //public function threads_may_only_be_deleted_by_those_who_have_permission()
    //{
    //    //
    //}

    // in the video Jeff says this is too much -- we will go with this instead

    /** @test */
    function unauthorized_users_may_not_delete_threads()
    {
        // same as guests cannot delete threads 
        $this->withExceptionHandling();
        $thread = create('App\Thread');
        //$response = $this->json('DELETE', $thread->path());
        $this->delete($thread->path())->assertRedirect('/login');
        $this->signIn();
        $this->delete($thread->path())->assertStatus(403);
    }
    
    public function publishThread($overrides = [])
    {
        $this->withExceptionHandling()->signIn();

        $thread = make('App\Thread', $overrides);

        return $this->post(route('threads'), $thread->toArray());
            //->assertSessionHasErrors('title');
    }
}
