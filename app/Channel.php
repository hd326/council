<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Channel extends Model
{
    public function getRouteKeyName()
    {
        //return parent::getRouteKeyName(); //defaults to primary key
        return 'slug';
    }

    public function threads()
    {
        return $this->hasMany(Thread::class);
    }
}
