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

    public function scopeOpen($query)
    {
        return $query->where('freeze', 0);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    protected function createModel($request)
    {
        $data = $this->getModelAttributes($request);

        DB::beginTransaction();
        try {

            if(isset($data['addition_info']['scan_centre_id'])) {
                $addition_info = $data['addition_info'];
                $addition_info['scan_status'] = 'Not Uploaded';
                $data['addition_info'] = $addition_info;
            }

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

        $forms_expect = ['imaging','lab','notes','icd','chief-complaints','health-insurance'];


        if($request->get('user_id')){
            $model->where('user_id', $request->get('user_id'));
        }

        if($request->user()){
            if($request->user()->role->code == 'scancentre'){
                $model->whereIn('docs.document_source', ['imaging','lab']);
                $model->whereJsonContains('docs.addition_info', ['scan_centre_id'=>$request->user()->id]);
            }
        }

        if ($request->get('slug') != 'chief-complaints') {
            $model->whereNotIn('docs.document_source', ['chief-complaints']);
        }

        if ($request->get('slug')) {

            // if(in_array($request->get('slug'), $forms_expect)) {
            //     $model->where('docs.document_source', $request->get('slug'));
            // }

            if($request->get('slug') != 'documents') {
                $model->where('docs.document_source', $request->get('slug'));
            }
        }

        
        if ($request->get('consult_id') || $request->get('consult_id') == '-1') {
            $model->where('docs.consult_id', $request->get('consult_id') == '-1'? null: $request->get('consult_id'));
        }


        if($request->get('from') && $request->get('to')){
            $from   = date('Y-m-d',strtotime($request->get('from')));
            $to     = date('Y-m-d',strtotime($request->get('to')));
            $model->whereBetween('docs.addition_info->date', [$from,$to]);
        }


        if($request->get('scanOrdersOnly') == 'true') {
            $model->whereNotNull('docs.addition_info->scan_centre_id');
        }

        if ($request->get('searchkey')) {
            
            if ($request->get('slug')) {
                if($request->get('searchkey') != 'All' && $request->get('slug') == 'documents'){
                    $model->where('docs.document_source',$request->get('searchkey'));
                }


                // if (in_array($request->get('slug'), $forms_expect)) {
                if ($request->get('slug') != 'documents') {

                    $model->where(function ($query) use ($request) {
                        $searchKey = strtolower($request->get('searchkey'));
                        $query->whereRaw("LOWER(JSON_UNQUOTE(JSON_EXTRACT(addition_info, '$.notes'))) LIKE ?", ["%{$searchKey}%"])
                              ->orWhereRaw("LOWER(JSON_UNQUOTE(JSON_EXTRACT(addition_info, '$.title'))) LIKE ?", ["%{$searchKey}%"])
                              ->orWhereRaw("LOWER(JSON_UNQUOTE(JSON_EXTRACT(addition_info, '$.insurance_company'))) LIKE ?", ["%{$searchKey}%"])
                              ->orWhereRaw("LOWER(JSON_UNQUOTE(JSON_EXTRACT(addition_info, '$.policy_number'))) LIKE ?", ["%{$searchKey}%"])
                              ->orWhereRaw("LOWER(JSON_UNQUOTE(JSON_EXTRACT(addition_info, '$.insured_amount'))) LIKE ?", ["%{$searchKey}%"]);
                    });

                    // $model->where(function ($query) use ($request) {
                    //         $query->Where('addition_info->notes', 'LIKE',"%".$request->get('searchkey')."%")
                    //         ->orWhere('addition_info->title', 'LIKE',"%".$request->get('searchkey')."%")
                    //         ->orWhere('addition_info->insurance_company', 'LIKE',"%".$request->get('searchkey')."%")
                    //         ->orWhere('addition_info->policy_number', 'LIKE',"%".$request->get('searchkey')."%")
                    //         ->orWhere('addition_info->insured_amount', 'LIKE',"%".$request->get('searchkey')."%");
                    //     });
                }
            }

            if($request->user()){
                if($request->user()->role->code == 'scancentre'){

                $model->where(function($query) use ($request) {


                    $query->where(function ($squery) use ($request) {
                            $squery->Where('addition_info->notes', 'LIKE',"%".$request->get('searchkey')."%")
                            ->orWhere('addition_info->title', 'LIKE',"%".$request->get('searchkey')."%");
                    });

                    $query->orwhereHas('user', function ($subquery) use ($request) {
                        $subquery->Where('users.email', 'LIKE',"%".$request->get('searchkey')."%")
                        ->orWhere('users.mobile', 'LIKE',"%".$request->get('searchkey')."%")
                        ->orWhere(DB::raw("CONCAT(`first_name`, ' ', `last_name`)"), 'LIKE',"%".$request->get('searchkey')."%")
                        ->orWhere('users.address', 'LIKE',"%".$request->get('searchkey')."%")
                        ->orWhere('users.gender', 'LIKE',"%".$request->get('searchkey')."%");
                    });

                });
                }
            }
        }


        if ($request->get('scanstatus')) {

            if ($request->get('scanstatus') != 'All') {
                $model->Where('addition_info->scan_status', $request->get('scanstatus'));
            }
        }

        if(!empty($request->get('onlyDocs'))){
            $model->whereNotNull('properties')         // Equivalent to "properties IS NOT NULL"
            ->where('properties', '!=', '')            // Equivalent to "properties != ''"
            ->whereRaw('JSON_LENGTH(properties) > 0');  // Equivalent to "JSON_LENGTH(properties) > 0"

            $model->whereRaw("JSON_UNQUOTE(JSON_EXTRACT(addition_info, '$.notes')) NOT IN (?, ?)", [
                    'lead1 - NSR, lead2 - SinTachy + IVCD, ',
                    'lead1 - NSR, '
            ]);
        }
        return $model;
    }
}
