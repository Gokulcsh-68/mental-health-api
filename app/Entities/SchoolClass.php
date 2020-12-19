<?php

namespace App\Entities;

use DB;

class SchoolClass extends BaseModel
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
        "name", "school_id", "staff_id",
    ];

    public function school()
    {
        return $this->belongsTo(School::class);
    }

    public function staff()
    {
        return $this->hasOne(Staff::class, 'id', 'staff_id');
    }

    protected function createModel($request)
    {
        $data = $this->getModelAttributes($request);

        DB::beginTransaction();
        try {

            $data['school_id'] = $request->user()->staff->school_id;

            $model = $this->create($data);
            DB::commit();
            return $model;
        } catch (Exception $e) {
            exceptionLogger("School Class Create Rollback", $e);
            DB::rollback();
        }

        return null;
    }

    // @Overwrite
    protected function updateModel($id, $request, $only = [])
    {
        $data = $this->getModelAttributes($request, $only);
        DB::beginTransaction();
        try {
            $logged_in_staff_detail = $request->attributes->get('staff');

            if ($request->staff_id) {
                $staff = Staff::where('id', $request->staff_id)
                    ->where('school_id', $logged_in_staff_detail->school_id)
                    ->first();

                if ($staff) {
                    $model = parent::updateModel($id, $request, $only);
                    DB::commit();
                    return $model;
                } else {
                    // Redirect to Error
                }
            }

        } catch (Exception $e) {
            exceptionLogger("School Class Update Rollback", $e);
            DB::rollback();
        }

        return null;
    }

    public function applyFilters($model, $isPluck)
    {
        $model = parent::applyFilters($model, $isPluck);
        $request = app('request');

        if ($request->get('staff')->school_id) {
            $model->where('school_classes.school_id', $request->get('staff')->school_id);
        }

        return $model;
    }
}
