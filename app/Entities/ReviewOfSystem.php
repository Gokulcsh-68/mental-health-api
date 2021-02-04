<?php

namespace App\Entities;
use Illuminate\Support\Facades\DB;

class ReviewOfSystem extends BaseModel
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
        "patient_id", "consult_id", "slug", "status", "values"
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
         'values' => 'array'
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

        DB::beginTransaction();
        try {

            $getData = $this->Where('slug',$data['slug'])
                            ->Where('patient_id',$data['patient_id'])
                            ->value('values');

            if($getData == null){ $getData = []; }


            if($data['status'] == 'normal'){

                if (($key = array_search($data['values'], $getData)) !== false) {
                   
                    unset($getData[$key]);
                }
            }

            if($data['status'] == 'abnormal'){
                $getData[] = $data['values'];
            }

            $data['values'] = $getData;



            $matchThese = ['slug' => $data['slug'],'patient_id' => $data['patient_id']];
            $model = $this->create($matchThese, $data);

            DB::commit();

            return $model;
        } catch (Exception $e) {
            exceptionLogger("ROS Create Rollback", $e);
            DB::rollback();
        }

        return null;
    }



    protected function  updateModel($id, $request, $only = []) {
    
        $data = $this->getModelAttributes($request);

        DB::beginTransaction();

        if($data['status'] == 'normal'){
            $data['values'] = [];
        }

        unset($request['patient_id']);
        
        $instance = $this->getModel($id);
        $instance->fill($data);
        $instance->save(['touch' => false]);
        return $instance;

        return null;
    }




    public function applyFilters($model, $isPluck)
    {
        $model = parent::applyFilters($model, $isPluck);
        $request = app('request');

        if($request->get('patient_id')){
            $model->where('patient_id', $request->get('patient_id'));
        }

        if($request->get('filter_slug')){
            $model->where('slug', $request->get('filter_slug'));
        }

        return $model;
    }
}
