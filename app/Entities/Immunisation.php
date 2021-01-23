<?php

namespace App\Entities;
use DB;

class Immunisation extends BaseModel
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
        "details", "patient_id", "slug"
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'details' => 'array'
        
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
                            ->value('details');

            $getData[] = $data['details'];

            $data['details'] = $getData;

            $matchThese = ['slug'=>$data['slug'],'patient_id'=>$data['patient_id']];

            $model = $this->updateOrCreate($matchThese,$data);


            DB::commit();

            return $model;
        } catch (Exception $e) {
            exceptionLogger("Immunisation Create Rollback", $e);
            DB::rollback();
        }

        return null;
    }



    public function applyFilters($model, $isPluck)
    {
        $model = parent::applyFilters($model, $isPluck);
        $request = app('request');

        if($request->get('patient_id')){
            $model->where('patient_id', $request->get('patient_id'));
        }

        return $model;
    }
}
