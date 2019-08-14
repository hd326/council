<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Channel extends Model
{

    protected $guarded = [];

    protected $casts = [
        'archived' => 'boolean'
    ];

    protected static function boot()
    {
        parent::boot();
        static::addGlobalScope('active', function ($builder) {
            $builder->where('archived', false);
        });
        static::addGlobalScope('sorted', function ($builder) {
            $builder->orderBy('name', 'asc');
        });
    }

    public function getRouteKeyName()
    {
        //return parent::getRouteKeyName(); //defaults to primary key
        return 'slug';
    }
    
    public function archive()
    {
        $this->update(['archived' => true]);
    }

    public function threads()
    {
        return $this->hasMany(Thread::class);
    }

    public function setNameAttribute($name)
    {
        $this->attributes['name'] = $name;
        $this->attributes['slug'] = str_slug($name);
    }

    public static function withArchived()
    {
        return (new static)->newQueryWithoutScope('active');
    }
}
