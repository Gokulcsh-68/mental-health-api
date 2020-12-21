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
        "class_id",
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

    public function getschoolclass()
    {
        return $this->hasOne(SchoolClass::class, 'id', 'class_id');
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

            $data['user']['role_id'] = Role::where("code", "student")->pluck('id')->first();

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

    protected function updateModel($id, $request, $only = [])
    {
        $data = $this->getModelAttributes($request, $only);

        DB::beginTransaction();
        try {
            // Find Student
            $student = Student::find($id);
            $logged_in_staff_detail = $request->attributes->get('staff');

            if ($student) {
                if (!empty($request->class_id)) {
                    $find_class = SchoolClass::where('id', $request->class_id)
                        ->where('school_id', $logged_in_staff_detail->school_id)->first();

                    // If Class found on the School
                    if ($find_class) {
                        $model = parent::updateModel($id, $request, $only);
                        unset($data['user']['role_id']);
                        $student->user->fill($data['user'])->save();
                        DB::commit();
                        return $student;
                    }
                }

            } else {
                // Error Message
            }

        } catch (Exception $e) {
            exceptionLogger("School Update Rollback", $e);
            DB::rollback();
        }

        return null;
    }

    public function applyFilters($model, $isPluck)
    {
        $model = parent::applyFilters($model, $isPluck);
        $request = app('request');

        if ($request->get('staff')->school_id) {
            $model->where('students.school_id', $request->get('staff')->school_id);
        }

        return $model;
    }
}
