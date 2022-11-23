<?php

namespace App\Entities\Actions;

trait GetEntity 
{
    /**
     * Model List.
     *  
     * @return collections
    */
    public function getModelList()
    {
        $model = $this;
        return $this->applyFilters($model, app('request')->get('pluck') ? true : false);
    }

    /**
     * Model Filter Apply.
     *  
     * @return collections
    */

    public function applyFilters($model, $isPluck)
    {        
        $model = $this->applyGlobalConditions($model);
        $model = $model->newQuery();

        $request = app('request');

        if ($request->get("id")) {
            $model = $model->where($this->getTable() . "." . $this->getKeyName(), $request->get("id"));
        }

        if ($request->get("order_by")) {
            if(!empty($request->get("dir"))){
                if($request->get("dir") == '1'){
                    $dir = 'asc';
                }else if($request->get("dir") == 'asc' || $request->get("dir") == 'desc'){
                    $dir = $request->get("dir");
                }else{
                    $dir = 'desc';
                }
            }else{
                $dir = $this->getOrderByDir();
            }

            $model = $model->orderBy($this->getTable() . "." . $request->get("order_by"), $dir);
        } else {
            $model = $model->orderBy($this->getTable() . "." . $this->getKeyName(), "desc" );
        }

        if ($request->query('from_date')) {
            $model = $model->createdAt();
        }

        if ($request->query('is_active')) {
            $model = $model->active();
        }

        if ($request->query('updated_at')) {
            $model = $model->where($this->getTable() . '.updated_at', '>=', $request->query('updated_at'));
        }

        if ($isPluck) {
            $model = $model->setEagerLoads([])->select([$this->getTable() . '.id', 'name']);
        } else {
            $model = $this->getAdditionalData($model);
            $model = $this->selectedColumns($model);
        }
       
        return $model;
    }

    public function getAdditionalData($model)
    {

        return $model;
    }

    /**
     * Model Sorting Dir.
     *  
     * @return string
    */

    public function getOrderByDir(): string
    {
        return app('request')->get('dir') == 1 ? 'asc' : 'desc';
    }

    /**
     * Model Data fetch limi.
     *  
     * @return int
    */

    public function getResourceDataFetchLimit(): int
    {
        return app('request')->get('limit') ? app('request')->get('limit') : 20;
    }
    
    public function pagination($model)
    {
        $result['total'] = !$model->isEmpty() ? (int) $model->total() : 0;
        $result['per_page'] = !$model->isEmpty() ? (int) $model->perPage() : 0;
        $result['current_page'] = !$model->isEmpty() ? (int) $model->currentPage() : 0;
        $result['last_page'] = !$model->isEmpty() ? (int) $model->lastPage() : 0;

        return $result;
    }

    public function listDeleteModel()
    {   
        $model = $this->onlyTrashed();

        if (app('request')->get('deleted_at')) {
            $model = $model->where('deleted_at', '>=', app('request')->get('deleted_at'));
            $model = $model->orderBy('deleted_at', $this->getOrderByDir());
        }

        return $model->pluck('id');
    }

    /**
     * Scope a query to only include active list.
     *
     * @param \Illuminate\Database\Eloquent\Builder $query
     * @return \Illuminate\Database\Eloquent\Builder
    */
    public function scopeActive($query)
    {
        return $query->where($this->getTable() . ".is_active", app('request')->get('is_active'));
    }

    /**
     * Scope a query to only include given date.
     *
     * @param \Illuminate\Database\Eloquent\Builder $query
     * @return \Illuminate\Database\Eloquent\Builder
    */
    public function scopeCreatedAt($query)
    {
        $query = $query->where($this->getTable() . "." . $this->getCreatedAtColumn(), '>=', dateTimezoneConversion(app('request')->get('from_date'), config('api.timezone')));

        if (app('request')->get('to_date')) {
            $query = $query->where($this->getTable() . "." . $this->getCreatedAtColumn(), '<=', dateTimezoneConversion(app('request')->get('to_date'), config('api.timezone')));
        }

        return $query;
    }

    public function selectedColumns($model)
    {
        return $model->select($this->selectedColumns);
    }
}
