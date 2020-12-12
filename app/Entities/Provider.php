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
        "user_id", "school_id", "practicing_since", "license_no", "specialities", "additional_info"
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
            $data['user_id'] = $user->id;
            $model = $this->create($data);

            //Provider Specialities add
            $model->providerSpeciality()->createMany($data['specialities']);
            
            DB::commit();

            return $model;
        } catch(Exception $e) {
            exceptionLogger("Provider Create Rollback", $e);
            DB::rollback();
        }
        
        return null;
    }

    protected function updateModel($id, $request, $only = [])
    {
        $data = $this->getModelAttributes($request, $only);

        DB::beginTransaction();
        try {
            $model = parent::updateModel($id, $request, $only);
            $model->user->fill($data['user'])
                ->save(['touch' => false]);

            $model->providerSpeciality()->createMany($data['specialities']);
            // $model->providerSpeciality()->updateOrCreate($data['specialities']);

            DB::commit();

            return $model;
        } catch(Exception $e) {
            exceptionLogger("Provider Update Rollback", $e);
            DB::rollback();
        }

        return null;
    }

}
