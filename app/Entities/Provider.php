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
        "user_id", "school_id", "practicing_since", "license_no", "additional_info"
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

    public function user()
    {
        return $this->belongsTo(User::class);
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

            $user = $this->user()->create($data['user']);

            $data['school_id'] = $request->get('staff')->school_id;
            $data['user_id'] = $user->id;
            // unset($data['user']);
            // unset($data['provider_speciality']);
            // echo "<pre>"; print_r($data);
            $model = $this->create($data);
            //Provider Specialities add
            $model->providerSpeciality()->createMany($data['provider_speciality']);
            
            DB::commit();

            return $model;
        } catch(Exception $e) {
            exceptionLogger("Provider Create Rollback", $e);
            DB::rollback();
        }
        
        return null;
    }

    public function multipleArraySearch($arrayValue, $exceptList) {
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

            $model->providerSpeciality()->delete();
            $model->providerSpeciality()->createMany($data['provider_speciality']);

            DB::commit();

            return $model;
        } catch(Exception $e) {
            exceptionLogger("Provider Update Rollback", $e);
            DB::rollback();
        }

        return null;
    }

}
