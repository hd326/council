<?php

namespace App;

trait RecordsActivity
{
    protected static function bootRecordsActivity()
    {
        if(auth()->guest()) return;
        // this removes integrity constraints
        foreach(static::getActivitiesToRecord() as $event){
        static::created(function ($model) use ($event){
            $model->recordActivity($event);
        });

        static::deleting(function ($model) {
            $model->activity()->delete();
            //why did I think that this would delete threads/replies...
            //thread uses this trait, so when it's being deleted, it's activity will be deleted
            //looks complicated but it's difficult to imagine
        });

        //whenever something is created in this Model/Controller - we want to record it in Activities
    }
    }

    protected static function getActivitiesToRecord()
    {
        return ['created'];
    }

    protected function recordActivity($event){
        $this->activity()->create([
            'user_id' => auth()->id(),
            //'type' => 'created_thread',
            //'type' => $this . '_' . strtolower(class_basename($this)),
            'type' => $this->getActivityType($event),
            //'subject_id' => $this->id,
            //'subject_type' => 'App\Thread'
            //'subject_type' => get_class($this)
        ]);
    }

    public function activity()
    {
        return $this->morphMany('App\Activity', 'activitiable');
    }

    protected function getActivityType($event)
    {
        $type = strtolower(class_basename($this));

        return "{$event}_{$type}"; 
    }
}