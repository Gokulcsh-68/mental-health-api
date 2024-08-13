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
        "act_catagory", "act_date", "act_duration", "act_intake", "act_intensity", "act_time", "act_type", "patient_id", "status", "unit", "detail",
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'detail' => 'object'
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
            else if($request->get('act_intake')){

                $act_intake = $request->get('act_intake');
                $actual_value = $request->get('actual_value') ?? false;
                foreach ($act_intake as $key => $value) {
                    $data['act_intake'] = $actual_value ? $value : ($value * 100);
                    $data['act_type'] = $key;
                    $model = $this->create($data);
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


        if($request->get('user_id')){
            $model->where('activity_wellness.patient_id', $request->get('user_id'));
        }
        
        if($request->get('act_catagory')){
            $model->where('activity_wellness.act_catagory', $request->get('act_catagory'));
        }

        if($request->get('act_type')){
            $model->where('activity_wellness.act_type', $request->get('act_type'));
        }

        if($request->get('from') && $request->get('to')){

            $from = date('Y-m-d',strtotime($request->get('from')));
            $to = date('Y-m-d',strtotime($request->get('to')));
            $model->whereBetween('act_date', [$from,$to]);
        }

        if($request->get('chart')){
           
            if(!$request->get('from') || !$request->get('to')){
                $last_date = $this->where('act_catagory',$request->get('act_catagory'))
                ->limit(1)
                ->orderBy('act_date','desc')
                ->value('act_date');
               
                $from = date('Y-m-d', strtotime("-10 days",strtotime($last_date))); // -10  days 
                $to = date('Y-m-d',strtotime($last_date));
                $model->whereBetween('act_date', [$from,$to]);
            }

            if($request->get('act_catagory') == 'mood'){
            $model->select(DB::raw('id,act_catagory,act_date,SUM(act_duration) as act_duration,act_intensity,act_time,act_type,patient_id,status,unit,count(act_type) as act_intake'));

            }
            else{
            $model->select(DB::raw('id,act_catagory,act_date,SUM(act_duration) as act_duration,act_intensity,act_time,act_type,patient_id,status,unit,SUM(act_intake) as act_intake'));

            }
            $model->groupBy('act_type');
        }


        // $model->where('ids','s');
        return $model;
    }

}
