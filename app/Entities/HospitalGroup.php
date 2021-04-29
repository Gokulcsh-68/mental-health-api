<?php

namespace App\Entities;

use Illuminate\Support\Facades\DB;

class HospitalGroup extends BaseModel
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
        "name", "reg_no", "additional_info"
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


    public function staff()
    {
        return $this->hasMany(Staff::class,'group_id','id');
    }

    public function primaryStaff()
    {
        return $this->hasOne(Staff::class,'group_id','id')->admin();
    }

     protected function createModel($request)
    {
        $data = $this->getModelAttributes($request);
        DB::beginTransaction();
        try {

            $hospital = $this->create($data);

            $data['user']['role_id'] = Role::where("code", $data['user']['role'])->pluck('id')->first();

            $user = User::create($data['user']);


            $staff = [
                'group_id' => $hospital->id,
                'user_id' => $user->id,
                'is_admin' => 1,
            ];

            $staff = $user->staff()->create($staff);

            DB::commit();

            return $hospital;

        } catch (Exception $e) {
            exceptionLogger("hospital Create Rollback", $e);
            DB::rollback();
        }

        return null;
    }

    protected function updateModel($id, $request, $only = [])
    {
        $data = $this->getModelAttributes($request, $only);

        DB::beginTransaction();
        try {
            // Update in hospital
            $model = parent::updateModel($id, $request, $only);
            // Update in users
            $staff = $this->getModel($id);
            // Enforce user role not be update
            unset($data['user']['role_id']);
            $staff->primaryStaff->user->fill($data['user'])->save();
            DB::commit();

            return $model;
        } catch (Exception $e) {
            exceptionLogger("hospital Update Rollback", $e);
            DB::rollback();
        }

        return null;
    }

    public function getOrderByDir(): string
    {
        return app('request')->get('dir') == 1 ? 'asc' : 'desc';
    }

    public function applyFilters($model, $isPluck)
    {
        $model = parent::applyFilters($model, $isPluck);
        $request = app('request');
       
        $status_key = $request->get('searchkey');
        if(strtolower($request->get('searchkey')) == "inactive" || strtolower($request->get('searchkey')) == "active"){
        $status_key = (strtolower($request->get('searchkey')) == "inactive")?"2":"1";

        }

        if ($request->get('searchkey')) {
            $model->where(function ($mquery) use ($request,$status_key) {
                $mquery->where(function ($query) use ($request) {
                        $query->Where('hospital_groups.name', 'LIKE',"%".$request->get('searchkey')."%")
                        ->orWhere('hospital_groups.additional_info', 'LIKE',"%".$request->get('searchkey')."%");
                    });
                $mquery->orwhereHas('primaryStaff', function ($query) use ($request,$status_key) {
                        $query->whereHas('user', function ($subquery) use ($request,$status_key) {
                            $subquery->Where('users.email', 'LIKE',"%".$request->get('searchkey')."%")
                            ->orWhere('users.mobile', 'LIKE',"%".$request->get('searchkey')."%")
                            ->orWhere(DB::raw("CONCAT(`first_name`, ' ', `last_name`)"), 'LIKE',"%".$request->get('searchkey')."%")
                            ->orWhere('users.is_active', 'LIKE',"%".$status_key."%");
                    });
                });
            });
        }
 

        return $model;
    }
}
