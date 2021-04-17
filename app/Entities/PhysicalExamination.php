<?php

namespace App\Entities;

class PhysicalExamination extends BaseModel
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
        "consult_id", "name", "patient_id", "slug", "status", "values"
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
         'values' => 'object'
        
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

    public function scopeOpen($query)
    {
        return $query->where('freeze', 0);
    }

    protected function createModel($request){
        $data = $this->getModelAttributes($request);

        if($data['status'] == 'normal'){
            $data['values'] = (object) [];
        }
        
        return $this->create($data);
    }

    protected function updateModel($id, $request, $only = []){
        $data = $this->getModelAttributes($request);

        if($data['status'] == 'normal'){
            $data['values'] = (object) [];
        }

        unset($request['patient_id']);
        unset($request['slug']);
        unset($request['name']);

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
            $model->where('patient_id', $request->get('user_id'));
        }

        if($request->get('filter_slug')){
            $model->where('slug', $request->get('filter_slug'));
        }

        if($request->get('from') && $request->get('to')){
            $from   = date('Y-m-d',strtotime($request->get('from')));
            $to     = date('Y-m-d',strtotime($request->get('to')));

            $model->whereBetween('created_at', [$from,$to]);            
        }

        return $model;
    }
}
