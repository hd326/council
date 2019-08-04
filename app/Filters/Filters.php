<?php

namespace App\Filters;
use Illuminate\Http\Request;

abstract class Filters
{
    protected $request;
    protected $builder;
    protected $filters = [];

    public function __construct(Request $request)
    {
        $this->request = $request;
    }

    public function apply($builder)
    {
        //if (/*$username = request('by')*/$username = $this->request->by)
        //return $this->by($builder, $username);

        //$user = User::where('name', $username)->firstOrFail();
        //return $builder->where('user_id', $user->id);
        $this->builder = $builder; 

        foreach ($this->getFilters() as $filter => $value){
            if (method_exists($this, $filter)) {
                //$this->$filter($this->request->$filter);
                $this->$filter($value);
                //dd($this->$filter($value));
            }
            //if (! $this->hasFilter($filter)) return;
            //$this->$filter($this->request->$filter);
        }
        //if ($this->request->has('by')) {
        //    $this->by($this->request->by);
        //}
        //if (! $username = $this->request->by) return $builder;
        //return $this->by($username);
        return $this->builder;
    }

    //protected function hasFilter($filter): bool
    //{
    //    return $this->request->has($filter);
    //}

    public function getFilters()
    {
        return $this->request->only($this->filters);
    }
}