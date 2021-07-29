<?php

namespace App\Entities;
use DB;

class Camera extends BaseModel
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
        "hospital_id", "camera_name", "camera_ip", "camera_type"
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        
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

        if($request->user()->role->code == 'hospital'){
            $data['hospital_id'] = $request->user()->staff->hospital_id;
        }

            $model = $this->Create($data);


            DB::commit();

            return $model;
        } catch (Exception $e) {
            exceptionLogger("Camera Create Rollback", $e);
            DB::rollback();
        }

        return null;
    }


    public function applyFilters($model, $isPluck)
    {
        $model = parent::applyFilters($model, $isPluck);
        $request = app('request');
    

        if($request->user()->role->code == 'hospital'){

            $model->where('hospital_id', $request->user()->staff->hospital_id);
        }

        return $model;
    }

}
