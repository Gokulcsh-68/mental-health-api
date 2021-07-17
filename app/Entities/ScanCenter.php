<?php

namespace App\Entities;


use App\Entities\Timezone;
use Illuminate\Support\Facades\DB;

class ScanCenter extends BaseModel
{

    const VIEW = true;

    const CREATE = true;

    const UPDATE = true;

    const ACTION = true;

    protected $table = "scan_centers";
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        "user_id", "primary_scan_centers_id", "hospital_id", "is_admin", "additional_info"
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        "additional_info"=>"object"
        
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
    

    protected function modelResponse($model): array
    {
        return ["id" => $model->getKey(),"user_id" => $model->user_id];
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
            
        $hospital_id = '';

        if($request->user()){
        
            if($request->user()->role->code == 'hospital'){

                
                    $hospital_id = $request->user()->staff->hospital_id;
                
            }
        }
            // Take role_id
            $data['user']['role_id'] = Role::where("code", $data['user']['role'])->pluck('id')
                                    ->first();

            if($request->get('country_code')){
                $timezone = Timezone::where('country_code', $request->get('country_code'))->first();
                $data['user']['timezone_id'] = $timezone ? $timezone->id : 1;
            }

            $user = User::create($data['user']);

             $is_admin = 0;
            if($request->get('is_admin')){
                $is_admin = 1;
            }

            $primary_scan_centers_id = '';
            if($request->filled('register')) {
               $primary_scan_centers_id = '';
            }else{

                if($request->user()){
            
                    if($request->user()->role->code == 'scancentre'){
                        $primary_scan_centers_id = $request->user() ? $request->user()->id: '';
                    }
                }
            }

            $scan_center = [
                'user_id'   => $user->id,
                'is_admin'  => $is_admin,
                'hospital_id'  => $hospital_id,
                'primary_scan_centers_id'  => $primary_scan_centers_id,
                'additional_info' => $request->get('additional_info')
            ];

            $model = $this->create($scan_center);
            DB::commit();

            
            return $model;
        } catch (Exception $e) {
            exceptionLogger("scan_centers Create Rollback", $e);
            DB::rollback();
        }

        return null;
    }

    protected function updateModel($id, $request, $only = [])
    {
        $data = $this->getModelAttributes($request, $only);

        DB::beginTransaction();
        try {
            // Nothing can update now on scan_centers table

            $model = parent::updateModel($id, $request, $only);
            $scan_center = ScanCenter::find($id);

           

            if(!empty($data['user']['role'])){
                $data['user']['role_id'] = Role::where("code", $data['user']['role'])->pluck('id')->first();
            }else{
                unset($data['user']['role_id']);
            }
            
            
            $scan_center->user->fill($data['user'])->save();
            DB::commit();

           

            return $scan_center;
        } catch (Exception $e) {
            exceptionLogger("scan_center Update Rollback", $e);
            DB::rollback();
        }

        return null;
    }

    public function applyFilters($model, $isPluck)
    {
        $model = parent::applyFilters($model, $isPluck);
        $request = app('request');



        if($request->get('is_admin')){
            $model->where('scan_centers.is_admin', 1);
        }
        else{
            if(!$request->get('type') && $request->user()){
                if($request->user()->role->code == 'scancentre'){
                   

                        $model->where(function ($query) use ($request) {
                            $query->where('primary_scan_centers_id', $request->user()->id);
                        });
                    

                }
            }
        }

        if($request->user()){
        
            if($request->user()->role->code == 'hospital'){

                    $hospital_id = $request->user()->staff->hospital_id;
                

                $model->where('scan_centers.hospital_id', $hospital_id);
            }
        }

        $status_key = 1;
        if (!empty($request->get('activeUsers'))) {
            $status_key = (strtolower($request->get('activeUsers')) == "inactive")?"0":"1";
        }else{
            $status_key = $request->get('searchkey');
            if(strtolower($request->get('searchkey')) == "inactive" || strtolower($request->get('searchkey')) == "active"){
                $status_key = (strtolower($request->get('searchkey')) == "inactive")?"0":"1";
            }
        }
        

        if ($request->get('searchkey')) {
            $model->whereHas('user', function ($subquery) use ($request) {
                    $subquery->Where('users.email', 'LIKE',"%".$request->get('searchkey')."%")
                    ->orWhere('users.mobile', 'LIKE',"%".$request->get('searchkey')."%")
                    ->orWhere(DB::raw("CONCAT(`first_name`, ' ', `last_name`)"), 'LIKE',"%".$request->get('searchkey')."%")
                    // ->orWhere('users.first_name', 'LIKE',"%".$request->get('searchkey')."%")
                    // ->orWhere('users.last_name', 'LIKE',"%".$request->get('searchkey')."%")
                    ->orWhere('users.address', 'LIKE',"%".$request->get('searchkey')."%")
                    ->orWhere('users.gender', 'LIKE',"%".$request->get('searchkey')."%");
            });

            if ($request->get('activeUsers')) {
                $model->whereHas('user', function ($subquery) use ($request, $status_key) {
                    $subquery->Where('users.is_active',$status_key);
                });
            }
        }

        // dd($model->toSql());

        return $model;
    }
}
