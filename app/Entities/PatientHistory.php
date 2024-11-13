<?php

namespace App\Entities;

class PatientHistory extends BaseModel
{
    const VIEW   = true;
    const CREATE = true;
    const UPDATE = true;
    const ACTION = true;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        "patient_id", "consult_id", "slug", "values"
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'values' => 'object'
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

    protected function createModel($request){
        $data = $this->getModelAttributes($request);

            if($request->get('additional')){
                $values = $data['values'];
                unset($data['values']);
                $data['values']['relationship'] = $values;
                $data['values']['additional'] = $data['additional'];
            }

            if(isset($data['values']['date'])){
                $data['values']['date'] = date('Y-m-d',strtotime($data['values']['date']));

            }

            if(isset($data['values']['surgery_date'])){
                $data['values']['surgery_date'] = date('Y-m-d',strtotime($data['values']['surgery_date']));

            }

            if($data['slug'] == 'student-history'){
                $matchThese = ['slug'=>$data['slug'],'patient_id'=>$data['patient_id']];
                $model = $this->updateOrCreate($matchThese,$data);
            }
            else{
                $model = $this->create($data);
            }

        return $model;
    }

    protected function updateModel($id, $request, $only = []){
        $data = $this->getModelAttributes($request);

        if(isset($data['values']['date'])){
            $data['values']['date'] = date('Y-m-d',strtotime($data['values']['date']));

        }
        if(isset($data['values']['surgery_date'])){
            $data['values']['surgery_date'] = date('Y-m-d',strtotime($data['values']['surgery_date']));

        }

        unset($request['patient_id']);

        $instance = $this->getModel($id);
        $instance->fill($data);
        $instance->save(['touch' => false]);
        return $instance;
    }

    public function applyFilters($model, $isPluck){
        $model = parent::applyFilters($model, $isPluck);
        $request = app('request');

        if ($request->get('slug')) {
            $model->where('patient_histories.slug', $request->get('slug'));
        }

         if ($request->get('consult_id') || $request->get('consult_id') == '-1') {
            $model->where('patient_histories.consult_id', $request->get('consult_id') == '-1'? null: $request->get('consult_id'));
        }


        if($request->get('user_id')){
            $model->where('patient_id', $request->get('user_id'));
        }

        if ($request->get('slug')) {
            $model->where('patient_histories.slug', $request->get('slug'));
        }

        if($request->get('from') && $request->get('to')){
            $from   = date('Y-m-d',strtotime($request->get('from')));
            $to     = date('Y-m-d',strtotime($request->get('to')));

            if($request->get('slug') == 'surgical-history'){
                $model->whereBetween('values->surgery_date', [$from,$to]);
            }else if($request->get('slug') == 'medical-history'){
                $model->whereBetween('values->onset_date', [$from,$to]);
            }else if($request->get('slug') == 'obstetric-history'){

                $model->where(function ($query) use ($from,$to) {
                    $query->orWhereBetween('created_at', [$from,$to])
                    ->orWhereBetween('values->lmp', [$from,$to]);
                });

            }else if($request->get('slug') == 'menstrual-history'){
                $model->whereBetween('values->lmp', [$from,$to]);
            }else if($request->get('slug') == 'screening-diagnostic-history'){
                $model->where(function ($query) use ($from,$to) {
                    $query->orWhereBetween('values->last_pap_smear_at', [$from,$to])
                    ->orWhereBetween('values->last_mamogram_at', [$from,$to])
                    ->orWhereBetween('values->last_breast_exam_at', [$from,$to]);
                });
            }
            else{
                $model->whereBetween('values->date', [$from,$to]);
            }

        }

        if ($request->get('searchkey')) {
            // Allergy
            if($request->get('slug') == 'medical-health'){

                $status_key = $request->get('searchkey');
                if(strtolower($request->get('searchkey')) == "inactive"
                    || strtolower($request->get('searchkey')) == "active"){
                    $status_key = (strtolower($request->get('searchkey')) == "inactive")?"0":"1";
                }

                $model->where(function ($query) use ($request,$status_key) {
                    $query->orWhere('values->name', 'LIKE',"%".$request->get('searchkey')."%")
                    ->orWhere('values->type', 'LIKE',"%".$request->get('searchkey')."%")
                    ->orWhere('values->category', 'LIKE',"%".$request->get('searchkey')."%")
                    ->orWhere('values->reaction', 'LIKE',"%".$request->get('searchkey')."%")
                    ->orWhere('values->severity', 'LIKE',"%".$request->get('searchkey')."%")
                    ->orWhere('values->is_active', 'LIKE',"%".$status_key."%");
                });

                // $model->Where('values->name', 'LIKE',"%".$request->get('searchkey')."%")
                //     ->orWhere('values->type', 'LIKE',"%".$request->get('searchkey')."%")
                //     ->orWhere('values->category', 'LIKE',"%".$request->get('searchkey')."%")
                //     ->orWhere('values->reaction', 'LIKE',"%".$request->get('searchkey')."%")
                //     ->orWhere('values->severity', 'LIKE',"%".$request->get('searchkey')."%")
                //     ->orWhere('values->is_active', 'LIKE',"%".$status_key."%");
            }

            if($request->get('slug') == 'obstetric-history'){

                $model->where(function ($query) use ($request) {
                    $query->orWhere('values->boh_desc', 'LIKE',"%".$request->get('searchkey')."%")
                    ->orWhere('values->fertility_treatment_desc', 'LIKE',"%".$request->get('searchkey')."%");
                });

            }
        }

        return $model;
    }

}
