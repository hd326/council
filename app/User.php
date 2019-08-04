<?php

namespace App;

use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;

class User extends Authenticatable
{
    use Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'email', 'password', 'avatar_path'
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token', 'email'
    ];

    protected $casts = [
        'confirmed' => 'boolean'
    ];

    public function getRouteKeyName()
    {
        //return parent::getRouteKeyName(); //defaults to primary key
        return 'name';
    }

    public function threads()
    {
        return $this->hasMany(Thread::class)->latest();
    }

    public function activity()
    {
        return $this->hasMany(Activity::class);
    }

    public function confirm()
    {
        $this->confirmed = true;
        $this->confirmation_token = null;
        //for consistency
        $this->save();
    }

    public function isAdmin()
    {
        return in_array($this->name, ['JohnDoe', 'JaneDoe', 'Richard']);
    }

    //public function getAvatarPathAttribute($avatar) //$user->avatar_path
    //{
    //    // #2 return $this->avatar_path ?: 'avatars/default.jpg';
    //    return asset($avatar ?: 'storage/avatars/default.jpg');
    //    //if(! $this->avatar_path) {
    //    //    return 'avatars/default.jpg';
    //    //}
    //    //return $this->avatar_path;
    //}

    public function avatar() //$user->avatar_path
    {
        // #2 return $this->avatar_path ?: 'avatars/default.jpg';
        return $this->avatar_path;
        //if(! $this->avatar_path) {
        //    return 'avatars/default.jpg';
        //}
        //return $this->avatar_path;
    }
}
