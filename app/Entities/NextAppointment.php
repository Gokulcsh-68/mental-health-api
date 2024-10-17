<?php

namespace App\Entities;

use App\Entities\Patient;

class NextAppointment extends BaseModel
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
        "patient_id", "provider_id", "date", "reason",
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

    public function patient()
    {
        return $this->belongsTo(User::class);
    }

    public function provider()
    {
        return $this->belongsTo(User::class);
    }

    public function applyFilters($model, $isPluck)
    {
        $model = parent::applyFilters($model, $isPluck);
        $request = app('request');

        if ($request->get('patient_id')) {
            $model->Where('patient_id', $request->get('patient_id'));
        }

        if ($request->get('provider_id')) {
            $model->Where('provider_id', $request->get('provider_id'));
        }

        if($request->get('from') && $request->get('to')){
            $from   = date('Y-m-d',strtotime($request->get('from')));
            $to     = date('Y-m-d',strtotime($request->get('to')));

            $model->whereBetween('date', [$from,$to]);

        }

        return $model;
    }
}
