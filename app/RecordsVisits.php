<?php

namespace App;

use Illuminate\Support\Facades\Redis;

trait RecordsVisits
{
    public function recordVisit()
    {
        Redis::incr($this->cacheKey());
        return $this;
    }

    public function visits()
    {
        return Redis::get($this->cacheKey()) ?? 0;
    }

    protected function cacheKey()
    {
        return "threads.{$this->id}.visits";
    }
    // add resetting for testing
}