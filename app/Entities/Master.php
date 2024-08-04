<?php

namespace App\Entities;

use App\Entities\Provider;
use App\Entities\ProviderSpeciality;
use App\Services\MasterService;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

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

    // Commented below line because of the detail function is not found in the master entity.
    // And its showing error when pull the patient basic info function
    // protected $appends = ['details'];

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


        if ($request->get('attr_slug')) {
            $model->where('masters.attributes->reference_slug', $request->get('attr_slug'));
        }

        if ($request->get('slug')) {

            if($request->get('slug') == 'allergy'){
                if($request->get('country') == 'IN'){
                    $model->whereIn('masters.master_type_slug', ['Generic',$request->get('slug')]);
                }
                else if($request->get('country') == 'US'){
                    $model->whereIn('masters.master_type_slug', ['medicine-us',$request->get('slug')]);
                }
                else{

                    $model->where('masters.master_type_slug', $request->get('slug'));
                }
            }else if($request->get('slug') == 'speciality'){

                if($request->get('speciality_type') == 'all'){
                    $model->where('masters.master_type_slug', $request->get('slug'));
                }else{
                    if($request->user()){
                        if($request->user()->role->code == 'provider'){
                            $speciality = $request->user()->provider->providerSpeciality->pluck('speciality');
                            $model->whereIn('masters.slug', $speciality);
                        }else if($request->user()->role->code == 'hospital'){
                            $speciality = $request->user()->staff->hospital->hospitalSpeciality->pluck('speciality');
                            $model->whereIn('masters.slug', $speciality);
                        }else{
                             $model->where('masters.master_type_slug', $request->get('slug'));
                        }

                    }
                    else{
                         $model->where('masters.master_type_slug', $request->get('slug'));
                    }
                }
            }else{
                $model->where('masters.master_type_slug', $request->get('slug'));
            }


            // Forms Access
            if($request->get('slug') == 'assessment-group'){
                $logged_in_role_code = Role::where('id', $request->user()->role_id)->value('code');

                $form_user_id   = User::where('id',$request->get('patient_id'))->value('role_id');
                $form_role_code = Role::where('id',$form_user_id)->value('code');

                $form_slug = '';

                 // ['apgar', 'adhd', 'healthy-heart', 'psychiatric-exam', 'stroke-scale'];
                if($logged_in_role_code == 'folio'){
                $form_slug = ['healthy-heart', 'psychiatric-exam', 'covid'];

                }else{
                $form_slug = ['healthy-heart', 'psychiatric-exam', 'stroke-scale', 'covid', 'vision', 'apgar'];

                }

                if(!empty($form_slug)){
                    $model->whereIn('masters.slug', $form_slug);
                }

            }

            if($request->get('slug') == 'vdx'){
                $model->where('masters.slug', '!=','vdx_duplicate_status');
            }


            // if($request->get('slug') == 'vdx'){
            //     if($request->user()->role->code == 'provider' || $request->get('provider_id')){

            //         if($request->get('provider_id')){
            //             $providerId = Provider::where('user_id',$request->get('provider_id'))->value('id');
            //         }else{
            //             $providerId = $request->user()->provider->id;

            //         }

            //         $providerSpeciality = ProviderSpeciality::where('provider_id',$providerId)->pluck('speciality');

            //         $model->where(function ($subquery) use ($request,$providerSpeciality) {
            //             foreach ($providerSpeciality as $key => $value) {
            //                 $subquery->orwhereJsonContains('attributes->speciality',$value);
            //             }
            //         });

            //     }
            // }

        }


        if ($request->get('searchkey')) {

            $model->whereRaw("concat(slug, ' ', name) like '%" . $request->get('searchkey') . "%' ");

            /* $exp_val = explode(" ", $request->get('searchkey'));

            $model->where(function ($subquery) use ($request,$exp_val) {
                foreach ($exp_val as $key => $value) {
                    $subquery->Where('masters.name', 'LIKE', $value."%")
                        ->orWhere('masters.slug', 'LIKE', $value."%");
                }
                        // $subquery->Where('masters.name', 'LIKE',"%".$request->get('searchkey')."%")
                        // ->orWhere('masters.slug', 'LIKE',"%".$request->get('searchkey')."%");
                }); */
        }


        return $model;
    }

    public function getResult($slug, $type, $attributes)
    {
        $request    = app('request');


        if($type == 'immunisation'){
            if($request->get('patient_id')){
                $user_dob = User::Where('id',$request->get('patient_id'))->value('dob');
            }else{
                $user_dob = auth()->user()->dob;
            }
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

                                $dosage_date = Carbon::parse($dosage_one_date)->addDays(56);

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
        }else if($type == 'vdx_sub_types' || $type == 'symptoms_reason_sub_types'){

            if(!empty($request->get('with_values'))) {

                $attributes->value_lists = $this->where('attributes->reference_slug',$slug)->get();

            }

            if(!empty($request->get('patient_id'))) {

                 $user_values = PatientHealth::where('patient_id',$request->get('patient_id'))->where('slug',$slug)->value('values');

                 $user_values = (array) $user_values;

                 $attributes->folio_values = [];
                foreach ($user_values as $key => $value) {
                    if($value == true){
                        $attributes->folio_values[] = $this->where('slug',$key)->value('name');
                    }

                }

            }
        }else if($type == 'symptoms_reason'){

            if(!empty($request->get('patient_id'))) { 

                 $user_values = PatientHealth::where('patient_id',$request->get('patient_id'))->where('slug',$slug)->value('values');

                 $user_values = (array) $user_values;

                 $attributes->folio_values = [];
                foreach ($user_values as $key => $value) {
                    if($value == true){
                        $attributes->folio_values[] = $this->where('slug',$key)->value('name');
                    }
                   
                }
                
            }
        }
        else if(
            $type == 'systemic-examination' || 
            $type == 'jayam-systemic-examination' || 
            $type == 'jayam-nursing-examination' ||
            $type == 'jayam-continuous-examination'
            ) {

            if(!empty($request->get('patient_id')) && $request->get('getValues') == 'yes') { 

            $from  = $request->get('from')? date('Y-m-d',strtotime($request->get('from'))): date('Y-m-d');
            $to    = $request->get('to')? date('Y-m-d',strtotime($request->get('to'))): date('Y-m-d');

                 $user_values = PatientHealth::where('patient_id',$request->get('patient_id'))
                 ->whereBetween('values->date', [$from, $to])->where('slug',$slug)->value('values');

                 $user_values = (array) $user_values;

                if($attributes == ''){
                    $attributes= (object) $attributes;
                }

                 $attributes->folio_values = [];
                foreach ($user_values as $key => $value) {
                    if($value === true){
                        $attributes->folio_values[] = $this->where('slug',$key)->value('name');
                    }
                    if($key == 'others'){
                        foreach ($value as $ko => $vo) {
                            $attributes->folio_values[] = $vo;
                        }
                   }
                }

                
            }
        }

        return $attributes;
    }

}
