<?php

namespace App\Entities;

use App\Services\MasterService;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Carbon\Carbon;

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

    protected $appends = ['details'];

    public function immunisation()
    {
        return $this->belongsTo(Immunisation::class, 'slug', 'slug');
    }

    public function familyHistory()
    {
        return $this->belongsTo(FamilyHistory::class, 'slug', 'slug');
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

            // Forms Access
            if($request->get('slug') == 'assessment-group'){
                $logged_in_role_code = Role::where('id', $request->user()->role_id)->value('code');

                $form_user_id   = User::where('id',$request->get('patient_id'))->value('role_id');
                $form_role_code = Role::where('id',$form_user_id)->value('code');

                $form_slug = '';

                 // ['apgar', 'adhd', 'healthy-heart', 'psychiatric-exam', 'stroke-scale'];
                $form_slug = ['covid','healthy-heart', 'psychiatric-exam', 'stroke-scale'];

                if(!empty($form_slug)){
                    $model->whereIn('masters.slug', $form_slug);
                }

            }
        }
        
        if ($request->get('searchkey')) {

            $exp_val = explode(" ", $request->get('searchkey'));

            $model->where(function ($subquery) use ($request,$exp_val) {
                foreach ($exp_val as $key => $value) {
                    $subquery->Where('masters.name', 'LIKE',"%".$value."%")
                        ->orWhere('masters.slug', 'LIKE',"%".$value."%");
                }
                        // $subquery->Where('masters.name', 'LIKE',"%".$request->get('searchkey')."%")
                        // ->orWhere('masters.slug', 'LIKE',"%".$request->get('searchkey')."%");
                });
        }


        return $model;
    }

    public function getResult($slug, $type, $attributes)
    {      
        $request    = app('request');

        
        if($type == 'immunisation'){
            $user_dob   = auth()->user()->dob;
            $patient_dosages    = [];
            $all_time_dosages   = [];

            $covid_taken = array('covid-rna-pfizer', 'covid-rna-moderna', 'covid-viral-vector-johnson', 
                    'covid-protein-based-novavax', 'covid-viral-vector-oxfordaz', 
                    'covid-viral-vector-sputnikv', 'covid-inactivated-virus-covaxin',
                    'covid-inactivated-virus-coronovac', 'covid-inactivated-virus-sinopharm');


            $all_time_dose_taken = (in_array($slug, $covid_taken) ? 'anytime' : 'periodic');

            foreach ($attributes->values as $key => $value) {

                $patient_dosages = DB::table('immunisations')
                    ->Where('immunisations.patient_id', $request->get('patient_id'))
                    ->Where('immunisations.slug', $slug)
                    ->whereJsonContains('immunisations.details', $value->periods)
                    ->select('freeze','id','details','taken_at')
                    ->first();

                if($all_time_dose_taken == 'periodic'){
                    $period = $value->periods;
                   
                    if($value->periods == 'Birth'){
                        $dosage_date = $user_dob;
                    }else if($period[strlen($period)-1] == 'w'){
                        $time_period_weeks  = explode('-', $value->periods);
                        if(is_array($time_period_weeks)){
                            $find_week = (int) str_replace("w","", $time_period_weeks[0]);
                        }else{
                            $find_week = (int) str_replace("w","",$value->periods);
                        }
                        $dosage_date = Carbon::parse($user_dob)->addWeeks($find_week);
                    }else if($period[strlen($period)-1] == 'm'){
                        $time_period_months  = explode('-', $value->periods);

                        if(is_array($time_period_months)){
                            $find_months = (int) str_replace("m","", $time_period_months[0]);
                        }else{
                            $find_months = (int) str_replace("m","",$value->periods);
                        }
                        $dosage_date = Carbon::parse($user_dob)->addMonths($find_months);
                    }else if($period[strlen($period)-1] == 'y'){
                        $time_period_years  = explode('-', $value->periods);

                        if(is_array($time_period_years)){
                            $find_years = (int) str_replace("m","", $time_period_years[0]);
                        }else{
                            $find_years = (int) str_replace("m","",$value->periods);
                        }

                        $dosage_date = Carbon::parse($user_dob)->addYears($find_years);
                    }
                }else{
                    $all_time_dosages[$slug][$value->periods] = (!empty($patient_dosages->taken_at) ? $patient_dosages->taken_at : null);

                    if($value->periods == 'Any Age (dose 2)'){
                        $dosage_one_date = $all_time_dosages[$slug]["Any Age (dose 1)"];

                        if(!empty($dosage_one_date)){
                            // $dosage_date = (!empty($patient_dosages->taken_at) ? $patient_dosages->taken_at : Carbon::parse($dosage_one_date)->addDays(45));

                            if($slug == 'covid-inactivated-virus-covaxin' || $slug == 'covid-viral-vector-oxfordaz'){

                                $dosage_date = Carbon::parse($dosage_one_date)->addDays(45);

                            }else{

                                $dosage_date = Carbon::parse($dosage_one_date)->addDays(21);
                            }
                        }else{
                            $dosage_date = null;
                        }
                    }else{
                        $dosage_date = (!empty($patient_dosages->taken_at) ? $patient_dosages->taken_at : null);
                        $all_time_dosages[$slug][$value->periods] = $dosage_date;
                    }
                }

                if($patient_dosages == null){ 
                    $dosages_info       = [];
                    $dosages_freeze     = 0;
                    $dosages_id         = null;
                    $dosages_taken_at   = null;
                }else{
                    $dosages_info       = json_decode($patient_dosages->details);
                    $dosages_freeze     = $patient_dosages->freeze;
                    $dosages_id         = $patient_dosages->id;
                    $dosages_taken_at   = $patient_dosages->taken_at;
                }
                    
                $newValue           = $value;
                $newValue->status   = in_array($value->periods, $dosages_info);
              
                $newValue->freeze       = $dosages_freeze;
                $newValue->imm_id       = $dosages_id;
                $newValue->taken_at     = $dosages_taken_at;
                $newValue->dosage_date  = $dosage_date;
            }
        }else if($type == 'family_history_diseases'){
            $patient_family_history = [];

            $patient_family_history = DB::table('family_histories')
                    ->Where('family_histories.patient_id', $request->get('patient_id'))
                    ->Where('family_histories.slug', $slug)
                    ->value('details');
            $patient_family_history = json_decode($patient_family_history);
            // $attributes->results    = $patient_family_history;

            if($patient_family_history == null){ $patient_family_history = []; }

            foreach ($attributes->values as $key => $value) {
                $newValue           = $value;
                $newValue->status   = in_array($value->relationship, $patient_family_history);
            }
        }else if($type == 'ros'){

            $slug_data = DB::table('review_of_systems')
                    ->Where('review_of_systems.patient_id', $request->get('patient_id'))
                    ->Where('review_of_systems.slug', $slug)->first();

            if(empty($attributes)){
                $attributes = new \stdClass();
                $attributes->ros_available = (!empty($slug_data) ? false : true);
            }

            // foreach ($attributes->values as $key => $value) {
            //     dd($value);
            //     $newValue                   = $value;
            //     $newValue->ros_available    = (!empty($slug_data) ? false : true);
            // }
        }else if($type == 'physical-examination'){

            $slug_data = DB::table('physical_examinations')
                    ->Where('physical_examinations.patient_id', $request->get('patient_id'))
                    ->Where('physical_examinations.slug', $slug)->first();

            if(empty($attributes)){
                $attributes = new \stdClass();
                $attributes->pe_available = (!empty($slug_data) ? false : true);
            }
        }

        return $attributes;        
    }

}
