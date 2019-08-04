<?php

/*namespace App;

use Redis;

class Visits
{
    protected $thread;

    public function __construct($thread)
    {
        $this->thread = $thread;
    }

    public function record()
    {
        Redis::incr($this->cacheKey());
        return $this;
    }

    // was visits before refactor
    public function count()
    {
        return Redis::get($this->cacheKey()) ?? 0;
    }

    protected function cacheKey()
    {
        return "threads.{$this->thread->id}.visits";
    }
    // add resetting for testing
}
*/