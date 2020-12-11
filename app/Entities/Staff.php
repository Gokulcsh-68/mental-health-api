<?php

namespace App\Entities;
use DB;
use Auth;

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
        "user_id", "school_id", "is_admin"
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

     protected function createModel($request)
    {
        $data = $this->getModelAttributes($request);

        DB::beginTransaction();
        try {

            $logged_in_user = Auth::user();

            $user = $this->user()->create($data['user']);
            $data['user_id']    = $user->id;
            $data['school_id']  = $logged_in_user->id;
            $model = $this->create($data);
            
            DB::commit();
            return $model;
        } catch(Exception $e) {
            exceptionLogger("staffs Create Rollback", $e);
            DB::rollback();
        }
        
        return null;
    }
}
