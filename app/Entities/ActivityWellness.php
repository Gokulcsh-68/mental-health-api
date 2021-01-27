<?php

namespace App\Entities;

use Illuminate\Support\Facades\DB;

class ActivityWellness extends BaseModel
{
    const VIEW = true;

    const CREATE = true;

    const UPDATE = true;

    const ACTION = true;

    protected $table = "activity_wellness";
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        "act_catagory", "act_date", "act_duration", "act_intake", "act_intensity", "act_time", "act_type", "patient_id", "status", "unit"
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

            // Take role_id
            if($request->get('details')){
                foreach ($request->get('details') as $key => $value) {
                    $model = $this->create($value);
                }

            }
            else{
                $model = $this->create($data);
            }

            
            DB::commit();
            return $model;
        } catch (Exception $e) {
            exceptionLogger("staffs Create Rollback", $e);
            DB::rollback();
        }

        return null;
    }


    public function applyFilters($model, $isPluck)
    {
        $model = parent::applyFilters($model, $isPluck);
        $request = app('request');


        if($request->get('patient_id')){
            $model->where('activity_wellness.patient_id', $request->get('patient_id'));
        }
        
        if($request->get('act_catagory')){
            $model->where('activity_wellness.act_catagory', $request->get('act_catagory'));
        }

        if($request->get('from') && $request->get('to')){

            $from = date('Y-m-d',strtotime($request->get('from')));
                $to = date('Y-m-d',strtotime($request->get('to')));
            $model->whereBetween('act_date', [$from,$to]);
        }




        // $model->where('ids','s');
        return $model;
    }

}
