<?php

namespace App\Entities;
use Illuminate\Support\Facades\DB;

class DynamicForm extends BaseModel
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
        "slug", "attributes"
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'attributes' => 'array'
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


    public function applyFilters($model, $isPluck)
    {
        $model = parent::applyFilters($model, $isPluck);
        $request = app('request');

        if($request->get('slug')){
            $model->where('slug', $request->get('slug'));
        }

        return $model;
    }

    public function getResult($slug, $attributes)
    {      
        $request = app('request');
        
        $patient_forms = [];

        $patient_forms = DB::table('review_of_systems')
                                ->Where('review_of_systems.patient_id', $request->get('patient_id'))
                                ->Where('review_of_systems.slug', $slug)
                                ->value('values');

        $patient_forms = json_decode($patient_forms);

        if($patient_forms == null){ $patient_forms = []; }

        $attributes['status'] = false;

        if(!empty($patient_forms)){
            foreach ($patient_forms as $key => $value) {
                if ( $key == $attributes['data'] ) {
                    $attributes['status'] = true;
                }
            }
        }
        
        // dd($attributes);

        return $attributes;        
    }
}
