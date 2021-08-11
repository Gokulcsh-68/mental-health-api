<?php

namespace App\Entities;

use App\Entities\Hospital;
use App\Services\CureselectApis\PeripheralApiService;
use DB;

class Patient extends BaseModel
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
        "user_id", "hospital_id", "additional_info","group_id"
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

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function hospital()
    {
        return $this->belongsTo(Hospital::class,'hospital_id','id');
    }


    protected function createModel($request)
    {
        $data = $this->getModelAttributes($request);

        DB::beginTransaction();
        try {
            
            $data['user']['role_id'] = Role::where("code", $data['user']['role'])
                                            ->pluck('id')->first();
            $user = User::create($data['user']);

            if(!$request->get('hospital_id')){
                $data['hospital_id']  = $request->user()->staff->hospital_id;
            }

           

            $data['user_id']    = $user->id;

            // Peripheral User Creation
            if(isset($data['peripheral_credentials']) && 
                !empty($data['peripheral_credentials']['username']) && 
                !empty($data['peripheral_credentials']['password'])
            ) {
                $peripheral_username = $data['peripheral_credentials']['username'];
                $peripheral_password = $data['peripheral_credentials']['password'];
                $peripheral_user_data = [
                    "username" => $peripheral_username,
                    "password" => $peripheral_password,
                    "ref_number" => $user->id,
                ];
                (new PeripheralApiService)->create($peripheral_user_data);
            }

            if($request->user()->role->code == 'hospitalgroup'){
                $data['group_id'] = $request->user()->staff->group_id;
            }
            else{
                $data['group_id'] = Hospital::Where('id',$request->user()->staff->hospital_id)->value('group_id');
            }
            $model = $this->create($data);
            DB::commit();
            return $model;
        } catch (Exception $e) {
            exceptionLogger("patients Create Rollback", $e);
            DB::rollback();
        }

        return null;
    }

  
    protected function updateModel($id, $request, $only = [])
    {
        $data = $this->getModelAttributes($request, $only);

        DB::beginTransaction();
        try {
            // Nothing can update now on patients table

            $model = parent::updateModel($id, $request, $only);
            $patient = Patient::find($id);

            if(!empty($data['user']['role'])){
                $data['user']['role_id'] = Role::where("code", $data['user']['role'])->pluck('id')->first();
            }else{
                unset($data['user']['role_id']);
            }

            // Peripheral User Creation / Updating
            if(isset($data['peripheral_credentials']) && 
                !empty($data['peripheral_credentials']['username']) && 
                !empty($data['peripheral_credentials']['password'])
            ) {
                $peripheralApiService = new PeripheralApiService();
                $peripheral_username = $data['peripheral_credentials']['username'];
                $peripheral_password = $data['peripheral_credentials']['password'];
                $peripheral_user_data = [
                    "username" => $peripheral_username,
                    "password" => $peripheral_password,
                    "ref_number" => $patient->user->id,
                ];

                $peripheral_credentials = $peripheralApiService->get($patient->user_id);
                if(isset($peripheral_credentials['id'])) {                    
                    $peripheralApiService->patch($peripheral_credentials['id'], $peripheral_user_data);
                } else {
                    $peripheralApiService->create($peripheral_user_data);
                }
            }
            
            
            $patient->user->fill($data['user'])->save();
            DB::commit();

            return $patient;
        } catch (Exception $e) {
            exceptionLogger("patient Update Rollback", $e);
            DB::rollback();
        }

        return null;
    }


     public function applyFilters($model, $isPluck)
    {
        $model = parent::applyFilters($model, $isPluck);
        $request = app('request');

        if ($request->get('staff')->hospital_id) {
            $model->where('patients.hospital_id', $request->get('staff')->hospital_id);
        }
     
        if ($request->get('staff')->group_id) { 
            $model->where('patients.group_id', $request->get('staff')->group_id);
        } 

        $role_code = Role::where('id', $request->user()->role_id)->value('code');

        

        $status_key = $request->get('searchkey');
        if(strtolower($request->get('searchkey')) == "inactive" || strtolower($request->get('searchkey')) == "active"){
            $status_key = (strtolower($request->get('searchkey')) == "inactive")?"0":"1";
        }

        if(strtolower($request->get('status')) == "inactive" || strtolower($request->get('status')) == "active"){
            $status = (strtolower($request->get('status')) == "inactive")?"0":"1";
            $model->whereHas('user', function ($subquery) use ($request, $status) {
                    $subquery->Where('users.is_active', 'LIKE',"%".$status."%");
            });
        }


        if ($request->get('searchkey')) {
            $model->whereHas('user', function ($subquery) use ($request, $status_key) {
                    $subquery->Where('users.email', 'LIKE',"%".$request->get('searchkey')."%")
                    ->orWhere('users.mobile', 'LIKE',"%".$request->get('searchkey')."%")
                    ->orWhere(DB::raw("CONCAT(`first_name`, ' ', `last_name`)"), 'LIKE',"%".$request->get('searchkey')."%")
                    ->orWhere('users.address', 'LIKE',"%".$request->get('searchkey')."%")
                    ->orWhere('users.gender', 'LIKE',"%".$request->get('searchkey')."%")
                    ->orWhere('users.is_active', 'LIKE',"%".$status_key."%");
            });
        }

        return $model;
    }
}
