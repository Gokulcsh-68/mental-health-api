<?php

namespace App\Entities;

// use App\Entities\Autocomplete\BloodPressure;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class CustomMaster extends BaseModel
{
    const VIEW   = true;
    const CREATE = true;
    const UPDATE = true;
    const ACTION = true;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        "provider_id", "master_type_slug", "name", "slug", "attributes", "is_active"
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'attributes' => 'object'
    ];


    public $timestamps = false;

    // protected function modelResponse($model): array
    // {
    //     return ["id" => $model->getKey(),"provider_id" => $model->provider_id];
    // }

    
   
    public function applyFilters($model, $isPluck)
    {
        $model = parent::applyFilters($model, $isPluck);
        $request = app('request');

        $provider_details = Provider::where('user_id', (!empty($request->get('provider_user_id')) ?  $request->get('provider_user_id') : app('request')->user()->id) )->first();

        if(!empty($provider_details)){
            $load_provider_id = (!empty($provider_details['primary_provider_id']) ? $provider_details['primary_provider_id'] : $provider_details['id']);
            $model->where('custom_masters.provider_id', $load_provider_id);
        }

        if ($request->get('slug')) {
            $model->where('custom_masters.master_type_slug', $request->get('slug'));
        }

        return $model;

    }


}
