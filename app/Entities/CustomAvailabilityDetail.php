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
        "provider_id", "from_date", "to_date", "timing"
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

    protected function createModel($request) {
        $data = $this->getModelAttributes($request);

        DB::beginTransaction();
        try {

            // $data['from_date']  = Carbon::parse($data['from_date'])->toDate();
            // $data['to_date']    = Carbon::parse($data['to_date'])->toDate();
            $data['timing']    = json_encode($data['timing']);

            $model = $this->create($data);
            DB::commit();

            return $model;
        } catch(Exception $e) {
            exceptionLogger("Custom Availability Detail Create Rollback", $e);
            DB::rollback();
        }
        
        return null;
    }

    protected function updateModel($id, $request, $only = []) {

        $data = $this->getModelAttributes($request);

        DB::beginTransaction();
        try {
            $model = parent::updateModel($id, $request, $only);
            DB::commit();
            return $model;
        } catch(Exception $e) {
            exceptionLogger("Custom Availability Detail Update Rollback", $e);
            DB::rollback();
        }

        return null;
    }

    protected function customAvailabilityCheck($request) {
        $data = $this->getModelAttributes($request);
        $responseData = [
            'status' => true,
            'error_type' => '',
        ];

        $data['from_date']  = Carbon::parse($data['from_date'])->toDateString();
        $data['to_date']    = Carbon::parse($data['to_date'])->toDateString();

        $details = CustomAvailabilityDetail::where('provider_id','=', $data['provider_id'])
                            ->where('from_date','<=', $data['from_date'])
                            ->where('to_date','>=', $data['to_date'])
                            ->get()->count();

        if ($details >= 1) {
            $responseData = [
                'status'     => false,
                'error_type' => 'Already',
            ];
        }

        
        return $responseData;
    }

    protected function editCustomAvailabilityCheck($request) {
        $data = $this->getModelAttributes($request);
        $responseData = [
            'status' => true,
            'error_type' => '',
        ];

        $authorized = CustomAvailabilityDetail::where('provider_id','=', $data['provider_id'])
                            ->where('id','=', $request->id)
                            ->get()->count();


        if ($authorized == 1) {
            $data['from_date']  = Carbon::parse($data['from_date'])->toDateString();
            $data['to_date']    = Carbon::parse($data['to_date'])->toDateString();
            
            $details = CustomAvailabilityDetail::where('provider_id','=', $data['provider_id'])
                            ->where('id','!=', $request->id)
                            ->where('from_date','<=', $data['from_date'])
                            ->where('to_date','>=', $data['to_date'])
                            ->get()->count();
            if ($details >= 1) {
                $responseData = [
                    'status'     => false,
                    'error_type' => 'Already',
                ];
            }
        } else {
            $responseData = [
                'status'     => false,
                'error_type' => 'Unauthorized',
            ];
        }   
        return $responseData;
    }
}