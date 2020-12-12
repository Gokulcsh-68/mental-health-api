<?php

namespace App\Entities;

use DB;

class Student extends BaseModel
{
    const VIEW = true;

    const CREATE = true;

    const UPDATE = true;

    const ACTION = true;

    public $timestamps = false;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        "user_id", "school_id", "class_id",
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

    public function school()
    {
        return $this->belongsTo(School::class);
    }

    public function schoolclass()
    {
        return $this->belongsTo(SchoolClass::class);
    }

    protected function createModel($request)
    {
        $data = $this->getModelAttributes($request);

        DB::beginTransaction();
        try {

            $user = User::create($data['user']);

            $data['school_id'] = $request->get('staff')->school_id;
            $data['user_id'] = $user->id;

            $model = $this->create($data);
            DB::commit();
            return $model;
        } catch (Exception $e) {
            exceptionLogger("staffs Create Rollback", $e);
            DB::rollback();
        }

        return null;
    }
}
