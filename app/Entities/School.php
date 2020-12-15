<?php

namespace App\Entities;

use DB;

class School extends BaseModel
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
        "reg_no", "name", "user_id", "logo", "additional_info",
    ];

    public function staff()
    {
        return $this->hasMany(Staff::class);
    }

    public function primaryStaff()
    {
        return $this->hasOne(Staff::class)->admin();
    }

    protected function createModel($request)
    {
        $data = $this->getModelAttributes($request);

        DB::beginTransaction();

        try {

            $school = $this->create($data);

            $user = User::create($data['user']);

            $staff = [
                'school_id' => $school->id,
                'is_admin' => 1,
            ];

            $staff = $user->staff()->create($staff);

            DB::commit();

            return $school;

        } catch (Exception $e) {
            exceptionLogger("school Create Rollback", $e);
            DB::rollback();
        }

        return null;
    }

    protected function updateModel($id, $request, $only = [])
    {
        $data = $this->getModelAttributes($request, $only);

        DB::beginTransaction();
        try {
            // Update in School
            $model = parent::updateModel($id, $request, $only);
            // Update in users
            $staff = $this->getModel($id);
            // Enforce user role not be update
            unset($data['user']['role_id']);
            $staff->primaryStaff->user->fill($data['user'])->save();
            DB::commit();

            return $model;
        } catch (Exception $e) {
            exceptionLogger("School Update Rollback", $e);
            DB::rollback();
        }

        return null;
    }
}
