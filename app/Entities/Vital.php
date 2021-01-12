<?php

namespace App\Entities;
use DB;

class Vital extends BaseModel
{
    const VIEW = true;

    const CREATE = true;

    const UPDATE = true;

    const ACTION = true;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        "user_id", "consult_id", "peripheral_id", "slug", "details"
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'details' => 'object'
        
    ];

    /**
     * The attributes excluded from the model's JSON form.
     *
     * @var array
     */
    protected $hidden = [
        
    ];

    /**
     * The attributes that should be updated on patch method.
     *
     * @var array
    */
    protected $partialFillable = [
        
    ];

    /**
     * The attributes that should be mutated to dates.
     *
     * @var array
    */
    protected $dates = [
        
    ];

    /**
     * The event map for the model.
     *
     * @var array
    */
    protected $dispatchesEvents = [
        
    ];


    protected function createModel($request)
    {
        $data = $this->getModelAttributes($request);

        if(isset($data['details']['date'])){
            $data['details']['date'] = date('Y-m-d',strtotime($data['details']['date']));
        }

        return $this->create($data);
    }


    protected function updateModel($id, $request, $only = [])
    {
        $data = $this->getModelAttributes($request, $only);

        if(isset($data['details']['date'])){
            $data['details']['date'] = date('Y-m-d',strtotime($data['details']['date']));
        }

        $instance = $this->getModel($id);
        $instance->fill($data);
        $instance->save(['touch' => false]);

        return $instance;
    }

    public function applyFilters($model, $isPluck)
    {
        $model = parent::applyFilters($model, $isPluck);
        $request = app('request');


        if($request->get('user_id')){
            $model->where('vitals.user_id', $request->get('user_id'));
        }
        
        if($request->get('slug')){
            $model->where('vitals.slug', $request->get('slug'));
        }


        if($request->get('from') && $request->get('to')){

            $from = date('Y-m-d',strtotime($request->get('from')));
                $to = date('Y-m-d',strtotime($request->get('to')));
            $model->whereBetween('details->date', [$from,$to]);
        }


        if ($request->get('searchkey')) {

        }
        // $model->where('ids','s');
        return $model;
    }
}
