<?php

use Faker\Generator as Faker;

/*
|--------------------------------------------------------------------------
| Model Factories
|--------------------------------------------------------------------------
|
| This directory should contain each of the model factory definitions for
| your application. Factories provide a convenient way to generate new
| model instances for testing / seeding your application's database.
|
*/

$factory->define(App\User::class, function (Faker $faker) {
    static $password;

    return [
        'name' => $faker->name,
        'email' => $faker->unique()->safeEmail,
        'password' => $password ?: $password = bcrypt('secret'),
        'remember_token' => str_random(10),
        'confirmed' => true
    ];
});

$factory->state(App\User::class, 'unconfirmed', function() {
    return [
        'confirmed' => false
    ];
});

$factory->state(App\User::class, 'administrator', function() {
    return [
        'name' => 'JohnDoe'
    ];
});


$factory->define(App\Thread::class, function (Faker $faker) {
    $title = $faker->sentence;
    return [
        'user_id' => function() {
            return factory('App\User')->create()->id;
        }, 
        // when we create thread
        // the user will be created, persisted, and associated with that thread
        'channel_id' => function() {
            return factory('App\Channel')->create()->id;
        },
        'title' => $title,
        'body' => $faker->paragraph,
        'visits' => 0,
        'slug' => str_slug($title),
        'locked' => false
    ];
});

$factory->define(App\Reply::class, function (Faker $faker) {
    return [
        'thread_id' => function() {
            return factory('App\Thread')->create()->id;
        },
        'user_id' => function() {
            return factory('App\User')->create()->id;
        },
        // when we create a reply
        // we will also create a thread and associate that thread id with our reply
        // we will also create a user and assocaited that user id with our reply
        'body' => $faker->paragraph
    ];
});


$factory->define(App\Channel::class, function (Faker $faker) {
    $name = $faker->word;
    
    return [
        'name' => $name, // Server Admin, slug to be server?
        'slug' => $name
    ];
});

$factory->define(\Illuminate\Notifications\DatabaseNotification::class, function($faker) {
    return [
        'id' => \Ramsey\Uuid\Uuid::uuid4()->toString(),
        'type' => 'App\Notifications\ThreadWasUpdated',
        'notifiable_id' => function() {
            return auth()->id() ?: factory('App\User')->create()->id;
        },
        'notifiable_type' => 'App\User',
        'data' => ['foo' => 'bar']
    ];
});