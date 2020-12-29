<?php

namespace App\Entities;
use DB;
use Carbon\Carbon;
use App\Entities\Provider;

class AvailabilityDetail extends BaseModel
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
        "provider_id", "day", "timing", "created_by",
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
        $responseData = $this->slotArray($request, $data);

        DB::beginTransaction();
        try {
            
            $model = Provider::find($request->provider_id);

            if (!empty($responseData)) {
                $model->availabilityDetail()->createMany($responseData);
            }
            DB::commit();

            return $model;

        } catch(Exception $e) {
            exceptionLogger("Availability Detail Create Rollback", $e);
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
            exceptionLogger("Availability Detail Update Rollback", $e);
            DB::rollback();
        }

        return null;
    }

    protected function editAvailabilityCheck($request) {

        $data = $this->getModelAttributes($request);
        $responseData = [
            'status' => true,
            'error_type' => '',
        ];

        $authorized = AvailabilityDetail::where('provider_id','=', $data['provider_id'])
                                ->where('id','=', $request->id)
                                ->get()->count();


        if ($authorized == 1) {
            $details = AvailabilityDetail::where('provider_id','=', $data['provider_id'])
                                ->where('id','!=', $request->id)
                                ->where('day','=', $data['day'])
                                ->get()->count();
            if ($details == 1) {
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

    protected function availabilityCheck($request) {

        $data = $this->getModelAttributes($request);
        $responseData = [
            'status' => true,
            'error_type' => '',
        ];

        foreach ($data['availabilityDetails'] as $key => $value) {
            $details = AvailabilityDetail::where('provider_id','=', $data['provider_id'])
                            ->where('day','=', $value['day'])
                            ->get()->count();
            if ($details >= 1) {
                return $responseData = [
                    'status'     => false,
                    'error_type' => 'Already',
                ];
            }
        }
        return $responseData;
    }

    public function modelCreateProcess($request): array
    {
        $model = $this->createModel($request);

        $result['success'] = false;
        $result['data'] = [];

        if ($model) {
            $result['success'] = true;
            $result['data'] = $model->availabilityDetail->toArray();
        }

        return $result;
    }

    protected function slotArray($request) {

        $data = $this->getModelAttributes($request);

        $createdBy = $request->user()->id;
        if (!empty($data['provider_id'])) {
            $data['provider_id']    = $data['provider_id'];
        }else{
            $data['provider_id'] = $request->user()->provider->id;
            $createdBy = $request->user()->provider->user_id;
        }

        $slotArray = [];
        foreach ($data['availabilityDetails'] as $key => $value) {
            $value['provider_id'] = $data['provider_id'];
            $value['created_by'] = $createdBy;
            $value['timing'] = json_encode($value['timing']);
            $slotArray[] = $value;
        }

        return $slotArray;
    }

    public function applyFilters($model, $isPluck) {
        $model = parent::applyFilters($model, $isPluck);
        $request = app('request');

        if ($request->get("provider_id")) {
            $model = $model->where("provider_id", $request->get("provider_id"));
        }
        
        return $model;
    }
}
