<?php

namespace App\Entities;

use DB;

class Provider extends BaseModel
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
        "user_id", "school_id", "practicing_since", "license_no", "additional_info",
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'additional_info' => 'object'

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

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function availabilityDetail()
    {
        return $this->hasMany(AvailabilityDetail::class);
    }

    public function providerSpeciality()
    {
        return $this->hasMany(ProviderSpeciality::class);
    }

    protected function createModel($request)
    {
        $data = $this->getModelAttributes($request);

        DB::beginTransaction();
        try {
            // dd($data);
            $data['user']['role_id'] = Role::where("code", $data['user']['role'])->pluck('id')->first();
            $user = $this->user()->create($data['user']);

            $data['school_id'] = $request->get('staff')->school_id;
            $data['user_id'] = $user->id;

            $model = $this->create($data);

            //Provider specialities add
            $provider_speciality = [];
            foreach ($data['provider_speciality'] as $key => $value) {
               $provider_speciality[$key] = ['speciality'=>$value];
            }

            $model->providerSpeciality()->createMany($provider_speciality);

            DB::commit();

            return $model;
        } catch (Exception $e) {
            exceptionLogger("Provider Create Rollback", $e);
            DB::rollback();
        }

        return null;
    }

    public function multipleArraySearch($arrayValue, $exceptList)
    {
        $exceptListKeys = [];
        foreach ($exceptList as $key => $value) {
            $exceptListKeys[] = array_search($value, $arrayValue);
        }
        return $exceptListKeys;
    }

    protected function updateModel($id, $request, $only = [])
    {

        $data = $this->getModelAttributes($request);

        //remove strict fields
        $exceptListKey = $this->multipleArraySearch($this->getFillable(), ['user_id', 'school_id']);
        $only = array_except($this->getFillable(), $exceptListKey);

        DB::beginTransaction();
        try {

            $model = parent::updateModel($id, $request, $only);

            $data['user'] = array_except($data['user'], ['role_id']);
            $model->user->fill($data['user'])
                ->save(['touch' => false]);

            if (!empty($data['provider_speciality'])) {
                //Provider specialities delete and add
              
                $model->providerSpeciality()->delete();
                $provider_speciality = [];
                foreach ($data['provider_speciality'] as $key => $value) {
                   $provider_speciality[$key] = ['speciality'=>$value];
                }
                
                if(!empty($provider_speciality)){
                $model->providerSpeciality()->createMany($provider_speciality);

                }
            }

            DB::commit();

            return $model;
        } catch (Exception $e) {
            exceptionLogger("Provider Update Rollback", $e);
            DB::rollback();
        }

        return null;
    }

    public function applyFilters($model, $isPluck)
    {
        $model = parent::applyFilters($model, $isPluck);
        $request = app('request');

        if ($request->get('staff')->school_id) {
            $model->where('providers.school_id', $request->get('staff')->school_id);
        }

        $status_key = $request->get('searchkey');
        if(strtolower($request->get('searchkey')) == "inactive" || strtolower($request->get('searchkey')) == "active"){
        $status_key = (strtolower($request->get('searchkey')) == "inactive")?"2":"1";

        }


        if ($request->get('searchkey')) {

            $model->where(function($query) use ($request,$status_key) {
                $query->whereHas('user', function ($subquery) use ($request,$status_key) {
                        $subquery->Where('users.email', 'LIKE',"%".$request->get('searchkey')."%")
                        ->orWhere('users.mobile', 'LIKE',"%".$request->get('searchkey')."%")
                        ->orWhere('users.first_name', 'LIKE',"%".$request->get('searchkey')."%")
                        ->orWhere('users.last_name', 'LIKE',"%".$request->get('searchkey')."%")
                        ->orWhere('users.address', 'LIKE',"%".$request->get('searchkey')."%")
                        ->orWhere('users.gender', 'LIKE',"%".$request->get('searchkey')."%")
                        ->orWhere('users.is_active', 'LIKE',"%".$status_key."%");
                });

                $query->orwhereHas('providerSpeciality', function ($subquery) use ($request) {
                        $subquery->Where('provider_specialities.speciality', 'LIKE',"%".$request->get('searchkey')."%");
                });
            });

        }
        
        return $model;
    }

}
