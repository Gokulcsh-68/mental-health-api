<?php

namespace App\Entities;

class Medicine extends BaseModel
{

    const VIEW = true;

    const CREATE = true;

    const UPDATE = true;

    const ACTION = true;

    protected $table = 'medicines';
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        "id", "user_id", "name", "type", "dosage", "generic_name", "attributes"
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        
        "attributes"=>"object"
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

        if($request->get('staff')){

            if ($request->get('staff')->hospital_id) { 
                $model->where('providers.hospital_id', $request->get('staff')->hospital_id);
            }       
            if ($request->get('staff')->group_id) { 
                $model->where('providers.group_id', $request->get('staff')->group_id);
            }   


            if ($request->get('user_id')) { 
                $model->where('providers.user_id', $request->get('user_id'));
            }         
        }
        else{ 
            $model->where('providers.user_id', $request->user()->id);
        }



        if ($request->get('searchkey')) {

            $model->where(function($query) use ($request) {
                $query->Where('name', 'LIKE',"%".$request->get('searchkey')."%")
                        ->orWhere('generic_name', 'LIKE',"%".$request->get('searchkey')."%");                
            });

        }
        
        return $model;
    }
}
