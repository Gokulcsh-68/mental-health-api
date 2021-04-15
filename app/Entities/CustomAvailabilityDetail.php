<?php

namespace App\Entities;
use DB;
use Carbon\Carbon;

class CustomAvailabilityDetail extends BaseModel
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
        "provider_id", "from_date", "timing"
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        "timing"=>"object"
        
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
    
    public function providers()
    {
        return $this->belongsTo(Provider::class);
    }



    public function applyFilters($model, $isPluck)
    {
        $model = parent::applyFilters($model, $isPluck);
        $request = app('request');

        if($request->get('user_id')){
            $model->where('provider_id', $request->get('user_id'));
        }
        else{
            $model->where('provider_id', $request->user()->id);
        }
        
        if($request->get('from') && $request->get('to')){

            $from = date('Y-m-d',strtotime($request->get('from')));
            $to = date('Y-m-d',strtotime($request->get('to')));
            $model->whereBetween('from_date', [$from,$to]);
        }


        return $model;
    }
}