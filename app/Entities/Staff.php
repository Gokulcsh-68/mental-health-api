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

    public function scopeAdmin($query)
    {
        return $query->where('is_admin', 1);
    }

    protected function createModel($request)
    {
        $data = $this->getModelAttributes($request);

        DB::beginTransaction();
        try {

            // Take role_id
            $data['user']['role_id'] = Role::where("code", $data['user']['role'])->pluck('id')->first();

            $user = User::create($data['user']);

            $staff = [
                'school_id' => $request->get('staff')->school_id,
                'user_id'   => $user->id,
                'is_admin'  => 0,
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

    protected function updateModel($id, $request, $only = [])
    {
        $data = $this->getModelAttributes($request, $only);

        DB::beginTransaction();
        try {
            // Nothing can update now on staffs table
            $staff = Staff::find($id);

            /*if (empty(app('request')->attributes->get('staff')->is_admin)) {
            // Staff
            if (app('request')->attributes->get('staff')->user_id != $id) {

            }
            }*/

            if(!empty($data['user']['role'])){
                $data['user']['role_id'] = Role::where("code", $data['user']['role'])->pluck('id')->first();
            }else{
                unset($data['user']['role_id']);
            }
            
            $staff->user->fill($data['user'])->save();
            DB::commit();

            return $staff;
        } catch (Exception $e) {
            exceptionLogger("staff Update Rollback", $e);
            DB::rollback();
        }

        return null;
    }

    public function applyFilters($model, $isPluck)
    {
        $model = parent::applyFilters($model, $isPluck);
        $request = app('request');

        if ($request->get('staff')->school_id) {
            $model->where('staffs.school_id', $request->get('staff')->school_id);
        }

        if($request->get('is_admin')){
            $model->where('staffs.is_admin', 1);
        }
        else{
            $model->where('staffs.id', '!=',$request->get('staff')->id);
        }

        $status_key = $request->get('searchkey');
        if(strtolower($request->get('searchkey')) == "inactive" || strtolower($request->get('searchkey')) == "active"){
        $status_key = (strtolower($request->get('searchkey')) == "inactive")?"2":"1";

        }


        if ($request->get('searchkey')) {

        }

        if ($request->get('searchkey')) {
            $model->whereHas('user', function ($subquery) use ($request,$status_key) {
                    $subquery->Where('users.email', 'LIKE',"%".$request->get('searchkey')."%")
                    ->orWhere('users.mobile', 'LIKE',"%".$request->get('searchkey')."%")
                    ->orWhere(DB::raw("CONCAT(`first_name`, ' ', `last_name`)"), 'LIKE',"%".$request->get('searchkey')."%")
                    // ->orWhere('users.first_name', 'LIKE',"%".$request->get('searchkey')."%")
                    // ->orWhere('users.last_name', 'LIKE',"%".$request->get('searchkey')."%")
                    ->orWhere('users.address', 'LIKE',"%".$request->get('searchkey')."%")
                    ->orWhere('users.gender', 'LIKE',"%".$request->get('searchkey')."%")
                    ->orWhere('users.is_active', 'LIKE',"%".$status_key."%");
            });
        }

        return $model;
    }
}
