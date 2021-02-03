<?php

namespace App\Entities;

use Illuminate\Support\Facades\DB;

class Doc extends BaseModel
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
        "addition_info", "consult_id", "created_by", "document_source", "properties", "user_id"
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'properties' => 'object',
        'addition_info' => 'object',
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


    protected function createModel($request)
    {
        $data = $this->getModelAttributes($request);

        DB::beginTransaction();
        try {

            $data['created_by'] = $request->user()->id;
            $model = $this->create($data);


            DB::commit();

            return $model;
        } catch (Exception $e) {
            exceptionLogger("Document Create Rollback", $e);
            DB::rollback();
        }

        return null;
    }


    public function applyFilters($model, $isPluck){
        $model = parent::applyFilters($model, $isPluck);
        $request = app('request');


        if($request->get('user_id')){
            $model->where('user_id', $request->get('user_id'));
        }

        if ($request->get('slug')) {

            if ($request->get('slug') == 'imaging' || $request->get('slug') == 'lab') {
                $model->where('docs.document_source', $request->get('slug'));
            }
        }

        if ($request->get('consult_id')) {
            $model->where('docs.consult_id', $request->get('consult_id'));
        }


        if($request->get('from') && $request->get('to')){
            $from   = date('Y-m-d',strtotime($request->get('from')));
            $to     = date('Y-m-d',strtotime($request->get('to')));
            $model->whereBetween('docs.addition_info->date', [$from,$to]);
        }


        if ($request->get('searchkey')) {
            
            if ($request->get('slug')) {
                if($request->get('searchkey') != 'All' && $request->get('slug') == 'documents'){
                    $model->where('docs.document_source',$request->get('searchkey'));
                }


                if ($request->get('slug') == 'imaging' || $request->get('slug') == 'lab') {
                    $model->where(function ($query) use ($request) {
                            $query->Where('addition_info->notes', 'LIKE',"%".$request->get('searchkey')."%")
                            ->orWhere('addition_info->title', 'LIKE',"%".$request->get('searchkey')."%");
                        });
                }
            }
        }

        return $model;
    }
}
