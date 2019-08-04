<?php

namespace App;

trait RecordsActivity2
{
    protected static function bootRecordsActivity2()
    {
        if(auth()->guest()) return;
        static::created(function ($model){
            $model->activity()->create([
                'user_id' => auth()->id(),
                'type' => 'created' . '_' . strtolower(class_basename($model)),
            ]);
        });
    }

    public function activity()
    {
        return $this->morphMany('App\Activity', 'activitiable');
    }

    //protected function getActivityType($event)
    //{
    //    $type = strtolower(class_basename($this));
    //    return "{$event}_{$type}"; 
    //}
}