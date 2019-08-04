<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\DatabaseMigrations;

class ParticipateInForumTest extends TestCase
{

    use DatabaseMigrations;

    /** @test */
    public function unauthenticated_users_may_not_add_replies()
    {
        //$this->expectException('Illuminate\Auth\AuthenticationException');
        $this->withExceptionHandling()->
        post('/threads/some-channel/1/replies', [])->
        assertRedirect('/login');

        // awesome
    }
    

    /** @test */
    public function an_authenticated_user_may_participate_in_forum_threads()
    {
        // Given we have auth user
        //$user = factory('App\User')->create();
        // Sets currently logged in user for the application
        //$this->be($user);
        // inline method
        // $user = factory('App\User')->create();
        // $this->be($user = factory('App\User')->create());
        $this->signIn();

        // And an existing thread
        $thread = factory('App\Thread')->create();
        // When user adds reply to thread
        //$reply = factory('App\Reply')->create(); //to persist
        $reply = factory('App\Reply')->make();

        $this->post($thread->path() . '/replies', $reply->toArray());

        // Then reply should be included in the page
        // dd($thread->path().'/replies');
        // this is done with VueJS now
        // $this->get($thread->path())
        //     ->assertSee($reply->body);
        $this->assertDatabaseHas('replies', ['body' => $reply->body]);
        $this->assertEquals(1, $thread->fresh()->replies_count);
    }

    /** @test */

    function a_reply_requires_a_body()
    {
        $this->withExceptionHandling()->signIn();

        $thread = factory('App\Thread')->create();

        $reply = factory('App\Reply', ['body' => null])->make();

        $this->post($thread->path() . '/replies', $reply->toArray())
            ->assertSessionHasErrors('body');
    }

    /** @test */

    function authorized_users_can_delete_replies()
    {
        $this->signIn();
        $reply = create('App\Reply', ['user_id' => auth()->id()]);
        $this->delete("/replies/{$reply->id}")->assertStatus(302);
        $this->assertDatabaseMissing('replies', ['id' => $reply->id]);
        $this->assertEquals(0, $reply->thread->fresh()->replies_count);
    }

    /** @test */
    function unauthorized_users_cannot_update_replies()
    {
        $this->withExceptionHandling();

        $reply = create('App\Reply');

        $this->patch("/replies/{$reply->id}")
            ->assertRedirect('login');

        $this->signIn()
            ->patch("/replies/{$reply->id}")
            ->assertStatus(403);
    }

    /** @test */

    function authorized_users_can_update_replies()
    {
        $this->signIn();
        $reply = create('App\Reply', ['user_id' => auth()->id()]);
        $this->patch("/replies/{$reply->id}", ['body' => 'You been changed, fool.']);
        $this->assertDatabaseHas('replies', ['id' => $reply->id, 'body' => 'You been changed, fool.']);
    }
}