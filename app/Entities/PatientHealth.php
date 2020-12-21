<?php

namespace App\Entities;
use DB;

class PatientHealth extends BaseModel
{
    const VIEW = true;

    const CREATE = true;

    const UPDATE = true;

    const ACTION = true;

    protected $table = 'patient_health';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        "patient_id", "consult_id", "slug", "values"
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
         "values"
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

    public function user()
    {
        return $this->belongsTo(User::class, 'patient_id', 'id');
    }

    public function master()
    {
        return $this->belongsTo(Master::class, 'slug', 'slug');
    }

    public function consult()
    {
        return $this->belongsTo(consult::class, 'consult_id', 'id');
    }

    protected function createModel($request)
    {
        $data = $this->getModelAttributes($request);

        DB::beginTransaction();
        try {
            $model = $this->create($data);
            DB::commit();
            return $model;
        } catch (Exception $e) {
            exceptionLogger("Patient health Create Rollback", $e);
            DB::rollback();
        }

        return null;
    }

    protected function updateModel($id, $request, $only = [])
    {

        $data = $this->getModelAttributes($request);

        DB::beginTransaction();
        try {
             unset($request['patient_id']);
            $model = parent::updateModel($id, $request, $only);
            DB::commit();

            return $model;
        } catch (Exception $e) {
            exceptionLogger("Patient health Update Rollback", $e);
            DB::rollback();
        }

        return null;
    }


    public function applyFilters($model, $isPluck)
    {
        $model = parent::applyFilters($model, $isPluck);
        $request = app('request');

        if ($request->get('slug')) {
            $model->where('patient_health.slug', $request->get('slug'));
        }

        if ($request->get('consult_id')) {
            $model->where('patient_health.consult_id', $request->get('consult_id'));
        }

        if ($request->get('slug')) {
            $model->where('patient_health.slug', $request->get('slug'));
        }

        return $model;
    }
}
