<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

trait Favoritable
{

    protected static function bootFavoritable()
    {
        static::deleting(function ($model) {
            $model->favorites->each->delete();
            //$model->favorites->each(function($favorite) {
            //    $favorite->delete();
            //});
        });
    }

    public function favorites()
    {
        return $this->morphMany(Favorite::class, 'favorited');
    }

    public function favorite()
    {
        $attributes = ['user_id' => auth()->id()];

        if (! $this->favorites()->where($attributes)->exists()) {
        // if we don't find any record in the database with existing, then, we create
        Reputation::award(auth()->user(), Reputation::REPLY_FAVORITED);
        return $this->favorites()->create($attributes);
        }
    }

    public function unfavorite()
    {
        $attributes = ['user_id' => auth()->id()];

        
        //$this->favorites()->where($attributes)->get()->each(function ($favorite) {
        //    $favorite->delete();
        //});
        $this->favorites()->where($attributes)->get()->each->delete();

        Reputation::reduce(auth()->user(), Reputation::REPLY_FAVORITED);
    }

    public function isFavorited()
    {
        return !! $this->favorites->where('user_id', auth()->id())->count();
    // this returns a bool I believe
    }

    public function getIsFavoritedAttribute()
    {
        return $this->isFavorited();
    }

    public function getFavoritesCountAttribute()
    {
        return $this->favorites->count();
    }
}