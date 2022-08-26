<?php

namespace App\Entities;
use DB;

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

        if($request->get('type')){
            $model->where('type', $request->get('type'));
        }

        if ($request->get('searchkey')) {

            $model->where(function($query) use ($request) {
                $query->Where(DB::raw("CONCAT(`name`, ' ', `dosage`)"), 'LIKE', $request->get('searchkey')."%")
                        ->orWhere('generic_name', 'LIKE', $request->get('searchkey')."%");                
            });

        }
        
        return $model;
    }
}
