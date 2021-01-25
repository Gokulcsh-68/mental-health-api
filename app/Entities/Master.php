<?php

namespace App\Entities;

use App\Services\MasterService;
use Illuminate\Support\Facades\DB;

class Master extends BaseModel
{
    const VIEW = true;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        "master_type_slug", "name", "slug", "attributes", "is_active"
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'attributes' => 'object'
        
    ];

    protected $appends = ['details'];

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


    public function immunisation()
    {
        return $this->belongsTo(Immunisation::class, 'slug', 'slug');
    }

    public function applyFilters($model, $isPluck)
    {
        $model = parent::applyFilters($model, $isPluck);
        $request = app('request');

        if ($request->get('master_type')) {
            $model->where('masters.master_type_slug', $request->get('master_type'));
        }

        if ($request->get('slug')) {
            $model->where('masters.master_type_slug', $request->get('slug'));
        }
        
        if ($request->get('searchkey')) {
            $model->where('masters.name', 'LIKE',"%".$request->get('searchkey')."%");
        }

        // if ($request->get('slug')) {
        //     // dd($this->immunisation());
        //     $model->whereHas('immunisation', function ($subquery) use ($request) {
        //                 $subquery->Where('immunisations.patient_id', 'LIKE',"%".$request->get('patient_id')."%");
        //         });
        // }


        // dd($model->toSql());

        return $model;
    }

    public function getResult($slug, $type, $attributes)
    {
        # code... 
      
        $request = app('request');
        
        if($type == 'immunisation'){
            $patient_dosages = [];

            $patient_dosages = DB::table('immunisations')
                    ->Where('immunisations.patient_id', $request->get('patient_id'))
                    ->Where('immunisations.slug', $slug)
                    ->value('details');
            $patient_dosages = json_decode($patient_dosages);

            if($patient_dosages == null){ $patient_dosages = []; }

            foreach ($attributes->values as $key => $value) {
                $newValue = $value;
                $newValue->status = in_array($value->periods, $patient_dosages);
            }

        }

        return $attributes;

        
    }

}
