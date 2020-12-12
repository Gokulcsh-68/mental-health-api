<?php

namespace App\Entities;

use DB;

class Staff extends BaseModel
{

    const VIEW = true;

    const CREATE = true;

    const UPDATE = true;

    const ACTION = true;

    protected $table = 'staffs';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        "user_id", "school_id", "is_admin",
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

    protected function createModel($request)
    {
        $data = $this->getModelAttributes($request);

        DB::beginTransaction();
        try {
            $user = User::create($data['user']);

            $staff = [
                'school_id' => $request->user()->staff->school_id,
                'user_id' => $user->id,
                'is_admin' => 0,
            ];

            $model = $this->create($staff);
            DB::commit();
            return $model;
        } catch (Exception $e) {
            exceptionLogger("staffs Create Rollback", $e);
            DB::rollback();
        }

        return null;
    }
}
