<?php

namespace App\Entities;

class AiLog extends BaseModel
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
        "patient_id", "created_by", "data", "status"
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'data' => 'object',
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
        return $this->belongsTo(User::class, 'created_by');
    }

    protected function createModel($request)
    {
        $data = $this->getModelAttributes($request);

        // Add created_by from the authenticated user
        if ($request->user()) {
            $data['created_by'] = $request->user()->id;
        }

        return $this->create($data);
    }
}
