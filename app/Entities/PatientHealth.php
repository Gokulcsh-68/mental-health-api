<?php

namespace App\Entities;
use DB;

class PatientHealth extends BaseModel
{
    const VIEW = true;

    const CREATE = true;

    const UPDATE = true;

    const ACTION = true;

    protected $table = 'patient_health';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        "id", "patient_id", "consult_id", "slug", "values"
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

    public function user()
    {
        return $this->hasOne(User::class, 'id', 'patient_id');
    }

    public function master()
    {
        return $this->belongsTo(Master::class, 'slug', 'slug');
    }

    public function consult()
    {
        return $this->belongsTo(Consult::class, 'consult_id', 'id');
    }

    protected function createModel($request)
    {
        $data = $this->getModelAttributes($request);

        if($data['slug'] == 'allergy'){
            $data['values'] += self::allergy_flag($data['values']);
        }

        if($data['slug'] == 'diet'){

            $dietmaster = $data['values']['nutritian_values'];
            
            foreach ($dietmaster as $key => $value) {
                if(is_numeric($value)){ 
                    $data['values']['nutritian_values'][$key] = round($value * $data['values']['intake'],2);
                 }
                 else{
                    $data['values']['nutritian_values'][$key] = $value;
                }
            }

        }

        if(isset($data['values']['date'])){
            $data['values']['date'] = date('Y-m-d',strtotime($data['values']['date']));
            
        }
        
        if(!empty($data['up_create'])){


            $matchThese = ['slug'=>$data['slug'],'patient_id'=>$data['patient_id']];

            return $this->updateOrCreate($matchThese,$data);

        }else{

            return $this->create($data);

        }
    }

    protected function updateModel($id, $request, $only = []){
        $data = $this->getModelAttributes($request);

        if($data['slug'] == 'allergy'){
            unset($data['values']['severityFlagColor'],$data['values']['range_code']);
            $data['values'] += self::allergy_flag($data['values']);
        }

        if($data['slug'] == 'diet'){

            $dietmaster = $data['values']['nutritian_values'];
            
            foreach ($dietmaster as $key => $value) {
                if(is_numeric($value)){ 
                    $data['values']['nutritian_values'][$key] = round($value * $data['values']['intake'],2);
                 }
                 else{
                    $data['values']['nutritian_values'][$key] = $value;
                }
            }

        }

        if(isset($data['values']['date'])){
            $data['values']['date'] = date('Y-m-d',strtotime($data['values']['date']));

        }
        unset($request['patient_id']);

        $instance = $this->getModel($id);
        $instance->fill($data);
        $instance->save(['touch' => false]);
        return $instance;
    }


    public function scopeOpen($query)
    {
        return $query->where('freeze', 0);
    }

    public function applyFilters($model, $isPluck){
        $model = parent::applyFilters($model, $isPluck);
        $request = app('request');

        if ($request->get('id')) {
            $model->where('patient_health.id', $request->get('id'));
        }

        if ($request->get('slug')) {
            $model->where('patient_health.slug', $request->get('slug'));
        }

        if ($request->get('consult_id') || $request->get('consult_id') == '-1') {
            $model->where('patient_health.consult_id', $request->get('consult_id') == '-1'? null: $request->get('consult_id'));
        }
        
        if($request->get('user_id')){
            $model->where('patient_id', $request->get('user_id'));
        }

         if($request->get('from') && $request->get('to')){
            $from   = date('Y-m-d',strtotime($request->get('from')));
            $to     = date('Y-m-d',strtotime($request->get('to')));

            if ($request->get('slug') == 'medicine') {
                $model->whereBetween('values->start_date', [$from, $to]);
            }
            else if($request->get('slug') == 'prescription') {
                $model->whereBetween('created_at', [$from." 00:00:00", $to." 23:59:59"]);
            }
            else if($request->get('slug') == 'prescription_glasses') {
                $model->whereBetween('created_at', [$from." 00:00:00", $to." 23:59:59"]);
            }
            else{
                $model->whereBetween('values->date', [$from, $to]);
            }
        }


        if ($request->get('searchkey')) {
            // Allergy
            if($request->get('slug') == 'allergy'){

                $status_key = $request->get('searchkey');
                if(strtolower($request->get('searchkey')) == "inactive" 
                    || strtolower($request->get('searchkey')) == "active"){
                    $status_key = (strtolower($request->get('searchkey')) == "inactive")?"0":"1";
                }

                $model->Where('values->name', 'LIKE',"%".$request->get('searchkey')."%")
                    ->orWhere('values->type', 'LIKE',"%".$request->get('searchkey')."%")
                    ->orWhere('values->category', 'LIKE',"%".$request->get('searchkey')."%")
                    ->orWhere('values->reaction', 'LIKE',"%".$request->get('searchkey')."%")
                    ->orWhere('values->severity', 'LIKE',"%".$request->get('searchkey')."%")
                    ->orWhere('values->is_active', 'LIKE',"%".$status_key."%");
            }
            // Diet
            if($request->get('slug') == 'diet' && $request->get('searchkey') != 'all'){
                $model->where('values->category',$request->get('searchkey'));
            }
            // HPI
            if($request->get('slug') == 'hpi' && $request->get('searchkey') != 'all'){
                $model->where('values->severity',$request->get('searchkey'));
            }

            // Medicine
            if($request->get('slug') == 'medicine'){

                $status_key = $request->get('searchkey');
                if(strtolower($request->get('searchkey')) == "inactive" 
                    || strtolower($request->get('searchkey')) == "active"){
                    $status_key = (strtolower($request->get('searchkey')) == "inactive")?"0":"1";
                }

                $model->Where('values->name', 'LIKE',"%".$request->get('searchkey')."%")

                    ->orWhere('values->type', 'LIKE',"%".$request->get('searchkey')."%")
                    ->orWhere('values->is_active', 'LIKE',"%".$status_key."%");

            }
        }

        return $model;
    }


    public static function allergy_flag($current_value){
        $input_data['severityFlagColor']        = 'success';
        $input_data['severity_range_code']      = '#008000';

        switch ($current_value['severity']) {
            case 'Severe':
                $input_data['severityFlagColor']    = 'danger';
                $input_data['severity_range_code']  = '#ff0000';
            break;

            case 'Moderate':
                $input_data['severityFlagColor']    = 'warning';
                $input_data['severity_range_code']  = '#FFA800';
            break;

            case 'Mild':
                $input_data['severityFlagColor']    = 'primary';
                $input_data['severity_range_code']  = '#0000ff';
            break;
            
            default:
                $input_data['severityFlagColor']    = 'success';
                $input_data['severity_range_code']  = '#008000';
            break;
        }
        
        return $input_data;
    }
    
}
