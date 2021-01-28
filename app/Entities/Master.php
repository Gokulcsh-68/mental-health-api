<?php

namespace App\Entities;

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

    public $timestamps = false;

    public function applyFilters($model, $isPluck)
    {
        $model = parent::applyFilters($model, $isPluck);
        $request = app('request');

        if ($request->get('slug')) {
            $model->where('masters.master_type_slug', $request->get('slug'));
        }
        
        if ($request->get('searchkey')) {
            $model->where('masters.name', 'LIKE',"%".$request->get('searchkey')."%");
        }

        // dd($model->toSql());

        return $model;
    }
}
