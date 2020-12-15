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
        return $this->hasOne(Staff::class);
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
            exceptionLogger("staffs Create Rollback", $e);
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
            $model = parent::updateModel($id, $request, $only);
            $model->user->fill($data)
                ->save(['touch' => false]);

            DB::commit();

            return $model;
        } catch (Exception $e) {
            exceptionLogger("Provider Update Rollback", $e);
            DB::rollback();
        }

        return null;
    }
}
