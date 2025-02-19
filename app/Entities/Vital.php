<?php

namespace App\Entities;
use App\Entities\User;
use App\Services\AuthService;
use App\Services\BluetoothPeripheralService;
use App\Services\CureselectApis\TeleConsultApiService;
use Carbon\Carbon;
use DB;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Log;
use App\Entities\Doc;

class Vital extends BaseModel
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
        "user_id", "consult_id", "peripheral_id", "slug", "details"
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'details' => 'object'

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
        return $this->hasOne(User::class);
    }

    public function scopeOpen($query)
    {
        return $query->where('freeze', 0);
    }

    protected function createModel($request, $data = false)
    {
        if(empty($data)){
            $data = $this->getModelAttributes($request);
        }


        if(!$request->get('consult_id')){
            $Cachevalue = Cache::get($data['user_id']);

            if(!empty($Cachevalue)){
                $data['consult_id'] = $Cachevalue['consult_id'];
                $data['details']['updated_by'] = "user";
            }
        }

        if(isset($data['details']['date'])){
            $data['details']['date'] = date('Y-m-d',strtotime($data['details']['date']));
        }

        if($data['slug'] == 'bmi'){
            $dateOfBirth = User::Where('id', $data['user_id'])->value('dob');

            $years  = Carbon::parse($dateOfBirth)->diff(Carbon::now())->format('%y');
            $months = Carbon::parse($dateOfBirth)->diff(Carbon::now())->format('%m');
            $days   = Carbon::parse($dateOfBirth)->diff(Carbon::now())->format('%d');

            $data['details'] += self::bmi_flag($data['details']['bmi'], $years, $months, $days);
        }

        if($data['slug'] == 'temperature'){
            $dateOfBirth = User::Where('id',$data['user_id'])->first(['dob','gender']);
            $years = Carbon::parse($dateOfBirth->dob)->diff(Carbon::now())->format('%y');
            $data['details'] += self::temp_flag($data['details'], $years, $dateOfBirth->dob);
        }

        if($data['slug'] == 'blood-sugar'){
            $dateOfBirth = User::Where('id', $data['user_id'])->value('dob');

            $years  = Carbon::parse($dateOfBirth)->diff(Carbon::now())->format('%y');
            $months = Carbon::parse($dateOfBirth)->diff(Carbon::now())->format('%m');
            $days   = Carbon::parse($dateOfBirth)->diff(Carbon::now())->format('%d');
            $data['details'] += self::blood_sugar_flag($data['details'], $years, $months, $days);
        }

        if($data['slug'] == 'blood-pressure'){
            $dateOfBirth = User::Where('id', $data['user_id'])->value('dob');

            $years  = Carbon::parse($dateOfBirth)->diff(Carbon::now())->format('%y');
            $months = Carbon::parse($dateOfBirth)->diff(Carbon::now())->format('%m');
            $days   = Carbon::parse($dateOfBirth)->diff(Carbon::now())->format('%d');
            $data['details'] += self::blood_pressure_flag($data['details'], $years, $months, $days);
        }

        if($data['slug'] == 'lipid-profile'){
            $dateOfBirth = User::Where('id', $data['user_id'])->value('dob');

            $years  = Carbon::parse($dateOfBirth)->diff(Carbon::now())->format('%y');
            $months = Carbon::parse($dateOfBirth)->diff(Carbon::now())->format('%m');
            $days   = Carbon::parse($dateOfBirth)->diff(Carbon::now())->format('%d');
            $data['details'] += self::cholesterol_flag($data['details'], $years, $months, $days);
        }

        if($data['slug'] == 'spO2'){
            $dateOfBirth = User::Where('id', $data['user_id'])->value('dob');

            $years  = Carbon::parse($dateOfBirth)->diff(Carbon::now())->format('%y');
            $months = Carbon::parse($dateOfBirth)->diff(Carbon::now())->format('%m');
            $days   = Carbon::parse($dateOfBirth)->diff(Carbon::now())->format('%d');

            $data['details'] += self::spo2_flag($data['details'], $years, $months, $days);
        }

        if($data['slug'] == 'urine'){
            $data['details'] += self::urine_flag($data['details']);
        }

        if($data['slug'] == 'respiration'){
            $dateOfBirth = User::Where('id',$data['user_id'])->first(['dob','gender']);
            $years = Carbon::parse($dateOfBirth->dob)->diff(Carbon::now())->format('%y');
            $data['details'] += self::respiration_flag($data['details'], $years, $dateOfBirth->dob);
        }

        if($data['slug'] == 'heart-rate'){
            $dateOfBirth = User::Where('id', $data['user_id'])->value('dob');

            $years  = Carbon::parse($dateOfBirth)->diff(Carbon::now())->format('%y');
            $months = Carbon::parse($dateOfBirth)->diff(Carbon::now())->format('%m');
            $days   = Carbon::parse($dateOfBirth)->diff(Carbon::now())->format('%d');

           $data['details'] += self::heart_rate_flag($data['details'], $years, $months, $days);

           if(isset($data['image'])){
            $data['details']['doc_id'] = (new BluetoothPeripheralService)->uploadECGFile($data);

            if(!isset($data['details']['device_type'])){
                $data['details']['device_type'] = '1 Lead ECG';
            }
           }

           if(isset($data['create_doc'])){
            $insert_data = array();
            $insert_data['created_by']      = $data['user_id'];
            $insert_data['user_id']         = $data['user_id'];
            $insert_data['document_source'] = 'imaging';

            $insert_data['addition_info']['title'] = 'Heart';
            $insert_data['addition_info']['notes'] = 'Heart';
            $insert_data['addition_info']['device_type'] = $data['details']['device_type'];
            $insert_data['properties']['file_path'] = $data['properties']['file_path'];
            $insert_data['properties']['file_name'] = $data['properties']['file_name'];


            $doc_id = Doc::create($insert_data);
            $data['details']['doc_id'] = $doc_id->id;
           }
        }

        if($data['slug'] == 'ecg'){
            $dateOfBirth = User::Where('id', $data['user_id'])->value('dob');

            $years  = Carbon::parse($dateOfBirth)->diff(Carbon::now())->format('%y');
            $months = Carbon::parse($dateOfBirth)->diff(Carbon::now())->format('%m');
            $days   = Carbon::parse($dateOfBirth)->diff(Carbon::now())->format('%d');

           $data['details'] += self::heart_rate_flag($data['details'], $years, $months, $days);

           if(isset($data['image'])){
            $data['details']['device_type'] = '1 Lead ECG';
            $data['details']['doc_id'] = (new BluetoothPeripheralService)->uploadECGFile($data);
           }
        }


        if($data['slug'] == 'keytone'){
            $data['details'] += self::keytone_flag($data['details']);
        }

        if($data['slug'] == 'hemoglobin'){
            $gender = User::Where('id', $data['user_id'])->value('gender');
            $dateOfBirth = User::Where('id', $data['user_id'])->value('dob');

            $years = Carbon::parse($dateOfBirth)->diff(Carbon::now())->format('%y');
            $months = Carbon::parse($dateOfBirth)->diff(Carbon::now())->format('%m');
            $days   = Carbon::parse($dateOfBirth)->diff(Carbon::now())->format('%d');
            $data['details'] += self::hemoglobin_flag($data['details'],$gender,$years,$months,$days);
        }

        if($data['slug'] == 'hct'){
            $dateOfBirth = User::Where('id', $data['user_id'])->value('dob');

            $years = Carbon::parse($dateOfBirth)->diff(Carbon::now())->format('%y');
            $months = Carbon::parse($dateOfBirth)->diff(Carbon::now())->format('%m');
            $days   = Carbon::parse($dateOfBirth)->diff(Carbon::now())->format('%d');
            $gender = User::Where('id', $data['user_id'])->value('gender');

            $data['details'] += self::hct_flag($data['details'],$years, $months, $days, $gender);
        }

        if($data['slug'] == 'uric_acid'){
            $dateOfBirth = User::Where('id', $data['user_id'])->value('dob');

            $years = Carbon::parse($dateOfBirth)->diff(Carbon::now())->format('%y');
            $months = Carbon::parse($dateOfBirth)->diff(Carbon::now())->format('%m');
            $days   = Carbon::parse($dateOfBirth)->diff(Carbon::now())->format('%d');
            $gender = User::Where('id', $data['user_id'])->value('gender');
            $data['details'] += self::uric_acid_flag($data['details'],$years, $months, $days,$gender);
        }

        if($data['slug'] == 'spirometer'){
            $dateOfBirth = User::Where('id', $data['user_id'])->value('dob');

            $years  = Carbon::parse($dateOfBirth)->diff(Carbon::now())->format('%y');
            $months = Carbon::parse($dateOfBirth)->diff(Carbon::now())->format('%m');
            $days   = Carbon::parse($dateOfBirth)->diff(Carbon::now())->format('%d');
            $gender = User::Where('id', $data['user_id'])->value('gender');
            $data['details'] += self::spirometer_flag($data['details'], $years, $months, $days, $gender);
        }

        if($data['slug'] == 'urea'){
            $dateOfBirth = User::Where('id', $data['user_id'])->value('dob');

            $years  = Carbon::parse($dateOfBirth)->diff(Carbon::now())->format('%y');
            $months = Carbon::parse($dateOfBirth)->diff(Carbon::now())->format('%m');
            $days   = Carbon::parse($dateOfBirth)->diff(Carbon::now())->format('%d');
            $data['details'] += self::urea_flag($data['details'], $years, $months, $days);
        }

        if($data['slug'] == 'creatinine'){
            $dateOfBirth = User::Where('id', $data['user_id'])->value('dob');

            $years = Carbon::parse($dateOfBirth)->diff(Carbon::now())->format('%y');
            $months = Carbon::parse($dateOfBirth)->diff(Carbon::now())->format('%m');
            $days   = Carbon::parse($dateOfBirth)->diff(Carbon::now())->format('%d');
            $gender = User::Where('id', $data['user_id'])->value('gender');

            $data['details'] += self::creatinine_flag($data['details'],$years, $months, $days,$gender);
        }

        if($data['slug'] == 'gfr'){
            $dateOfBirth = User::Where('id', $data['user_id'])->value('dob');

            $years = Carbon::parse($dateOfBirth)->diff(Carbon::now())->format('%y');
            $months = Carbon::parse($dateOfBirth)->diff(Carbon::now())->format('%m');
            $days   = Carbon::parse($dateOfBirth)->diff(Carbon::now())->format('%d');
            $gender = User::Where('id', $data['user_id'])->value('gender');

            $data['details'] += self::gfr_flag($data['details'],$years, $months, $days, $gender);
        }


        return $this->create($data);
    }


    protected function updateModel($id, $request, $only = [])
    {
        $data = $this->getModelAttributes($request, $only);

        if(isset($data['details']['date'])){
            $data['details']['date'] = date('Y-m-d',strtotime($data['details']['date']));
        }

        if($data['slug'] == 'bmi'){
            unset($data['details']['bmiFlag'], $data['details']['bmiFlagColor'], $data['details']['range_code']);

            $dateOfBirth = User::Where('id', $data['user_id'])->value('dob');

            $years  = Carbon::parse($dateOfBirth)->diff(Carbon::now())->format('%y');
            $months = Carbon::parse($dateOfBirth)->diff(Carbon::now())->format('%m');
            $days   = Carbon::parse($dateOfBirth)->diff(Carbon::now())->format('%d');
            $data['details'] += self::bmi_flag($data['details']['bmi'], $years, $months, $days);
        }


        if($data['slug'] == 'temperature'){
            unset($data['details']['temperatureFlag'], $data['details']['temperatureFlagColor'], $data['details']['range_code']);

            $dateOfBirth = User::Where('id',$data['user_id'])->first(['dob','gender']);
            $years = Carbon::parse($dateOfBirth->dob)->diff(Carbon::now())->format('%y');
            $data['details'] += self::temp_flag($data['details'], $years, $dateOfBirth->dob);
        }

        if($data['slug'] == 'blood-sugar'){
            unset($data['details']['bsFlag'], $data['details']['bsFlagColor'], $data['details']['range_code']);

            $dateOfBirth = User::Where('id', $data['user_id'])->value('dob');

            $years  = Carbon::parse($dateOfBirth)->diff(Carbon::now())->format('%y');
            $months = Carbon::parse($dateOfBirth)->diff(Carbon::now())->format('%m');
            $days   = Carbon::parse($dateOfBirth)->diff(Carbon::now())->format('%d');
            $data['details'] += self::blood_sugar_flag($data['details'],$years,$months,$days);
        }


        if($data['slug'] == 'blood-pressure'){
            unset($data['details']['bpFlag'], $data['details']['bpFlagColor'], $data['details']['range_code']);

            $dateOfBirth = User::Where('id', $data['user_id'])->value('dob');

            $years  = Carbon::parse($dateOfBirth)->diff(Carbon::now())->format('%y');
            $months = Carbon::parse($dateOfBirth)->diff(Carbon::now())->format('%m');
            $days   = Carbon::parse($dateOfBirth)->diff(Carbon::now())->format('%d');

            $data['details'] += self::blood_pressure_flag($data['details'],$years,$months,$days);
        }

        if($data['slug'] == 'lipid-profile'){
            unset($data['details']['ldl_message'],
                $data['details']['ldl_message_flag'],
                $data['details']['hdl_message'],
                $data['details']['hdl_message_flag'],
                $data['details']['triglycerides_message'],
                $data['details']['triglycerides_message_flag'],
                $data['details']['hdl_ldl_message'],
                $data['details']['hdl_ldl_message_flag'],
                $data['details']['vldl_message'],
                $data['details']['vldl_message_flag'],
                $data['details']['total_message'],
                $data['details']['total_message_flag'],
                $data['details']['ldl_range_code'],
                $data['details']['total_range_code'],
                $data['details']['vldl_range_code'],
                $data['details']['triglycerides_range_code'],
                $data['details']['hdl_ldl_range_code']);

                $dateOfBirth = User::Where('id', $data['user_id'])->value('dob');

                $years  = Carbon::parse($dateOfBirth)->diff(Carbon::now())->format('%y');
                $months = Carbon::parse($dateOfBirth)->diff(Carbon::now())->format('%m');
                $days   = Carbon::parse($dateOfBirth)->diff(Carbon::now())->format('%d');

            $data['details'] += self::cholesterol_flag($data['details'],$years, $months, $days);
        }

        if($data['slug'] == 'heart-rate'){
            unset($data['details']['heartRateFlag'],
                $data['details']['heartRateFlagColor'],
                $data['details']['range_code']);

            $dateOfBirth = User::Where('id',$data['user_id'])->value('dob');

            $years = Carbon::parse($dateOfBirth)->diff(Carbon::now())->format('%y');
            $months = Carbon::parse($dateOfBirth)->diff(Carbon::now())->format('%m');
            $days = Carbon::parse($dateOfBirth)->diff(Carbon::now())->format('%d');

           $data['details'] += self::heart_rate_flag($data['details'], $years, $months, $days);


           if(isset($data['create_doc'])){
            $insert_data = array();
            $insert_data['created_by']      = $data['user_id'];
            $insert_data['user_id']         = $data['user_id'];
            $insert_data['document_source'] = 'imaging';

            $insert_data['addition_info']['title'] = 'Heart';
            $insert_data['addition_info']['notes'] = 'Heart';
            $insert_data['addition_info']['device_type'] = $data['details']['device_type'];
            $insert_data['properties']['file_path'] = $data['properties']['file_path'];
            $insert_data['properties']['file_name'] = $data['properties']['file_name'];


            $doc_id = Doc::create($insert_data);
            $data['details']['doc_id'] = $doc_id->id;
           }
        }

        if($data['slug'] == 'spO2'){
            unset($data['details']['spo2Flag'],
                $data['details']['spo2FlagColor'],
                $data['details']['range_code']);

                $dateOfBirth = User::Where('id', $data['user_id'])->value('dob');

                $years  = Carbon::parse($dateOfBirth)->diff(Carbon::now())->format('%y');
                $months = Carbon::parse($dateOfBirth)->diff(Carbon::now())->format('%m');
                $days   = Carbon::parse($dateOfBirth)->diff(Carbon::now())->format('%d');
            $data['details'] += self::spo2_flag($data['details'],$years,$months,$days);
        }

        if($data['slug'] == 'respiration'){
            unset($data['details']['respirationFlag'],
                $data['details']['respirationFlagColor'],
                $data['details']['range_code']);

            $dateOfBirth = User::Where('id',$data['user_id'])->first(['dob','gender']);
            $years = Carbon::parse($dateOfBirth->dob)->diff(Carbon::now())->format('%y');
            $data['details'] += self::respiration_flag($data['details'], $years, $dateOfBirth->dob);
        }

        if($data['slug'] == 'urine'){
            unset($data['details']['leukocytes_message'],
                    $data['details']['leukocytes_flag'],
                    $data['details']['leukocytes_range_code'],
                    $data['details']['protein_message'],
                    $data['details']['protein_flag'],
                    $data['details']['protein_range_code'],
                    $data['details']['rbc_message'],
                    $data['details']['rbc_flag'],
                    $data['details']['rbc_range_code'],
                    $data['details']['value_message'],
                    $data['details']['value'],
                    $data['details']['value_flag'],
                    $data['details']['value_range_code'],
                    $data['details']['sugar_message'],
                    $data['details']['sugar_flag'],
                    $data['details']['sugar_range_code'],
                    $data['details']['sugar_message'],
                    $data['details']['sugar_flag'],
                    $data['details']['sugar_range_code']
                );


            $data['details'] += self::urine_flag($data['details']);
        }


        if($data['slug'] == 'keytone'){
            unset($data['details']['keytoneFlag'], $data['details']['keytoneFlagColor'], $data['details']['range_code']);
            $data['details'] += self::keytone_flag($data['details']);
        }

        if($data['slug'] == 'hemoglobin'){
            unset($data['details']['hemoglobinFlag'], $data['details']['hemoglobinFlagColor'], $data['details']['range_code']);
            $gender = User::Where('id', $data['user_id'])->value('gender');
            $dateOfBirth = User::Where('id', $data['user_id'])->value('dob');

            $years = Carbon::parse($dateOfBirth)->diff(Carbon::now())->format('%y');
            $months = Carbon::parse($dateOfBirth)->diff(Carbon::now())->format('%m');
            $days   = Carbon::parse($dateOfBirth)->diff(Carbon::now())->format('%d');
            $data['details'] += self::hemoglobin_flag($data['details'],$gender,$years,$months,$days);
        }

        if($data['slug'] == 'hct'){
            unset($data['details']['hctFlag'], $data['details']['hctFlagColor'], $data['details']['range_code']);
            $gender = User::Where('id', $data['user_id'])->value('gender');
            $dateOfBirth = User::Where('id', $data['user_id'])->value('dob');

            $years = Carbon::parse($dateOfBirth)->diff(Carbon::now())->format('%y');
            $months = Carbon::parse($dateOfBirth)->diff(Carbon::now())->format('%m');
            $days   = Carbon::parse($dateOfBirth)->diff(Carbon::now())->format('%d');
            $data['details'] += self::hct_flag($data['details'],$gender,$years, $months, $days);
        }

        if($data['slug'] == 'uric_acid'){
            unset($data['details']['uricFlag'], $data['details']['uricFlagColor'], $data['details']['range_code']);
            $gender = User::Where('id', $data['user_id'])->value('gender');
            $dateOfBirth = User::Where('id', $data['user_id'])->value('dob');

            $years = Carbon::parse($dateOfBirth)->diff(Carbon::now())->format('%y');
            $months = Carbon::parse($dateOfBirth)->diff(Carbon::now())->format('%m');
            $days   = Carbon::parse($dateOfBirth)->diff(Carbon::now())->format('%d');
            $data['details'] += self::uric_acid_flag($data['details'],$gender,$years,$months,$days);
        }

        if($data['slug'] == 'spirometer'){
            unset($data['details']['spirometerFlag'], $data['details']['spirometerFlagColor'], $data['details']['range_code'], $data['details']['flags']);
            $dateOfBirth = User::Where('id', $data['user_id'])->value('dob');

            $years  = Carbon::parse($dateOfBirth)->diff(Carbon::now())->format('%y');
            $months = Carbon::parse($dateOfBirth)->diff(Carbon::now())->format('%m');
            $days   = Carbon::parse($dateOfBirth)->diff(Carbon::now())->format('%d');
            $gender = User::Where('id', $data['user_id'])->value('gender');
            $data['details'] += self::spirometer_flag($data['details'], $years, $months, $days, $gender);
        }

        if($data['slug'] == 'urea'){
            unset($data['details']['ureaFlag'], $data['details']['ureaFlagColor'], $data['details']['range_code']);
            $dateOfBirth = User::Where('id', $data['user_id'])->value('dob');

            $years  = Carbon::parse($dateOfBirth)->diff(Carbon::now())->format('%y');
            $months = Carbon::parse($dateOfBirth)->diff(Carbon::now())->format('%m');
            $days   = Carbon::parse($dateOfBirth)->diff(Carbon::now())->format('%d');
            $data['details'] += self::urea_flag($data['details'],$years,$months,$days);
        }

        if($data['slug'] == 'creatinine'){
            unset($data['details']['creatinineFlag'], $data['details']['creatinineFlagColor'], $data['details']['range_code']);
            $dateOfBirth = User::Where('id', $data['user_id'])->value('dob');

            $years = Carbon::parse($dateOfBirth)->diff(Carbon::now())->format('%y');
            $months = Carbon::parse($dateOfBirth)->diff(Carbon::now())->format('%m');
            $days   = Carbon::parse($dateOfBirth)->diff(Carbon::now())->format('%d');
            $gender = User::Where('id', $data['user_id'])->value('gender');
            $data['details'] += self::creatinine_flag($data['details'],$years,$months,$days,$gender);
        }

        if($data['slug'] == 'gfr'){
            unset($data['details']['gfrFlag'], $data['details']['gfrFlagColor'], $data['details']['range_code']);

            $dateOfBirth = User::Where('id', $data['user_id'])->value('dob');

            $years = Carbon::parse($dateOfBirth)->diff(Carbon::now())->format('%y');
            $months = Carbon::parse($dateOfBirth)->diff(Carbon::now())->format('%m');
            $days   = Carbon::parse($dateOfBirth)->diff(Carbon::now())->format('%d');
            $gender = User::Where('id', $data['user_id'])->value('gender');
            $data['details'] += self::gfr_flag($data['details'],$years, $months,$days,$gender);
        }

        $instance = $this->getModel($id);
        $instance->fill($data);
        $instance->save(['touch' => false]);

        return $instance;
    }


    public function applyFilters($model, $isPluck)
    {
        $model = parent::applyFilters($model, $isPluck);
        $request = app('request');

        if($request->get('user_id')){
            $model->where('vitals.user_id', $request->get('user_id'));
        }

        if($request->get('slug')){
            $model->where('vitals.slug', $request->get('slug'));
        }

        if($request->get('slug_array')){
            $model->whereIn('vitals.slug', explode(',',$request->get('slug_array')));
        }

        if ($request->get('consult_id') || $request->get('consult_id') == '-1') {
            $model->where('vitals.consult_id', $request->get('consult_id') == '-1'? null: $request->get('consult_id'));
        }

        if($request->get('from') && $request->get('to')){

            $from = date('Y-m-d',strtotime($request->get('from')));
            $to = date('Y-m-d',strtotime($request->get('to')));
            $model->whereBetween('details->date', [$from,$to]);
        }

        if($request->get('from_heart') && $request->get('to_heart')){

            $from_heart = (int) $request->get('from_heart');
            $to_heart = (int) $request->get('to_heart');
            $model->whereBetween('details->heart', [$from_heart, $to_heart]);
        }

        if($request->get('interpretation')){

            $search_interpretation = strtolower(trim($request->get('interpretation')));
            $model->whereRaw('LOWER(`details`->>"$.interpretation") LIKE ?', ["%".$search_interpretation."%"]);
        }

        if($request->get('device_type')){

            $model->Where('details->device_type', 'LIKE',"%".$request->get('device_type')."%");
        }

        if ($request->get('searchkey') && $request->get('searchkey') != 'undefined') {

            $model->Where('details->created_app', 'LIKE',"%".$request->get('searchkey')."%");
        }

        if ($request->get("order_by") == 'details->date') {
            $model = $model->orderBy($this->getTable() . "." . $request->get("order_by"), $this->getOrderByDir());
            $model = $model->orderBy($this->getTable() . ".details->time", $this->getOrderByDir());
        }

        // $model->where('ids','s');
        return $model;
    }


    public static function bmi_flag($bmi_value,  $years, $months, $days)
    {
        $input_data['bmiFlag']      = '';
        $input_data['bmiFlagColor'] = '';
        $input_data['range_code'] = '';

        // this old one run with out year for previous 
        // if (!empty($bmi_value)) {
        //     if ($bmi_value < 18.5) {
        //         $input_data['bmiFlag']      = 'Below normal weight';
        //         $input_data['bmiFlagColor'] = 'primary';
        //         $input_data['range_code']   = '#0000ff';
        //     }
            
        //     if (($bmi_value >= 18.5) && ($bmi_value < 25)) {
        //         $input_data['bmiFlag']      = 'Normal weight';
        //         $input_data['bmiFlagColor'] = 'success';
        //         $input_data['range_code']   = '#008000';
        //     }
            
        //     if (($bmi_value >= 25) && ($bmi_value < 30)) {
        //         $input_data['bmiFlag']      = 'Overweight';
        //         $input_data['bmiFlagColor'] = 'danger';
        //         $input_data['range_code']   = '#ff0000';
        //     }
            
        //     if (($bmi_value >= 30) && ($bmi_value < 35)) {
        //         $input_data['bmiFlag']      = 'Class I Obesity';
        //         $input_data['bmiFlagColor'] = 'danger';
        //         $input_data['range_code']   = '#ff0000';
        //     }
            
        //     if (($bmi_value >= 35) && ($bmi_value < 40)) {
        //         $input_data['bmiFlag']      = 'Class II Obesity';
        //         $input_data['bmiFlagColor'] = 'danger';
        //         $input_data['range_code']   = '#ff0000';
        //     }

        //     if ($bmi_value >= 40) {
        //         $input_data['bmiFlag']      = 'Class III Obesity';
        //         $input_data['bmiFlagColor'] = 'danger';
        //         $input_data['range_code']   = '#ff0000';
        //     }
        // }
        // this on is new one for future with year dob 
        if (!empty($bmi_value)) 
            {
           
                    // 2-5 years
                    if (($years >= 0) && ($years <= 5)) {
                        if ($bmi_value < 14.9) {
                            $input_data['bmiFlag']      = 'Underweight';
                            $input_data['bmiFlagColor'] = 'primary';
                            $input_data['range_code']   = '#0000ff';
                        }
                        if (($bmi_value >= 15.0) && ($bmi_value < 18.5)) {
                            $input_data['bmiFlag']      = 'Normal';
                            $input_data['bmiFlagColor'] = 'success';
                            $input_data['range_code']   = '#008000';
                        }
                        if (($bmi_value >= 18.5) && ($bmi_value < 22.0)) {
                            $input_data['bmiFlag']      = 'Overweight';
                            $input_data['bmiFlagColor'] = 'success';
                            $input_data['range_code']   = '#fff707';
                        }
                        if (($bmi_value >= 22.0) && ($bmi_value < 25.0)) {
                            $input_data['bmiFlag']      = 'Obese ';
                            $input_data['bmiFlagColor'] = 'danger';
                            $input_data['range_code']   = '#FFC107';
                        }
                        if (($bmi_value >= 25.0) && ($bmi_value < 30.0)) {
                            $input_data['bmiFlag']      = 'Severely Obese (Class 2)';
                            $input_data['bmiFlagColor'] = 'danger';
                            $input_data['range_code']   = '#ff0000';
                        }
                        if (($bmi_value >= 30.0)) {
                            $input_data['bmiFlag']      = 'Extremely Obese (Class 3)';
                            $input_data['bmiFlagColor'] = 'danger';
                            $input_data['range_code']   = '#FF0000';
                        }     
            }
               // 6-11 years
               if (($years >= 6) && ($years <= 11)) {
                if ($bmi_value < 15.5) {
                    $input_data['bmiFlag']      = 'Underweight';
                    $input_data['bmiFlagColor'] = 'primary';
                    $input_data['range_code']   = '#0000ff';
                }
                if (($bmi_value >= 15.5) && ($bmi_value < 18.5)) {
                    $input_data['bmiFlag']      = 'Normal';
                    $input_data['bmiFlagColor'] = 'success';
                    $input_data['range_code']   = '#008000';
                }
                if (($bmi_value >= 18.5) && ($bmi_value < 22.0)) {
                    $input_data['bmiFlag']      = 'Overweight';
                    $input_data['bmiFlagColor'] = 'success';
                    $input_data['range_code']   = '#fff707';
                }
                if (($bmi_value >= 25.0) && ($bmi_value < 30.0)) {
                    $input_data['bmiFlag']      = 'Obese ';
                    $input_data['bmiFlagColor'] = 'danger';
                    $input_data['range_code']   = '#FFC107';
                }
                if (($bmi_value >= 30.0) && ($bmi_value < 35.0)) {
                    $input_data['bmiFlag']      = 'Severely Obese (Class 2) ';
                    $input_data['bmiFlagColor'] = 'danger';
                    $input_data['range_code']   = '#ff0000';
                }
                if (($bmi_value >= 35.0)) {
                    $input_data['bmiFlag']      = 'Extremely Obese (Class 3) ';
                    $input_data['bmiFlagColor'] = 'danger';
                    $input_data['range_code']   = '#FF0000';
                }     
    }
      // 12-19 years
      if (($years >= 12) && ($years <= 17)) {
        if ($bmi_value < 17.0) {
            $input_data['bmiFlag']      = 'Underweight';
            $input_data['bmiFlagColor'] = 'primary';
            $input_data['range_code']   = '#0000ff';
        }
        if (($bmi_value >= 17.0) && ($bmi_value < 21.5)) {
            $input_data['bmiFlag']      = 'Normal';
            $input_data['bmiFlagColor'] = 'success';
            $input_data['range_code']   = '#008000';
        }
        if (($bmi_value >= 21.5) && ($bmi_value < 30.0)) {
            $input_data['bmiFlag']      = 'Overweight';
            $input_data['bmiFlagColor'] = 'success';
            $input_data['range_code']   = '#fff707';
        }
        if (($bmi_value >= 30.0) && ($bmi_value < 35.0)) {
            $input_data['bmiFlag']      = 'Obese ';
            $input_data['bmiFlagColor'] = 'danger';
            $input_data['range_code']   = '#FFC107';
        }
        if (($bmi_value >= 35.0) && ($bmi_value < 40.0)) {
            $input_data['bmiFlag']      = 'Severely Obese (Class 2) ';
            $input_data['bmiFlagColor'] = 'danger';
            $input_data['range_code']   = '#ff0000';
        }
        if (($bmi_value >= 40.0)) {
            $input_data['bmiFlag']      = 'Extremely Obese (Class 3) ';
            $input_data['bmiFlagColor'] = 'danger';
            $input_data['range_code']   = '#FF0000';
        }     
}
    if(($years >= 18)){
        if ($bmi_value < 18.5) {
            $input_data['bmiFlag']      = 'Underweight';
            $input_data['bmiFlagColor'] = 'primary';
            $input_data['range_code']   = '#0000ff';
        }
        if (($bmi_value >= 18.5) && ($bmi_value < 24.9)) {
            $input_data['bmiFlag']      = 'Normal';
            $input_data['bmiFlagColor'] = 'success';
            $input_data['range_code']   = '#008000';
        }
        if (($bmi_value >= 25.0) && ($bmi_value < 30.0)) {
            $input_data['bmiFlag']      = 'Overweight';
            $input_data['bmiFlagColor'] = 'success';
            $input_data['range_code']   = '#fff707';
        }
        if (($bmi_value >= 30.0) && ($bmi_value < 35.0)) {
            $input_data['bmiFlag']      = 'Obese ';
            $input_data['bmiFlagColor'] = 'danger';
            $input_data['range_code']   = '#FFC107';
        }
        if (($bmi_value >= 35.0) && ($bmi_value < 40.0)) {
            $input_data['bmiFlag']      = 'Severely Obese (Class 2) ';
            $input_data['bmiFlagColor'] = 'danger';
            $input_data['range_code']   = '#ff0000';
        }
        if (($bmi_value >= 40.0)) {
            $input_data['bmiFlag']      = 'Extremely Obese (Class 3) ';
            $input_data['bmiFlagColor'] = 'danger';
            $input_data['range_code']   = '#FF0000';
        } 
      
    }
}

        return $input_data;
    }



    public static function temp_flag($input_data, $years, $dob)
    {
        if (!empty($input_data['unit']) && !empty($input_data['temperature'])) {
            // $input_data['temperatureFlag']      = '';
            // $input_data['temperatureFlagColor'] = '';
            // $input_data['range_code'] = '';

            if(empty($dob) || $dob == '0000-00-00'){
                $years = 20;
            }

            switch ($input_data['unit']) {
                case 'Fahrenheit':

                    // if ($input_data['temperature'] <= 95) {
                    //     $input_data['temperatureFlag']      = 'Hypothermia';
                    //     $input_data['temperatureFlagColor'] = 'primary';
                    //     $input_data['range_code']    = '#0000ff';
                    // }

                    // if (($input_data['temperature'] >= 97.7) && ($input_data['temperature'] < 99.5)) {
                    //     $input_data['temperatureFlag']      = 'Normal';
                    //     $input_data['temperatureFlagColor'] = 'success';
                    //     $input_data['range_code']    = '#008000';
                    // }

                    // if (($input_data['temperature'] >= 99.5) || ($input_data['temperature'] <= 100.9)) {
                    //     $input_data['temperatureFlag']      = 'Hyperthermia';
                    //     $input_data['temperatureFlagColor'] = 'warning';
                    //     $input_data['range_code']    = '#FFC107';
                    // }

                    // if ($input_data['temperature'] > 100.9) {
                    //     $input_data['temperatureFlag']      = 'Hyperpyrexia';
                    //     $input_data['temperatureFlagColor'] = 'danger';
                    //     $input_data['range_code']    = '#ff0000';
                    // }


                    if($years <= 1){
                        if (($input_data['temperature'] <= 97)) {
                            $input_data['temperatureFlag']      = 'Low (Hypothermia)';
                            $input_data['temperatureFlagColor'] = 'success';
                            $input_data['range_code']    = '#0000ff';
                        }
                        if (($input_data['temperature'] > 97) && ($input_data['temperature'] <= 100)) {
                            $input_data['temperatureFlag']      = 'Normal (Normothermia)';
                            $input_data['temperatureFlagColor'] = 'success';
                            $input_data['range_code']    = '#008000';
                        }
                        if (($input_data['temperature'] >= 100.1) && ($input_data['temperature'] <= 101.3)) {
                            $input_data['temperatureFlag']      = 'Slightly Increased (Mild Fever)';
                            $input_data['temperatureFlagColor'] = 'success';
                            $input_data['range_code']    = '#fff707';
                        }
                        if (($input_data['temperature'] >= 101.4) && ($input_data['temperature'] < 103)) {
                            $input_data['temperatureFlag']      = 'Moderately Increased (Moderate Fever)'; 
                            $input_data['temperatureFlagColor'] = 'success';
                            $input_data['range_code']    = '#FFC107';
                        }
                        if (($input_data['temperature'] >= 103)) {
                            $input_data['temperatureFlag']      = 'Severely High (Hyperpyrexia)';
                            $input_data['temperatureFlagColor'] = 'success';
                            $input_data['range_code']    = '#FF0000';
                        }
                    }

                    if($years > 1 && $years <= 18){
                        if (($input_data['temperature'] <= 97.6)) {
                            $input_data['temperatureFlag']      = 'Low (Hypothermia)';
                            $input_data['temperatureFlagColor'] = 'success';
                            $input_data['range_code']    = '#0000ff';
                        }
                        if (($input_data['temperature'] >= 97.7) && ($input_data['temperature'] <= 99.5)) {
                            $input_data['temperatureFlag']      = 'Normal (Normothermia)';
                            $input_data['temperatureFlagColor'] = 'success';
                            $input_data['range_code']    = '#008000';
                        }
                        if (($input_data['temperature'] >= 99.6) && ($input_data['temperature'] <= 101.3)) {
                            $input_data['temperatureFlag']      = 'Slightly Increased (Mild Fever)';
                            $input_data['temperatureFlagColor'] = 'success';
                            $input_data['range_code']    = '#fff707';
                        }
                        if (($input_data['temperature'] >= 101.4) && ($input_data['temperature'] <= 103)) {
                            $input_data['temperatureFlag']      = 'Moderately Increased (Moderate Fever)';
                            $input_data['temperatureFlagColor'] = 'success';
                            $input_data['range_code']    = '#FFC107';
                        }
                        if (($input_data['temperature'] > 103)) {
                            $input_data['temperatureFlag']      = 'Severely High (Hyperpyrexia)';
                            $input_data['temperatureFlagColor'] = 'success';
                            $input_data['range_code']    = '#FF0000';
                        }
                    }

                    if($years >= 19 && $years <= 65){
                        if (($input_data['temperature'] <= 97)) {
                            $input_data['temperatureFlag']      = 'Low (Hypothermia)';
                            $input_data['temperatureFlagColor'] = 'success';
                            $input_data['range_code']    = '#0000ff';
                        }
                        if (($input_data['temperature'] >= 97) && ($input_data['temperature'] <= 99)) {
                            $input_data['temperatureFlag']      = 'Normal (Normothermia)';
                            $input_data['temperatureFlagColor'] = 'success';
                            $input_data['range_code']    = '#008000';
                        }
                        if (($input_data['temperature'] >= 99.1) && ($input_data['temperature'] <= 100.6)) {
                            $input_data['temperatureFlag']      = 'Slightly Increased (Mild Fever)';
                            $input_data['temperatureFlagColor'] = 'success';
                            $input_data['range_code']    = '#fff707';
                        }
                        if (($input_data['temperature'] >= 100.8) && ($input_data['temperature'] < 103)) {
                            $input_data['temperatureFlag']      = 'Moderately Increased (Moderate Fever)';
                            $input_data['temperatureFlagColor'] = 'success';
                            $input_data['range_code']    = '#FFC107';
                        }
                        if (($input_data['temperature'] >= 103)) {
                            $input_data['temperatureFlag']      = 'Severely High (Hyperpyrexia)';
                            $input_data['temperatureFlagColor'] = 'success';
                            $input_data['range_code']    = '#FF0000';
                        }
                    }

                    if($years >= 65){
                        if (($input_data['temperature'] <= 96.8)) {
                            $input_data['temperatureFlag']      = 'Low (Hypothermia)';
                            $input_data['temperatureFlagColor'] = 'success';
                            $input_data['range_code']    = '#0000ff';
                        }
                        if (($input_data['temperature'] >= 96.8) && ($input_data['temperature'] <= 98.6)) {
                            $input_data['temperatureFlag']      = 'Normal (Normothermia)';
                            $input_data['temperatureFlagColor'] = 'success';
                            $input_data['range_code']    = '#008000';
                        }
                        if (($input_data['temperature'] >= 98.8) && ($input_data['temperature'] <= 100.2)) {
                            $input_data['temperatureFlag']      = 'Slightly Increased (Mild Fever)';
                            $input_data['temperatureFlagColor'] = 'success';
                            $input_data['range_code']    = '#fff707';
                        }
                        if (($input_data['temperature'] >= 100.4) && ($input_data['temperature'] < 102.2)) {
                            $input_data['temperatureFlag']      = 'Moderately Increased (Moderate Fever)';
                            $input_data['temperatureFlagColor'] = 'success';
                            $input_data['range_code']    = '#FFC107';
                        }
                        if (($input_data['temperature'] >= 102.2)) {
                            $input_data['temperatureFlag']      = 'Severely High (Hyperpyrexia)';
                            $input_data['temperatureFlagColor'] = 'success';
                            $input_data['range_code']    = '#FF0000';
                        }
                    }

                    break;

                case 'Celsius':
                    // if ($input_data['temperature'] <= 36.5) {
                    //     $input_data['temperatureFlag']      = 'Hypothermia';
                    //     $input_data['temperatureFlagColor'] = 'primary';
                    //     $input_data['range_code']    = '#0000ff';
                    // }

                    // if (($input_data['temperature'] >= 36.5) && ($input_data['temperature'] <= 37.5)) {
                    //     $input_data['temperatureFlag']      = 'Normal';
                    //     $input_data['temperatureFlagColor'] = 'success';
                    //     $input_data['range_code']    = '#008000';
                    // }

                    // if (($input_data['temperature'] > 37.5) && ($input_data['temperature'] <= 38.3)) {
                    //     $input_data['temperatureFlag']      = 'Hyperthermia';
                    //     $input_data['temperatureFlagColor'] = 'warning';
                    //     $input_data['range_code']    = '#FFC107';
                    // }

                    // if ($input_data['temperature'] > 38.3) {
                    //     $input_data['temperatureFlag']      = 'Hyperpyrexia';
                    //     $input_data['temperatureFlagColor'] = 'danger';
                    //     $input_data['range_code']    = '#ff0000';
                    // }



                    if($years <= 1){
                        if (($input_data['temperature'] <= 36.1)) {
                            $input_data['temperatureFlag']      = 'Low (Hypothermia)';
                            $input_data['temperatureFlagColor'] = 'success';
                            $input_data['range_code']    = '#0000ff';
                        }
                        if (($input_data['temperature'] > 36.1) && ($input_data['temperature'] <= 37.8)) {
                            $input_data['temperatureFlag']      = 'Normal (Normothermia)';
                            $input_data['temperatureFlagColor'] = 'success';
                            $input_data['range_code']    = '#008000';
                        }
                        if (($input_data['temperature'] >= 37.9) && ($input_data['temperature'] <= 38.5)) {
                            $input_data['temperatureFlag']      = 'Slightly Increased (Mild Fever)';
                            $input_data['temperatureFlagColor'] = 'success';
                            $input_data['range_code']    = '#fff707';
                        }
                        if (($input_data['temperature'] >= 38.6) && ($input_data['temperature'] < 39.4)) {
                            $input_data['temperatureFlag']      = 'Moderately Increased (Moderate Fever)';
                            $input_data['temperatureFlagColor'] = 'success';
                            $input_data['range_code']    = '#FFC107';
                        }
                        if (($input_data['temperature'] >= 39.4)) {
                            $input_data['temperatureFlag']      = 'Severely High (Hyperpyrexia)';
                            $input_data['temperatureFlagColor'] = 'success';
                            $input_data['range_code']    = '#FF0000';
                        }
                    }

                    if($years > 1 && $years <= 18){
                        if (($input_data['temperature'] <= 36.5)) {
                            $input_data['temperatureFlag']      = 'Low (Hypothermia)';
                            $input_data['temperatureFlagColor'] = 'success';
                            $input_data['range_code']    = '#0000ff';
                        }
                        if (($input_data['temperature'] >= 36.5) && ($input_data['temperature'] <= 37.5)) {
                            $input_data['temperatureFlag']      = 'Normal (Normothermia)';
                            $input_data['temperatureFlagColor'] = 'success';
                            $input_data['range_code']    = '#008000';
                        }
                        if (($input_data['temperature'] >= 37.6) && ($input_data['temperature'] <= 38.5)) {
                            $input_data['temperatureFlag']      = 'Slightly Increased (Mild Fever)';
                            $input_data['temperatureFlagColor'] = 'success';
                            $input_data['range_code']    = '#fff707';
                        }
                        if (($input_data['temperature'] >= 38.6) && ($input_data['temperature'] < 39.4)) {
                            $input_data['temperatureFlag']      = 'Moderately Increased (Moderate Fever)';
                            $input_data['temperatureFlagColor'] = 'success';
                            $input_data['range_code']    = '#FFC107';
                        }
                        if (($input_data['temperature'] >= 39.4)) {
                            $input_data['temperatureFlag']      = 'Severely High (Hyperpyrexia)';
                            $input_data['temperatureFlagColor'] = 'success';
                            $input_data['range_code']    = '#FF0000';
                        }
                    }

                    if($years >= 19 && $years <= 65){
                        if (($input_data['temperature'] <= 36.1)) {
                            $input_data['temperatureFlag']      = 'Low (Hypothermia)';
                            $input_data['temperatureFlagColor'] = 'success';
                            $input_data['range_code']    = '#0000ff';
                        }
                        if (($input_data['temperature'] >= 36.1) && ($input_data['temperature'] <= 37.2)) {
                            $input_data['temperatureFlag']      = 'Normal (Normothermia)';
                            $input_data['temperatureFlagColor'] = 'success';
                            $input_data['range_code']    = '#008000';
                        }
                        if (($input_data['temperature'] >= 37.3) && ($input_data['temperature'] <= 38.1)) {
                            $input_data['temperatureFlag']      = 'Slightly Increased (Mild Fever)';
                            $input_data['temperatureFlagColor'] = 'success';
                            $input_data['range_code']    = '#fff707';
                        }
                        if (($input_data['temperature'] >= 38.2 ) && ($input_data['temperature'] < 39.4)) {
                            $input_data['temperatureFlag']      = 'Moderately Increased (Moderate Fever)';
                            $input_data['temperatureFlagColor'] = 'success';
                            $input_data['range_code']    = '#FFC107';
                        }
                        if (($input_data['temperature'] >= 39.4)) {
                            $input_data['temperatureFlag']      = 'Severely High (Hyperpyrexia)';
                            $input_data['temperatureFlagColor'] = 'success';
                            $input_data['range_code']    = '#FF0000';
                        }
                    }

                    if($years >= 65){
                        if (($input_data['temperature'] < 36.0)) {
                            $input_data['temperatureFlag']      = 'Low (Hypothermia)';
                            $input_data['temperatureFlagColor'] = 'success';
                            $input_data['range_code']    = '#0000ff';
                        }
                        if (($input_data['temperature'] >= 36.0) && ($input_data['temperature'] <= 37.0)) {
                            $input_data['temperatureFlag']      = 'Normal (Normothermia)';
                            $input_data['temperatureFlagColor'] = 'success';
                            $input_data['range_code']    = '#008000';
                        }
                        if (($input_data['temperature'] >= 37.1) && ($input_data['temperature'] <= 37.9)) {
                            $input_data['temperatureFlag']      = 'Slightly Increased (Mild Fever)';
                            $input_data['temperatureFlagColor'] = 'success';
                            $input_data['range_code']    = '#fff707';
                        }
                        if (($input_data['temperature'] >= 38.0) && ($input_data['temperature'] < 39.0)) {
                            $input_data['temperatureFlag']      = 'Moderately Increased (Moderate Fever)';
                            $input_data['temperatureFlagColor'] = 'success';
                            $input_data['range_code']    = '#FFC107';
                        }
                        if (($input_data['temperature'] >= 39.0)) {
                            $input_data['temperatureFlag']      = 'Severely High (Hyperpyrexia)';
                            $input_data['temperatureFlagColor'] = 'success';
                            $input_data['range_code']    = '#FF0000';
                        }
                    }

                    break;
            }

        }


        return $input_data;
    }



    public static function blood_sugar_flag($input_data,  $years, $months, $days)
    {
        $input_data['bsFlag']      = '';
        $input_data['bsFlagColor'] = '';
        $input_data['range_code']  = '';

        $type = trim($input_data['type']);
        if (!empty($input_data['blood_sugar'])) {

            if ($years <= 60) {

                //0-5  year

                if (($years >= 0) && ($years <= 5)) {

                if ($input_data['unit'] == 'mg/dL') {

                    // low
                    if(( $type == 'Fasting' && $input_data['blood_sugar'] < 50) || ( $type == 'Postprandial' && $input_data['blood_sugar'] < 70) || ( $type == 'HbA1c (%)' && $input_data['blood_sugar'] < 3.5) || ( $type == 'Random' && $input_data['blood_sugar'] <50) ){
                        $input_data['bsFlag']      = 'Low (Hypoglycemia)';
                        $input_data['bsFlagColor'] = 'primary';
                        $input_data['range_code']  = '#0000ff';
                    }
        
                    // normal
                    // if ($input_data['blood_sugar'] >= 71 && $input_data['blood_sugar'] <= 127) {
                    if (($type == 'Fasting' && $input_data['blood_sugar'] >= 50 && $input_data['blood_sugar'] <= 90) || ($type == 'Postprandial' && $input_data['blood_sugar'] >= 70 && $input_data['blood_sugar'] <= 130) || ($type == 'HbA1c (%)' && $input_data['blood_sugar'] >= 3.5 && $input_data['blood_sugar'] <= 5.5) || (($type == 'Random' && $input_data['blood_sugar'] >= 50 && $input_data['blood_sugar'] <= 90))) {
                        $input_data['bsFlag']      = 'Normal';
                        $input_data['bsFlagColor'] = 'success';
                        $input_data['range_code']  = '#008000';
                    }
                    // Mildly Reduced (Early Signs of Insulin Resistance)
                    if (($type == 'Fasting' && $input_data['blood_sugar'] >= 91 && $input_data['blood_sugar'] <= 100) || ($type == 'Postprandial' && $input_data['blood_sugar'] >= 131 && $input_data['blood_sugar'] <= 150) || ($type == 'HbA1c (%)' && $input_data['blood_sugar'] >= 5.6 && $input_data['blood_sugar'] <= 6.0) || (($type == 'Random' && $input_data['blood_sugar'] >= 91 && $input_data['blood_sugar'] <= 100))) {
                        $input_data['bsFlag']      = 'Mildly Reduced (Early Signs of Insulin Resistance)';
                        $input_data['bsFlagColor'] = 'warning';
                        $input_data['range_code']  = '#fff707';
                    }
        
                    // Moderately Reduced (Impaired Glucose Tolerance/Pre-Diabetes)
                    if (($type == 'Fasting' && $input_data['blood_sugar'] >= 101 && $input_data['blood_sugar'] <= 110) || ($type == 'Postprandial' && $input_data['blood_sugar'] >= 151 && $input_data['blood_sugar'] <= 170) || ($type == 'HbA1c (%)' && $input_data['blood_sugar'] >= 6.1 && $input_data['blood_sugar'] <= 6.4) || (($type == 'Random' && $input_data['blood_sugar'] >= 101 && $input_data['blood_sugar'] <= 110))) {
                        $input_data['bsFlag']      = 'Moderately Reduced (Impaired Glucose Tolerance/Pre-Diabetes)';
                        $input_data['bsFlagColor'] = 'warning';
                        $input_data['range_code']  = '#ffc107';
                    }
                    // Severely Reduced(Diabetes/Severe Hyperglycemia)
                    if (($type == 'Fasting' && $input_data['blood_sugar'] >= 111) || ($type == 'Postprandial' && $input_data['blood_sugar'] >= 171) || ($type == 'HbA1c (%)' && $input_data['blood_sugar'] >= 6.5) || ($type == 'Random' && $input_data['blood_sugar'] >= 111)) {
                        $input_data['bsFlag']      = 'Severely Reduced (Diabetes/Severe Hyperglycemia)';
                        $input_data['bsFlagColor'] = 'danger';
                        $input_data['range_code']  = '#ff0000';
                    }
        
        
                }
        
            }
            // 6 -19 year
            if (($years >= 6) && ($years <= 19)) {

                if ($input_data['unit'] == 'mg/dL') {

                    // low
                    if(( $type == 'Fasting' && $input_data['blood_sugar'] < 60) || ( $type == 'Postprandial' && $input_data['blood_sugar'] < 80) || ( $type == 'HbA1c (%)' && $input_data['blood_sugar'] < 4.0) || ( $type == 'Random' && $input_data['blood_sugar'] <60) ){
                        $input_data['bsFlag']      = 'Low (Hypoglycemia)';
                        $input_data['bsFlagColor'] = 'primary';
                        $input_data['range_code']  = '#0000ff';
                    }
        
                    // normal
                    // if ($input_data['blood_sugar'] >= 71 && $input_data['blood_sugar'] <= 127) {
                    if (($type == 'Fasting' && $input_data['blood_sugar'] >= 60 && $input_data['blood_sugar'] <= 100) || ($type == 'Postprandial' && $input_data['blood_sugar'] >= 80 && $input_data['blood_sugar'] <= 140) || ($type == 'HbA1c (%)' && $input_data['blood_sugar'] >= 4.0 && $input_data['blood_sugar'] <= 5.6) || (($type == 'Random' && $input_data['blood_sugar'] >= 60 && $input_data['blood_sugar'] <= 100))) {
                        $input_data['bsFlag']      = 'Normal';
                        $input_data['bsFlagColor'] = 'success';
                        $input_data['range_code']  = '#008000';
                    }
                    // Mildly Reduced (Early Signs of Insulin Resistance)
                    if (($type == 'Fasting' && $input_data['blood_sugar'] >= 101 && $input_data['blood_sugar'] <= 110) || ($type == 'Postprandial' && $input_data['blood_sugar'] >= 141 && $input_data['blood_sugar'] <= 160) || ($type == 'HbA1c (%)' && $input_data['blood_sugar'] >= 5.7 && $input_data['blood_sugar'] <= 6.0) || (($type == 'Random' && $input_data['blood_sugar'] >= 101 && $input_data['blood_sugar'] <= 110))) {
                        $input_data['bsFlag']      = 'Mildly Reduced (Early Signs of Insulin Resistance)';
                        $input_data['bsFlagColor'] = 'warning';
                        $input_data['range_code']  = '#fff707';
                    }
        
                    // Moderately Reduced (Impaired Glucose Tolerance/Pre-Diabetes)
                    if (($type == 'Fasting' && $input_data['blood_sugar'] >= 111 && $input_data['blood_sugar'] <= 125) || ($type == 'Postprandial' && $input_data['blood_sugar'] >= 161 && $input_data['blood_sugar'] <= 180) || ($type == 'HbA1c (%)' && $input_data['blood_sugar'] >= 6.1 && $input_data['blood_sugar'] <= 6.4) || (($type == 'Random' && $input_data['blood_sugar'] >= 111 && $input_data['blood_sugar'] <= 125))) {
                        $input_data['bsFlag']      = 'Moderately Reduced (Impaired Glucose Tolerance/Pre-Diabetes)';
                        $input_data['bsFlagColor'] = 'warning';
                        $input_data['range_code']  = '#ffc107';
                    }
                    // Severely Reduced(Diabetes/Severe Hyperglycemia)
                    if (($type == 'Fasting' && $input_data['blood_sugar'] >= 126) || ($type == 'Postprandial' && $input_data['blood_sugar'] >= 181) || ($type == 'HbA1c (%)' && $input_data['blood_sugar'] >= 6.5) || ($type == 'Random' && $input_data['blood_sugar'] >= 126)) {
                        $input_data['bsFlag']      = 'Severely Reduced (Diabetes/Severe Hyperglycemia)';
                        $input_data['bsFlagColor'] = 'danger';
                        $input_data['range_code']  = '#ff0000';
                    }
        
        
                }
        
            }
            //20-59 year
            if (($years >= 20) && ($years <= 59)) {

                if ($input_data['unit'] == 'mg/dL') {

                    // low
                    if(( $type == 'Fasting' && $input_data['blood_sugar'] <= 60) || ( $type == 'Postprandial' && $input_data['blood_sugar'] <= 80) || ( $type == 'HbA1c (%)' && $input_data['blood_sugar'] <= 4.0) || ( $type == 'Random' && $input_data['blood_sugar'] <= 60) ){
                        $input_data['bsFlag']      = 'Low (Hypoglycemia)';
                        $input_data['bsFlagColor'] = 'primary';
                        $input_data['range_code']  = '#0000ff';
                    }
        
                    // normal
                    // if ($input_data['blood_sugar'] >= 71 && $input_data['blood_sugar'] <= 127) {
                    if (($type == 'Fasting' && $input_data['blood_sugar'] >= 60 && $input_data['blood_sugar'] <= 100) || ($type == 'Postprandial' && $input_data['blood_sugar'] >= 80 && $input_data['blood_sugar'] <= 140) || ($type == ' HbA1c (%)' && $input_data['blood_sugar'] >= 4.0 && $input_data['blood_sugar'] <= 5.6) || (($type == 'Random' && $input_data['blood_sugar'] >= 60 && $input_data['blood_sugar'] <= 100))) {
                        $input_data['bsFlag']      = 'Normal';
                        $input_data['bsFlagColor'] = 'success';
                        $input_data['range_code']  = '#008000';
                    }
                    // Mildly Reduced (Early Signs of Insulin Resistance)
                    if (($type == 'Fasting' && $input_data['blood_sugar'] >= 101 && $input_data['blood_sugar'] <= 110) || ($type == 'Postprandial' && $input_data['blood_sugar'] >= 141 && $input_data['blood_sugar'] <= 160) || ($type == 'HbA1c (%)' && $input_data['blood_sugar'] >= 5.7 && $input_data['blood_sugar'] <= 6.0) || (($type == 'Random' && $input_data['blood_sugar'] >= 101 && $input_data['blood_sugar'] <= 110))) {
                        $input_data['bsFlag']      = 'Mildly Reduced (Early Signs of Insulin Resistance)';
                        $input_data['bsFlagColor'] = 'warning';
                        $input_data['range_code']  = '#fff707';
                    }
        
                    // Moderately Reduced (Impaired Glucose Tolerance/Pre-Diabetes)
                    if (($type == 'Fasting' && $input_data['blood_sugar'] >= 111 && $input_data['blood_sugar'] <= 125) || ($type == 'Postprandial' && $input_data['blood_sugar'] >= 161 && $input_data['blood_sugar'] <= 180) || ($type == 'HbA1c (%)' && $input_data['blood_sugar'] >= 6.1 && $input_data['blood_sugar'] <= 6.4) || (($type == 'Random' && $input_data['blood_sugar'] >= 111 && $input_data['blood_sugar'] <= 125))) {
                        $input_data['bsFlag']      = 'Moderately Reduced (Impaired Glucose Tolerance/Pre-Diabetes)';
                        $input_data['bsFlagColor'] = 'warning';
                        $input_data['range_code']  = '#ffc107';
                    }
                    // Severely Reduced(Diabetes/Severe Hyperglycemia)
                    if (($type == 'Fasting' && $input_data['blood_sugar'] >= 126) || ($type == 'Postprandial' && $input_data['blood_sugar'] >= 181) || ($type == 'HbA1c (%)' && $input_data['blood_sugar'] >= 6.5) || ($type == 'Random' && $input_data['blood_sugar'] >= 126)) {
                        $input_data['bsFlag']      = 'Severely Reduced (Diabetes/Severe Hyperglycemia)';
                        $input_data['bsFlagColor'] = 'danger';
                        $input_data['range_code']  = '#ff0000';
                    }
        
        
                }
        
            }
        }

        if ($years >= 60) {
            if ($input_data['unit'] == 'mg/dL') {

                // low
                if(( $type == 'Fasting' && $input_data['blood_sugar'] < 70) || ( $type == 'Postprandial' && $input_data['blood_sugar'] < 90) || ( $type == 'HbA1c (%)' && $input_data['blood_sugar'] < 4.5) || ( $type == 'Random' && $input_data['blood_sugar'] <70) ){
                    $input_data['bsFlag']      = 'Low (Hypoglycemia)';
                    $input_data['bsFlagColor'] = 'primary';
                    $input_data['range_code']  = '#0000ff';
                }
    
                // normal
                // if ($input_data['blood_sugar'] >= 71 && $input_data['blood_sugar'] <= 127) {
                if (($type == 'Fasting' && $input_data['blood_sugar'] >= 70 && $input_data['blood_sugar'] <= 110) || ($type == 'Postprandial' && $input_data['blood_sugar'] >= 90 && $input_data['blood_sugar'] <= 150 ) || ($type == 'HbA1c (%)' && $input_data['blood_sugar'] >= 4.0 && $input_data['blood_sugar'] <= 6.0) || (($type == 'Random' && $input_data['blood_sugar'] >= 70 && $input_data['blood_sugar'] <= 110))) {
                    $input_data['bsFlag']      = 'Normal';
                    $input_data['bsFlagColor'] = 'success';
                    $input_data['range_code']  = '#008000';
                }
                // Mildly Reduced (Early Signs of Insulin Resistance)
                if (($type == 'Fasting' && $input_data['blood_sugar'] >= 111 && $input_data['blood_sugar'] <= 120) || ($type == 'Postprandial' && $input_data['blood_sugar'] >= 141 && $input_data['blood_sugar'] <= 160) || ($type == 'HbA1c (%)' && $input_data['blood_sugar'] >= 5.7 && $input_data['blood_sugar'] <= 6.0) || (($type == 'Random' && $input_data['blood_sugar'] >= 111 && $input_data['blood_sugar'] <= 120))) {
                    $input_data['bsFlag']      = 'Mildly Reduced (Early Signs of Insulin Resistance)';
                    $input_data['bsFlagColor'] = 'warning';
                    $input_data['range_code']  = '#fff707';
                }
    
                // Moderately Reduced (Impaired Glucose Tolerance/Pre-Diabetes)
                if (($type == 'Fasting' && $input_data['blood_sugar'] >= 121 && $input_data['blood_sugar'] <= 135) || ($type == 'Postprandial' && $input_data['blood_sugar'] >= 171 && $input_data['blood_sugar'] <= 190) || ($type == 'HbA1c (%)' && $input_data['blood_sugar'] >= 6.1 && $input_data['blood_sugar'] <= 7.0) || (($type == 'Random' && $input_data['blood_sugar'] >= 121 && $input_data['blood_sugar'] <= 135))) {
                    $input_data['bsFlag']      = 'Moderately Reduced (Impaired Glucose Tolerance/Pre-Diabetes)';
                    $input_data['bsFlagColor'] = 'warning';
                    $input_data['range_code']  = '#ffc107';
                }
                // Severely Reduced(Diabetes/Severe Hyperglycemia)
                if (($type == 'Fasting' && $input_data['blood_sugar'] >= 136) || ($type == 'Postprandial' && $input_data['blood_sugar'] >= 191) || ($type == 'HbA1c (%)' && $input_data['blood_sugar'] >= 7.1) || ($type == 'Random' && $input_data['blood_sugar'] >= 136)) {
                    $input_data['bsFlag']      = 'Severely Reduced (Diabetes/Severe Hyperglycemia)';
                    $input_data['bsFlagColor'] = 'danger';
                    $input_data['range_code']  = '#ff0000';
                }
    
    
            }

        }


        // if ($input_data['unit'] == 'mg/dL') {


        //     if ($input_data['blood_sugar'] >= 20 && $input_data['blood_sugar'] <= 70) {
        //         $input_data['bsFlag']      = 'Low (Hypoglycemia)';
        //         $input_data['bsFlagColor'] = 'primary';
        //         $input_data['range_code']  = '#0000ff';
        //     }


        //     // if ($input_data['blood_sugar'] >= 71 && $input_data['blood_sugar'] <= 127) {
        //     if (($type == 'Fasting' && $input_data['blood_sugar'] >= 71 && $input_data['blood_sugar'] <= 99) || ($type == 'Random' && $input_data['blood_sugar'] >= 71 && $input_data['blood_sugar'] <= 140) || ($type == 'Postprandial' && $input_data['blood_sugar'] >= 71 && $input_data['blood_sugar'] <= 140)) {
        //         $input_data['bsFlag']      = 'Normal';
        //         $input_data['bsFlagColor'] = 'success';
        //         $input_data['range_code']  = '#008000';
        //     }

        //     if (($type == 'Fasting' && $input_data['blood_sugar'] >= 100 && $input_data['blood_sugar'] <= 125) || ($type == 'Random' && $input_data['blood_sugar'] >= 140 && $input_data['blood_sugar'] <= 199) || ($type == 'Postprandial' && $input_data['blood_sugar'] >= 140 && $input_data['blood_sugar'] <= 199)) {
        //         $input_data['bsFlag']      = 'Mildly Elevated (Pre-diabetes)';
        //         $input_data['bsFlagColor'] = 'warning';
        //         $input_data['range_code']  = '#fff707';
        //     }


        //     if (($type == 'Fasting' && $input_data['blood_sugar'] >= 126 && $input_data['blood_sugar'] <= 160) || ($type == 'Random' && $input_data['blood_sugar'] >= 200 && $input_data['blood_sugar'] <= 300) || ($type == 'Postprandial' && $input_data['blood_sugar'] >= 200 && $input_data['blood_sugar'] <= 300)) {
        //         $input_data['bsFlag']      = 'Moderately Elevated (High blood sugar)';
        //         $input_data['bsFlagColor'] = 'warning';
        //         $input_data['range_code']  = '#ffc107';
        //     }

        //     if (($type == 'Fasting' && $input_data['blood_sugar'] > 160) || ($type == 'Random' && $input_data['blood_sugar'] > 300) || ($type == 'Postprandial' && $input_data['blood_sugar'] > 300)) {
        //         $input_data['bsFlag']      = 'Severely Elevated (Severe Hyperglycemia)';
        //         $input_data['bsFlagColor'] = 'danger';
        //         $input_data['range_code']  = '#ff0000';
        //     }

        //     if ($input_data['blood_sugar'] < 20) {
        //         $input_data['bsFlag']      = 'Dangerously low';
        //         $input_data['bsFlagColor'] = 'danger';
        //         $input_data['range_code']  = '#ff0000';
        //     }


        // }

        // if ($input_data['unit'] == 'mmol/L') {


        //     if ($input_data['blood_sugar'] >= 1.1 && $input_data['blood_sugar'] <= 3.9) {
        //         $input_data['bsFlag']      = 'Low (Hypoglycemia)';
        //         $input_data['bsFlagColor'] = 'primary';
        //         $input_data['range_code']  = '#0000ff';
        //     }


        //     if ($input_data['blood_sugar'] >= 4.0 && $input_data['blood_sugar'] <= 7.0) {
        //         $input_data['bsFlag']      = 'Normal';
        //         $input_data['bsFlagColor'] = 'success';
        //         $input_data['range_code']  = '#008000';
        //     }

        //     if ($input_data['blood_sugar'] >= 7.1 && $input_data['blood_sugar'] <= 10) {
        //         $input_data['bsFlag']      = 'Mildly Elevated (Pre-diabetes)';
        //         $input_data['bsFlagColor'] = 'warning';
        //         $input_data['range_code']  = '#fff707';
        //     }


        //     if ($input_data['blood_sugar'] >= 10.1 && $input_data['blood_sugar'] <= 13.8) {
        //         $input_data['bsFlag']      = 'Moderately Elevated (High blood sugar)';
        //         $input_data['bsFlagColor'] = 'warning';
        //         $input_data['range_code']  = '#ffc107';
        //     }

        //     if ($input_data['blood_sugar'] >= 13.9) {
        //         $input_data['bsFlag']      = 'Severely Elevated (Severe Hyperglycemia)';
        //         $input_data['bsFlagColor'] = 'danger';
        //         $input_data['range_code']  = '#ff0000';
        //     }

        //     if ($input_data['blood_sugar'] < 1.1) {
        //         $input_data['bsFlag']      = 'Dangerously low';
        //         $input_data['bsFlagColor'] = 'danger';
        //         $input_data['range_code']  = '#ff0000';
        //     }


        // }


        }

        return $input_data;
    }



    public static function spo2_flag($input_data,  $years, $months, $days)
    {
        $input_data['spo2Flag']      = '';
        $input_data['spo2FlagColor'] = '';
        $input_data['range_code']    = '';
        if (!empty($input_data['spo2'])) {

            if ($years <= 65) {
                   // 0 - 12 Months
                   if($years < 1){

                // if ($input_data['spo2'] <= 88) {
                //     $input_data['spo2Flag']      = 'Low (Hypoxemia)';
                //     $input_data['spo2FlagColor'] = 'danger';
                //     $input_data['range_code']    = '#0000ff';
                // }
                if (($input_data['spo2'] >= 90) && ($input_data['spo2'] <= 100)) {
                    $input_data['spo2Flag']      = 'Normal  ';
                    $input_data['spo2FlagColor'] = 'success';
                    $input_data['range_code']    = '#008000';
                }
                if (($input_data['spo2'] >= 86) && ($input_data['spo2'] <= 89)) {
                    $input_data['spo2Flag']      = 'Slightly Decreased(Mild Hypoxemia)';
                    $input_data['spo2FlagColor'] = 'warning';
                    $input_data['range_code']    = '#fff707';
                }
                if (($input_data['spo2'] >= 80) && ($input_data['spo2'] <= 85)) {
                    $input_data['spo2Flag']      = 'Moderately Decreased (Moderate Hypoxemia)';
                    $input_data['spo2FlagColor'] = 'primary';
                    $input_data['range_code']    = '#FFC107';
                }
                if ($input_data['spo2'] <= 79) {
                    $input_data['spo2Flag']      = 'Severely Low (Severe Hypoxemia)  ';
                    $input_data['spo2FlagColor'] = 'danger';
                    $input_data['range_code']    = '#ff0000';
                }
                
            
        }

                if (($years >= 1) && ($years <= 3)) {
                    // if ($input_data['spo2'] <= 88) {
                    //     $input_data['spo2Flag']      = 'Low (Hypoxemia)';
                    //     $input_data['spo2FlagColor'] = 'danger';
                    //     $input_data['range_code']    = '#0000ff';
                    // }
                    if (($input_data['spo2'] >= 90) && ($input_data['spo2'] <= 100)) {
                        $input_data['spo2Flag']      = 'Normal  ';
                        $input_data['spo2FlagColor'] = 'success';
                        $input_data['range_code']    = '#008000';
                    }
                    if (($input_data['spo2'] >= 86) && ($input_data['spo2'] <= 89)) {
                        $input_data['spo2Flag']      = 'Slightly Decreased(Mild Hypoxemia)';
                        $input_data['spo2FlagColor'] = 'warning';
                        $input_data['range_code']    = '#fff707';
                    }
                    if (($input_data['spo2'] >= 80) && ($input_data['spo2'] <= 85)) {
                        $input_data['spo2Flag']      = 'Moderately Decreased (Moderate Hypoxemia)';
                        $input_data['spo2FlagColor'] = 'primary';
                        $input_data['range_code']    = '#FFC107';
                    }
                    if ($input_data['spo2'] <= 79) {
                        $input_data['spo2Flag']      = 'Severely Low (Severe Hypoxemia)';
                        $input_data['spo2FlagColor'] = 'danger';
                        $input_data['range_code']    = '#ff0000';
                    }
                }
                // 4–12 years
                if (($years >= 4) && ($years <= 12)) {
                    // if ($input_data['spo2'] <= 90) {
                    //     $input_data['spo2Flag']      = 'Low (Hypoxemia)';
                    //     $input_data['spo2FlagColor'] = 'danger';
                    //     $input_data['range_code']    = '#0000ff';
                    // }
                    if (($input_data['spo2'] >= 91) && ($input_data['spo2'] <= 100)) {
                        $input_data['spo2Flag']      = 'Normal';
                        $input_data['spo2FlagColor'] = 'success';
                        $input_data['range_code']    = '#008000';
                    }
                    if (($input_data['spo2'] >= 87) && ($input_data['spo2'] <= 90)) {
                        $input_data['spo2Flag']      = 'Slightly Decreased(Mild Hypoxemia)';
                        $input_data['spo2FlagColor'] = 'warning';
                        $input_data['range_code']    = '#fff707';
                    }
                    if (($input_data['spo2'] >= 82) && ($input_data['spo2'] <= 86)) {
                        $input_data['spo2Flag']      = 'Moderately Decreased (Moderate Hypoxemia)';
                        $input_data['spo2FlagColor'] = 'primary';
                        $input_data['range_code']    = '#FFC107';
                    }
                    if ($input_data['spo2'] <= 81) {
                        $input_data['spo2Flag']      = 'Severely Low (Severe Hypoxemia)';
                        $input_data['spo2FlagColor'] = 'danger';
                        $input_data['range_code']    = '#ff0000';
                    }
                }
                    // 13–18 years
                    if (($years >= 13) && ($years <= 18)) {
                        // if ($input_data['spo2'] <= 92) {
                        //     $input_data['spo2Flag']      = 'Low (Hypoxemia)';
                        //     $input_data['spo2FlagColor'] = 'danger';
                        //     $input_data['range_code']    = '#0000ff';
                        // }
                        if (($input_data['spo2'] >= 93) && ($input_data['spo2'] <= 100)) {
                            $input_data['spo2Flag']      = 'Normal  ';
                            $input_data['spo2FlagColor'] = 'success';
                            $input_data['range_code']    = '#008000';
                        }
                        if (($input_data['spo2'] >= 89) && ($input_data['spo2'] <= 92)) {
                            $input_data['spo2Flag']      = 'Slightly Decreased(Mild Hypoxemia)  ';
                            $input_data['spo2FlagColor'] = 'warning';
                            $input_data['range_code']    = '#fff707';
                        }
                        if (($input_data['spo2'] >= 84) && ($input_data['spo2'] <= 88)) {
                            $input_data['spo2Flag']      = 'Moderately Decreased (Moderate Hypoxemia)  ';
                            $input_data['spo2FlagColor'] = 'primary';
                            $input_data['range_code']    = '#FFC107';
                        }
                        if ($input_data['spo2'] <= 83) {
                            $input_data['spo2Flag']      = 'Severely Low (Severe Hypoxemia)';
                            $input_data['spo2FlagColor'] = 'danger';
                            $input_data['range_code']    = '#ff0000';
                        }
                    }
                            // 19–65 years
                            if (($years >= 19) && ($years <= 65)) {
                                // if ($input_data['spo2'] < 92) {
                                //     $input_data['spo2Flag']      = 'Low (Hypoxemia)';
                                //     $input_data['spo2FlagColor'] = 'danger';
                                //     $input_data['range_code']    = '#0000ff';
                                // }
                                if (($input_data['spo2'] >= 94) && ($input_data['spo2'] <= 100)) {
                                    $input_data['spo2Flag']      = 'Normal';
                                    $input_data['spo2FlagColor'] = 'success';
                                    $input_data['range_code']    = '#008000';
                                }
                                if (($input_data['spo2'] >= 90) && ($input_data['spo2'] <= 93)) {
                                    $input_data['spo2Flag']      = 'Slightly Decreased(Mild Hypoxemia)';
                                    $input_data['spo2FlagColor'] = 'warning';
                                    $input_data['range_code']    = '#fff707';
                                }
                                if (($input_data['spo2'] >= 85) && ($input_data['spo2'] <= 89)) {
                                    $input_data['spo2Flag']      = 'Moderately Decreased (Moderate Hypoxemia)';
                                    $input_data['spo2FlagColor'] = 'primary';
                                    $input_data['range_code']    = '#FFC107';
                                }
                                if ($input_data['spo2'] <= 83) {
                                    $input_data['spo2Flag']      = 'Severely Low (Severe Hypoxemia)';
                                    $input_data['spo2FlagColor'] = 'danger';
                                    $input_data['range_code']    = '#ff0000';
                                }
                            }
            }

            if ($years > 65) {
                // if ($input_data['spo2'] < 90) {
                //     $input_data['spo2Flag']      = 'Low (Hypoxemia)  ';
                //     $input_data['spo2FlagColor'] = 'danger';
                //     $input_data['range_code']    = '#0000ff';
                // }
                if (($input_data['spo2'] >= 91) && ($input_data['spo2'] <= 100)) {
                    $input_data['spo2Flag']      = 'Normal  ';
                    $input_data['spo2FlagColor'] = 'success';
                    $input_data['range_code']    = '#008000';
                }
                if (($input_data['spo2'] >= 87) && ($input_data['spo2'] <= 89)) {
                    $input_data['spo2Flag']      = 'Slightly Decreased(Mild Hypoxemia)  ';
                    $input_data['spo2FlagColor'] = 'warning';
                    $input_data['range_code']    = '#fff707';
                }
                if (($input_data['spo2'] >= 82) && ($input_data['spo2'] <= 86)) {
                    $input_data['spo2Flag']      = 'Moderately Decreased (Moderate Hypoxemia)  ';
                    $input_data['spo2FlagColor'] = 'primary';
                    $input_data['range_code']    = '#FFC107';
                }
                if ($input_data['spo2'] <= 81) {
                    $input_data['spo2Flag']      = 'Severely Low (Severe Hypoxemia)';
                    $input_data['spo2FlagColor'] = 'danger';
                    $input_data['range_code']    = '#ff0000';
                }
            }

      
            // if ($input_data['spo2'] < 75) {
            //     $input_data['spo2Flag']      = 'Severe Hypoxemia';
            //     $input_data['spo2FlagColor'] = 'danger';
            //     $input_data['range_code']    = '#ff0000';
            // }

            // if (($input_data['spo2'] >= 75) && ($input_data['spo2'] <= 89)) {
            //     $input_data['spo2Flag']      = 'Moderate Hypoxemia';
            //     $input_data['spo2FlagColor'] = 'warning';
            //     $input_data['range_code']    = '#ffc107';
            // }

            // if (($input_data['spo2'] >= 90) && ($input_data['spo2'] <= 94)) {
            //     $input_data['spo2Flag']      = 'Mild Hypoxemia';
            //     $input_data['spo2FlagColor'] = 'primary';
            //     $input_data['range_code']    = '#0000ff';
            // }

            // if ($input_data['spo2'] >= 95) {
            //     $input_data['spo2Flag']      = 'Normal';
            //     $input_data['spo2FlagColor'] = 'success';
            //     $input_data['range_code']    = '#008000';
            // }
        }

        return $input_data;
    }

    public static function respiration_flag($input_data, $years, $dob)
    {
        $input_data['respirationFlag']      = '';
        $input_data['respirationFlagColor'] = '';
        $input_data['range_code']    = '';

        if(empty($dob) || $dob == '0000-00-00'){
            $years = 20;
        }

        if (!empty($input_data['respiration'])) {

            if($years <= 1){
                if ($input_data['respiration'] < 30) {
                    $input_data['respirationFlag']      = 'Low(Bradypnea)';
                    $input_data['respirationFlagColor'] = 'primary';
                    $input_data['range_code']    = '#0000ff';
                }

                if (($input_data['respiration'] >= 30) && ($input_data['respiration'] <= 60)) {
                    $input_data['respirationFlag']      = 'Normal(Eupnea)';
                    $input_data['respirationFlagColor'] = 'success';
                    $input_data['range_code']    = '#008000';
                }

                if (($input_data['respiration'] >= 61) && ($input_data['respiration'] <= 70)) {
                    $input_data['respirationFlag']      = 'Slightly Increased (Mild Tachypnea)';
                    $input_data['respirationFlagColor'] = 'danger';
                    $input_data['range_code']    = '#fff707';
                }

                if (($input_data['respiration'] >= 71) && ($input_data['respiration'] <= 80)) {
                    $input_data['respirationFlag']      = 'Moderately Increased (Moderate Tachypnea)';
                    $input_data['respirationFlagColor'] = 'danger';
                    $input_data['range_code']    = '#FFC107';
                }

                if ($input_data['respiration'] > 80) {
                    $input_data['respirationFlag']      = 'Severely High (Severe Tachypnea)';
                    $input_data['respirationFlagColor'] = 'danger';
                    $input_data['range_code']    = '#FF0000';
                }
            }

            if($years >= 1 && $years <= 3){
                if ($input_data['respiration'] < 24) {
                    $input_data['respirationFlag']      = 'Low(Bradypnea)';
                    $input_data['respirationFlagColor'] = 'primary';
                    $input_data['range_code']    = '#0000ff';
                }

                if (($input_data['respiration'] >= 24) && ($input_data['respiration'] <= 40)) {
                    $input_data['respirationFlag']      = 'Normal(Eupnea)';
                    $input_data['respirationFlagColor'] = 'success';
                    $input_data['range_code']    = '#008000';
                }

                if (($input_data['respiration'] >= 41) && ($input_data['respiration'] <= 50)) {
                    $input_data['respirationFlag']      = 'Slightly Increased (Mild Tachypnea)';
                    $input_data['respirationFlagColor'] = 'danger';
                    $input_data['range_code']    = '#fff707';
                }

                if (($input_data['respiration'] >= 51) && ($input_data['respiration'] <= 60)) {
                    $input_data['respirationFlag']      = 'Moderately Increased (Moderate Tachypnea)';
                    $input_data['respirationFlagColor'] = 'danger';
                    $input_data['range_code']    = '#FFC107';
                }

                if ($input_data['respiration'] > 60) {
                    $input_data['respirationFlag']      = 'Severely High (Severe Tachypnea)';
                    $input_data['respirationFlagColor'] = 'danger';
                    $input_data['range_code']    = '#FF0000';
                }
            }

            if($years >= 4 && $years <= 5){
                if ($input_data['respiration'] < 20) {
                    $input_data['respirationFlag']      = 'Low(Bradypnea)';
                    $input_data['respirationFlagColor'] = 'primary';
                    $input_data['range_code']    = '#0000ff';
                }

                if (($input_data['respiration'] >= 20) && ($input_data['respiration'] <= 30)) {
                    $input_data['respirationFlag']      = 'Normal(Eupnea)';
                    $input_data['respirationFlagColor'] = 'success';
                    $input_data['range_code']    = '#008000';
                }

                if (($input_data['respiration'] >= 31) && ($input_data['respiration'] <= 40)) {
                    $input_data['respirationFlag']      = 'Slightly Increased (Mild Tachypnea)';
                    $input_data['respirationFlagColor'] = 'danger';
                    $input_data['range_code']    = '#fff707';
                }

                if (($input_data['respiration'] >= 41) && ($input_data['respiration'] <= 50)) {
                    $input_data['respirationFlag']      = 'Moderately Increased (Moderate Tachypnea)';
                    $input_data['respirationFlagColor'] = 'danger';
                    $input_data['range_code']    = '#FFC107';
                }

                if ($input_data['respiration'] > 50) {
                    $input_data['respirationFlag']      = 'Severely High (Severe Tachypnea)';
                    $input_data['respirationFlagColor'] = 'danger';
                    $input_data['range_code']    = '#FF0000';
                }
            }

            if($years >= 6 && $years <= 12){
                if ($input_data['respiration'] < 18) {
                    $input_data['respirationFlag']      = 'Low(Bradypnea)';
                    $input_data['respirationFlagColor'] = 'primary';
                    $input_data['range_code']    = '#0000ff';
                }

                if (($input_data['respiration'] >= 18) && ($input_data['respiration'] <= 30)) {
                    $input_data['respirationFlag']      = 'Normal(Eupnea)';
                    $input_data['respirationFlagColor'] = 'success';
                    $input_data['range_code']    = '#008000';
                }

                if (($input_data['respiration'] >= 31) && ($input_data['respiration'] <= 40)) {
                    $input_data['respirationFlag']      = 'Slightly Increased (Mild Tachypnea)';
                    $input_data['respirationFlagColor'] = 'danger';
                    $input_data['range_code']    = '#fff707';
                }

                if (($input_data['respiration'] >= 41) && ($input_data['respiration'] <= 50)) {
                    $input_data['respirationFlag']      = 'Moderately Increased (Moderate Tachypnea)';
                    $input_data['respirationFlagColor'] = 'danger';
                    $input_data['range_code']    = '#FFC107';
                }

                if ($input_data['respiration'] > 50) {
                    $input_data['respirationFlag']      = 'Severely High (Severe Tachypnea)';
                    $input_data['respirationFlagColor'] = 'danger';
                    $input_data['range_code']    = '#FF0000';
                }
            }

            if($years >= 13 && $years <= 65){
                if ($input_data['respiration'] < 12) {
                    $input_data['respirationFlag']      = 'Low(Bradypnea)';
                    $input_data['respirationFlagColor'] = 'primary';
                    $input_data['range_code']    = '#0000ff';
                }

                if (($input_data['respiration'] >= 12) && ($input_data['respiration'] <= 20)) {
                    $input_data['respirationFlag']      = 'Normal(Eupnea)';
                    $input_data['respirationFlagColor'] = 'success';
                    $input_data['range_code']    = '#008000';
                }

                if (($input_data['respiration'] >= 21) && ($input_data['respiration'] <= 25)) {
                    $input_data['respirationFlag']      = 'Slightly Increased (Mild Tachypnea)';
                    $input_data['respirationFlagColor'] = 'danger';
                    $input_data['range_code']    = '#fff707';
                }

                if (($input_data['respiration'] >= 26) && ($input_data['respiration'] <= 30)) {
                    $input_data['respirationFlag']      = 'Moderately Increased (Moderate Tachypnea)';
                    $input_data['respirationFlagColor'] = 'danger';
                    $input_data['range_code']    = '#FFC107';
                }

                if ($input_data['respiration'] > 30) {
                    $input_data['respirationFlag']      = 'Severely High (Severe Tachypnea)';
                    $input_data['respirationFlagColor'] = 'danger';
                    $input_data['range_code']    = '#FF0000';
                }
            }

            if($years > 65 ){
                if ($input_data['respiration'] < 12) {
                    $input_data['respirationFlag']      = 'Low(Bradypnea)';
                    $input_data['respirationFlagColor'] = 'primary';
                    $input_data['range_code']    = '#0000ff';
                }

                if (($input_data['respiration'] >= 12) && ($input_data['respiration'] <= 22)) {
                    $input_data['respirationFlag']      = 'Normal(Eupnea)';
                    $input_data['respirationFlagColor'] = 'success';
                    $input_data['range_code']    = '#008000';
                }

                if (($input_data['respiration'] >= 23) && ($input_data['respiration'] <= 26)) {
                    $input_data['respirationFlag']      = 'Slightly Increased (Mild Tachypnea)';
                    $input_data['respirationFlagColor'] = 'danger';
                    $input_data['range_code']    = '#fff707';
                }

                if (($input_data['respiration'] >= 27) && ($input_data['respiration'] <= 30)) {
                    $input_data['respirationFlag']      = 'Moderately Increased (Moderate Tachypnea)';
                    $input_data['respirationFlagColor'] = 'danger';
                    $input_data['range_code']    = '#FFC107';
                }

                if ($input_data['respiration'] > 30) {
                    $input_data['respirationFlag']      = 'Severely High (Severe Tachypnea)';
                    $input_data['respirationFlagColor'] = 'danger';
                    $input_data['range_code']    = '#FF0000';
                }
            }
        }

        // if (!empty($input_data['respiration'])) {

        //     if($years <= 1){
        //         if ($input_data['respiration'] < 30) {
        //             $input_data['respirationFlag']      = 'Low';
        //             $input_data['respirationFlagColor'] = 'primary';
        //             $input_data['range_code']    = '#0000ff';
        //         }

        //         if (($input_data['respiration'] >= 30) && ($input_data['respiration'] <= 40)) {
        //             $input_data['respirationFlag']      = 'Normal';
        //             $input_data['respirationFlagColor'] = 'success';
        //             $input_data['range_code']    = '#008000';
        //         }

        //         if ($input_data['respiration'] > 40) {
        //             $input_data['respirationFlag']      = 'High';
        //             $input_data['respirationFlagColor'] = 'danger';
        //             $input_data['range_code']    = '#ff0000';
        //         }
        //     }

        //     if($years >= 2 && $years <= 5){
        //         if ($input_data['respiration'] < 20) {
        //             $input_data['respirationFlag']      = 'Low';
        //             $input_data['respirationFlagColor'] = 'primary';
        //             $input_data['range_code']    = '#0000ff';
        //         }

        //         if (($input_data['respiration'] >= 20) && ($input_data['respiration'] <= 40)) {
        //             $input_data['respirationFlag']      = 'Normal';
        //             $input_data['respirationFlagColor'] = 'success';
        //             $input_data['range_code']    = '#008000';
        //         }

        //         if ($input_data['respiration'] > 40) {
        //             $input_data['respirationFlag']      = 'High';
        //             $input_data['respirationFlagColor'] = 'danger';
        //             $input_data['range_code']    = '#ff0000';
        //         }
        //     }

        //     if($years >= 6 && $years <= 10){
        //         if ($input_data['respiration'] < 15) {
        //             $input_data['respirationFlag']      = 'Low';
        //             $input_data['respirationFlagColor'] = 'primary';
        //             $input_data['range_code']    = '#0000ff';
        //         }

        //         if (($input_data['respiration'] >= 15) && ($input_data['respiration'] <= 25)) {
        //             $input_data['respirationFlag']      = 'Normal';
        //             $input_data['respirationFlagColor'] = 'success';
        //             $input_data['range_code']    = '#008000';
        //         }

        //         if ($input_data['respiration'] > 25) {
        //             $input_data['respirationFlag']      = 'High';
        //             $input_data['respirationFlagColor'] = 'danger';
        //             $input_data['range_code']    = '#ff0000';
        //         }
        //     }

        //     if(($years >= 11 && $years <= 18) || ($years > 70)){
        //         if ($input_data['respiration'] < 15) {
        //             $input_data['respirationFlag']      = 'Low';
        //             $input_data['respirationFlagColor'] = 'primary';
        //             $input_data['range_code']    = '#0000ff';
        //         }

        //         if (($input_data['respiration'] >= 15) && ($input_data['respiration'] <= 20)) {
        //             $input_data['respirationFlag']      = 'Normal';
        //             $input_data['respirationFlagColor'] = 'success';
        //             $input_data['range_code']    = '#008000';
        //         }

        //         if ($input_data['respiration'] > 20) {
        //             $input_data['respirationFlag']      = 'High';
        //             $input_data['respirationFlagColor'] = 'danger';
        //             $input_data['range_code']    = '#ff0000';
        //         }
        //     }

        //     if($years >= 18 && $years <= 70){
        //         if ($input_data['respiration'] < 12) {
        //             $input_data['respirationFlag']      = 'Low';
        //             $input_data['respirationFlagColor'] = 'primary';
        //             $input_data['range_code']    = '#0000ff';
        //         }

        //         if (($input_data['respiration'] >= 12) && ($input_data['respiration'] <= 20)) {
        //             $input_data['respirationFlag']      = 'Normal';
        //             $input_data['respirationFlagColor'] = 'success';
        //             $input_data['range_code']    = '#008000';
        //         }

        //         if ($input_data['respiration'] > 20) {
        //             $input_data['respirationFlag']      = 'High';
        //             $input_data['respirationFlagColor'] = 'danger';
        //             $input_data['range_code']    = '#ff0000';
        //         }
        //     }
        // }

        return $input_data;
    }



    public static function urine_flag($input_data)
    {
        $input_data['leukocytes_message']    = '';
        $input_data['leukocytes_flag']       = '';
        $input_data['leukocytes_range_code'] = '';

        $input_data['color_message']    = '';
        $input_data['color_flag']       = '';
        $input_data['color_range_code'] = '';

        $input_data['Specific_gravity_message']    = '';
        $input_data['Specific_gravity_flag']       = '';
        $input_data['Specific_gravity_range_code'] = '';

        $input_data['glucose_message']    = '';
        $input_data['glucose_flag']       = '';
        $input_data['glucose_range_code'] = '';

        $input_data['Ketones_message']    = '';
        $input_data['Ketones_flag']       = '';
        $input_data['Ketones_range_code'] = '';

        $input_data['blood_in_urine_message']    = '';
        $input_data['blood_in_urine_flag']       = '';
        $input_data['blood_in_urine_range_code'] = '';

        $input_data['protein_message']               = '';
        $input_data['protein_flag']          = '';
        $input_data['protein_range_code']    = '';

        $input_data['Nitrites_message']               = '';
        $input_data['Nitrites_flag']          = '';
        $input_data['Nitrites_range_code']    = '';
        
        $input_data['Urobilinogen_message']               = '';
        $input_data['Urobilinogen_flag']          = '';
        $input_data['Urobilinogen_range_code']    = '';
        
        $input_data['bilirubin_message']               = '';
        $input_data['bilirubin_flag']          = '';
        $input_data['bilirubin_range_code']    = '';

        $input_data['rbc_message']              = '';
        $input_data['rbc_flag']         = '';
        $input_data['rbc_range_code']   = '';

        $input_data['wbc_message']              = '';
        $input_data['wbc_flag']         = '';
        $input_data['wbc_range_code']   = '';

        $input_data['value']              = '';
        $input_data['value_flag']         = '';
        $input_data['value_range_code']   = '';

        $input_data['ph_message']              = '';
        $input_data['ph_flag']         = '';
        $input_data['ph_range_code']   = '';

        $input_data['sugar_message']    = '';
        $input_data['sugar_flag']       = '';
        $input_data['sugar_range_code'] = '';

        if (!empty($input_data['leukocytes'])) {

            switch ($input_data['leukocytes']) {
                case 'Negative':
                    $input_data['leukocytes_message']            = 'Normal';
                    $input_data['leukocytes_flag']       = 'success';
                    $input_data['leukocytes_range_code'] = '#008000';
                    break;
                    case 'Trace':
                        $input_data['leukocytes_message']            = 'Below Normal';
                        $input_data['leukocytes_flag']       = 'success';
                        $input_data['leukocytes_range_code'] = '#89d4f5';
                        break;
                case '+ (100 mg/dL)':
                    $input_data['leukocytes_message']            = 'Moderately High';
                    $input_data['leukocytes_flag']       = 'success';
                    $input_data['leukocytes_range_code'] = '#fff707';
                    break;
                case '++ (250 mg/dL)':
                    $input_data['leukocytes_message']            = 'High';
                    $input_data['leukocytes_flag']       = 'warning';
                    $input_data['leukocytes_range_code'] = '#ffc107';
                    break;
                case '+++ (500 mg/dL)':
                    $input_data['leukocytes_message']            = 'Very High';
                    $input_data['leukocytes_flag']       = 'danger';
                    $input_data['leukocytes_range_code'] = '#ff0000';
                    break;
                case '++++ (1000 mg/dL)':
                        $input_data['leukocytes_message']            = 'Extremely High';
                        $input_data['leukocytes_flag']       = 'danger';
                        $input_data['leukocytes_range_code'] = '#8B0000';
                        break;
            }
        }

        if (!empty($input_data['color'])) {

            switch ($input_data['color']) {
                case 'Straw':
                    // $input_data['Color_message']            = 'Small';
                    // $input_data['Color_flag']       = 'success';
                    $input_data['color_range_code'] = '#E4D96F';
                    break;
                case 'Yellow':
                    // $input_data['Color_message']            = 'Small';
                    // $input_data['Color_flag']       = 'success';
                    $input_data['color_range_code'] = '#fff707';
                    break;
                case 'Amber': 
                        // $input_data['Color_message']            = 'Small';
                        // $input_data['Color_flag']       = 'success';
                    $input_data['color_range_code'] = '#FFC107';
                    break;
                case 'Red':
                    // $input_data['Color_message']            = 'Moderate';
                    // $input_data['Color_flag']       = 'warning';
                    $input_data['color_range_code'] = '#FF0000';
                    break;
                case 'Brown':
                    // $input_data['Color_message']            = 'Large';
                    // $input_data['Color_flag']       = 'danger';
                    $input_data['color_range_code'] = '#8B4513';
                    break;
                case 'Green':
                        // $input_data['Color_message']            = 'Large';
                        // $input_data['Color_flag']       = 'danger';
                        $input_data['color_range_code'] = '#008000';
                        break;
            }
        }

        if (!empty($input_data['protein'])) {

            switch ($input_data['protein']) {
                case '+ (30 mg/dL)':
                    $input_data['protein_message']    = 'Small';
                    $input_data['protein_flag']       = 'success';
                    $input_data['protein_range_code'] = '#008000';
                    break;
                case '++ (100 mg/dL)':
                    $input_data['protein_message']            = 'Moderate';
                    $input_data['protein_flag']       = 'warning';
                    $input_data['protein_range_code'] = '#ffc107';
                    break;
                case '+++ (300 mg/dL)':
                    $input_data['protein_message']            = 'Large';
                    $input_data['protein_flag']       = 'danger';
                    $input_data['protein_range_code'] = '#ff0000';
                    break;
                case '++++ (2000 mg/dL)':
                    $input_data['protein_message']            = 'Very Large';
                    $input_data['protein_flag']       = 'danger';
                    $input_data['protein_range_code'] = '#ff0000';
                    break;
            }
        }

        if (!empty($input_data['rbc'])) {

            switch ($input_data['rbc']) {
                case '+':
                    $input_data['rbc_message']            = 'Small';
                    $input_data['rbc_flag']       = 'success';
                    $input_data['rbc_range_code'] = '#008000';
                    break;
                case '++':
                    $input_data['rbc_message']            = 'Moderate';
                    $input_data['rbc_flag']       = 'warning';
                    $input_data['rbc_range_code'] = '#ffc107';
                    break;
                case '+++':
                    $input_data['rbc_message']            = 'Large';
                    $input_data['rbc_flag']       = 'danger';
                    $input_data['rbc_range_code'] = '#ff0000';
                    break;
            }
        }

        if (!empty($input_data['sugar_unit'])) {


            $input_data['sugar_message']    = 'Low';
            $input_data['sugar_flag']       = 'danger';
            $input_data['sugar_range_code'] = '#ff0000';

            if ($input_data['sugar_unit'] == 'mg/dL') {

                if ($input_data['sugar'] >= 100 && $input_data['sugar'] <= 249) {

                        $input_data['sugar_message']    = 'Normal';
                        $input_data['sugar_flag']       = 'success';
                        $input_data['sugar_range_code'] = '#008000';
                }

                if ($input_data['sugar'] >= 250 && $input_data['sugar'] <= 499) {

                        $input_data['sugar_message']    = 'Small';
                        $input_data['sugar_flag']       = 'warning';
                        $input_data['sugar_range_code'] = '#ffc107';
                }

                if ($input_data['sugar'] >= 500 && $input_data['sugar'] <= 999) {

                        $input_data['sugar_message']    = 'High';
                        $input_data['sugar_flag']       = 'danger';
                        $input_data['sugar_range_code'] = '#ff0000';
                }

                if ($input_data['sugar'] >= 1000) {

                        $input_data['sugar_message']    = 'Very High';
                        $input_data['sugar_flag']       = 'danger';
                        $input_data['sugar_range_code'] = '#ff0000';
                }

            }

            if ($input_data['sugar_unit'] == 'mmol/L') {

                if ($input_data['sugar'] >= 5.55 && $input_data['sugar'] <= 11.0) {

                        $input_data['sugar_message']    = 'Normal';
                        $input_data['sugar_flag']       = 'success';
                        $input_data['sugar_range_code'] = '#008000';
                }

                if ($input_data['sugar'] >= 11.1 && $input_data['sugar'] <= 27.74) {

                        $input_data['sugar_message']    = 'Small';
                        $input_data['sugar_flag']       = 'warning';
                        $input_data['sugar_range_code'] = '#ffc107';
                }

                if ($input_data['sugar'] >= 27.5 && $input_data['sugar'] <= 55.4) {

                        $input_data['sugar_message']    = 'High';
                        $input_data['sugar_flag']       = 'danger';
                        $input_data['sugar_range_code'] = '#ff0000';
                }

                if ($input_data['sugar'] >= 55.5) {

                        $input_data['sugar_message']    = 'Very High';
                        $input_data['sugar_flag']       = 'danger';
                        $input_data['sugar_range_code'] = '#ff0000';
                }


            }

        }

        if (!empty($input_data['urine'])) {

            if ($input_data['urine'] > 0) {
                if ($input_data['urine'] < 6.0) {
                    $input_data['value']            = 'Very Acidic';
                    $input_data['value_flag']       = 'danger';
                    $input_data['value_range_code'] = '#ff0000';
                }

                if (($input_data['urine'] >= 6.0) && ($input_data['urine'] < 6.5)) {
                    // $input_data['value']            = 'Acidic';
                    $input_data['value_flag']       = 'danger';
                    $input_data['value_range_code'] = '#ff0000';
                }

                if (($input_data['urine'] >= 6.5) && ($input_data['urine'] < 7)) {
                    // $input_data['value']            = 'Moderate';
                    $input_data['value_flag']       = 'warning';
                    $input_data['value_range_code'] = '#ffc107';
                }


                if (($input_data['urine'] >= 7) && ($input_data['urine'] < 8)) {
                    // $input_data['value']            = 'Optimal';
                    $input_data['value_flag']       = 'success';
                    $input_data['value_range_code'] = '#008000';
                }

                if ($input_data['urine'] >= 8) {
                    // $input_data['value']            = 'Too Alkaline';
                    $input_data['value_flag']       = 'danger';
                    $input_data['value_range_code'] = '#ff0000';
                }

            }
        }
        if (!empty($input_data['ph'])) {

       
                if ($input_data['ph'] < 6) {
                     $input_data['ph_message']            = 'Acidic';
                    $input_data['ph_flag']       = 'danger';
                    $input_data['ph_range_code'] = '#FF0000';
                }

                if (($input_data['ph'] >= 6) && ($input_data['urine'] < 7)) {
                     $input_data['ph_message']            = 'Normal';
                    $input_data['ph_flag']       = 'danger';
                    $input_data['ph_range_code'] = '#008000';
                }

                if ($input_data['ph'] >= 7) {
                     $input_data['ph_message']            = 'Alkaline';
                    $input_data['ph_flag']       = 'danger';
                    $input_data['ph_range_code'] = '#0000ff';
                }

            
        }
        if (!empty($input_data['Specific_gravity'])) {

            if ($input_data['Specific_gravity'] > 0) {

                if (($input_data['Specific_gravity'] >= 1.001) && ($input_data['urine'] < 1.005)) {
                    $input_data['Specific_gravity_message']            = 'Very Dilute';
                    $input_data['Specific_gravity_flag']       = 'danger';
                    $input_data['Specific_gravity_range_code'] = '#89d4f5';
                }

                if (($input_data['Specific_gravity'] >= 1.006) && ($input_data['urine'] < 1.010)) {
                    $input_data['Specific_gravity_message']            = 'Dilute';
                    // $input_data['Specific_gravity_flag']       = 'warning';
                    $input_data['Specific_gravity_range_code'] = '#0000ff';
                }


                if (($input_data['Specific_gravity'] >= 1.011) && ($input_data['urine'] < 1.015)) {
                    $input_data['Specific_gravity_message']            = 'Normal';
                    // $input_data['Specific_gravity_flag']       = 'success';
                    $input_data['Specific_gravity_range_code'] = '#008000';
                }
                if (($input_data['Specific_gravity'] >= 1.016) && ($input_data['urine'] < 1.020)) {
                    $input_data['Specific_gravity_message']            = 'Concentrated';
                    // $input_data['Specific_gravity_flag']       = 'success';
                    $input_data['Specific_gravity_range_code'] = '#fff707';
                }
                if (($input_data['Specific_gravity'] >= 1.021) && ($input_data['urine'] < 1.025)) {
                    $input_data['Specific_gravity_message']            = 'Very Concentrated';
                    // $input_data['Specific_gravity_flag']       = 'success';
                    $input_data['Specific_gravity_range_code'] = '#008000';
                }
                if (($input_data['Specific_gravity'] >= 1.026) && ($input_data['urine'] < 1.030)) {
                    $input_data['Specific_gravity_message']            = 'Highly Concentrated';
                    // $input_data['Specific_gravity_flag']       = 'success';
                    $input_data['Specific_gravity_range_code'] = '#FF0000';
                }

            }
        }

        if (!empty($input_data['bilirubin'])) {

            switch ($input_data['bilirubin']) {
                case 'Negative':
                    $input_data['bilirubin_message']    = 'Normal';
                    $input_data['bilirubin_flag']       = 'success';
                    $input_data['bilirubin_range_code'] = '#008000';
                    break;
                case 'Positive':
                        $input_data['bilirubin_message']    = 'Below';
                        $input_data['bilirubin_flag']       = 'success';
                        $input_data['bilirubin_range_code'] = '#FF0000';
                        break;}}
        if (!empty($input_data['glucose'])) {

            switch ($input_data['glucose']) {
                case 'Negative':
                    $input_data['glucose_message']    = 'Normal';
                    $input_data['glucose_flag']       = 'success';
                    $input_data['glucose_range_code'] = '#008000';
                    break;
                case 'Trace':
                        $input_data['glucose_message']    = 'Below';
                        $input_data['glucose_flag']       = 'success';
                        $input_data['glucose_range_code'] = '#89d4f5';
                        break;
                case '+ (100 mg/dL)':
                    $input_data['glucose_message']    = 'Moderately High';
                    $input_data['glucose_flag']       = 'success';
                    $input_data['glucose_range_code'] = '#fff707';
                    break;
                case '++ (250 mg/dL)':
                    $input_data['glucose_message']            = 'High';
                    $input_data['glucose_flag']       = 'warning';
                    $input_data['glucose_range_code'] = '#ffc107';
                    break;
                case '+++ (500 mg/dL)':
                    $input_data['glucose_message']            = 'Very High';
                    $input_data['glucose_flag']       = 'danger';
                    $input_data['glucose_range_code'] = '#ff0000';
                    break;
                case '++++ (1000 mg/dL)':
                        $input_data['glucose_message']            = 'Extremely High';
                        $input_data['glucose_flag']       = 'danger';
                        $input_data['glucose_range_code'] = '#8B0000';
                        break;
            }
        }
        if (!empty($input_data['Ketones'])) {

            switch ($input_data['Ketones']) {
                case 'Negative':
                    $input_data['Ketones_message']    = 'Normal';
                    $input_data['Ketones_flag']       = 'success';
                    $input_data['Ketones_range_code'] = '#008000';
                    break;
                case 'Trace':
                        $input_data['Ketones_message']    = 'Below';
                        $input_data['Ketones_flag']       = 'success';
                        $input_data['Ketones_range_code'] = '#89d4f5';
                        break;
                case '+ (100 mg/dL)':
                    $input_data['Ketones_message']    = 'Moderately High';
                    $input_data['Ketones_flag']       = 'success';
                    $input_data['Ketones_range_code'] = '#fff707';
                    break;
                case '++ (250 mg/dL)':
                    $input_data['Ketones_message']            = 'High';
                    $input_data['Ketones_flag']       = 'warning';
                    $input_data['Ketones_range_code'] = '#ffc107';
                    break;
                case '+++ (500 mg/dL)':
                    $input_data['Ketones_message']            = 'Very High';
                    $input_data['Ketones_flag']       = 'danger';
                    $input_data['Ketones_range_code'] = '#ff0000';
                    break;
                case '++++ (1000 mg/dL)':
                        $input_data['Ketones_message']            = 'Extremely High';
                        $input_data['Ketones_flag']       = 'danger';
                        $input_data['Ketones_range_code'] = '#8B0000';
                        break;
            }
        }
        if (!empty($input_data['blood_in_urine'])) {

            switch ($input_data['blood_in_urine']) {
                case 'Negative':
                    $input_data['blood_in_urine_message']    = 'Normal';
                    $input_data['blood_in_urine_flag']       = 'success';
                    $input_data['blood_in_urine_range_code'] = '#008000';
                    break;
                case 'Trace':
                        $input_data['blood_in_urine_message']    = 'Below Normal';
                        $input_data['blood_in_urine_flag']       = 'success';
                        $input_data['blood_in_urine_range_code'] = '#89d4f5';
                        break;
                case '+ 100 mg/dL':
                    $input_data['blood_in_urine_message']    = 'Mild';
                    $input_data['blood_in_urine_flag']       = 'success';
                    $input_data['blood_in_urine_range_code'] = '#fff707';
                    break;
                case '++ 250 mg/dL':
                    $input_data['blood_in_urine_message']            = 'Moderate ';
                    $input_data['blood_in_urine_flag']       = 'warning';
                    $input_data['blood_in_urine_range_code'] = '#ffc107';
                    break;
                case '+++ 500 mg/dL':
                    $input_data['blood_in_urine_message']            = 'High';
                    $input_data['blood_in_urine_flag']       = 'danger';
                    $input_data['blood_in_urine_range_code'] = '#ff0000';
                    break;
                case '++++ 1000 mg/dL':
                        $input_data['blood_in_urine_message']            = 'Very High';
                        $input_data['blood_in_urine_flag']       = 'danger';
                        $input_data['blood_in_urine_range_code'] = '#8B0000';
                        break;
            }
        }
        return $input_data;
    }


    public static function blood_pressure_flag($input_data,  $years, $months, $days){
        $input_data['bpFlag']      = '';
        $input_data['bpFlagColor'] = '';
        $input_data['range_code']  = '';

        if (!empty($input_data['systolic']) && !empty($input_data['diastolic'])){
            if ($years <= 60) {
                // 0 - 29 Days
                if ($years == 0 && $months == 0 && $days >= 0 && $days <= 29) {
                    if (($input_data['systolic'] <= 59) )
                    //  ($input_data['diastolic'] <= 29)
                  {
                        $input_data['bpFlag']      = 'Low BP (Hypotension)';
                        $input_data['bpFlagColor'] = 'warning';
                        $input_data['range_code']  = '#0000ff';
                    }

                    if ((($input_data['systolic'] >= 60) && ($input_data['systolic'] <= 90)) ) 
                    // || (($input_data['diastolic'] >= 30) && ($input_data['diastolic'] <= 60))
                 {
                        $input_data['bpFlag']      = 'Normal BP ';
                        $input_data['bpFlagColor'] = 'success';
                        $input_data['range_code']  = '#008000';
                    }
                    if ((($input_data['systolic'] >= 91) && ($input_data['systolic'] <= 100)) )
                    // || (($input_data['diastolic'] >= 61) && ($input_data['diastolic'] <= 65))
                 {
                        $input_data['bpFlag']      = 'Elevated BP (Pre Hypertension )';
                        $input_data['bpFlagColor'] = 'warning';
                        $input_data['range_code']  = '#fff707';
                    }
                    if ((($input_data['systolic'] >= 101) && ($input_data['systolic'] <= 110)) )
                    // || (($input_data['diastolic'] >= 66) && ($input_data['diastolic'] <= 75))
                 {
                        $input_data['bpFlag']      = 'Hypertension Stage 1';
                        $input_data['bpFlagColor'] = 'warning';
                        $input_data['range_code']  = '#FFC107';
                    }
                    if ((($input_data['systolic'] >= 111) && ($input_data['systolic'] <= 119)) )
                    // || (($input_data['diastolic'] >= 76) && ($input_data['diastolic'] <= 84))
                 {
                        $input_data['bpFlag']      = 'Hypertension Stage 2';
                        $input_data['bpFlagColor'] = 'warning';
                        $input_data['range_code']  = '#FFC107';
                    }
                    if ((($input_data['systolic'] >= 120) && ($input_data['systolic'] <= 179)) )
                    // || (($input_data['diastolic'] >= 85) && ($input_data['diastolic'] <= 119))
                 {
                        $input_data['bpFlag']      = 'Severely High BP';
                        $input_data['bpFlagColor'] = 'warning';
                        $input_data['range_code']  = '#ff0000';
                    }
                    if (($input_data['systolic'] >= 180) ) 
                    // || ($input_data['diastolic'] >= 120)
                {
                        $input_data['bpFlag']      = 'Hypertensive Crisis';
                        $input_data['bpFlagColor'] = 'danger';
                        $input_data['range_code']  = '#ff0000';
                    }
                }
            
                // 1 - 12 Months
                if ($months >= 1 && $months <= 12) {
                    if (($input_data['systolic'] <= 69) ) 
                    // && ($input_data['diastolic'] <= 39)
                {
                        $input_data['bpFlag']      = 'Low BP (Hypotension)';
                        $input_data['bpFlagColor'] = 'warning';
                        $input_data['range_code']  = '#0000ff';
                    }

                    if ((($input_data['systolic'] >= 70) && ($input_data['systolic'] <= 100)) )
                    // || (($input_data['diastolic'] >= 40) && ($input_data['diastolic'] <= 60))
                 {
                        $input_data['bpFlag']      = 'Normal BP';
                        $input_data['bpFlagColor'] = 'success';
                        $input_data['range_code']  = '#008000';
                    }
                    if ((($input_data['systolic'] >= 101) && ($input_data['systolic'] <= 110)) ) 
                    // || (($input_data['diastolic'] >= 61) && ($input_data['diastolic'] <= 70)) 
                {
                        $input_data['bpFlag']      = 'Elevated BP (Pre Hypertension )';
                        $input_data['bpFlagColor'] = 'warning';
                        $input_data['range_code']  = '#fff707';
                    }
                    if ((($input_data['systolic'] >= 111) && ($input_data['systolic'] <= 120)) ) 
                    // || (($input_data['diastolic'] >= 71) && ($input_data['diastolic'] <= 80)) 
                {
                        $input_data['bpFlag']      = 'Hypertension Stage 1';
                        $input_data['bpFlagColor'] = 'Pre Hypertension ';
                        $input_data['range_code']  = '#FFC107';
                    }
                    if ((($input_data['systolic'] >= 121) && ($input_data['systolic'] <= 129)) )
                    // || (($input_data['diastolic'] >= 81) && ($input_data['diastolic'] <= 90))
                 {
                        $input_data['bpFlag']      = 'Hypertension Stage 2';
                        $input_data['bpFlagColor'] = 'Pre Hypertension ';
                        $input_data['range_code']  = '#FFC107';
                    }
                    if ((($input_data['systolic'] >= 130) && ($input_data['systolic'] <= 179)) )
                    // || (($input_data['diastolic'] >= 91) && ($input_data['diastolic'] <= 119))
                 {
                        $input_data['bpFlag']      = 'Severely High BP';
                        $input_data['bpFlagColor'] = 'Pre Hypertension ';
                        $input_data['range_code']  = '#ff0000';
                    }
                    if (($input_data['systolic'] >= 180) )
                    // || ($input_data['diastolic'] >= 120)
                 {
                        $input_data['bpFlag']      = 'Hypertensive Crisis';
                        $input_data['bpFlagColor'] = 'danger';
                        $input_data['range_code']  = '#ff0000';
                    }
                    
                }
    
                // 1 - 5 Years
                if (($years >= 1) && ($years <= 5)) {
                    if (($input_data['systolic'] <= 79) )
                    // || ($input_data['diastolic'] <= 49)
                 {
                        $input_data['bpFlag']      = 'Low BP (Hypotension)';
                        $input_data['bpFlagColor'] = 'warning';
                        $input_data['range_code']  = '#0000ff';
                    }

                    if ((($input_data['systolic'] >= 80) && ($input_data['systolic'] <= 110)) )
                    // || (($input_data['diastolic'] >= 50) && ($input_data['diastolic'] <= 70))
                 {
                        $input_data['bpFlag']      = 'Normal BP';
                        $input_data['bpFlagColor'] = 'success';
                        $input_data['range_code']  = '#008000';
                    }
                    if ((($input_data['systolic'] >= 111) && ($input_data['systolic'] <= 120)) )
                    // || (($input_data['diastolic'] >= 71) && ($input_data['diastolic'] <= 80))
                 {
                        $input_data['bpFlag']      = 'Elevated BP (Pre Hypertension)';
                        $input_data['bpFlagColor'] = 'warning';
                        $input_data['range_code']  = '#fff707';
                    }
                    if ((($input_data['systolic'] >= 121) && ($input_data['systolic'] <= 130)) )
                    // || (($input_data['diastolic'] >= 81) && ($input_data['diastolic'] <= 90))
                 {
                        $input_data['bpFlag']      = 'Hypertension Stage 1';
                        $input_data['bpFlagColor'] = 'warning';
                        $input_data['range_code']  = '#FFC107';
                    }
                    if ((($input_data['systolic'] >= 131) && ($input_data['systolic'] <= 139)) )
                    // || (($input_data['diastolic'] >= 91) && ($input_data['diastolic'] <= 100))
                 {
                        $input_data['bpFlag']      = 'Hypertension Stage 2';
                        $input_data['bpFlagColor'] = 'warning';
                        $input_data['range_code']  = '#FFC107';
                    }
                    if ((($input_data['systolic'] >= 140) && ($input_data['systolic'] <= 179)) )
                    // || (($input_data['diastolic'] >= 101) && ($input_data['diastolic'] <= 119))
                 {
                        $input_data['bpFlag']      = 'Severely High BP';
                        $input_data['bpFlagColor'] = 'warning';
                        $input_data['range_code']  = '#ff0000';
                    }
                    if (($input_data['systolic'] >= 180) )
                    // || ($input_data['diastolic'] >= 120)
                 {
                        $input_data['bpFlag']      = 'Hypertensive Crisis';
                        $input_data['bpFlagColor'] = 'danger';
                        $input_data['range_code']  = '#ff0000';
                    }
                }

                // 6 - 13 Years
                if (($years >= 6) && ($years <= 13)) {
                    if (($input_data['systolic'] <= 89) )
                    // || ($input_data['diastolic'] <= 59)
                 {
                        $input_data['bpFlag']      = 'Low BP (Hypotension)';
                        $input_data['bpFlagColor'] = 'warning';
                        $input_data['range_code']  = '#0000ff';
                    } elseif (($input_data['systolic'] >= 90 && $input_data['systolic'] <= 120) )
                    // ||  ($input_data['diastolic'] >= 60 && $input_data['diastolic'] <= 80)
                     {
                        $input_data['bpFlag']      = 'Normal BP';
                        $input_data['bpFlagColor'] = 'success';
                        $input_data['range_code']  = '#008000';
                    } elseif (($input_data['systolic'] >= 121 && $input_data['systolic'] <= 130)) 
                    //  ||($input_data['diastolic'] >= 81 && $input_data['diastolic'] <= 85)
                    {
                        $input_data['bpFlag']      = 'Elevated BP (Pre Hypertension)';
                        $input_data['bpFlagColor'] = 'warning';
                        $input_data['range_code']  = '#fff707';
                    } elseif (($input_data['systolic'] >= 131 && $input_data['systolic'] <= 140) )
                    // ||                    ($input_data['diastolic'] >= 86 && $input_data['diastolic'] <= 90)
                     {
                        $input_data['bpFlag']      = 'Hypertension Stage 1';
                        $input_data['bpFlagColor'] = 'warning';
                        $input_data['range_code']  = '#FFC107';
                    } elseif (($input_data['systolic'] >= 141 && $input_data['systolic'] <= 149) 
                    // ||       ($input_data['diastolic'] >= 91 && $input_data['diastolic'] <= 100)
                    ) {
                        $input_data['bpFlag']      = 'Hypertension Stage 2';
                        $input_data['bpFlagColor'] = 'warning';
                        $input_data['range_code']  = '#FFC107';
                    } elseif (($input_data['systolic'] >= 150 && $input_data['systolic'] <= 179))
                    // ||                    ($input_data['diastolic'] >= 91 && $input_data['diastolic'] <= 119) 
                     {
                        $input_data['bpFlag']      = 'Severely High BP';
                        $input_data['bpFlagColor'] = 'warning';
                        $input_data['range_code']  = '#ff0000';
                    } elseif (($input_data['systolic'] >= 180))
                    //  || ($input_data['diastolic'] >= 120)
                     {
                        $input_data['bpFlag']      = 'Hypertensive Crisis';
                        $input_data['bpFlagColor'] = 'danger';
                        $input_data['range_code']  = '#ff0000';
                    }
                }
                

                // 14 - 18 Years
                if (($years >= 14) && ($years <= 18)) {
                    if (($input_data['systolic'] <= 99) ) 
                    // || ($input_data['diastolic'] <= 59)
                {
                        $input_data['bpFlag']      = 'Low BP (Hypotension)';
                        $input_data['bpFlagColor'] = 'warning';
                        $input_data['range_code']  = '#0000ff';
                    }
    
                    if ((($input_data['systolic'] >= 100) && ($input_data['systolic'] <= 120)) )
                    // || (($input_data['diastolic'] >= 60) && ($input_data['diastolic'] <= 80))
                 {
                        $input_data['bpFlag']      = 'Normal BP';
                        $input_data['bpFlagColor'] = 'success';
                        $input_data['range_code']  = '#008000';
                    }
                    if ((($input_data['systolic'] >= 121) && ($input_data['systolic'] <= 130)) )
                    // || (($input_data['diastolic'] >= 81) && ($input_data['diastolic'] <= 85))
                 {
                        $input_data['bpFlag']      = 'Elevated BP (Pre Hypertension)';
                        $input_data['bpFlagColor'] = 'warning';
                        $input_data['range_code']  = '#fff707';
                    }
                    if ((($input_data['systolic'] >= 131) && ($input_data['systolic'] <= 140)) )
                    // || (($input_data['diastolic'] >= 86) && ($input_data['diastolic'] <= 90))
                 {
                        $input_data['bpFlag']      = 'Hypertension Stage 1';
                        $input_data['bpFlagColor'] = 'warning';
                        $input_data['range_code']  = '#FFC107';
                    }
                    if ((($input_data['systolic'] >= 141) && ($input_data['systolic'] <= 149)) )
                    // || (($input_data['diastolic'] >= 91) && ($input_data['diastolic'] <= 100))
                 {
                        $input_data['bpFlag']      = 'Hypertension Stage 2';
                        $input_data['bpFlagColor'] = 'warning';
                        $input_data['range_code']  = '#FFC107';
                    }
                    if ((($input_data['systolic'] >= 150) && ($input_data['systolic'] <= 179)) )
                    // || (($input_data['diastolic'] >= 91) && ($input_data['diastolic'] <= 119))
                 {
                        $input_data['bpFlag']      = 'Severely High BP';
                        $input_data['bpFlagColor'] = 'warning';
                        $input_data['range_code']  = '#ff0000';
                    }
                    if (($input_data['systolic'] >= 180) )
                    // || ($input_data['diastolic'] >= 120)
                 {
                        $input_data['bpFlag']      = 'Hypertensive Crisis';
                        $input_data['bpFlagColor'] = 'danger';
                        $input_data['range_code']  = '#ff0000';
                    }
                }

                // 19 - 29 Years
                if (($years >= 19) && ($years <= 29)) {
                    if (($input_data['systolic'] <= 89) )
                    //|| ($input_data['diastolic'] <= 59)
                  {
                        $input_data['bpFlag']      = 'Low BP (Hypotension)';
                        $input_data['bpFlagColor'] = 'warning';
                        $input_data['range_code']  = '#0000ff';
                    }
    
                    if ((($input_data['systolic'] >= 90) && ($input_data['systolic'] <= 120)) ) 
                    // || (($input_data['diastolic'] >= 60) && ($input_data['diastolic'] <= 80))
                    {
                        $input_data['bpFlag']      = 'Normal BP';
                        $input_data['bpFlagColor'] = 'success';
                        $input_data['range_code']  = '#008000';
                    }
                    if ((($input_data['systolic'] >= 121) && ($input_data['systolic'] <= 129)) ) 
                    // || (($input_data['diastolic'] >= 81))
                {
                        $input_data['bpFlag']      = 'Elevated BP (Pre Hypertension)';
                        $input_data['bpFlagColor'] = 'warning';
                        $input_data['range_code']  = '#fff707';
                    }
                    if ((($input_data['systolic'] >= 130) && ($input_data['systolic'] <= 139)) )
                    // || (($input_data['diastolic'] = 82) && ($input_data['diastolic'] <= 89))
                 {
                        $input_data['bpFlag']      = 'Hypertension Stage 1';
                        $input_data['bpFlagColor'] = 'warning';
                        $input_data['range_code']  = '#FFC107';
                    }
                    if ((($input_data['systolic'] >= 140) && ($input_data['systolic'] <= 159)) )
                    // || (($input_data['diastolic'] >= 90) && ($input_data['diastolic'] <= 100))
                 {
                        $input_data['bpFlag']      = 'Hypertension Stage 2';
                        $input_data['bpFlagColor'] = 'warning';
                        $input_data['range_code']  = '#FFC107';
                    }
                    if ((($input_data['systolic'] >= 160) && ($input_data['systolic'] <= 179)))
                    //  || (($input_data['diastolic'] >= 101) && ($input_data['diastolic'] <= 119))
                 {
                        $input_data['bpFlag']      = 'Severely High BP';
                        $input_data['bpFlagColor'] = 'warning';
                        $input_data['range_code']  = '#ff0000';
                    }
                    if (($input_data['systolic'] >= 180) )
                    // || ($input_data['diastolic'] >= 120)
                 {
                        $input_data['bpFlag']      = 'Hypertensive Crisis';
                        $input_data['bpFlagColor'] = 'danger';
                        $input_data['range_code']  = '#ff0000';
                    }
                }
                        // 30 - 39 Years
                if (($years >= 30) && ($years <= 39)) {
                    if (($input_data['systolic'] <= 89) )
                    // || ($input_data['diastolic'] <= 59)
                 {
                        $input_data['bpFlag']      = 'Low BP (Hypotension)';
                        $input_data['bpFlagColor'] = 'warning';
                        $input_data['range_code']  = '#0000ff';
                    }
    
                    if ((($input_data['systolic'] >= 90) && ($input_data['systolic'] <= 125)))
                    //  || (($input_data['diastolic'] >= 60) && ($input_data['diastolic'] <= 85))
                 {
                        $input_data['bpFlag']      = 'Normal BP ';
                        $input_data['bpFlagColor'] = 'success';
                        $input_data['range_code']  = '#008000';
                    }
                    if ((($input_data['systolic'] >= 126) && ($input_data['systolic'] <= 129)) )
                    // || (($input_data['diastolic'] >= 86) && ($input_data['diastolic'] <= 89))
                 {
                        $input_data['bpFlag']      = 'Elevated BP (Pre Hypertension)';
                        $input_data['bpFlagColor'] = 'warning';
                        $input_data['range_code']  = '#fff707';
                    }
                    if ((($input_data['systolic'] >= 130) && ($input_data['systolic'] <= 139)))
                    //  || (($input_data['diastolic'] >= 90) && ($input_data['diastolic'] <= 95))
                 {
                        $input_data['bpFlag']      = 'Hypertension Stage 1';
                        $input_data['bpFlagColor'] = 'warning';
                        $input_data['range_code']  = '#FFC107';
                    }
                    if ((($input_data['systolic'] >= 140) && ($input_data['systolic'] <= 159)) )
                    // || (($input_data['diastolic'] >= 96) && ($input_data['diastolic'] <= 105))
                 {
                        $input_data['bpFlag']      = 'Hypertension Stage 2';
                        $input_data['bpFlagColor'] = 'warning';
                        $input_data['range_code']  = '#FFC107';
                    }
                    if ((($input_data['systolic'] >= 160) && ($input_data['systolic'] <= 179)) )
                    // || (($input_data['diastolic'] >= 106) && ($input_data['diastolic'] <= 119))
                 {
                        $input_data['bpFlag']      = 'Severely High BP';
                        $input_data['bpFlagColor'] = 'warning';
                        $input_data['range_code']  = '#ff0000';
                    }
                    if (($input_data['systolic'] >= 180) )
                    // || ($input_data['diastolic'] >= 120)
                 {
                        $input_data['bpFlag']      = 'Hypertensive Crisis';
                        $input_data['bpFlagColor'] = 'danger';
                        $input_data['range_code']  = '#ff0000';
                    }
                }

                //40 - 49 Years
                if (($years >= 40) && ($years <= 49)) {
                    if (($input_data['systolic'] <= 89) )
                    // || ($input_data['diastolic'] <= 59)
                 {
                        $input_data['bpFlag']      = 'Low BP (Hypotension)';
                        $input_data['bpFlagColor'] = 'warning';
                        $input_data['range_code']  = '#0000ff';
                    }
    
                    if ((($input_data['systolic'] >= 90) && ($input_data['systolic'] <= 130)) )
                    // || (($input_data['diastolic'] >= 60) && ($input_data['diastolic'] <= 80))
                 {
                        $input_data['bpFlag']      = 'Normal BP ';
                        $input_data['bpFlagColor'] = 'success';
                        $input_data['range_code']  = '#008000';
                    }
                    if ((($input_data['systolic'] >= 131) && ($input_data['systolic'] <= 139)))
                    //  || (($input_data['diastolic'] >= 90) && ($input_data['diastolic'] <= 94))
                 {
                        $input_data['bpFlag']      = 'Elevated BP (Pre Hypertension)';
                        $input_data['bpFlagColor'] = 'warning';
                        $input_data['range_code']  = '#fff707';
                    }
                    if ((($input_data['systolic'] >= 140) && ($input_data['systolic'] <= 159)) )
                    // || (($input_data['diastolic'] >= 95) && ($input_data['diastolic'] <= 100))
                 {
                        $input_data['bpFlag']      = 'Hypertension Stage 1';
                        $input_data['bpFlagColor'] = 'warning';
                        $input_data['range_code']  = '#FFC107';
                    }
                    if ((($input_data['systolic'] >= 160) && ($input_data['systolic'] <= 169)) )
                    // || (($input_data['diastolic'] >= 101) && ($input_data['diastolic'] <= 110))
                 {
                        $input_data['bpFlag']      = 'Hypertension Stage 2';
                        $input_data['bpFlagColor'] = 'warning';
                        $input_data['range_code']  = '#FFC107';
                    }
                    if ((($input_data['systolic'] >= 170) && ($input_data['systolic'] <= 179)) )
                    // || (($input_data['diastolic'] >= 111) && ($input_data['diastolic'] <= 119))
                 {
                        $input_data['bpFlag']      = 'Severely High BP';
                        $input_data['bpFlagColor'] = 'warning';
                        $input_data['range_code']  = '#ff0000';
                    }
                    if (($input_data['systolic'] >= 180) )
                    // || ($input_data['diastolic'] >= 120)
                 {
                        $input_data['bpFlag']      = 'Hypertensive Crisis';
                        $input_data['bpFlagColor'] = 'danger';
                        $input_data['range_code']  = '#ff0000';
                    }
                }

                // 50 - 59 Years
                if (($years >= 50) && ($years <= 59)) {
                    if (($input_data['systolic'] <= 89) )
                    // || ($input_data['diastolic'] <= 59)
                 {
                        $input_data['bpFlag']      = 'Low BP (Hypotension)';
                        $input_data['bpFlagColor'] = 'warning';
                        $input_data['range_code']  = '#0000ff';
                    }
    
                    if ((($input_data['systolic'] >= 90) && ($input_data['systolic'] <= 135)) )
                    // || (($input_data['diastolic'] >= 60) && ($input_data['diastolic'] <= 90))
                 {
                        $input_data['bpFlag']      = 'Normal BP ';
                        $input_data['bpFlagColor'] = 'success';
                        $input_data['range_code']  = '#008000';
                    }
                    if ((($input_data['systolic'] >= 136) && ($input_data['systolic'] <= 139)) )
                    // || (($input_data['diastolic'] >= 91) && ($input_data['diastolic'] <= 94))
                 {
                        $input_data['bpFlag']      = 'Elevated BP (Pre Hypertension)';
                        $input_data['bpFlagColor'] = 'warning';
                        $input_data['range_code']  = '#fff707';
                    }
                    if ((($input_data['systolic'] >= 140) && ($input_data['systolic'] <= 159)) )
                    // || (($input_data['diastolic'] >= 95) && ($input_data['diastolic'] <= 100))
                 {
                        $input_data['bpFlag']      = 'Hypertension Stage 1';
                        $input_data['bpFlagColor'] = 'warning';
                        $input_data['range_code']  = '#FFC107';
                    }
                    if ((($input_data['systolic'] >= 160) && ($input_data['systolic'] <= 169)) )
                    // || (($input_data['diastolic'] >= 101) && ($input_data['diastolic'] <= 110))
                 {
                        $input_data['bpFlag']      = 'Hypertension Stage 2';
                        $input_data['bpFlagColor'] = 'warning';
                        $input_data['range_code']  = '#FFC107';
                    }
                    if ((($input_data['systolic'] >= 170) && ($input_data['systolic'] <= 179)) )
                    // || (($input_data['diastolic'] >= 111) && ($input_data['diastolic'] <= 119))
                 {
                        $input_data['bpFlag']      = 'Severely High BP';
                        $input_data['bpFlagColor'] = 'warning';
                        $input_data['range_code']  = '#ff0000';
                    }
                    if (($input_data['systolic'] >= 180) )
                    // || ($input_data['diastolic'] >= 120)
                 {
                        $input_data['bpFlag']      = 'Hypertensive Crisis';
                        $input_data['bpFlagColor'] = 'danger';
                        $input_data['range_code']  = '#ff0000';
                    }
                }
            } else if ( $years >= 60) {
                if (($input_data['systolic'] <= 89) )
                // || ($input_data['diastolic'] <= 59)
             {
                    $input_data['bpFlag']      = 'Low BP (Hypotension)';
                    $input_data['bpFlagColor'] = 'warning';
                    $input_data['range_code']  = '#0000ff';
                }
        
                if ((($input_data['systolic'] >= 90) && ($input_data['systolic'] <= 140)) )
                // || (($input_data['diastolic'] >= 60) && ($input_data['diastolic'] <= 90))
             {
                    $input_data['bpFlag']      = 'Normal BP ';
                    $input_data['bpFlagColor'] = 'success';
                    $input_data['range_code']  = '#008000';
                }
                if ((($input_data['systolic'] >= 141) && ($input_data['systolic'] <= 149)) )
                // || (($input_data['diastolic'] >= 91) && ($input_data['diastolic'] <= 95))
             {
                    $input_data['bpFlag']      = 'Elevated BP (Pre Hypertension)';
                    $input_data['bpFlagColor'] = 'warning';
                    $input_data['range_code']  = '#fff707';
                }
                if ((($input_data['systolic'] >= 150) && ($input_data['systolic'] <= 159)) )
                // || (($input_data['diastolic'] >= 96) && ($input_data['diastolic'] <= 100))
             {
                    $input_data['bpFlag']      = 'Hypertension Stage 1';
                    $input_data['bpFlagColor'] = 'warning';
                    $input_data['range_code']  = '#FFC107';
                }
                if ((($input_data['systolic'] >= 160) && ($input_data['systolic'] <= 169)) )
                // || (($input_data['diastolic'] >= 101) && ($input_data['diastolic'] <= 110))
             {
                    $input_data['bpFlag']      = 'Hypertension Stage 2';
                    $input_data['bpFlagColor'] = 'warning';
                    $input_data['range_code']  = '#FFC107';
                }
                if ((($input_data['systolic'] >= 170) && ($input_data['systolic'] <= 179)) )
                // || (($input_data['diastolic'] >= 111) && ($input_data['diastolic'] <= 119))
             {
                    $input_data['bpFlag']      = 'Severely High BP';
                    $input_data['bpFlagColor'] = 'warning';
                    $input_data['range_code']  = '#ff0000';
                }
                if (($input_data['systolic'] >= 180))
                //  || ($input_data['diastolic'] >= 120)
             {
                    $input_data['bpFlag']      = 'Hypertensive Crisis';
                    $input_data['bpFlagColor'] = 'danger';
                    $input_data['range_code']  = '#ff0000';
                }
            }
        }
        return $input_data;
    }



    public static function heart_rate_flag($input_data, $years, $months, $days)
    {
        $input_data['heartRateFlag']      = '';
        $input_data['heartRateFlagColor'] = '';
        $input_data['range_code'] = '';

        if (!empty($input_data['heart'])) {
            

            // 1-2 yeara
            if ($years <= 12) {

                           // 0-28 Days
                           if ($years == 0 && $months == 0 && $days >= 0 && $days <= 28) {
                           
                                if (($input_data['heart'] < 100)) {
                                    $input_data['heartRateFlag']      = 'Low Heart Rate (Bradycardia)';
                                    $input_data['heartRateFlagColor'] = 'primary';
                                    $input_data['range_code']         = '#0000ff';
                                }
            
                                if (($input_data['heart'] >= 100) && ($input_data['heart'] <= 160 )) {
                                    $input_data['heartRateFlag']      = 'Normal Heart Rate(Normal Sinus Rhythm)';
                                    $input_data['heartRateFlagColor'] = 'success';
                                    $input_data['range_code']         = '#008000';
                                }
                                if (($input_data['heart'] >= 161) && ($input_data['heart'] <= 180 )) {
                                    $input_data['heartRateFlag']      = 'Elevated Heart Rate';
                                    $input_data['heartRateFlagColor'] = 'success';
                                    $input_data['range_code']         = '#fff707';
                                }
            
                                if (($input_data['heart'] >= 181) && ($input_data['heart'] <= 200 )) {
                                    $input_data['heartRateFlag']      = 'Mild Tachycardia';
                                    $input_data['heartRateFlagColor'] = 'success';
                                    $input_data['range_code']         = '#FFC107';
                                }
            
                                if (($input_data['heart'] > 200)) {
                                    $input_data['heartRateFlag']      = 'Severe Tachycardia';
                                    $input_data['heartRateFlagColor'] = 'danger';
                                    $input_data['range_code']         = '#FF0000';
                                }
                            
                        }

                        if (($months >= 1) && ($months <= 12)) {

                            if (($input_data['heart'] < 90)) {
                                $input_data['heartRateFlag']      = 'Low Heart Rate (Bradycardia)';
                                $input_data['heartRateFlagColor'] = 'primary';
                                $input_data['range_code']         = '#0000ff';
                            }
        
                            if (($input_data['heart'] >= 90) && ($input_data['heart'] <= 150)) {
                                $input_data['heartRateFlag']      = 'Normal Heart Rate(Normal Sinus Rhythm)';
                                $input_data['heartRateFlagColor'] = 'success';
                                $input_data['range_code']         = '#008000';
                            }
                            if (($input_data['heart'] >= 151) && ($input_data['heart'] <= 170)) {
                                $input_data['heartRateFlag']      = 'Elevated Heart Rate';
                                $input_data['heartRateFlagColor'] = 'success';
                                $input_data['range_code']         = '#fff707';
                            }
                            if (($input_data['heart'] >= 171) && ($input_data['heart'] <= 190)) {
                                $input_data['heartRateFlag']      = 'Mild Tachycardia';
                                $input_data['heartRateFlagColor'] = 'success';
                                $input_data['range_code']         = '#FFC107';
                            }
        
                            if (($input_data['heart'] > 190)) {
                                $input_data['heartRateFlag']      = 'Severe Tachycardia';
                                $input_data['heartRateFlagColor'] = 'danger';
                                $input_data['range_code']         = '#FF0000';
                            }
                        }
                        

                // if ($years == 0 && $months > 0) {
                //     if (($months >= 1) && ($months < 12)) {
                //         if (($input_data['heart'] < 79)) {
                //             $input_data['heartRateFlag']      = 'Low Heart Rate (Bradycardia)';
                //             $input_data['heartRateFlagColor'] = 'primary';
                //             $input_data['range_code']         = '#0000ff';
                //         }
    
                //         if (($input_data['heart'] >= 80) && ($input_data['heart'] <= 130 )) {
                //             $input_data['heartRateFlag']      = 'Normal Heart Rate(Normal Sinus Rhythm)';
                //             $input_data['heartRateFlagColor'] = 'success';
                //             $input_data['range_code']         = '#008000';
                //         }
                //         if (($input_data['heart'] >= 131) && ($input_data['heart'] <= 150 )) {
                //             $input_data['heartRateFlag']      = 'Elevated Heart Rate';
                //             $input_data['heartRateFlagColor'] = 'success';
                //             $input_data['range_code']         = '#fff707';
                //         }
    
                //         if (($input_data['heart'] >= 151) && ($input_data['heart'] <= 170 )) {
                //             $input_data['heartRateFlag']      = 'Mild Tachycardia';
                //             $input_data['heartRateFlagColor'] = 'success';
                //             $input_data['range_code']         = '#FFC107';
                //         }
    
                //         if (($input_data['heart'] > 170)) {
                //             $input_data['heartRateFlag']      = 'Severe Tachycardia';
                //             $input_data['heartRateFlagColor'] = 'danger';
                //             $input_data['range_code']         = '#FF0000';
                //         }
                //     }
                // }

                if (($years >= 1) && ($years <= 2)) {

                    if (($input_data['heart'] <= 79)) {
                        $input_data['heartRateFlag']      = 'Low Heart Rate (Bradycardia)';
                        $input_data['heartRateFlagColor'] = 'primary';
                        $input_data['range_code']         = '#0000ff';
                    }

                    if (($input_data['heart'] >= 80) && ($input_data['heart'] <= 130)) {
                        $input_data['heartRateFlag']      = 'Normal Heart Rate(Normal Sinus Rhythm)';
                        $input_data['heartRateFlagColor'] = 'success';
                        $input_data['range_code']         = '#008000';
                    }
                    if (($input_data['heart'] >= 131) && ($input_data['heart'] <= 150)) {
                        $input_data['heartRateFlag']      = 'Elevated Heart Rate';
                        $input_data['heartRateFlagColor'] = 'success';
                        $input_data['range_code']         = '#fff707';
                    }
                    if (($input_data['heart'] >= 151) && ($input_data['heart'] <= 170)) {
                        $input_data['heartRateFlag']      = 'Mild Tachycardia';
                        $input_data['heartRateFlagColor'] = 'success';
                        $input_data['range_code']         = '#FFC107';
                    }

                    if (($input_data['heart'] > 170)) {
                        $input_data['heartRateFlag']      = 'Severe Tachycardia';
                        $input_data['heartRateFlagColor'] = 'danger';
                        $input_data['range_code']         = '#FF0000';
                    }
                }
                //  3-5years
                if (($years >= 3) && ($years <= 5)) {
                    if (($input_data['heart'] <= 74)) {
                        $input_data['heartRateFlag']      = 'Low Heart Rate (Bradycardia)';
                        $input_data['heartRateFlagColor'] = 'primary';
                        $input_data['range_code']         = '#0000ff';
                    }

                    if (($input_data['heart'] >= 75) && ($input_data['heart'] <= 120)) {
                        $input_data['heartRateFlag']      = 'Normal Heart Rate (Normal Sinus Rhythm)';
                        $input_data['heartRateFlagColor'] = 'success';
                        $input_data['range_code']         = '#008000';
                        // return "Pre-School"; // green 90 - 140
                    }
                    if (($input_data['heart'] >= 121) && ($input_data['heart'] <= 140)) {
                        $input_data['heartRateFlag']      = 'Elevated Heart Rate';
                        $input_data['heartRateFlagColor'] = 'black';
                        $input_data['range_code']         = '#fff707';
                        // return "Pre-School"; // green 90 - 140
                    }
                    if (($input_data['heart'] >= 141) && ($input_data['heart'] <= 160)) {
                        $input_data['heartRateFlag']      = 'Mild Tachycardia';
                        $input_data['heartRateFlagColor'] = 'success';
                        $input_data['range_code']         = '#FFC107';
                        // return "Pre-School"; // green 90 - 140
                    }

                    if (($input_data['heart'] > 160)) {
                        $input_data['heartRateFlag']      = 'Severe Tachycardia';
                        $input_data['heartRateFlagColor'] = 'danger';
                        $input_data['range_code']         = '#ff0000';
                    }
                }

                // 6-12years
                if (($years >= 6) && ($years <= 12)) {
                    if (($input_data['heart'] <= 69)) {
                        $input_data['heartRateFlag']      = 'Low Heart Rate (Bradycardia)';
                        $input_data['heartRateFlagColor'] = 'primary';
                        $input_data['range_code']         = '#0000ff';
                    }

                    if (($input_data['heart'] >= 70) && ($input_data['heart'] <= 110)) {
                        $input_data['heartRateFlag']      = 'Normal Heart Rate(Normal Sinus Rhythm)';
                        $input_data['heartRateFlagColor'] = 'success';
                        $input_data['range_code']         = '#008000';
                        // return "Normal"; // green
                    }
                    if (($input_data['heart'] >= 111) && ($input_data['heart'] <= 130)) {
                        $input_data['heartRateFlag']      = 'Elevated Heart Rate';
                        $input_data['heartRateFlagColor'] = 'success';
                        $input_data['range_code']         = '#fff707';
                        // return "Normal"; // green
                    }
                    if (($input_data['heart'] >= 131) && ($input_data['heart'] <= 150)) {
                        $input_data['heartRateFlag']      = 'Mild Tachycardia';
                        $input_data['heartRateFlagColor'] = 'success';
                        $input_data['range_code']         = '#FFC107';
                        // return "Normal"; // green
                    }

                    if (($input_data['heart'] > 150)) {
                        $input_data['heartRateFlag']      = 'Severe Tachycardia';
                        $input_data['heartRateFlagColor'] = 'danger';
                        $input_data['range_code']         = '#ff0000';
                    }
                }
            }

                     // 13-19years
                     if (($years >= 13) && ($years <= 19)) {
                        if (($input_data['heart'] <= 59)) {
                            $input_data['heartRateFlag']      = 'Low Heart Rate (Bradycardia) ';
                            $input_data['heartRateFlagColor'] = 'primary';
                            $input_data['range_code']         = '#0000ff';
                        }
    
                        if (($input_data['heart'] >= 60) && ($input_data['heart'] <= 100)) {
                            $input_data['heartRateFlag']      = 'Normal Heart Rate(Normal Sinus Rhythm)';
                            $input_data['heartRateFlagColor'] = 'success';
                            $input_data['range_code']         = '#008000';
                            // return "Normal"; // green
                        }
                        if (($input_data['heart'] >= 101) && ($input_data['heart'] <= 120)) {
                            $input_data['heartRateFlag']      = 'Elevated Heart Rate';
                            $input_data['heartRateFlagColor'] = 'success';
                            $input_data['range_code']         = '#fff707';
                            // return "Normal"; // green
                        }
                        if (($input_data['heart'] >= 121) && ($input_data['heart'] <= 140)) {
                            $input_data['heartRateFlag']      = 'Mild Tachycardia';
                            $input_data['heartRateFlagColor'] = 'success';
                            $input_data['range_code']         = '#FFC107';
                            // return "Normal"; // green
                        }
    
                        if (($input_data['heart'] > 140)) {
                            $input_data['heartRateFlag']      = 'Severe Tachycardia';
                            $input_data['heartRateFlagColor'] = 'danger';
                            $input_data['range_code']         = '#ff0000';
                        }
                    }
                         // 20-59years
                         if (($years >= 20) && ($years <= 59 )) {
                            if (($input_data['heart'] <= 59)) {
                                $input_data['heartRateFlag']      = 'Low Heart Rate (Bradycardia) ';
                                $input_data['heartRateFlagColor'] = 'primary';
                                $input_data['range_code']         = '#0000ff';
                            }
        
                            if (($input_data['heart'] >= 60) && ($input_data['heart'] <= 100)) {
                                $input_data['heartRateFlag']      = 'Normal Heart Rate(Normal Sinus Rhythm)';
                                $input_data['heartRateFlagColor'] = 'success';
                                $input_data['range_code']         = '#008000';
                                // return "Normal"; // green
                            }
                            if (($input_data['heart'] >= 101) && ($input_data['heart'] <= 120)) {
                                $input_data['heartRateFlag']      = 'Elevated Heart Rate';
                                $input_data['heartRateFlagColor'] = 'success';
                                $input_data['range_code']         = '#fff707';
                                // return "Normal"; // green
                            }
                            if (($input_data['heart'] >= 121) && ($input_data['heart'] <= 140)) {
                                $input_data['heartRateFlag']      = 'Mild Tachycardia';
                                $input_data['heartRateFlagColor'] = 'success';
                                $input_data['range_code']         = '#FFC107';
                                // return "Normal"; // green
                            }
        
                            if (($input_data['heart'] > 140)) {
                                $input_data['heartRateFlag']      = 'Severe Tachycardia';
                                $input_data['heartRateFlagColor'] = 'danger';
                                $input_data['range_code']         = '#ff0000';
                            }
                        }

                        if ($years >= 60) {

                            if (($input_data['heart'] < 50)) {
                                $input_data['heartRateFlag']      = 'Low Heart Rate (Bradycardia)';
                                $input_data['heartRateFlagColor'] = 'primary';
                                $input_data['range_code']         = '#0000ff';
                            }
        
                            if (($input_data['heart'] >= 50) && ($input_data['heart'] <= 90)) {
                                $input_data['heartRateFlag']      = 'Normal Heart Rate(Normal Sinus Rhythm)';
                                $input_data['heartRateFlagColor'] = 'success';
                                $input_data['range_code']         = '#008000';
                            }
                            if (($input_data['heart'] >= 91) && ($input_data['heart'] <= 110)) {
                                $input_data['heartRateFlag']      = 'Elevated Heart Rate';
                                $input_data['heartRateFlagColor'] = 'success';
                                $input_data['range_code']         = '#fff707';
                                // return "Normal"; // green
                            }
                            if (($input_data['heart'] >= 111) && ($input_data['heart'] <= 130)) {
                                $input_data['heartRateFlag']      = 'Mild Tachycardia';
                                $input_data['heartRateFlagColor'] = 'success';
                                $input_data['range_code']         = '#FFC107';
                                // return "Normal"; // green
                            }
        
                            if (($input_data['heart'] > 130)) {
                                $input_data['heartRateFlag']      = 'Severe Tachycardia';
                                $input_data['heartRateFlagColor'] = 'danger';
                                $input_data['range_code']         = '#ff0000';
                            }
        
                        }

                // if (($years == 12)) {
                //     if (($input_data['heart'] < 60)) {
                //         $input_data['heartRateFlag']      = 'Low';
                //         $input_data['heartRateFlagColor'] = 'primary';
                //         $input_data['range_code']         = '#0000ff';
                //     }

                //     if (($input_data['heart'] >= 60) && ($input_data['heart'] <= 100)) {
                //         $input_data['heartRateFlag']      = 'Normal';
                //         $input_data['heartRateFlagColor'] = 'success';
                //         $input_data['range_code']         = '#008000';
                //     }

                //     if (($input_data['heart'] > 100)) {
                //         $input_data['heartRateFlag']      = 'High';
                //         $input_data['heartRateFlagColor'] = 'danger';
                //         $input_data['range_code']         = '#ff0000';
                //     }
                // }
            

           
                    // if (($input_data['heart'] < 40)) {
                    //     $input_data['heartRateFlag']      = 'Low';
                    //     $input_data['heartRateFlagColor'] = 'primary';
                    //     $input_data['range_code']         = '#0000ff';
                    // }

                    // if (($input_data['heart'] >= 40) && ($input_data['heart'] <= 60)) {
                    //     $input_data['heartRateFlag']      = 'Athlete';
                    //     $input_data['heartRateFlagColor'] = 'success';
                    //     $input_data['range_code']         = '#008000';
                    // }

                    // if (($input_data['heart'] > 60)) {
                    //     $input_data['heartRateFlag']      = 'High';
                    //     $input_data['heartRateFlagColor'] = 'danger';
                    //     $input_data['range_code']         = '#ff0000';
                    // }

                    // if (($input_data['heart'] >= 40)) {
                    //     $input_data['heartRateFlag']      = 'High';
                    //     $input_data['heartRateFlagColor'] = 'danger';
                    //     $input_data['range_code']         = '#ff0000';
                    // }
            

            
        }

        return $input_data;
    }


    public static function cholesterol_flag($input_data,  $years, $months, $days)
    {
        $input_data['ldl_message']      = '';
        $input_data['ldl_message_flag'] = '';
        $input_data['ldl_range_code'] = '';

        $input_data['vldl_message']      = '';
        $input_data['vldl_message_flag'] = '';
        $input_data['vldl_range_code'] = '';

        $input_data['hdl_message']      = '';
        $input_data['hdl_message_flag'] = '';
        $input_data['hdl_range_code'] = '';

        $input_data['triglycerides_message']      = '';
        $input_data['triglycerides_message_flag'] = '';
        $input_data['triglycerides_range_code'] = '';

        $input_data['hdl_ldl_message']      = '';
        $input_data['hdl_ldl_message_flag'] = '';
        $input_data['hdl_ldl_range_code'] = '';

        $input_data['total_message']      = '';
        $input_data['total_message_flag'] = '';
        $input_data['total_range_code'] = '';

        if (!empty($input_data['ldl']) && !empty($input_data['ldl_unit'])) {
            if($years >= 0 && $years <= 5){
                if ($input_data['ldl_unit'] == 'mg/dL') {

                    if ($input_data['ldl'] < 30) {
                        $input_data['ldl_message'] = 'Low (Hypo/Deficient)'; 
                    }
    
                    if (($input_data['ldl'] >= 30) && ($input_data['ldl'] <= 90)) {
                        $input_data['ldl_message'] = 'Normal (Desirable)'; 
                    }


                    if (($input_data['ldl'] >= 91) && ($input_data['ldl'] <= 110)) {
                        $input_data['ldl_message'] = 'Borderline High (Elevated Risk)'; 
                    }

                    if (($input_data['ldl'] >= 111) && ($input_data['ldl'] <= 130)) {
                        $input_data['ldl_message'] = 'High (Increased Risk)'; 
                    }

                    if ($input_data['ldl'] >= 131) {
                        $input_data['ldl_message'] = 'Very High (Severe Risk)'; 
                    }
                } 


            }
            if($years >= 6 && $years <= 19){
                if ($input_data['ldl_unit'] == 'mg/dL') {

                    if ($input_data['ldl'] < 50) {
                        $input_data['ldl_message'] = 'Low (Hypo/Deficient)'; 
                    }
    
                    if (($input_data['ldl'] >= 50) && ($input_data['ldl'] <= 110)) {
                        $input_data['ldl_message'] = 'Normal (Desirable)'; 
                    }


                    if (($input_data['ldl'] >= 111) && ($input_data['ldl'] <= 130)) {
                        $input_data['ldl_message'] = 'Borderline High (Elevated Risk)'; 
                    }

                    if (($input_data['ldl'] >= 131) && ($input_data['ldl'] <= 160)) {
                        $input_data['ldl_message'] = 'High (Increased Risk)'; 
                    }

                    if ($input_data['ldl'] >= 161) {
                        $input_data['ldl_message'] = 'Very High (Severe Risk)'; 
                    }
                } 


            }
            if($years >= 20 && $years <= 59){
                if ($input_data['ldl_unit'] == 'mg/dL') {

                    if ($input_data['ldl'] < 50) {
                        $input_data['ldl_message'] = 'Low (Hypo/Deficient)'; 
                    }
    
                    if (($input_data['ldl'] >= 50) && ($input_data['ldl'] <= 130)) {
                        $input_data['ldl_message'] = 'Normal (Desirable)'; 
                    }


                    if (($input_data['ldl'] >= 131) && ($input_data['ldl'] <= 160)) {
                        $input_data['ldl_message'] = 'Borderline High (Elevated Risk)'; 
                    }

                    if (($input_data['ldl'] >= 161) && ($input_data['ldl'] <= 190)) {
                        $input_data['ldl_message'] = 'High (Increased Risk)'; 
                    }

                    if ($input_data['ldl'] >= 191) {
                        $input_data['ldl_message'] = 'Very High (Severe Risk)'; 
                    }
                } 


            }
            if($years >= 60){
                if ($input_data['ldl_unit'] == 'mg/dL') {

                    if ($input_data['ldl'] < 50) {
                        $input_data['ldl_message'] = 'Low (Hypo/Deficient)'; 
                    }
    
                    if (($input_data['ldl'] >= 50) && ($input_data['ldl'] <= 140)) {
                        $input_data['ldl_message'] = 'Normal (Desirable)'; 
                    }


                    if (($input_data['ldl'] >= 141) && ($input_data['ldl'] <= 170)) {
                        $input_data['ldl_message'] = 'Borderline High (Elevated Risk)'; 
                    }

                    if (($input_data['ldl'] >= 171) && ($input_data['ldl'] <= 200)) {
                        $input_data['ldl_message'] = 'High (Increased Risk)'; 
                    }

                    if ($input_data['ldl'] >= 201) {
                        $input_data['ldl_message'] = 'Very High (Severe Risk)'; 
                    }
                } 


            }
            switch ($input_data['ldl_message']) {
                case 'Low (Hypo/Deficient)':
                    $input_data['ldl_message_flag'] = 'warning';
                    $input_data['ldl_range_code']   = '#0000ff';
                break;
                case 'Normal (Desirable)':
                    $input_data['ldl_message_flag'] = 'success';
                    $input_data['ldl_range_code']   = '#008000';
                break;
                case 'Borderline High (Elevated Risk)':
                    $input_data['ldl_message_flag'] = 'warning';
                    $input_data['ldl_range_code']   = '#fff707';
                break;
                case 'High (Increased Risk)':
                $input_data['ldl_message_flag'] = 'warning';
                $input_data['ldl_range_code']   = '#FFC107';
                break;
                case 'Very High (Severe Risk)':
                    $input_data['ldl_message_flag'] = 'danger';
                    $input_data['ldl_range_code']   = '#ff0000';
                break;
            }
        }

        // totall
        if (!empty($input_data['total']) && !empty($input_data['total_unit'])) {
            if($years >= 0 && $years <= 5){
                if ($input_data['total_unit'] == 'mg/dL') {

                    if ($input_data['total'] < 100) {
                        $input_data['total_message'] = 'Low (Hypo/Deficient)'; 
                    }
    
                    if (($input_data['total'] >= 100) && ($input_data['total'] <= 150)) {
                        $input_data['total_message'] = 'Normal (Desirable)'; 
                    }


                    if (($input_data['total'] >= 151) && ($input_data['total'] <= 175)) {
                        $input_data['total_message'] = 'Borderline High (Elevated Risk)'; 
                    }

                    if (($input_data['total'] >= 176) && ($input_data['total'] <= 200)) {
                        $input_data['total_message'] = 'High (Increased Risk)'; 
                    }

                    if ($input_data['total'] >= 201) {
                        $input_data['total_message'] = 'Very High (Severe Risk)'; 
                    }
                } 


            }
            if($years >= 6 && $years <= 19){
                if ($input_data['total_unit'] == 'mg/dL') {

                    if ($input_data['total'] < 120) {
                        $input_data['total_message'] = 'Low (Hypo/Deficient)'; 
                    }
    
                    if (($input_data['total'] >= 120) && ($input_data['total'] <= 170)) {
                        $input_data['total_message'] = 'Normal (Desirable)'; 
                    }


                    if (($input_data['total'] >= 171) && ($input_data['total'] <= 200)) {
                        $input_data['total_message'] = 'Borderline High (Elevated Risk)'; 
                    }

                    if (($input_data['total'] >= 201) && ($input_data['total'] <= 220)) {
                        $input_data['total_message'] = 'High (Increased Risk)'; 
                    }

                    if ($input_data['total'] >= 221) {
                        $input_data['total_message'] = 'Very High (Severe Risk)'; 
                    }
                } 


            }
            if($years >= 20 && $years <= 59){
                if ($input_data['total_unit'] == 'mg/dL') {

                    if ($input_data['total'] < 150) {
                        $input_data['total_message'] = 'Low (Hypo/Deficient)'; 
                    }
    
                    if (($input_data['total'] >= 150) && ($input_data['total'] <= 200)) {
                        $input_data['total_message'] = 'Normal (Desirable)'; 
                    }


                    if (($input_data['total'] >= 201) && ($input_data['total'] <= 240)) {
                        $input_data['total_message'] = 'Borderline High (Elevated Risk)'; 
                    }

                    if (($input_data['total'] >= 241) && ($input_data['total'] <= 270)) {
                        $input_data['total_message'] = 'High (Increased Risk)'; 
                    }

                    if ($input_data['total'] >= 271) {
                        $input_data['total_message'] = 'Very High (Severe Risk)'; 
                    }
                } 


            }
            if($years >= 60){
                if ($input_data['total_unit'] == 'mg/dL') {

                    if ($input_data['total'] < 140) {
                        $input_data['total_message'] = 'Low (Hypo/Deficient)'; 
                    }
    
                    if (($input_data['total'] >= 140) && ($input_data['total'] <= 220)) {
                        $input_data['total_message'] = 'Normal (Desirable)'; 
                    }


                    if (($input_data['total'] >= 221) && ($input_data['total'] <= 250)) {
                        $input_data['total_message'] = 'Borderline High (Elevated Risk)'; 
                    }

                    if (($input_data['total'] >= 251) && ($input_data['total'] <= 280)) {
                        $input_data['total_message'] = 'High (Increased Risk)'; 
                    }

                    if ($input_data['total'] >= 281) {
                        $input_data['total_message'] = 'Very High (Severe Risk)'; 
                    }
                } 


            }
            switch ($input_data['total_message']) {
                case 'Low (Hypo/Deficient)':
                    $input_data['total_message_flag'] = 'warning';
                    $input_data['total_range_code']   = '#0000ff';
                break;
                case 'Normal (Desirable)':
                    $input_data['total_message_flag'] = 'success';
                    $input_data['total_range_code']   = '#008000';
                break;
                case 'Borderline High (Elevated Risk)':
                    $input_data['total_message_flag'] = 'warning';
                    $input_data['total_range_code']   = '#fff707';
                break;
                case 'High (Increased Risk)':
                $input_data['total_message_flag'] = 'warning';
                $input_data['total_range_code']   = '#FFC107';
                break;
                case 'Very High (Severe Risk)':
                    $input_data['total_message_flag'] = 'danger';
                    $input_data['total_range_code']   = '#ff0000';
                break;
            }
        }

        if (!empty($input_data['vldl']) && !empty($input_data['vldl_unit'])) {
            if($years >= 0 && $years <= 5){
                if ($input_data['vldl_unit'] == 'mg/dL') {

                    if ($input_data['vldl'] < 5) {
                        $input_data['vldl_message'] = 'Low (Hypo/Deficient)'; 
                    }
    
                    if (($input_data['vldl'] >= 5) && ($input_data['vldl'] <= 15)) {
                        $input_data['vldl_message'] = 'Normal (Desirable)'; 
                    }


                    if (($input_data['vldl'] >= 16) && ($input_data['vldl'] <= 20)) {
                        $input_data['vldl_message'] = 'Borderline High (Elevated Risk)'; 
                    }

                    if (($input_data['vldl'] >= 21) && ($input_data['vldl'] <= 30)) {
                        $input_data['vldl_message'] = 'High (Increased Risk)'; 
                    }

                    if ($input_data['vldl'] >= 31) {
                        $input_data['vldl_message'] = 'Very High (Severe Risk)'; 
                    }
                } 


            }
            if($years >= 6 && $years <= 19){
                if ($input_data['vldl_unit'] == 'mg/dL') {

                    if ($input_data['vldl'] < 5) {
                        $input_data['vldl_message'] = 'Low (Hypo/Deficient)'; 
                    }
    
                    if (($input_data['vldl'] >= 5) && ($input_data['vldl'] <= 15)) {
                        $input_data['vldl_message'] = 'Normal (Desirable)'; 
                    }


                    if (($input_data['vldl'] >= 16) && ($input_data['vldl'] <= 20)) {
                        $input_data['vldl_message'] = 'Borderline High (Elevated Risk)'; 
                    }

                    if (($input_data['vldl'] >= 21) && ($input_data['vldl'] <= 30)) {
                        $input_data['vldl_message'] = 'High (Increased Risk)'; 
                    }

                    if ($input_data['vldl'] >= 31) {
                        $input_data['vldl_message'] = 'Very High (Severe Risk)'; 
                    }
                } 


            }
            if($years >= 20 && $years <= 59){
                if ($input_data['vldl_unit'] == 'mg/dL') {

                    if ($input_data['vldl'] < 5) {
                        $input_data['vldl_message'] = 'Low (Hypo/Deficient)'; 
                    }
    
                    if (($input_data['vldl'] >= 5) && ($input_data['vldl'] <= 15)) {
                        $input_data['vldl_message'] = 'Normal (Desirable)'; 
                    }


                    if (($input_data['vldl'] >= 16) && ($input_data['vldl'] <= 20)) {
                        $input_data['vldl_message'] = 'Borderline High (Elevated Risk)'; 
                    }

                    if (($input_data['vldl'] >= 21) && ($input_data['vldl'] <= 30)) {
                        $input_data['vldl_message'] = 'High (Increased Risk)'; 
                    }

                    if ($input_data['vldl'] >= 31) {
                        $input_data['vldl_message'] = 'Very High (Severe Risk)'; 
                    }
                } 


            }
            if($years >= 60){
                if ($input_data['vldl_unit'] == 'mg/dL') {

                    if ($input_data['vldl'] < 5) {
                        $input_data['vldl_message'] = 'Low (Hypo/Deficient)'; 
                    }
    
                    if (($input_data['vldl'] >= 5) && ($input_data['vldl'] <= 15)) {
                        $input_data['vldl_message'] = 'Normal (Desirable)'; 
                    }


                    if (($input_data['vldl'] >= 16) && ($input_data['vldl'] <= 20)) {
                        $input_data['vldl_message'] = 'Borderline High (Elevated Risk)'; 
                    }

                    if (($input_data['vldl'] >= 21) && ($input_data['vldl'] <= 30)) {
                        $input_data['vldl_message'] = 'High (Increased Risk)'; 
                    }

                    if ($input_data['vldl'] >= 31) {
                        $input_data['vldl_message'] = 'Very High (Severe Risk)'; 
                    }
                } 


            }
            switch ($input_data['vldl_message']) {
                case 'Low (Hypo/Deficient)':
                    $input_data['vldl_message_flag'] = 'warning';
                    $input_data['vldl_range_code']   = '#0000ff';
                break;
                case 'Normal (Desirable)':
                    $input_data['vldl_message_flag'] = 'success';
                    $input_data['vldl_range_code']   = '#008000';
                break;
                case 'Borderline High (Elevated Risk)':
                    $input_data['vldl_message_flag'] = 'warning';
                    $input_data['vldl_range_code']   = '#fff707';
                break;
                case 'High (Increased Risk)':
                $input_data['vldl_message_flag'] = 'warning';
                $input_data['vldl_range_code']   = '#FFC107';
                break;
                case 'Very High (Severe Risk)':
                    $input_data['vldl_message_flag'] = 'danger';
                    $input_data['vldl_range_code']   = '#ff0000';
                break;
            }
        }

      

        if (!empty($input_data['hdl']) ) {
            if($years >= 0 && $years <= 5){
                if ($input_data['hdl_unit'] == 'mg/dL') {

                    if ($input_data['hdl'] < 35) {
                        $input_data['hdl_message'] = 'Low (Hypo/Deficient)'; 
                    }
    
                    if (($input_data['hdl'] >= 35) && ($input_data['hdl'] <= 55)) {
                        $input_data['hdl_message'] = 'Normal (Desirable)'; 
                    }


                    if (($input_data['hdl'] >= 56) && ($input_data['hdl'] <= 60)) {
                        $input_data['hdl_message'] = 'Borderline High (Elevated Risk)'; 
                    }

                    if (($input_data['hdl'] >= 61) && ($input_data['hdl'] <= 70)) {
                        $input_data['hdl_message'] = 'High (Increased Risk)'; 
                    }

                    if ($input_data['hdl'] > 70) {
                        $input_data['hdl_message'] = 'Very High (Severe Risk)'; 
                    }
                } 


            }
            if($years >= 6 && $years <= 19){
                if ($input_data['hdl_unit'] == 'mg/dL') {

                    if ($input_data['hdl'] < 40) {
                        $input_data['hdl_message'] = 'Low (Hypo/Deficient)'; 
                    }
    
                    if (($input_data['hdl'] >= 40) && ($input_data['hdl'] <= 60)) {
                        $input_data['hdl_message'] = 'Normal (Desirable)'; 
                    }


                    if (($input_data['hdl'] >= 61) && ($input_data['hdl'] <= 65)) {
                        $input_data['hdl_message'] = 'Borderline High (Elevated Risk)'; 
                    }

                    if (($input_data['hdl'] >= 66) && ($input_data['hdl'] <= 75)) {
                        $input_data['hdl_message'] = 'High (Increased Risk)'; 
                    }

                    if ($input_data['hdl'] > 75) {
                        $input_data['hdl_message'] = 'Very High (Severe Risk)'; 
                    }
                } 


            }
            if($years >= 20 && $years <= 59){
                if ($input_data['hdl_unit'] == 'mg/dL') {

                    if ($input_data['hdl'] < 40) {
                        $input_data['hdl_message'] = 'Low (Hypo/Deficient)'; 
                    }
    
                    if (($input_data['hdl'] >= 40) && ($input_data['hdl'] <= 60)) {
                        $input_data['hdl_message'] = 'Normal (Desirable)'; 
                    }


                    if (($input_data['hdl'] >= 61) && ($input_data['hdl'] <= 65)) {
                        $input_data['hdl_message'] = 'Borderline High (Elevated Risk)'; 
                    }

                    if (($input_data['hdl'] >= 66) && ($input_data['hdl'] <= 75)) {
                        $input_data['hdl_message'] = 'High (Increased Risk)'; 
                    }

                    if ($input_data['hdl'] >= 75) {
                        $input_data['hdl_message'] = 'Very High (Severe Risk)'; 
                    }
                } 


            }
            if($years >= 60){
                if ($input_data['hdl_unit'] == 'mg/dL') {

                    if ($input_data['hdl'] < 40) {
                        $input_data['hdl_message'] = 'Low (Hypo/Deficient)'; 
                    }
    
                    if (($input_data['hdl'] >= 40) && ($input_data['hdl'] <= 60)) {
                        $input_data['hdl_message'] = 'Normal (Desirable)'; 
                    }


                    if (($input_data['hdl'] >= 61) && ($input_data['hdl'] <= 65)) {
                        $input_data['hdl_message'] = 'Borderline High (Elevated Risk)'; 
                    }

                    if (($input_data['hdl'] >= 66) && ($input_data['hdl'] <= 75)) {
                        $input_data['hdl_message'] = 'High (Increased Risk)'; 
                    }

                    if ($input_data['hdl'] >= 75) {
                        $input_data['hdl_message'] = 'Very High (Severe Risk)'; 
                    }
                } 


            }
            switch ($input_data['hdl_message']) {
                case 'Low (Hypo/Deficient)':
                    $input_data['hdl_message_flag'] = 'warning';
                    $input_data['hdl_range_code']   = '#0000ff';
                break;
                case 'Normal (Desirable)':
                    $input_data['hdl_message_flag'] = 'success';
                    $input_data['hdl_range_code']   = '#008000';
                break;
                case 'Borderline High (Elevated Risk)':
                    $input_data['hdl_message_flag'] = 'warning';
                    $input_data['hdl_range_code']   = '#fff707';
                break;
                case 'High (Increased Risk)':
                $input_data['hdl_message_flag'] = 'warning';
                $input_data['hdl_range_code']   = '#FFC107';
                break;
                case 'Very High (Severe Risk)':
                    $input_data['hdl_message_flag'] = 'danger';
                    $input_data['hdl_range_code']   = '#ff0000';
                break;
            }
        }
        // hdl_ldl
        if (!empty($input_data['hdl_ldl']) ) {
            if($years >= 0 && $years <= 5){
              

                    if ($input_data['hdl_ldl'] < 0.5) {
                        $input_data['hdl_ldl_message'] = 'Low (Hypo/Deficient)'; 
                    }
    
                    if (($input_data['hdl_ldl'] >= 0.5) && ($input_data['hdl_ldl'] <= 3.5)) {
                        $input_data['hdl_ldl_message'] = 'Normal (Desirable)'; 
                    }


                    if (($input_data['hdl_ldl'] >= 3.6) && ($input_data['hdl_ldl'] <= 4.0)) {
                        $input_data['hdl_ldl_message'] = 'Borderline High (Elevated Risk)'; 
                    }

                    if (($input_data['hdl_ldl'] >= 4.1) && ($input_data['hdl_ldl'] <= 4.5)) {
                        $input_data['hdl_ldl_message'] = 'High (Increased Risk)'; 
                    }

                    if ($input_data['hdl_ldl'] >= 4.6) {
                        $input_data['hdl_ldl_message'] = 'Very High (Severe Risk)'; 
                    }
                
            }
            if($years >= 6 && $years <= 19){
             

                    if ($input_data['hdl_ldl'] < 0.5) {
                        $input_data['hdl_ldl_message'] = 'Low (Hypo/Deficient)'; 
                    }
    
                    if (($input_data['hdl_ldl'] >= 0.5) && ($input_data['hdl_ldl'] <= 3.5)) {
                        $input_data['hdl_ldl_message'] = 'Normal (Desirable)'; 
                    }


                    if (($input_data['hdl_ldl'] >= 3.6) && ($input_data['hdl_ldl'] <= 4.0)) {
                        $input_data['hdl_ldl_message'] = 'Borderline High (Elevated Risk)'; 
                    }

                    if (($input_data['hdl_ldl'] >= 4.1) && ($input_data['hdl_ldl'] <= 4.5)) {
                        $input_data['hdl_ldl_message'] = 'High (Increased Risk)'; 
                    }

                    if ($input_data['hdl_ldl'] >= 4.6) {
                        $input_data['hdl_ldl_message'] = 'Very High (Severe Risk)'; 
                    }
                
            }
            if($years >= 20 && $years <= 59){
              

                    if ($input_data['hdl_ldl'] < 0.5) {
                        $input_data['hdl_ldl_message'] = 'Low (Hypo/Deficient)'; 
                    }
    
                    if (($input_data['hdl_ldl'] >= 0.5) && ($input_data['hdl'] <= 3.5)) {
                        $input_data['hdl_ldl_message'] = 'Normal (Desirable)'; 
                    }


                    if (($input_data['hdl_ldl'] >= 3.6) && ($input_data['hdl'] <= 4.0)) {
                        $input_data['hdl_ldl_message'] = 'Borderline High (Elevated Risk)'; 
                    }

                    if (($input_data['hdl_ldl'] >= 4.1) && ($input_data['hdl'] <= 4.5)) {
                        $input_data['hdl_ldl_message'] = 'High (Increased Risk)'; 
                    }

                    if ($input_data['hdl_ldl'] >= 4.6) {
                        $input_data['hdl_ldl_message'] = 'Very High (Severe Risk)'; 
                    }
               


            }
            if($years >= 60){
              

                    if ($input_data['hdl_ldl'] < 0.5) {
                        $input_data['hdl_ldl_message'] = 'Low (Hypo/Deficient)'; 
                    }
    
                    if (($input_data['hdl_ldl'] >= 0.5) && ($input_data['hdl_ldl'] <= 3.5)) {
                        $input_data['hdl_ldl_message'] = 'Normal (Desirable)'; 
                    }


                    if (($input_data['hdl_ldl'] >= 3.6) && ($input_data['hdl_ldl'] <= 4.0)) {
                        $input_data['hdl_ldl_message'] = 'Borderline High (Elevated Risk)'; 
                    }

                    if (($input_data['hdl_ldl'] >= 4.1) && ($input_data['hdl_ldl'] <= 4.5)) {
                        $input_data['hdl_ldl_message'] = 'High (Increased Risk)'; 
                    }

                    if ($input_data['hdl_ldl'] >= 4.6) {
                        $input_data['hdl_ldl_message'] = 'Very High (Severe Risk)'; 
                    }

            }
            switch ($input_data['hdl_ldl_message']) {
                case 'Low (Hypo/Deficient)':
                    $input_data['hdl_ldl_message_flag'] = 'warning';
                    $input_data['hdl_ldl_range_code']   = '#0000ff';
                break;
                case 'Normal (Desirable)':
                    $input_data['hdl_ldl_message_flag'] = 'success';
                    $input_data['hdl_ldl_range_code']   = '#008000';
                break;
                case 'Borderline High (Elevated Risk)':
                    $input_data['hdl_ldl_message_flag'] = 'warning';
                    $input_data['hdl_ldl_range_code']   = '#fff707';
                break;
                case 'High (Increased Risk)':
                $input_data['hdl_ldl_message_flag'] = 'warning';
                $input_data['hdl_ldl_range_code']   = '#FFC107';
                break;
                case 'Very High (Severe Risk)':
                    $input_data['hdl_ldl_message_flag'] = 'danger';
                    $input_data['hdl_ldl_range_code']   = '#ff0000';
                break;
            }
        }

        if (!empty($input_data['triglycerides']) && !empty($input_data['triglycerides_unit'])) {
            if($years >= 0 && $years <= 5){
                if ($input_data['triglycerides_unit'] == 'mg/dL') {

                    if ($input_data['triglycerides'] < 40) {
                        $input_data['triglycerides_message'] = 'Low (Hypo/Deficient)'; 
                    }
    
                    if (($input_data['triglycerides'] >= 40) && ($input_data['triglycerides'] <= 100)) {
                        $input_data['triglycerides_message'] = 'Normal (Desirable)'; 
                    }


                    if (($input_data['triglycerides'] >= 101) && ($input_data['triglycerides'] <= 120)) {
                        $input_data['triglycerides_message'] = 'Borderline High (Elevated Risk)'; 
                    }

                    if (($input_data['triglycerides'] >= 121) && ($input_data['triglycerides'] <= 150)) {
                        $input_data['triglycerides_message'] = 'High (Increased Risk)'; 
                    }

                    if ($input_data['triglycerides'] >= 151) {
                        $input_data['triglycerides_message'] = 'Very High (Severe Risk)'; 
                    }
                } 


            }
            if($years >= 6 && $years <= 19){
                if ($input_data['triglycerides_unit'] == 'mg/dL') {

                    if ($input_data['triglycerides'] < 60) {
                        $input_data['triglycerides_message'] = 'Low (Hypo/Deficient)'; 
                    }
    
                    if (($input_data['triglycerides'] >= 60) && ($input_data['triglycerides'] <= 130)) {
                        $input_data['triglycerides_message'] = 'Normal (Desirable)'; 
                    }


                    if (($input_data['triglycerides'] >= 131) && ($input_data['triglycerides'] <= 150)) {
                        $input_data['triglycerides_message'] = 'Borderline High (Elevated Risk)'; 
                    }

                    if (($input_data['triglycerides'] >= 151) && ($input_data['triglycerides'] <= 200)) {
                        $input_data['triglycerides_message'] = 'High (Increased Risk)'; 
                    }

                    if ($input_data['triglycerides'] >= 201) {
                        $input_data['triglycerides_message'] = 'Very High (Severe Risk)'; 
                    }
                } 


            }
            if($years >= 20 && $years <= 59){
                if ($input_data['triglycerides_unit'] == 'mg/dL') {

                    if ($input_data['triglycerides'] < 50) {
                        $input_data['triglycerides_message'] = 'Low (Hypo/Deficient)'; 
                    }
    
                    if (($input_data['triglycerides'] >= 50) && ($input_data['triglycerides'] <= 150)) {
                        $input_data['triglycerides_message'] = 'Normal (Desirable)'; 
                    }


                    if (($input_data['triglycerides'] >= 151) && ($input_data['triglycerides'] <= 200)) {
                        $input_data['triglycerides_message'] = 'Borderline High (Elevated Risk)'; 
                    }

                    if (($input_data['triglycerides'] >= 201) && ($input_data['triglycerides'] <= 205)) {
                        $input_data['triglycerides_message'] = 'High (Increased Risk)'; 
                    }

                    if ($input_data['triglycerides'] >= 251) {
                        $input_data['triglycerides_message'] = 'Very High (Severe Risk)'; 
                    }
                } 


            }
            if($years >= 60){
                if ($input_data['triglycerides_unit'] == 'mg/dL') {

                    if ($input_data['triglycerides'] < 60) {
                        $input_data['triglycerides_message'] = 'Low (Hypo/Deficient)'; 
                    }
    
                    if (($input_data['triglycerides'] >= 60) && ($input_data['triglycerides'] <= 150)) {
                        $input_data['triglycerides_message'] = 'Normal (Desirable)'; 
                    }


                    if (($input_data['triglycerides'] >= 151) && ($input_data['triglycerides'] <= 200)) {
                        $input_data['triglycerides_message'] = 'Borderline High (Elevated Risk)'; 
                    }

                    if (($input_data['triglycerides'] >= 201) && ($input_data['triglycerides'] <= 250)) {
                        $input_data['triglycerides_message'] = 'High (Increased Risk)'; 
                    }

                    if ($input_data['triglycerides'] >= 251) {
                        $input_data['triglycerides_message'] = 'Very High (Severe Risk)'; 
                    }
                } 


            }
            switch ($input_data['triglycerides_message']) {
                case 'Low (Hypo/Deficient)':
                    $input_data['triglycerides_message_flag'] = 'warning';
                    $input_data['triglycerides_range_code']   = '#0000ff';
                break;
                case 'Normal (Desirable)':
                    $input_data['triglycerides_message_flag'] = 'success';
                    $input_data['triglycerides_range_code']   = '#008000';
                break;
                case 'Borderline High (Elevated Risk)':
                    $input_data['triglycerides_message_flag'] = 'warning';
                    $input_data['triglycerides_range_code']   = '#fff707';
                break;
                case ' High (Increased Risk)':
                $input_data['triglycerides_message_flag'] = 'warning';
                $input_data['triglycerides_range_code']   = '#FFC107';
                break;
                case 'Very High (Severe Risk)':
                    $input_data['triglycerides_message_flag'] = 'danger';
                    $input_data['triglycerides_range_code']   = '#ff0000';
                break;
            }
        }


    //     if (!empty($input_data['total']) && !empty($input_data['total_unit'])) {

    //         if ($input_data['total_unit'] == 'mg/dL') {

    //             if ($input_data['total'] < 200) {
    //                 $input_data['total_message'] = 'Optimal'; // green
    //             }

    //             if (($input_data['total'] >= 200) && ($input_data['total'] <= 239)) {
    //                 $input_data['total_message'] = 'Intermediate'; // green
    //             }

    //             if ($input_data['total'] > 239) {
    //                 $input_data['total_message'] = 'High'; // red
    //             }
    //         }

    //         if ($input_data['total_unit'] == 'mmol/L') {

    //             if ($input_data['total'] < 5.3) {
    //                 $input_data['total_message'] = 'Optimal';
    //             }

    //             if (($input_data['total'] >= 5.3) && ($input_data['total'] <= 6.2)) {
    //                 $input_data['total_message'] = 'Intermediate';
    //             }

    //             if ($input_data['total'] > 6.2) {
    //                 $input_data['total_message'] = 'High';
    //             }
    //         }

    //         switch ($input_data['total_message']) {
    //             case 'Optimal':
    //                 $input_data['total_message_flag'] = 'success';
    //                 $input_data['total_range_code']   = '#008000';
    //             break;
    //             case 'Intermediate':
    //                 $input_data['total_message_flag'] = 'warning';
    //                 $input_data['total_range_code']   = '#FFC107';
    //             break;
    //             case 'High':
    //                 $input_data['total_message_flag'] = 'danger';
    //                 $input_data['total_range_code']   = '#ff0000';
    //             break;
    //         }
    //     }



    //     if (!empty($input_data['vldl']) && !empty($input_data['vldl_unit'])) {

    //         if ($input_data['vldl_unit'] == 'mg/dL') {

    //             if($input_data['vldl'] < 2) {
    //                 $input_data['vldl_message'] = 'Low';
    //             }

    //             if ($input_data['vldl'] >= 2 && $input_data['vldl'] <= 30) {
    //                 $input_data['vldl_message'] = 'Optimal';
    //             }

    //             if ($input_data['vldl'] > 30 && $input_data['vldl'] <= 40) {
    //                 $input_data['vldl_message'] = 'Borderline elevated';
    //             }

    //             if ($input_data['vldl'] > 40) {
    //                 $input_data['vldl_message'] = 'High';
    //             }
    //         }

    //         if ($input_data['vldl_unit'] == 'mmol/L') {

    //             if($input_data['vldl'] < 0.1) {
    //                 $input_data['vldl_message'] = 'Low';
    //             }

    //             if ($input_data['vldl'] >= 0.1 && $input_data['vldl'] <= 0.8) {
    //                 $input_data['vldl_message'] = 'Optimal';
    //             }

    //             if ($input_data['vldl'] > 0.8 && $input_data['vldl'] <= 1.0) {
    //                 $input_data['vldl_message'] = 'Borderline elevated';
    //             }

    //             if ($input_data['vldl'] > 1.0) {
    //                 $input_data['vldl_message'] = 'High';
    //             }
    //         }

    //         switch ($input_data['vldl_message']) {
    //             case 'Low':
    //                 $input_data['vldl_message_flag'] = 'primary';
    //                 $input_data['vldl_range_code']   = '#0000ff';
    //                 break;
    //             case 'Optimal':
    //                 $input_data['vldl_message_flag'] = 'success';
    //                 $input_data['vldl_range_code']   = '#008000';
    //                 break;
    //             case 'Borderline elevated':
    //                 $input_data['vldl_message_flag'] = 'warning';
    //                 $input_data['vldl_range_code']   = '#ffc107';
    //                 break;
    //             case 'High':
    //                 $input_data['vldl_message_flag'] = 'danger';
    //                 $input_data['vldl_range_code']   = '#ff0000';
    //                 break;
    //         }
    //     }

    //     if (!empty($input_data['ldl']) && !empty($input_data['ldl_unit'])) {

    //         if ($input_data['ldl_unit'] == 'mg/dL') {

    //             if ($input_data['ldl'] < 130) {
    //                 $input_data['ldl_message'] = 'Optimal';
    //             }

    //             if (($input_data['ldl'] >= 130) && ($input_data['ldl'] <= 159)) {
    //                 $input_data['ldl_message'] = 'Intermediate';
    //             }

    //             if ($input_data['ldl'] > 159) {
    //                 $input_data['ldl_message'] = 'High';
    //             }
    //         }

    //         if ($input_data['ldl_unit'] == 'mmol/L') {

    //             if ($input_data['ldl'] < 3.36) {
    //                 $input_data['ldl_message'] = 'Optimal';
    //             }

    //             if (($input_data['ldl'] >= 3.36) && ($input_data['ldl'] <= 4.11)) {
    //                 $input_data['ldl_message'] = 'Intermediate';
    //             }

    //             if ($input_data['ldl'] > 4.11) {
    //                 $input_data['ldl_message'] = 'High';
    //             }
    //         }

    //         switch ($input_data['ldl_message']) {
    //             case 'Optimal':
    //                 $input_data['ldl_message_flag'] = 'success';
    //                 $input_data['ldl_range_code']   = '#008000';
    //                 break;
    //             case 'Intermediate':
    //                 $input_data['ldl_message_flag'] = 'warning';
    //                 $input_data['ldl_range_code']   = '#ffc107';
    //                 break;
    //             case 'High':
    //                 $input_data['ldl_message_flag'] = 'danger';
    //                 $input_data['ldl_range_code']   = '#ff0000';
    //                 break;
    //         }
    //     }

    //     if (!empty($input_data['hdl']) && !empty($input_data['hdl_unit'])) {

    //         if ($input_data['hdl_unit'] == 'mg/dL') {

    //             if ($input_data['hdl'] < 40) {
    //                 $input_data['hdl_message'] = 'High';
    //             }

    //             if (($input_data['hdl'] >= 40) && ($input_data['hdl'] <= 60)) {
    //                 $input_data['hdl_message'] = 'Intermediate';
    //             }

    //             if ($input_data['hdl'] > 60) {
    //                 $input_data['hdl_message'] = 'Optimal';
    //             }
    //         }

    //         if ($input_data['hdl_unit'] == 'mmol/L') {

    //             if ($input_data['hdl'] < 1.03) {
    //                 $input_data['hdl_message'] = 'High';
    //             }

    //             if (($input_data['hdl'] >= 1.03) && ($input_data['hdl'] <= 1.55)) {
    //                 $input_data['hdl_message'] = 'Intermediate';
    //             }

    //             if ($input_data['hdl'] > 1.55) {
    //                 $input_data['hdl_message'] = 'Optimal';
    //             }
    //         }

    //         switch ($input_data['hdl_message']) {
    //             case 'Optimal':
    //                 $input_data['hdl_message_flag'] = 'success';
    //                 $input_data['hdl_range_code']   = '#008000';
    //                 break;
    //             case 'Intermediate':
    //                 $input_data['hdl_message_flag'] = 'warning';
    //                 $input_data['hdl_range_code']   = '#ffc107';
    //                 break;
    //             case 'High':
    //                 $input_data['hdl_message_flag'] = 'danger';
    //                 $input_data['hdl_range_code']   = '#ff0000';
    //                 break;
    //         }
    //     }

    //     if (!empty($input_data['triglycerides']) && !empty($input_data['triglycerides_unit'])) {
    //         if ($input_data['triglycerides_unit'] == 'mg/dL') {

    //             if ($input_data['triglycerides'] < 150) {
    //                 $input_data['triglycerides_message'] = 'Optimal';
    //             }

    //             if (($input_data['triglycerides'] >= 150) && ($input_data['triglycerides'] <= 199)) {
    //                 $input_data['triglycerides_message'] = 'Intermediate';
    //             }

    //             if ($input_data['triglycerides'] > 199) {
    //                 $input_data['triglycerides_message'] = 'High';
    //             }
    //         }

    //         if ($input_data['triglycerides_unit'] == 'mmol/L') {

    //             if ($input_data['triglycerides'] < 1.69) {
    //                 $input_data['triglycerides_message'] = 'Optimal';
    //             }

    //             if (($input_data['triglycerides'] >= 1.69) && ($input_data['triglycerides'] <= 2.25)) {
    //                 $input_data['triglycerides_message'] = 'Intermediate';
    //             }

    //             if ($input_data['triglycerides'] > 2.25) {
    //                 $input_data['triglycerides_message'] = 'High';
    //             }
    //         }

    //         switch ($input_data['triglycerides_message']) {
    //             case 'Optimal':
    //                 $input_data['triglycerides_message_flag'] = 'success';
    //                 $input_data['triglycerides_range_code']   = '#008000';
    //                 break;
    //             case 'Intermediate':
    //                 $input_data['triglycerides_message_flag'] = 'warning';
    //                 $input_data['triglycerides_range_code']   = '#ffc107';
    //                 break;
    //             case 'High':
    //                 $input_data['triglycerides_message_flag'] = 'danger';
    //                 $input_data['triglycerides_range_code']   = '#ff0000';
    //                 break;
    //         }
    //     }

    //     if (!empty($input_data['hdl_ldl'])) {

    //         if ($input_data['hdl_ldl'] < 2.5) {
    //             $input_data['hdl_ldl_message'] = 'Optimal';
    //             $input_data['hdl_ldl_message_flag'] = 'success';
    //             $input_data['hdl_ldl_range_code']   = '#008000';
    //         }

    //         if (($input_data['hdl_ldl'] >= 2.5) && ($input_data['hdl_ldl'] <= 3.5)) {
    //             $input_data['hdl_ldl_message'] = 'Moderate';
    //             $input_data['hdl_ldl_message_flag'] = 'warning';
    //             $input_data['hdl_ldl_range_code']   = '#ffc107';
    //         }

    //         if ($input_data['hdl_ldl'] > 3.5) {
    //             $input_data['hdl_ldl_message'] = 'High';
    //             $input_data['hdl_ldl_message_flag'] = 'danger';
    //             $input_data['hdl_ldl_range_code']   = '#ff0000';
    //         }
    //     }



        return $input_data;
    }



    public static function keytone_flag($input_data)
    {
        $input_data['keytoneFlag']      = 'Warning';
        $input_data['keytoneFlagColor'] = 'warning';
        $input_data['range_code']    = '#ffc107';
        if (!empty($input_data['keytone'])) {

            if ($input_data['keytone'] < 0.5) {
                $input_data['keytoneFlag']      = 'Low (Hypoglycemia)';
                $input_data['keytoneFlagColor'] = 'warning';
                $input_data['range_code']    = '#0000ff';
            }

            if (($input_data['keytone'] >= 0.5) && ($input_data['keytone'] <= 1.5)) {
                $input_data['keytoneFlag']      = 'Moderate (Ketosis)';
                $input_data['keytoneFlagColor'] = 'success';
                $input_data['range_code']    = '#ffc107';
            }

             if (($input_data['keytone'] >= 1.6)) {
                $input_data['keytoneFlag']      = 'High (Ketoacidosis)';
                $input_data['keytoneFlagColor'] = 'danger';
                $input_data['range_code']    = '#ff0000';
            }
        }

        return $input_data;
    }

    public static function hemoglobin_flag($input_data, $gender,$years, $months, $days)
    {
        $input_data['hemoglobinFlag']      = '';
        $input_data['hemoglobinFlagColor'] = '';
        $input_data['range_code']    = '';

        if (!empty($input_data['hemoglobin'])) {
            if($years >= 0 && $years <= 1){
                if (($input_data['hemoglobin'] < '10')) {
                                $input_data['hemoglobinFlag']      = 'Anemia (Low)';
                                $input_data['hemoglobinFlagColor'] = 'primary';
                                $input_data['range_code']    = '#0000ff';
                            }
            
                            if (($input_data['hemoglobin'] >= '10') && ($input_data['hemoglobin'] <= '24.9')) {
                                $input_data['hemoglobinFlag']      = 'Normal';
                                $input_data['hemoglobinFlagColor'] = 'success';
                                $input_data['range_code']    = '#008000';
                            }
            
                            if (($input_data['hemoglobin'] >= '25')) {
                                $input_data['hemoglobinFlag']      = 'Polycythemia (High)';
                                $input_data['hemoglobinFlagColor'] = 'danger';
                                $input_data['range_code']    = '#ff0000';
                            }

            }

            if($years >= 2 && $years <= 5){
                if (($input_data['hemoglobin'] < '10')) {
                                $input_data['hemoglobinFlag']      = 'Anemia (Low)';
                                $input_data['hemoglobinFlagColor'] = 'primary';
                                $input_data['range_code']    = '#0000ff';
                            }
            
                            if (($input_data['hemoglobin'] >= '10') && ($input_data['hemoglobin'] <= '18.5')) {
                                $input_data['hemoglobinFlag']      = 'Normal';
                                $input_data['hemoglobinFlagColor'] = 'success';
                                $input_data['range_code']    = '#008000';
                            }
            
                            if (($input_data['hemoglobin'] >= '18.5')) {
                                $input_data['hemoglobinFlag']      = 'Polycythemia (High)';
                                $input_data['hemoglobinFlagColor'] = 'danger';
                                $input_data['range_code']    = '#ff0000';
                            }

            }

            if($years >= 6 && $years <= 11){
                if (($input_data['hemoglobin'] < '9')) {
                                $input_data['hemoglobinFlag']      = 'Anemia (Low)';
                                $input_data['hemoglobinFlagColor'] = 'primary';
                                $input_data['range_code']    = '#0000ff';
                            }
            
                            if (($input_data['hemoglobin'] >= '9') && ($input_data['hemoglobin'] <= '15')) {
                                $input_data['hemoglobinFlag']      = 'Normal';
                                $input_data['hemoglobinFlagColor'] = 'success';
                                $input_data['range_code']    = '#008000';
                            }
            
                            if (($input_data['hemoglobin'] > '15')) {
                                $input_data['hemoglobinFlag']      = 'Polycythemia (High)';
                                $input_data['hemoglobinFlagColor'] = 'danger';
                                $input_data['range_code']    = '#ff0000';
                            }

            }

            if($years >=12  && $years <= 19){
                if (($input_data['hemoglobin'] < '10')) {
                                $input_data['hemoglobinFlag']      = 'Anemia (Low)';
                                $input_data['hemoglobinFlagColor'] = 'primary';
                                $input_data['range_code']    = '#0000ff';
                            }
            
                            if (($input_data['hemoglobin'] >= '10') && ($input_data['hemoglobin'] <= '16')) {
                                $input_data['hemoglobinFlag']      = 'Normal';
                                $input_data['hemoglobinFlagColor'] = 'success';
                                $input_data['range_code']    = '#008000';
                            }
            
                            if (($input_data['hemoglobin'] >= '16')) {
                                $input_data['hemoglobinFlag']      = 'Polycythemia (High)';
                                $input_data['hemoglobinFlagColor'] = 'danger';
                                $input_data['range_code']    = '#ff0000';
                            }

            }

            if($years >=20  && $years <= 59){
                if($gender == 'Male'){

                            if (($input_data['hemoglobin'] < '11.5')) {
                                $input_data['hemoglobinFlag']      = 'Anemia (Low)';
                                $input_data['hemoglobinFlagColor'] = 'primary';
                                $input_data['range_code']    = '#0000ff';
                            }
            
                            if (($input_data['hemoglobin'] >= '11.5') && ($input_data['hemoglobin'] <= '17.5')) {
                                $input_data['hemoglobinFlag']      = 'Normal';
                                $input_data['hemoglobinFlagColor'] = 'success';
                                $input_data['range_code']    = '#008000';
                            }
            
                            if (($input_data['hemoglobin'] > '17.5')) {
                                $input_data['hemoglobinFlag']      = 'Polycythemia (High)';
                                $input_data['hemoglobinFlagColor'] = 'danger';
                                $input_data['range_code']    = '#ff0000';
                            }
                        }
                        if($gender == 'Female'){
            
                            if (($input_data['hemoglobin'] <= '10.0')) {
                                $input_data['hemoglobinFlag']      = 'Anemia (Low)';
                                $input_data['hemoglobinFlagColor'] = 'primary';
                                $input_data['range_code']    = '#0000ff';
                            }
            
                            if (($input_data['hemoglobin'] >= '10.5') && ($input_data['hemoglobin'] <= '15.5')) {
                                $input_data['hemoglobinFlag']      = 'Normal';
                                $input_data['hemoglobinFlagColor'] = 'success';
                                $input_data['range_code']    = '#008000';
                            }
            
                            if (($input_data['hemoglobin'] > '15.5')) {
                                $input_data['hemoglobinFlag']      = 'Polycythemia (High)';
                                $input_data['hemoglobinFlagColor'] = 'danger';
                                $input_data['range_code']    = '#ff0000';
                            }
                        }
            }

            if($years >=60 ){
                if($gender == 'Male'){

                            if (($input_data['hemoglobin'] < '11.5')) {
                                $input_data['hemoglobinFlag']      = 'Anemia (Low)';
                                $input_data['hemoglobinFlagColor'] = 'primary';
                                $input_data['range_code']    = '#0000ff';
                            }
            
                            if (($input_data['hemoglobin'] >= '11.5') && ($input_data['hemoglobin'] <= '17.5')) {
                                $input_data['hemoglobinFlag']      = 'Normal';
                                $input_data['hemoglobinFlagColor'] = 'success';
                                $input_data['range_code']    = '#008000';
                            }
            
                            if (($input_data['hemoglobin'] > '17.5')) {
                                $input_data['hemoglobinFlag']      = 'Polycythemia (High)';
                                $input_data['hemoglobinFlagColor'] = 'danger';
                                $input_data['range_code']    = '#ff0000';
                            }
                        }
                        if($gender == 'Female'){
            
                            if (($input_data['hemoglobin'] <= '10.0')) {
                                $input_data['hemoglobinFlag']      = 'Anemia (Low)';
                                $input_data['hemoglobinFlagColor'] = 'primary';
                                $input_data['range_code']    = '#0000ff';
                            }
            
                            if (($input_data['hemoglobin'] >= '10.5') && ($input_data['hemoglobin'] <= '15.5')) {
                                $input_data['hemoglobinFlag']      = 'Normal';
                                $input_data['hemoglobinFlagColor'] = 'success';
                                $input_data['range_code']    = '#008000';
                            }
            
                            if (($input_data['hemoglobin'] > '15.5')) {
                                $input_data['hemoglobinFlag']      = 'Polycythemia (High)';
                                $input_data['hemoglobinFlagColor'] = 'danger';
                                $input_data['range_code']    = '#ff0000';
                            }
                        }
            }

        }


        // if (!empty($input_data['hemoglobin'])) {

        //     if($gender == 'Male'){

        //         if (($input_data['hemoglobin'] < '13.8')) {
        //             $input_data['hemoglobinFlag']      = 'Low';
        //             $input_data['hemoglobinFlagColor'] = 'primary';
        //             $input_data['range_code']    = '#0000ff';
        //         }

        //         if (($input_data['hemoglobin'] >= '13.8') && ($input_data['hemoglobin'] <= '17.2')) {
        //             $input_data['hemoglobinFlag']      = 'Normal';
        //             $input_data['hemoglobinFlagColor'] = 'success';
        //             $input_data['range_code']    = '#008000';
        //         }

        //         if (($input_data['hemoglobin'] > '17.2')) {
        //             $input_data['hemoglobinFlag']      = 'High';
        //             $input_data['hemoglobinFlagColor'] = 'danger';
        //             $input_data['range_code']    = '#ff0000';
        //         }
        //     }
        //     if($gender == 'Female'){

        //         if (($input_data['hemoglobin'] < '12.1')) {
        //             $input_data['hemoglobinFlag']      = 'Low';
        //             $input_data['hemoglobinFlagColor'] = 'primary';
        //             $input_data['range_code']    = '#0000ff';
        //         }

        //         if (($input_data['hemoglobin'] >= '12.1') && ($input_data['hemoglobin'] <= '15.1')) {
        //             $input_data['hemoglobinFlag']      = 'Normal';
        //             $input_data['hemoglobinFlagColor'] = 'success';
        //             $input_data['range_code']    = '#008000';
        //         }

        //         if (($input_data['hemoglobin'] > '15.1')) {
        //             $input_data['hemoglobinFlag']      = 'High';
        //             $input_data['hemoglobinFlagColor'] = 'danger';
        //             $input_data['range_code']    = '#ff0000';
        //         }
        //     }
        // }

        return $input_data;
    }

    public static function hct_flag($input_data, $years, $months, $days, $gender)
    {
        $input_data['hctFlag']      = '';
        $input_data['hctFlagColor'] = '';
        $input_data['range_code']    = '';
        // if(empty($dob) || $dob == '0000-00-00'){
        //     $years = 20;
        // }
        // if (!empty($input_data['hct'])) {

        //     if (($input_data['hct'] >= '41') && ($input_data['hct'] <= '50')) {
        //             $input_data['hctFlag']      = 'Normal';
        //             $input_data['hctFlagColor'] = 'success';
        //             $input_data['range_code']    = '#008000';
        //         }

        //     if($gender == 'Male'){

        //         if (($input_data['hct'] < '41')) {
        //             $input_data['hctFlag']      = 'Low';
        //             $input_data['hctFlagColor'] = 'primary';
        //             $input_data['range_code']    = '#0000ff';
        //         }

        //         if (($input_data['hct'] >= '41') && ($input_data['hct'] <= '50')) {
        //             $input_data['hctFlag']      = 'Normal';
        //             $input_data['hctFlagColor'] = 'success';
        //             $input_data['range_code']    = '#008000';
        //         }

        //         if (($input_data['hct'] > '50')) {
        //             $input_data['hctFlag']      = 'High';
        //             $input_data['hctFlagColor'] = 'danger';
        //             $input_data['range_code']    = '#ff0000';
        //         }
        //     }
        //     if($gender == 'Female'){

        //         if (($input_data['hct'] < '36')) {
        //             $input_data['hctFlag']      = 'Low';
        //             $input_data['hctFlagColor'] = 'primary';
        //             $input_data['range_code']    = '#0000ff';
        //         }

        //         if (($input_data['hct'] >= '36') && ($input_data['hct'] <= '48')) {
        //             $input_data['hctFlag']      = 'Normal';
        //             $input_data['hctFlagColor'] = 'success';
        //             $input_data['range_code']    = '#008000';
        //         }

        //         if (($input_data['hct'] > '48')) {
        //             $input_data['hctFlag']      = 'High';
        //             $input_data['hctFlagColor'] = 'danger';
        //             $input_data['range_code']    = '#ff0000';
        //         }
        //     }
        // }

        if($years >= 0 && $years <= 1){
            if (($input_data['hct'] <= '33')) {
                $input_data['hctFlag']      = 'Low (Anemia/Deficiency)';
                $input_data['hctFlagColor'] = 'primary';
                $input_data['range_code']    = '#0000ff';
            }

            if (($input_data['hct'] >= '33') && ($input_data['hct'] <= '39')) {
                $input_data['hctFlag']      = 'Normal';
                $input_data['hctFlagColor'] = 'success';
                $input_data['range_code']    = '#008000';
            }

            if (($input_data['hct'] > '39')) {
                $input_data['hctFlag']      = 'High (Polycythemia)';
                $input_data['hctFlagColor'] = 'danger';
                $input_data['range_code']    = '#FF0000';
            }
        }
        if($years >= 2 && $years <= 5){
            if (($input_data['hct'] < '34')) {
                $input_data['hctFlag']      = 'Low (Anemia/Deficiency)';
                $input_data['hctFlagColor'] = 'primary';
                $input_data['range_code']    = '#0000ff';
            }

            if (($input_data['hct'] >= '34') && ($input_data['hct'] <= '40')) {
                $input_data['hctFlag']      = 'Normal';
                $input_data['hctFlagColor'] = 'success';
                $input_data['range_code']    = '#008000';
            }

            if (($input_data['hct'] > '40')) {
                $input_data['hctFlag']      = 'High (Polycythemia)';
                $input_data['hctFlagColor'] = 'danger';
                $input_data['range_code']    = '#FF0000';
            }
        }
        if($years >= 6 && $years <= 12){
            if (($input_data['hct'] < '36')) {
                $input_data['hctFlag']      = 'Low (Anemia/Deficiency)';
                $input_data['hctFlagColor'] = 'primary';
                $input_data['range_code']    = '#0000ff';
            }

            if (($input_data['hct'] >= '36') && ($input_data['hct'] <= '46')) {
                $input_data['hctFlag']      = 'Normal';
                $input_data['hctFlagColor'] = 'success';
                $input_data['range_code']    = '#008000';
            }

            if (($input_data['hct'] > '46')) {
                $input_data['hctFlag']      = 'High (Polycythemia)';
                $input_data['hctFlagColor'] = 'danger';
                $input_data['range_code']    = '#FF0000';
            }
        }
        // male & female
        if($years >= 13 && $years <= 18){
            if($gender == 'Male'){

                if (($input_data['hct'] < '37')) {
                    $input_data['hctFlag']      = 'Low (Anemia/Deficiency)';
                    $input_data['hctFlagColor'] = 'primary';
                    $input_data['range_code']    = '#0000ff';
                }

                if (($input_data['hct'] >= '37') && ($input_data['hct'] <= '47')) {
                    $input_data['hctFlag']      = 'Normal';
                    $input_data['hctFlagColor'] = 'success';
                    $input_data['range_code']    = '#008000';
                }

                if (($input_data['hct'] >= '47')) {
                    $input_data['hctFlag']      = 'High (Polycythemia)';
                    $input_data['hctFlagColor'] = 'danger';
                    $input_data['range_code']    = '#FF0000';
                }
            }
            if($gender == 'Female'){

                if (($input_data['hct'] < '36')) {
                    $input_data['hctFlag']      = 'Low (Anemia/Deficiency)';
                    $input_data['hctFlagColor'] = 'primary';
                    $input_data['range_code']    = '#0000ff';
                }

                if (($input_data['hct'] >= '36') && ($input_data['hct'] <= '44')) {
                    $input_data['hctFlag']      = 'Normal';
                    $input_data['hctFlagColor'] = 'success';
                    $input_data['range_code']    = '#008000';
                }

                if (($input_data['hct'] >= '44')) {
                    $input_data['hctFlag']      = 'High (Polycythemia)';
                    $input_data['hctFlagColor'] = 'danger';
                    $input_data['range_code']    = '#FF0000';
                }
            }
        }
              // male & female
        if($years >= 19 && $years <= 59){
            if($gender == 'Male'){

                if (($input_data['hct'] <= '39')) {
                    $input_data['hctFlag']      = 'Low (Anemia/Deficiency)';
                    $input_data['hctFlagColor'] = 'primary';
                    $input_data['range_code']    = '#0000ff';
                }

                if (($input_data['hct'] >= '39') && ($input_data['hct'] <= '50')) {
                    $input_data['hctFlag']      = 'Normal';
                    $input_data['hctFlagColor'] = 'success';
                    $input_data['range_code']    = '#008000';
                }

                if (($input_data['hct'] >= '50')) {
                    $input_data['hctFlag']      = 'High (Polycythemias)';
                    $input_data['hctFlagColor'] = 'danger';
                    $input_data['range_code']    = '#FF0000';
                }
            }
            if($gender == 'Female'){

                if (($input_data['hct'] <= '36')) {
                    $input_data['hctFlag']      = 'Low (Anemia/Deficiency)';
                    $input_data['hctFlagColor'] = 'primary';
                    $input_data['range_code']    = '#0000ff';
                }

                if (($input_data['hct'] >= '36') && ($input_data['hct'] <= '46')) {
                    $input_data['hctFlag']      = 'Normal';
                    $input_data['hctFlagColor'] = 'success';
                    $input_data['range_code']    = '#008000';
                }

                if (($input_data['hct'] >= '46')) {
                    $input_data['hctFlag']      = 'High (Polycythemia)';
                    $input_data['hctFlagColor'] = 'danger';
                    $input_data['range_code']    = '#FF0000';
                }
            }
        }

        if($years >60){
            if($gender == 'Male'){
               
                if (($input_data['hct'] <= 38)) {
                    $input_data['hctFlag']      = 'Low (Anemia/Deficiency)';
                    $input_data['hctFlagColor'] = 'primary';
                    $input_data['range_code']    = '#0000ff';
                }

                if (($input_data['hct'] >= '38') && ($input_data['hct'] <= '50')) {
                    $input_data['hctFlag']      = 'Normal';
                    $input_data['hctFlagColor'] = 'success';
                    $input_data['range_code']    = '#008000';
                }

                if (($input_data['hct'] > '50')) {
                    $input_data['hctFlag']      = 'High (Polycythemia) ';
                    $input_data['hctFlagColor'] = 'danger';
                    $input_data['range_code']    = '#FF0000';
                } 
            }
        }

        if($years >60){
            if($gender == 'Female'){
               
                if (($input_data['hct'] <= '37')) {
                    $input_data['hctFlag']      = 'Low (Anemia/Deficiency)';
                    $input_data['hctFlagColor'] = 'primary';
                    $input_data['range_code']    = '#0000ff';
                }

                if (($input_data['hct'] >= '37') && ($input_data['hct'] <= '47')) {
                    $input_data['hctFlag']      = 'Normal';
                    $input_data['hctFlagColor'] = 'success';
                    $input_data['range_code']    = '#008000';
                }

                if (($input_data['hct'] >= '47')) {
                    $input_data['hctFlag']      = 'High (Polycythemia)';
                    $input_data['hctFlagColor'] = 'danger';
                    $input_data['range_code']    = '#FF0000';
                } 
            }
        }
 

        return $input_data;
    }


    public static function uric_acid_flag($input_data,  $years, $months, $days,$gender)
    {
        $input_data['uricFlag']      = '';
        $input_data['uricFlagColor'] = '';
        $input_data['range_code']    = '';

        if (!empty($input_data['uric_acid'])) {
            if($years >= 0 && $years <= 5){
                if (($input_data['uric_acid'] < '2.0')) {
                    $input_data['uricFlag']      = 'Low (Hypouricemia)';
                    $input_data['uricFlagColor'] = 'primary';
                    $input_data['range_code']    = '#0000ff';
                }

                if (($input_data['uric_acid'] >= '2.0') && ($input_data['uric_acid'] <= '5.5')) {
                    $input_data['uricFlag']      = 'Normal';
                    $input_data['uricFlagColor'] = 'success';
                    $input_data['range_code']    = '#008000';
                }

                if (($input_data['uric_acid'] > '5.5')) {
                    $input_data['uricFlag']      = 'High (Hyperuricemia)';
                    $input_data['uricFlagColor'] = 'danger';
                    $input_data['range_code']    = '#FFC107';
                }
            }  
            if($years >= 6 && $years <= 19){
                if (($input_data['uric_acid'] < '2.0')) {
                    $input_data['uricFlag']      = 'Low (Hypouricemia)';
                    $input_data['uricFlagColor'] = 'primary';
                    $input_data['range_code']    = '#0000ff';
                }

                if (($input_data['uric_acid'] >= '2.0') && ($input_data['uric_acid'] <= '6.0')) {
                    $input_data['uricFlag']      = 'Normal';
                    $input_data['uricFlagColor'] = 'success';
                    $input_data['range_code']    = '#008000';
                }

                if (($input_data['uric_acid'] > '6.0')) {
                    $input_data['uricFlag']      = 'High (Hyperuricemia)';
                    $input_data['uricFlagColor'] = 'danger';
                    $input_data['range_code']    = '#FF0000';
                }
            } 
            if($years >= 20 && $years <= 59) 
            {
             if($gender == 'Male'){

                if (($input_data['uric_acid'] < '3.0')) {
                    $input_data['uricFlag']      = 'Low (Hypouricemia)';
                    $input_data['uricFlagColor'] = 'primary';
                    $input_data['range_code']    = '#0000ff';
                }

                if (($input_data['uric_acid'] >= '3.0') && ($input_data['uric_acid'] <= '7.0')) {
                    $input_data['uricFlag']      = 'Normal';
                    $input_data['uricFlagColor'] = 'success';
                    $input_data['range_code']    = '#008000';
                }

                if (($input_data['uric_acid'] > '7.0')) {
                    $input_data['uricFlag']      = 'High (Hyperuricemia)';
                    $input_data['uricFlagColor'] = 'danger';
                    $input_data['range_code']    = '#FF0000';
                }
            }
            }
            if($years >= 20 && $years <= 59) 
            {
             if($gender == 'Female'){

                if (($input_data['uric_acid'] < '3.0')) {
                    $input_data['uricFlag']      = 'Low (Hypouricemia)';
                    $input_data['uricFlagColor'] = 'primary';
                    $input_data['range_code']    = '#0000ff';
                }

                if (($input_data['uric_acid'] >= '3.0') && ($input_data['uric_acid'] <= '6.0')) {
                    $input_data['uricFlag']      = 'Normal';
                    $input_data['uricFlagColor'] = 'success';
                    $input_data['range_code']    = '#008000';
                }

                if (($input_data['uric_acid'] > '6.0')) {
                    $input_data['uricFlag']      = 'High (Hyperuricemia)';
                    $input_data['uricFlagColor'] = 'danger';
                    $input_data['range_code']    = '#FF0000';
                }
            }
            }
            if($years >= 60) 
            {
             if($gender == 'Male'){
                if (($input_data['uric_acid'] < '3.0')) {
                    $input_data['uricFlag']      = 'Low (Hypouricemia)';
                    $input_data['uricFlagColor'] = 'primary';
                    $input_data['range_code']    = '#0000ff';
                }

                if (($input_data['uric_acid'] >= '3.0') && ($input_data['uric_acid'] <= '7.5')) {
                    $input_data['uricFlag']      = 'Normal';
                    $input_data['uricFlagColor'] = 'success';
                    $input_data['range_code']    = '#008000';
                }

                if (($input_data['uric_acid'] > '7.5')) {
                    $input_data['uricFlag']      = 'High (Hyperuricemia)';
                    $input_data['uricFlagColor'] = 'danger';
                    $input_data['range_code']    = '#FF0000';
                }
             }}
             if($years >= 60) 
             {
              if($gender == 'Female'){
                 if (($input_data['uric_acid'] < '3.0')) {
                     $input_data['uricFlag']      = 'Low (Hypouricemia)';
                     $input_data['uricFlagColor'] = 'primary';
                     $input_data['range_code']    = '#0000ff';
                 }
 
                 if (($input_data['uric_acid'] >= '3.0') && ($input_data['uric_acid'] <= '7.0')) {
                     $input_data['uricFlag']      = 'Normal';
                     $input_data['uricFlagColor'] = 'success';
                     $input_data['range_code']    = '#008000';
                 }
 
                 if (($input_data['uric_acid'] > '7.0')) {
                     $input_data['uricFlag']      = 'High (Hyperuricemia)';
                     $input_data['uricFlagColor'] = 'danger';
                     $input_data['range_code']    = '#FF0000';
                 }
              }}
        }

        // if (!empty($input_data['uric_acid'])) {

        //     if($gender == 'Male'){

        //         if (($input_data['uric_acid'] < '4.0')) {
        //             $input_data['uricFlag']      = 'Low';
        //             $input_data['uricFlagColor'] = 'primary';
        //             $input_data['range_code']    = '#0000ff';
        //         }

        //         if (($input_data['uric_acid'] >= '4.0') && ($input_data['uric_acid'] <= '8.5')) {
        //             $input_data['uricFlag']      = 'Normal';
        //             $input_data['uricFlagColor'] = 'success';
        //             $input_data['range_code']    = '#008000';
        //         }

        //         if (($input_data['uric_acid'] > '8.5')) {
        //             $input_data['uricFlag']      = 'High';
        //             $input_data['uricFlagColor'] = 'danger';
        //             $input_data['range_code']    = '#ff0000';
        //         }
        //     }
        //     if($gender == 'Female'){

        //         if (($input_data['uric_acid'] < '2.7')) {
        //             $input_data['uricFlag']      = 'Low';
        //             $input_data['uricFlagColor'] = 'primary';
        //             $input_data['range_code']    = '#0000ff';
        //         }

        //         if (($input_data['uric_acid'] >= '2.7') && ($input_data['uric_acid'] <= '7.3')) {
        //             $input_data['uricFlag']      = 'Normal';
        //             $input_data['uricFlagColor'] = 'success';
        //             $input_data['range_code']    = '#008000';
        //         }

        //         if (($input_data['uric_acid'] > '7.3')) {
        //             $input_data['uricFlag']      = 'High';
        //             $input_data['uricFlagColor'] = 'danger';
        //             $input_data['range_code']    = '#ff0000';
        //         }
        //     }
        // }

        return $input_data;
    }

    public static function spirometer_flag($input_data, $years, $months, $days, $gender)
    {
        $input_data['fvcFlag']      = '';
        $input_data['fvcFlagColor'] = '';
        $input_data['fvcRangeCode']    = '';

        $input_data['fev1Flag']      = '';
        $input_data['fev1FlagColor'] = '';
        $input_data['fev1RangeCode']    = '';

        $input_data['fev1FvcFlag']      = '';
        $input_data['fev1FvcFlagColor'] = '';
        $input_data['fev1FvcRangeCode']    = '';

        $input_data['pefFlag']      = '';
        $input_data['pefFlagColor'] = '';
        $input_data['pefRangeCode']    = '';

        $input_data['fef25Flag']      = '';
        $input_data['fef25FlagColor'] = '';
        $input_data['fef25RangeCode']    = '';

        $input_data['fef50Flag']      = '';
        $input_data['fef50FlagColor'] = '';
        $input_data['fef50RangeCode']    = '';

        $input_data['fef75Flag']      = '';
        $input_data['fef75FlagColor'] = '';
        $input_data['fef75RangeCode']    = '';

        $input_data['fef2575Flag']      = '';
        $input_data['fef2575FlagColor'] = '';
        $input_data['fef2575RangeCode']    = '';
        
    

        // $fvcFlag = $fev1Flag = $fev1FvcFlag = $pefFlag = '';

        // $flags = ['fvcFlag' => '', 'fvcFlagColor' => '', 'fvcRangeCode' => '', 'fev1Flag' => '', 'fev1FlagColor' => '', 'fev1RangeCode' => '', 'fev1FvcFlag' => '', 'fev1FvcFlagColor' => '', 'fev1FvcRangeCode' => '', 'pefFlag' => '', 'pefFlagColor' => '', 'pefRangeCode' => ''];
        // fvc(l/s)
        if(!empty($input_data['fvc'])) {
            if($years >= 0 && $years <= 4){

            if ($input_data['fvc'] >= 0.8) {
                $input_data['fvcFlag']      = 'Normal';
                $input_data['fvcFlagColor'] = 'success';
                $input_data['fvcRangeCode']    = '#008000';
            } else if ($input_data['fvc'] >= 0.6 && $input_data['fvc'] <= 0.79) {
                $input_data['fvcFlag']      = 'Mildly Reduced';
                $input_data['fvcFlagColor'] = 'primary';
                $input_data['fvcRangeCode']    = '#fff707';
            }
            else if ($input_data['fvc'] >= 0.4 && $input_data['fvc'] <= 0.59) {
                $input_data['fvcFlag']      = 'Moderately Reduced';
                $input_data['fvcFlagColor'] = 'primary';
                $input_data['fvcRangeCode']    = '#FFC107';
            } 
            else if ($input_data['fvc'] < 0.4) {
                $input_data['fvcFlag']      = 'Severely Reduced';
                $input_data['fvcFlagColor'] = 'danger';
                $input_data['fvcRangeCode']    = '#FF0000';
            }
            }
            // fvc(l/s)
            if($years >= 5 && $years <= 12){

        if ($input_data['fvc'] >= 1.5) {
            $input_data['fvcFlag']      = 'Normal';
            $input_data['fvcFlagColor'] = 'success';
            $input_data['fvcRangeCode']    = '#008000';
        } else if ($input_data['fvc'] >= 1.2 && $input_data['fvc'] <= 1.49) {
            $input_data['fvcFlag']      = 'Mildly Reduced';
            $input_data['fvcFlagColor'] = 'primary';
            $input_data['fvcRangeCode']    = '#fff707';
        }
        else if ($input_data['fvc'] >= 0.8 && $input_data['fvc'] <= 1.19) {
            $input_data['fvcFlag']      = 'Moderately Reduced';
            $input_data['fvcFlagColor'] = 'primary';
            $input_data['fvcRangeCode']    = '#FFC107';
        } 
        else if ($input_data['fvc'] < 0.8) {
            $input_data['fvcFlag']      = 'Severely Reduced';
            $input_data['fvcFlagColor'] = 'danger';
            $input_data['fvcRangeCode']    = '#FF0000';
        }
        }
        // fvc(l/s)
        if($years >= 13 && $years <= 19){

        if ($input_data['fvc'] >= 2.5) {
        $input_data['fvcFlag']      = 'Normal';
        $input_data['fvcFlagColor'] = 'success';
        $input_data['fvcRangeCode']    = '#008000';
         } else if ($input_data['fvc'] >= 2.0 && $input_data['fvc'] <= 2.49) {
        $input_data['fvcFlag']      = 'Mildly Reduced';
        $input_data['fvcFlagColor'] = 'primary';
        $input_data['fvcRangeCode']    = '#fff707';
        }
        else if ($input_data['fvc'] >= 1.5 && $input_data['fvc'] <= 1.99) {
        $input_data['fvcFlag']      = 'Moderately Reduced';
        $input_data['fvcFlagColor'] = 'primary';
        $input_data['fvcRangeCode']    = '#FFC107';
         } 
         else if ($input_data['fvc'] < 1.5) {
        $input_data['fvcFlag']      = 'Severely Reduced';
        $input_data['fvcFlagColor'] = 'danger';
        $input_data['fvcRangeCode']    = '#FF0000';
        }
        }
        // fvc(l/s)
        if($years >= 20 && $years <= 39){
        if($gender == 'Male' ){ 

        if ($input_data['fvc'] >= 4.0) {
    $input_data['fvcFlag']      = 'Normal';
    $input_data['fvcFlagColor'] = 'success';
    $input_data['fvcRangeCode']    = '#008000';
} else if ($input_data['fvc'] >= 3.5 && $input_data['fvc'] <= 3.99) {
    $input_data['fvcFlag']      = 'Mildly Reduced';
    $input_data['fvcFlagColor'] = 'primary';
    $input_data['fvcRangeCode']    = '#fff707';
}
else if ($input_data['fvc'] >= 2.5 && $input_data['fvc'] <= 3.49) {
    $input_data['fvcFlag']      = 'Moderately Reduced';
    $input_data['fvcFlagColor'] = 'primary';
    $input_data['fvcRangeCode']    = '#FFC107';
} 
else if ($input_data['fvc'] < 2.5) {
    $input_data['fvcFlag']      = 'Severely Reduced';
    $input_data['fvcFlagColor'] = 'danger';
    $input_data['fvcRangeCode']    = '#FF0000';
}
}
// fvc(l/s)
if($gender == 'Female' ){ 

if ($input_data['fvc'] >= 3.5) {
$input_data['fvcFlag']      = 'Normal';
$input_data['fvcFlagColor'] = 'success';
$input_data['fvcRangeCode']    = '#008000';
} else if ($input_data['fvc'] >= 3.0 && $input_data['fvc'] <= 3.49) {
$input_data['fvcFlag']      = 'Mildly Reduced';
$input_data['fvcFlagColor'] = 'primary';
$input_data['fvcRangeCode']    = '#fff707';
}
else if ($input_data['fvc'] >= 2.0 && $input_data['fvc'] <= 2.99) {
$input_data['fvcFlag']      = 'Moderately Reduced';
$input_data['fvcFlagColor'] = 'primary';
$input_data['fvcRangeCode']    = '#FFC107';
} 
else if ($input_data['fvc'] < 2.0) {
$input_data['fvcFlag']      = 'Severely Reduced';
$input_data['fvcFlagColor'] = 'danger';
$input_data['fvcRangeCode']    = '#FF0000';
}
}

        }
        // fvc(l/s)
        if($years >= 40 && $years <= 59){
            if($gender == 'Male' ){ 
    
    if ($input_data['fvc'] >= 3.5) {
        $input_data['fvcFlag']      = 'Normal';
        $input_data['fvcFlagColor'] = 'success';
        $input_data['fvcRangeCode']    = '#008000';
    } else if ($input_data['fvc'] >= 3.0 && $input_data['fvc'] <= 3.49) {
        $input_data['fvcFlag']      = 'Mildly Reduced';
        $input_data['fvcFlagColor'] = 'primary';
        $input_data['fvcRangeCode']    = '#fff707';
    }
    else if ($input_data['fvc'] >= 2.0 && $input_data['fvc'] <= 2.99) {
        $input_data['fvcFlag']      = 'Moderately Reduced';
        $input_data['fvcFlagColor'] = 'primary';
        $input_data['fvcRangeCode']    = '#FFC107';
    } 
    else if ($input_data['fvc'] < 2.0) {
        $input_data['fvcFlag']      = 'Severely Reduced';
        $input_data['fvcFlagColor'] = 'danger';
        $input_data['fvcRangeCode']    = '#FF0000';
    }
    }
    // fvc(l/s)
    if($gender == 'Female' ){ 
    
    if ($input_data['fvc'] >= 3.0) {
    $input_data['fvcFlag']      = 'Normal';
    $input_data['fvcFlagColor'] = 'success';
    $input_data['fvcRangeCode']    = '#008000';
    } else if ($input_data['fvc'] >= 2.5 && $input_data['fvc'] <= 2.99) {
    $input_data['fvcFlag']      = 'Mildly Reduced';
    $input_data['fvcFlagColor'] = 'primary';
    $input_data['fvcRangeCode']    = '#fff707';
    }
    else if ($input_data['fvc'] >= 1.5 && $input_data['fvc'] <= 2.49) {
    $input_data['fvcFlag']      = 'Moderately Reduced';
    $input_data['fvcFlagColor'] = 'primary';
    $input_data['fvcRangeCode']    = '#FFC107';
    } 
    else if ($input_data['fvc'] < 1.5) {
    $input_data['fvcFlag']      = 'Severely Reduced';
    $input_data['fvcFlagColor'] = 'danger';
    $input_data['fvcRangeCode']    = '#FF0000';
    }
    }
}
    
            // }fvc(l/s)
            if($years >= 60){

        if ($input_data['fvc'] >= 3.0) {
            $input_data['fvcFlag']      = 'Normal';
            $input_data['fvcFlagColor'] = 'success';
            $input_data['fvcRangeCode']    = '#008000';
        } else if ($input_data['fvc'] >= 2.5 && $input_data['fvc'] <= 2.99) {
            $input_data['fvcFlag']      = 'Mildly Reduced';
            $input_data['fvcFlagColor'] = 'primary';
            $input_data['fvcRangeCode']    = '#fff707';
        }
        else if ($input_data['fvc'] >= 2.0 && $input_data['fvc'] <= 2.49) {
            $input_data['fvcFlag']      = 'Moderately Reduced';
            $input_data['fvcFlagColor'] = 'primary';
            $input_data['fvcRangeCode']    = '#FFC107';
        } 
        else if ($input_data['fvc'] < 2.0) {
            $input_data['fvcFlag']      = 'Severely Reduced';
            $input_data['fvcFlagColor'] = 'danger';
            $input_data['fvcRangeCode']    = '#FF0000';
        }
        }
        
    }

        
        // fev1(l/s)
        if(!empty($input_data['fev1'])) {
            if($years >= 0 && $years <= 4){

            if ($input_data['fev1'] >= 0.6) {
                $input_data['fev1Flag']      = 'Normal';
                $input_data['fev1FlagColor'] = 'success';
                $input_data['fev1RangeCode']    = '#008000';
            } else if ($input_data['fev1'] >= 0.4 && $input_data['fev1'] <= 0.59) {
                $input_data['fev1Flag']      = 'Mildly Reduced';
                $input_data['fev1FlagColor'] = 'primary';
                $input_data['fev1RangeCode']    = '#fff707';
            }
            else if ($input_data['fev1'] >= 0.3 && $input_data['fev1'] <= 0.39) {
                $input_data['fev1Flag']      = 'Moderately Reduced';
                $input_data['fev1FlagColor'] = 'primary';
                $input_data['fev1RangeCode']    = '#FFC107';
            } 
            else if ($input_data['fev1'] < 0.3) {
                $input_data['fev1Flag']      = 'Severely Reduced';
                $input_data['fev1FlagColor'] = 'danger';
                $input_data['fev1RangeCode']    = '#FF0000';
            }
            }

            if($years >= 5 && $years <= 12){

        if ($input_data['fev1'] >= 1.2) {
            $input_data['fev1Flag']      = 'Normal';
            $input_data['fvcFlagColor'] = 'success';
            $input_data['fvcRangeCode']    = '#008000';
        } else if ($input_data['fev1'] >= 0.9 && $input_data['fev1'] <= 1.9) {
            $input_data['fev1Flag']      = 'Mildly Reduced';
            $input_data['fev1FlagColor'] = 'primary';
            $input_data['fev1RangeCode']    = '#fff707';
        }
        else if ($input_data['fev1'] >= 0.6 && $input_data['fev1'] <= 0.89) {
            $input_data['fev1Flag']      = 'Moderately Reduced';
            $input_data['fev1FlagColor'] = 'primary';
            $input_data['fev1RangeCode']    = '#FFC107';
        } 
        else if ($input_data['fev1'] < 0.6) {
            $input_data['fev1Flag']      = 'Severely Reduced';
            $input_data['fev1FlagColor'] = 'danger';
            $input_data['fev1RangeCode']    = '#FF0000';
        }
        }
        if($years >= 13 && $years <= 19){

        if ($input_data['fev1'] >= 2.0) {
        $input_data['fev1Flag']      = 'Normal';
        $input_data['fev1FlagColor'] = 'success';
        $input_data['fev1RangeCode']    = '#008000';
         } else if ($input_data['fev1'] >= 1.5 && $input_data['fev1'] <= 1.99) {
        $input_data['fev1Flag']      = 'Mildly Reduced';
        $input_data['fev1FlagColor'] = 'primary';
        $input_data['fev1RangeCode']    = '#fff707';
        }
        else if ($input_data['fev1'] >= 1.0 && $input_data['fev1'] <= 1.49) {
        $input_data['fev1Flag']      = 'Moderately Reduced';
        $input_data['fev1FlagColor'] = 'primary';
        $input_data['fev1RangeCode']    = '#FFC107';
         } 
         else if ($input_data['fev1'] < 1.0) {
        $input_data['fev1Flag']      = 'Severely Reduced';
        $input_data['fev1FlagColor'] = 'danger';
        $input_data['fev1RangeCode']    = '#FF0000';
        }
        }
        if($years >= 20 && $years <= 39){
        if($gender == 'Male' ){ 

        if ($input_data['fev1'] >= 3.0) {
    $input_data['fev1Flag']      = 'Normal';
    $input_data['fev1FlagColor'] = 'success';
    $input_data['fev1RangeCode']    = '#008000';
} else if ($input_data['fev1'] >= 2.5 && $input_data['fev1'] <= 2.99) {
    $input_data['fev1Flag']      = 'Mildly Reduced';
    $input_data['fev1FlagColor'] = 'primary';
    $input_data['fev1RangeCode']    = '#fff707';
}
else if ($input_data['fev1'] >= 2.0 && $input_data['fev1'] <= 2.49) {
    $input_data['fev1Flag']      = 'Moderately Reduced';
    $input_data['fev1FlagColor'] = 'primary';
    $input_data['fev1RangeCode']    = '#FFC107';
} 
else if ($input_data['fev1'] < 2.0) {
    $input_data['fev1Flag']      = 'Severely Reduced';
    $input_data['fev1FlagColor'] = 'danger';
    $input_data['fev1RangeCode']    = '#FF0000';
}
}
if($gender == 'Female' ){ 

if ($input_data['fev1'] >= 2.5) {
$input_data['fev1Flag']      = 'Normal';
$input_data['fev1FlagColor'] = 'success';
$input_data['fev1RangeCode']    = '#008000';
} else if ($input_data['fev1'] >= 2.0 && $input_data['fev1'] <= 2.49) {
$input_data['fev1Flag']      = 'Mildly Reduced';
$input_data['fev1FlagColor'] = 'primary';
$input_data['fev1RangeCode']    = '#fff707';
}
else if ($input_data['fev1'] >= 1.5 && $input_data['fev1'] <= 1.99) {
$input_data['fev1Flag']      = 'Moderately Reduced';
$input_data['fev1FlagColor'] = 'primary';
$input_data['fev1RangeCode']    = '#FFC107';
} 
else if ($input_data['fev1'] < 1.5) {
$input_data['fev1Flag']      = 'Severely Reduced';
$input_data['fev1FlagColor'] = 'danger';
$input_data['fev1RangeCode']    = '#FF0000';
}
}

        }
        if($years >= 40 && $years <= 59){
            if($gender == 'Male' ){ 
         
    
    if ($input_data['fev1'] >= 2.5) {
        $input_data['fev1Flag']      = 'Normal';
        $input_data['fev1FlagColor'] = 'success';
        $input_data['fev1RangeCode']    = '#008000';
    } else if ($input_data['fev1'] >= 2.0 && $input_data['fev1'] <= 2.49) {
        $input_data['fev1Flag']      = 'Mildly Reduced';
        $input_data['fev1FlagColor'] = 'primary';
        $input_data['fev1RangeCode']    = '#fff707';
    }
    else if ($input_data['fev1'] >= 1.5 && $input_data['fev1'] <= 1.99) {
        $input_data['fev1Flag']      = 'Moderately Reduced';
        $input_data['fev1FlagColor'] = 'primary';
        $input_data['fev1RangeCode']    = '#FFC107';
    } 
    else if ($input_data['fev1'] < 1.5) {
        $input_data['fev1Flag']      = 'Severely Reduced';
        $input_data['fev1FlagColor'] = 'danger';
        $input_data['fev1RangeCode']    = '#FF0000';
    }
 
    }
    if($gender == 'Female' ){ 

    
    if ($input_data['fev1'] >= 2.0) {
    $input_data['fev1Flag']      = 'Normal';
    $input_data['fev1FlagColor'] = 'success';
    $input_data['fev1RangeCode']    = '#008000';
    } else if ($input_data['fev1'] >= 1.5 && $input_data['fev1'] <= 1.99) {
    $input_data['fev1Flag']      = 'Mildly Reduced';
    $input_data['fev1FlagColor'] = 'primary';
    $input_data['fev1RangeCode']    = '#fff707';
    }
    else if ($input_data['fev1'] >= 1.0 && $input_data['fev1'] <= 1.49) {
    $input_data['fev1Flag']      = 'Moderately Reduced';
    $input_data['fev1FlagColor'] = 'primary';
    $input_data['fev1RangeCode']    = '#FFC107';
    } 
    else if ($input_data['fev1'] < 1.0) {
    $input_data['fev1Flag']      = 'Severely Reduced';
    $input_data['fev1FlagColor'] = 'danger';
    $input_data['fev1RangeCode']    = '#FF0000';
    }
 
    }
    
            }
            if($years >= 60){
          

        if ($input_data['fev1'] >= 2.0) {
            $input_data['fev1Flag']      = 'Normal';
            $input_data['fev1FlagColor'] = 'success';
            $input_data['fev1RangeCode']    = '#008000';
        } else if ($input_data['fev1'] >= 1.5 && $input_data['fev1'] <= 1.99) {
            $input_data['fev1Flag']      = 'Mildly Reduced';
            $input_data['fev1FlagColor'] = 'primary';
            $input_data['fev1RangeCode']    = '#fff707';
        }
        else if ($input_data['fev1'] >= 1.0 && $input_data['fev1'] <= 1.49) {
            $input_data['fev1Flag']      = 'Moderately Reduced';
            $input_data['fev1FlagColor'] = 'primary';
            $input_data['fev1RangeCode']    = '#FFC107';
        } 
        else if ($input_data['fev1'] < 1.0) {
            $input_data['fev1Flag']      = 'Severely Reduced';
            $input_data['fev1FlagColor'] = 'danger';
            $input_data['fev1RangeCode']    = '#FF0000';
        }
    
        }
        }

               // FEV1/FVC (%)
               if(!empty($input_data['fev1_fvc'])){
       
                if($years >=0 && $years <= 4 ){
                    if ($input_data['fev1_fvc'] >= 85) {
                                $input_data['fev1FvcFlag']      = 'Normal';
                                $input_data['fev1FvcFlagColor'] = 'success';
                                $input_data['fev1FvcRangeCode']    = '#008000';
                            } else if ($input_data['fev1_fvc'] >= 75 && $input_data['fev1_fvc'] <= 84) {
                                $input_data['fev1FvcFlag']      = 'Mildly Reduced';
                                $input_data['fev1FvcFlagColor'] = 'primary';
                                $input_data['fev1FvcRangeCode']    = '#fff707';
                            } else if ($input_data['fev1_fvc'] >= 50 && $input_data['fev1_fvc'] <= 74) {
                                $input_data['fev1FvcFlag']      = 'Moderately Reduced ';
                                $input_data['fev1FvcFlagColor'] = 'primary';
                                $input_data['fev1FvcRangeCode']    = '#FFC107';
                            }
                            else if ($input_data['fev1_fvc'] < 50) {
                                $input_data['fev1FvcFlag']      = 'Severely Reduced';
                                $input_data['fev1FvcFlagColor'] = 'danger';
                                $input_data['fev1FvcRangeCode']    = '#FF0000';
                            }
                       
                }
            }
            if(!empty($input_data['fev1_fvc'])){
             
                if($years >=5 && $years <= 12 ){
                    if ($input_data['fev1_fvc'] >= 85) {
                                $input_data['fev1FvcFlag']      = 'Normal';
                                $input_data['fev1FvcFlagColor'] = 'success';
                                $input_data['fev1FvcRangeCode']    = '#008000';
                            } else if ($input_data['fev1_fvc'] >= 75 && $input_data['fev1_fvc'] <= 84) {
                                $input_data['fev1FvcFlag']      = 'Mildly Reduced';
                                $input_data['fev1FvcFlagColor'] = 'primary';
                                $input_data['fev1FvcRangeCode']    = '#fff707';
                            } else if ($input_data['fev1_fvc'] >= 50 && $input_data['fev1_fvc'] <= 74) {
                                $input_data['fev1FvcFlag']      = 'Moderately Reduced ';
                                $input_data['fev1FvcFlagColor'] = 'primary';
                                $input_data['fev1FvcRangeCode']    = '#FFC107';
                            }
                            else if ($input_data['fev1_fvc'] < 50) {
                                $input_data['fev1FvcFlag']      = 'Severely Reduced';
                                $input_data['fev1FvcFlagColor'] = 'danger';
                                $input_data['fev1FvcRangeCode']    = '#FF0000';
                            }
                         
                }
            }
            if(!empty($input_data['fev1_fvc'])){
               
                if($years >=13 && $years <= 19 ){
                    if ($input_data['fev1_fvc'] >= 85) {
                                $input_data['fev1FvcFlag']      = 'Normal';
                                $input_data['fev1FvcFlagColor'] = 'success';
                                $input_data['fev1FvcRangeCode']    = '#008000';
                            } else if ($input_data['fev1_fvc'] >= 75 && $input_data['fev1_fvc'] <= 84) {
                                $input_data['fev1FvcFlag']      = 'Mildly Reduced';
                                $input_data['fev1FvcFlagColor'] = 'primary';
                                $input_data['fev1FvcRangeCode']    = '#fff707';
                            } else if ($input_data['fev1_fvc'] >= 50 && $input_data['fev1_fvc'] <= 74) {
                                $input_data['fev1FvcFlag']      = 'Moderately Reduced ';
                                $input_data['fev1FvcFlagColor'] = 'primary';
                                $input_data['fev1FvcRangeCode']    = '#FFC107';
                            }
                            else if ($input_data['fev1_fvc'] < 50) {
                                $input_data['fev1FvcFlag']      = 'Severely Reduced';
                                $input_data['fev1FvcFlagColor'] = 'danger';
                                $input_data['fev1FvcRangeCode']    = '#FF0000';
                            }
                          
                }
            }
            if(!empty($input_data['fev1_fvc'])){
                if($years >=20 && $years <= 39 ){
                    if ($input_data['fev1_fvc'] >= 75) {
                                $input_data['fev1FvcFlag']      = 'Normal';
                                $input_data['fev1FvcFlagColor'] = 'success';
                                $input_data['fev1FvcRangeCode']    = '#008000';
                            } else if ($input_data['fev1_fvc'] >= 60 && $input_data['fev1_fvc'] <= 74) {
                                $input_data['fev1FvcFlag']      = 'Mildly Reduced';
                                $input_data['fev1FvcFlagColor'] = 'primary';
                                $input_data['fev1FvcRangeCode']    = '#fff707';
                            } else if ($input_data['fev1_fvc'] >= 50 && $input_data['fev1_fvc'] <= 59) {
                                $input_data['fev1FvcFlag']      = 'Moderately Reduced ';
                                $input_data['fev1FvcFlagColor'] = 'primary';
                                $input_data['fev1FvcRangeCode']    = '#FFC107';
                            }
                            else if ($input_data['fev1_fvc'] < 50) {
                                $input_data['fev1FvcFlag']      = 'Severely Reduced';
                                $input_data['fev1FvcFlagColor'] = 'danger';
                                $input_data['fev1FvcRangeCode']    = '#FF0000';
                            }
                }
            }
            if(!empty($input_data['fev1_fvc'])){
                if($years >=40 && $years <= 59 ){
                    if ($input_data['fev1_fvc'] >= 75) {
                                $input_data['fev1FvcFlag']      = 'Normal';
                                $input_data['fev1FvcFlagColor'] = 'success';
                                $input_data['fev1FvcRangeCode']    = '#008000';
                            } else if ($input_data['fev1_fvc'] >= 60 && $input_data['fev1_fvc'] <= 74) {
                                $input_data['fev1FvcFlag']      = 'Mildly Reduced';
                                $input_data['fev1FvcFlagColor'] = 'primary';
                                $input_data['fev1FvcRangeCode']    = '#fff707';
                            } else if ($input_data['fev1_fvc'] >= 50 && $input_data['fev1_fvc'] <= 59) {
                                $input_data['fev1FvcFlag']      = 'Moderately Reduced ';
                                $input_data['fev1FvcFlagColor'] = 'primary';
                                $input_data['fev1FvcRangeCode']    = '#FFC107';
                            }
                            else if ($input_data['fev1_fvc'] < 50) {
                                $input_data['fev1FvcFlag']      = 'Severely Reduced';
                                $input_data['fev1FvcFlagColor'] = 'danger';
                                $input_data['fev1FvcRangeCode']    = '#FF0000';
                            }
                }
            }
            if(!empty($input_data['fev1_fvc'])){
                if($years >=60  ){
                    if ($input_data['fev1_fvc'] >= 65) {
                                $input_data['fev1FvcFlag']      = 'Normal';
                                $input_data['fev1FvcFlagColor'] = 'success';
                                $input_data['fev1FvcRangeCode']    = '#008000';
                            } else if ($input_data['fev1_fvc'] >= 55 && $input_data['fev1_fvc'] <= 64) {
                                $input_data['fev1FvcFlag']      = 'Mildly Reduced';
                                $input_data['fev1FvcFlagColor'] = 'primary';
                                $input_data['fev1FvcRangeCode']    = '#fff707';
                            } else if ($input_data['fev1_fvc'] >= 40 && $input_data['fev1_fvc'] <= 54) {
                                $input_data['fev1FvcFlag']      = 'Moderately Reduced ';
                                $input_data['fev1FvcFlagColor'] = 'primary';
                                $input_data['fev1FvcRangeCode']    = '#FFC107';
                            }
                            else if ($input_data['fev1_fvc'] < 40) {
                                $input_data['fev1FvcFlag']      = 'Severely Reduced';
                                $input_data['fev1FvcFlagColor'] = 'danger';
                                $input_data['fev1FvcRangeCode']    = '#FF0000';
                            }
                }
            }

                   // PEF
        if(!empty($input_data['pef'])) {
            if($years >= 0 && $years <= 4){

            if ($input_data['pef'] >= 2.0) {
                $input_data['pefFlag']      = 'Normal';
                $input_data['pefFlagColor'] = 'success';
                $input_data['pefRangeCode']    = '#008000';
            } else if ($input_data['pef'] >= 1.5 && $input_data['pef'] <= 1.99) {
                $input_data['pefFlag']      = 'Mildly Reduced';
                $input_data['pefFlagColor'] = 'primary';
                $input_data['pefRangeCode']    = '#fff707';
            }
            else if ($input_data['pef'] >= 1.0 && $input_data['pef'] <= 1.49) {
                $input_data['pefFlag']      = 'Moderately Reduced';
                $input_data['pefFlagColor'] = 'primary';
                $input_data['pefRangeCode']    = '#FFC107';
            } 
            else if ($input_data['pef'] < 1.0) {
                $input_data['pefFlag']      = 'Severely Reduced';
                $input_data['pefFlagColor'] = 'danger';
                $input_data['pefRangeCode']    = '#FF0000';
            }
 
            }
            if($years >= 5 && $years <= 12){
                $input_data['pef'] = $input_data['pef'];
        if ($input_data['pef'] >= 4.0) {
            $input_data['pefFlag']      = 'Normal';
            $input_data['pefFlagColor'] = 'success';
            $input_data['pefRangeCode']    = '#008000';
        } else if ($input_data['pef'] >= 3.0 && $input_data['pef'] <= 3.99) {
            $input_data['pefFlag']      = 'Mildly Reduced';
            $input_data['pefFlagColor'] = 'primary';
            $input_data['pefRangeCode']    = '#fff707';
        }
        else if ($input_data['pef'] >= 2.0 && $input_data['pef'] <= 2.99) {
            $input_data['pefFlag']      = 'Moderately Reduced';
            $input_data['pefFlagColor'] = 'primary';
            $input_data['pefRangeCode']    = '#FFC107';
        } 
        else if ($input_data['pef'] < 2.0) {
            $input_data['pefFlag']      = 'Severely Reduced';
            $input_data['pefFlagColor'] = 'danger';
            $input_data['pefRangeCode']    = '#FF0000';
        }

        }
        if($years >= 13 && $years <= 19){

    if ($input_data['pef'] >= 6.0) {
        $input_data['pefFlag']      = 'Normal';
        $input_data['pefFlagColor'] = 'success';
        $input_data['pefRangeCode']    = '#008000';
    } else if ($input_data['pef'] >= 5.0 && $input_data['pef'] <= 5.99) {
        $input_data['pefFlag']      = 'Mildly Reduced';
        $input_data['pefFlagColor'] = 'primary';
        $input_data['pefRangeCode']    = '#fff707';
    }
    else if ($input_data['pef'] >= 3.0 && $input_data['pef'] <= 4.99) {
        $input_data['pefFlag']      = 'Moderately Reduced';
        $input_data['pefFlagColor'] = 'primary';
        $input_data['pefRangeCode']    = '#FFC107';
    } 
    else if ($input_data['pef'] < 3.0) {
        $input_data['pefFlag']      = 'Severely Reduced';
        $input_data['pefFlagColor'] = 'danger';
        $input_data['pefRangeCode']    = '#FF0000';
    }
 
    }
    if($years >= 20 && $years <= 39){


if ($input_data['pef'] >= 9.0) {
    $input_data['pefFlag']      = 'Normal';
    $input_data['pefFlagColor'] = 'success';
    $input_data['pefRangeCode']    = '#008000';
} else if ($input_data['pef'] >= 7.5 && $input_data['pef'] <= 8.99) {
    $input_data['pefFlag']      = 'Mildly Reduced';
    $input_data['pefFlagColor'] = 'primary';
    $input_data['pefRangeCode']    = '#fff707';
}
else if ($input_data['pef'] >= 6.0 && $input_data['pef'] <= 7.49) {
    $input_data['pefFlag']      = 'Moderately Reduced';
    $input_data['pefFlagColor'] = 'primary';
    $input_data['pefRangeCode']    = '#FFC107';
} 
else if ($input_data['pef'] < 6.0) {
    $input_data['pefFlag']      = 'Severely Reduced';
    $input_data['pefFlagColor'] = 'danger';
    $input_data['pefRangeCode']    = '#FF0000';
}
}
if($years >= 40 && $years <= 59){


if ($input_data['pef'] >= 7.0) {
$input_data['pefFlag']      = 'Normal';
$input_data['pefFlagColor'] = 'success';
$input_data['pefRangeCode']    = '#008000';
} else if ($input_data['pef'] >= 5.5 && $input_data['pef'] <= 6.99) {
$input_data['pefFlag']      = 'Mildly Reduced';
$input_data['pefFlagColor'] = 'primary';
$input_data['pefRangeCode']    = '#fff707';
}
else if ($input_data['pef'] >= 4.0 && $input_data['pef'] <= 5.49) {
$input_data['pefFlag']      = 'Moderately Reduced';
$input_data['pefFlagColor'] = 'primary';
$input_data['pefRangeCode']    = '#FFC107';
} 
else if ($input_data['pef'] < 4.0) {
$input_data['pefFlag']      = 'Severely Reduced';
$input_data['pefFlagColor'] = 'danger';
$input_data['pefRangeCode']    = '#FF0000';
}

}
if($years >= 60 ){

if ($input_data['pef'] >= 5.0) {
$input_data['pefFlag']      = 'Normal';
$input_data['pefFlagColor'] = 'success';
$input_data['pefRangeCode']    = '#008000';
} else if ($input_data['pef'] >= 4.0 && $input_data['pef'] <= 4.99) {
$input_data['pefFlag']      = 'Mildly Reduced';
$input_data['pefFlagColor'] = 'primary';
$input_data['pefRangeCode']    = '#fff707';
}
else if ($input_data['pef'] >= 3.0 && $input_data['pef'] <= 3.99) {
$input_data['pefFlag']      = 'Moderately Reduced';
$input_data['pefFlagColor'] = 'primary';
$input_data['pefRangeCode']    = '#FFC107';
} 
else if ($input_data['pef'] < 3.0) {
$input_data['pefFlag']      = 'Severely Reduced';
$input_data['pefFlagColor'] = 'danger';
$input_data['pefRangeCode']    = '#FF0000';
}
}

// FEF25(l/s)
if(!empty($input_data['fef25'])) {
    if($years >= 0 && $years <= 4){

    if ($input_data['fef25'] >= 1.2) {
        $input_data['fef25Flag']      = 'Normal';
        $input_data['fef25FlagColor'] = 'success';
        $input_data['fef25RangeCode']    = '#008000';
    } else if ($input_data['fef25'] >= 0.9 && $input_data['fef25'] <= 1.19) {
        $input_data['fef25Flag']      = 'Mildly Reduced';
        $input_data['fef25FlagColor'] = 'primary';
        $input_data['fef25RangeCode']    = '#fff707';
    }
    else if ($input_data['fef25'] >= 0.6 && $input_data['fef25'] <= 0.89) {
        $input_data['fef25Flag']      = 'Moderately Reduced';
        $input_data['fef25FlagColor'] = 'primary';
        $input_data['fef25RangeCode']    = '#FFC107';
    } 
    else if ($input_data['fef25'] < 0.6) {
        $input_data['fef25Flag']      = 'Severely Reduced';
        $input_data['fef25FlagColor'] = 'danger';
        $input_data['fef25RangeCode']    = '#FF0000';
    }

    }
    if($years >= 5 && $years <= 12){

if ($input_data['fef25'] >= 2.5) {
    $input_data['fef25Flag']      = 'Normal';
    $input_data['fef25FlagColor'] = 'success';
    $input_data['fef25RangeCode']    = '#008000';
} else if ($input_data['fef25'] >= 2.0 && $input_data['fef25'] <= 2.49) {
    $input_data['fef25Flag']      = 'Mildly Reduced';
    $input_data['fef25FlagColor'] = 'primary';
    $input_data['fef25RangeCode']    = '#fff707';
}
else if ($input_data['fef25'] >= 1.5 && $input_data['fef25'] <= 1.99) {
    $input_data['fef25Flag']      = 'Moderately Reduced';
    $input_data['fef25FlagColor'] = 'primary';
    $input_data['fef25RangeCode']    = '#FFC107';
} 
else if ($input_data['fef25'] < 1.5) {
    $input_data['fef25Flag']      = 'Severely Reduced';
    $input_data['fef25FlagColor'] = 'danger';
    $input_data['fef25RangeCode']    = '#FF0000';
}

}
if($years >= 13 && $years <= 19){


if ($input_data['fef25'] >= 3.0) {
$input_data['fef25Flag']      = 'Normal';
$input_data['fef25FlagColor'] = 'success';
$input_data['fef25RangeCode']    = '#008000';
} else if ($input_data['fef25'] >= 2.4 && $input_data['fef25'] <= 2.99) {
$input_data['fef25Flag']      = 'Mildly Reduced';
$input_data['fef25FlagColor'] = 'primary';
$input_data['fef25RangeCode']    = '#fff707';
}
else if ($input_data['fef25'] >= 1.8 && $input_data['fef25'] <= 2.39) {
$input_data['fef25Flag']      = 'Moderately Reduced';
$input_data['fef25FlagColor'] = 'primary';
$input_data['fef25RangeCode']    = '#FFC107';
} 
else if ($input_data['fef25'] < 1.8) {
$input_data['fef25Flag']      = 'Severely Reduced';
$input_data['fef25FlagColor'] = 'danger';
$input_data['fef25RangeCode']    = '#FF0000';
}

}
if($years >= 20 && $years <= 39){


if ($input_data['fef25'] >= 4.0) {
$input_data['fef25Flag']      = 'Normal';
$input_data['fef25FlagColor'] = 'success';
$input_data['fef25RangeCode']    = '#008000';
} else if ($input_data['fef25'] >= 3.5 && $input_data['fef25'] <= 3.99) {
$input_data['fef25Flag']      = 'Mildly Reduced';
$input_data['fef25FlagColor'] = 'primary';
$input_data['fef25RangeCode']    = '#fff707';
}
else if ($input_data['fef25'] >= 2.5 && $input_data['fef25'] <= 3.49) {
$input_data['fef25Flag']      = 'Moderately Reduced';
$input_data['fef25FlagColor'] = 'primary';
$input_data['fef25RangeCode']    = '#FFC107';
} 
else if ($input_data['fef25'] < 2.5) {
$input_data['fef25Flag']      = 'Severely Reduced';
$input_data['fef25FlagColor'] = 'danger';
$input_data['fef25RangeCode']    = '#FF0000';
}

}
if($years >= 40 && $years <= 59){


if ($input_data['fef25'] >= 3.5) {
$input_data['fef25Flag']      = 'Normal';
$input_data['fef25FlagColor'] = 'success';
$input_data['fef25RangeCode']    = '#008000';
} else if ($input_data['fef25'] >= 3.0 && $input_data['fef25'] <= 3.49) {
$input_data['fef25Flag']      = 'Mildly Reduced';
$input_data['fef25FlagColor'] = 'primary';
$input_data['fef25RangeCode']    = '#fff707';
}
else if ($input_data['fef25'] >= 2.0 && $input_data['fef25'] <= 2.99) {
$input_data['fef25Flag']      = 'Moderately Reduced';
$input_data['fef25FlagColor'] = 'primary';
$input_data['fef25RangeCode']    = '#FFC107';
} 
else if ($input_data['fef25'] < 2.0) {
$input_data['fef25Flag']      = 'Severely Reduced';
$input_data['fef25FlagColor'] = 'danger';
$input_data['fef25RangeCode']    = '#FF0000';
}

}
if($years >= 60){


if ($input_data['fef25'] >= 3.0) {
$input_data['fef25Flag']      = 'Normal';
$input_data['fef25FlagColor'] = 'success';
$input_data['fef25RangeCode']    = '#008000';
} else if ($input_data['fef25'] >= 2.5 && $input_data['fef25'] <= 2.99) {
$input_data['fef25Flag']      = 'Mildly Reduced';
$input_data['fef25FlagColor'] = 'primary';
$input_data['fef25RangeCode']    = '#fff707';
}
else if ($input_data['fef25'] >= 2.0 && $input_data['fef25'] <= 2.49) {
$input_data['fef25Flag']      = 'Moderately Reduced';
$input_data['fef25FlagColor'] = 'primary';
$input_data['fef25RangeCode']    = '#FFC107';
} 
else if ($input_data['fef25'] < 2.0) {
$input_data['fef25Flag']      = 'Severely Reduced';
$input_data['fef25FlagColor'] = 'danger';
$input_data['fef25RangeCode']    = '#FF0000';
}

}
}

// FEF50 (l/s)
if(!empty($input_data['fef50'])) {
    if($years >= 0 && $years <= 4){


    if ($input_data['fef50'] >= 0.8) {
        $input_data['fef50Flag']      = 'Normal';
        $input_data['fef50FlagColor'] = 'success';
        $input_data['fef50 RangeCode']    = '#008000';
    } else if ($input_data['fef50'] >= 0.6 && $input_data['fef50'] <= 0.79) {
        $input_data['fef50Flag']      = 'Mildly Reduced';
        $input_data['fef50FlagColor'] = 'primary';
        $input_data['fef50RangeCode']    = '#fff707';
    }
    else if ($input_data['fef50'] >= 0.4 && $input_data['fef50'] <= 0.59) {
        $input_data['fef50Flag']      = 'Moderately Reduced';
        $input_data['fef50FlagColor'] = 'primary';
        $input_data['fef50RangeCode']    = '#FFC107';
    } 
    else if ($input_data['fef50'] < 0.4) {
        $input_data['fef50Flag']      = 'Severely Reduced';
        $input_data['fef50FlagColor'] = 'danger';
        $input_data['fef50RangeCode']    = '#FF0000';
    }

    }

if($years >= 5 && $years <= 12){


if ($input_data['fef50'] >= 1.8) {
$input_data['fef50Flag']      = 'Normal';
$input_data['fef50FlagColor'] = 'success';
$input_data['fef50RangeCode']    = '#008000';
} else if ($input_data['fef50'] >= 1.4 && $input_data['fef50'] <= 1.79) {
$input_data['fef50Flag']      = 'Mildly Reduced';
$input_data['fef50FlagColor'] = 'primary';
$input_data['fef50RangeCode']    = '#fff707';
}
else if ($input_data['fef50'] >= 1.0 && $input_data['fef50'] <= 1.39) {
$input_data['fef50Flag']      = 'Moderately Reduced';
$input_data['fef50FlagColor'] = 'primary';
$input_data['fef50RangeCode']    = '#FFC107';
} 
else if ($input_data['fef50'] < 1.0) {
$input_data['fef50Flag']      = 'Severely Reduced';
$input_data['fef50FlagColor'] = 'danger';
$input_data['fef50RangeCode']    = '#FF0000';
}

}
if($years >= 13 && $years <= 19){


if ($input_data['fef50'] >= 2.0) {
$input_data['fef50Flag']      = 'Normal';
$input_data['fef50FlagColor'] = 'success';
$input_data['fef50RangeCode']    = '#008000';
} else if ($input_data['fef50'] >= 1.6 && $input_data['fef50'] <= 1.99) {
$input_data['fef50Flag']      = 'Mildly Reduced';
$input_data['fef50FlagColor'] = 'primary';
$input_data['fef50RangeCode']    = '#fff707';
}
else if ($input_data['fef50'] >= 1.2 && $input_data['fef50'] <= 1.59) {
$input_data['fef50Flag']      = 'Moderately Reduced';
$input_data['fef50FlagColor'] = 'primary';
$input_data['fef50RangeCode']    = '#FFC107';
} 
else if ($input_data['fef50'] < 1.2) {
$input_data['fef50Flag']      = 'Severely Reduced';
$input_data['fef50FlagColor'] = 'danger';
$input_data['fef50RangeCode']    = '#FF0000';
}

}
if($years >= 20 && $years <= 59){


if ($input_data['fef50'] >= 3.0) {
$input_data['fef50Flag']      = 'Normal';
$input_data['fef50FlagColor'] = 'success';
$input_data['fef50RangeCode']    = '#008000';
} else if ($input_data['fef50'] >= 2.5 && $input_data['fef50'] <= 2.99) {
$input_data['fef50Flag']      = 'Mildly Reduced';
$input_data['fef50FlagColor'] = 'primary';
$input_data['fef50RangeCode']    = '#fff707';
}
else if ($input_data['fef50'] >= 2.0 && $input_data['fef50'] <= 2.49) {
$input_data['fef50Flag']      = 'Moderately Reduced';
$input_data['fef50FlagColor'] = 'primary';
$input_data['fef50RangeCode']    = '#FFC107';
} 
else if ($input_data['fef50'] < 2.0) {
$input_data['fef50Flag']      = 'Severely Reduced';
$input_data['fef50FlagColor'] = 'danger';
$input_data['fef50RangeCode']    = '#FF0000';
}

}
if($years >= 60){

if ($input_data['fef50'] >= 2.5) {
$input_data['fef50Flag']      = 'Normal';
$input_data['fef50FlagColor'] = 'success';
$input_data['fef50RangeCode']    = '#008000';
} else if ($input_data['fef50'] >= 2.0 && $input_data['fef50'] <= 2.49) {
$input_data['fef50Flag']      = 'Mildly Reduced';
$input_data['fef50FlagColor'] = 'primary';
$input_data['fef50RangeCode']    = '#fff707';
}
else if ($input_data['fef50'] >= 1.5 && $input_data['fef50'] <= 1.99) {
$input_data['fef50Flag']      = 'Moderately Reduced';
$input_data['fef50FlagColor'] = 'primary';
$input_data['fef50RangeCode']    = '#FFC107';
} 
else if ($input_data['fef50'] < 1.5) {
$input_data['fef50Flag']      = 'Severely Reduced';
$input_data['fef50FlagColor'] = 'danger';
$input_data['fef50RangeCode']    = '#FF0000';
}
}
}
// FEF75 (l/s)
if(!empty($input_data['fef75'])) {
    if($years >= 0 && $years <= 4){


    if ($input_data['fef75'] >= 0.4) {
        $input_data['fef75Flag']      = 'Normal';
        $input_data['fef75FlagColor'] = 'success';
        $input_data['fef75 RangeCode']    = '#008000';
    } else if ($input_data['fef75'] >= 0.3 && $input_data['fef75'] <= 0.39) {
        $input_data['fef75Flag']      = 'Mildly Reduced';
        $input_data['fef75FlagColor'] = 'primary';
        $input_data['fef75RangeCode']    = '#fff707';
    }
    else if ($input_data['fef75'] >= 0.2 && $input_data['fef75'] <= 0.29) {
        $input_data['fef75Flag']      = 'Moderately Reduced';
        $input_data['fef75FlagColor'] = 'primary';
        $input_data['fef75RangeCode']    = '#FFC107';
    } 
    else if ($input_data['fef75'] < 0.2) {
        $input_data['fef75 Flag']      = 'Severely Reduced';
        $input_data['fef75 FlagColor'] = 'danger';
        $input_data['fef75 RangeCode']    = '#FF0000';
    }

    }

if($years >= 5 && $years <= 12){


if ($input_data['fef75'] >= 0.9) {
$input_data['fef75Flag']      = 'Normal';
$input_data['fef75FlagColor'] = 'success';
$input_data['fef75RangeCode']    = '#008000';
} else if ($input_data['fef75'] >= 0.7 && $input_data['fef75'] <= 0.89) {
$input_data['fef75Flag']      = 'Mildly Reduced';
$input_data['fef75FlagColor'] = 'primary';
$input_data['fef75RangeCode']    = '#fff707';
}
else if ($input_data['fef75'] >= 0.5 && $input_data['fef75'] <= 0.69) {
$input_data['fef75Flag']      = 'Moderately Reduced';
$input_data['fef75FlagColor'] = 'primary';
$input_data['fef75RangeCode']    = '#FFC107';
} 
else if ($input_data['fef75'] < 0.5) {
$input_data['fef75Flag']      = 'Severely Reduced';
$input_data['fef75FlagColor'] = 'danger';
$input_data['fef75RangeCode']    = '#FF0000';
}

}
if($years >= 13 && $years <= 19){


if ($input_data['fef75'] >= 0.1) {
$input_data['fef75Flag']      = 'Normal';
$input_data['fef75FlagColor'] = 'success';
$input_data['fef75RangeCode']    = '#008000';
} else if ($input_data['fef75'] >= 0.8 && $input_data['fef75'] <= 0.99) {
$input_data['fef75Flag']      = 'Mildly Reduced';
$input_data['fef75FlagColor'] = 'primary';
$input_data['fef75RangeCode']    = '#fff707';
}
else if ($input_data['fef75'] >= 0.6 && $input_data['fef75'] <= 0.79) {
$input_data['fef75Flag']      = 'Moderately Reduced';
$input_data['fef75FlagColor'] = 'primary';
$input_data['fef75RangeCode']    = '#FFC107';
} 
else if ($input_data['fef75'] < 0.6) {
$input_data['fef75Flag']      = 'Severely Reduced';
$input_data['fef75FlagColor'] = 'danger';
$input_data['fef75RangeCode']    = '#FF0000';
}

}
if($years >= 20 && $years <= 59){


if ($input_data['fef75'] >= 2.5) {
$input_data['fef75Flag']      = 'Normal';
$input_data['fef75FlagColor'] = 'success';
$input_data['fef75RangeCode']    = '#008000';
} else if ($input_data['fef75'] >= 2.0 && $input_data['fef75'] <= 2.49) {
$input_data['fef75Flag']      = 'Mildly Reduced';
$input_data['fef75FlagColor'] = 'primary';
$input_data['fef75RangeCode']    = '#fff707';
}
else if ($input_data['fef75'] >= 1.5 && $input_data['fef75'] <= 1.99) {
$input_data['fef75Flag']      = 'Moderately Reduced';
$input_data['fef75FlagColor'] = 'primary';
$input_data['fef75RangeCode']    = '#FFC107';
} 
else if ($input_data['fef75'] < 1.5) {
$input_data['fef75Flag']      = 'Severely Reduced';
$input_data['fef75FlagColor'] = 'danger';
$input_data['fef75RangeCode']    = '#FF0000';
}

}
if($years >= 60){

if ($input_data['fef75'] >= 2.0) {
$input_data['fef75Flag']      = 'Normal';
$input_data['fef75FlagColor'] = 'success';
$input_data['fef75RangeCode']    = '#008000';
} else if ($input_data['fef75'] >= 1.5 && $input_data['fef75'] <= 1.99) {
$input_data['fef75Flag']      = 'Mildly Reduced';
$input_data['fef75FlagColor'] = 'primary';
$input_data['fef75RangeCode']    = '#fff707';
}
else if ($input_data['fef75'] >= 1.0 && $input_data['fef75'] <= 1.49) {
$input_data['fef75Flag']      = 'Moderately Reduced';
$input_data['fef75FlagColor'] = 'primary';
$input_data['fef75RangeCode']    = '#FFC107';
} 
else if ($input_data['fef75'] < 1.0) {
$input_data['fef75Flag']      = 'Severely Reduced';
$input_data['fef75FlagColor'] = 'danger';
$input_data['fef75RangeCode']    = '#FF0000';
}
}
}

// FEF2575 (l/s)
if(!empty($input_data['fef2575'])) {
    if($years >= 0 && $years <= 4){

    if ($input_data['fef2575'] >= 0.7) {
        $input_data['fef2575Flag']      = 'Normal';
        $input_data['fef2575FlagColor'] = 'success';
        $input_data['fef2575 RangeCode']    = '#008000';
    } else if ($input_data['fef2575'] >= 0.5 && $input_data['fef2575'] <= 0.69) {
        $input_data['fef2575Flag']      = 'Mildly Reduced';
        $input_data['fef2575FlagColor'] = 'primary';
        $input_data['fef2575RangeCode']    = '#fff707';
    }
    else if ($input_data['fef2575'] >= 0.3 && $input_data['fef2575'] <= 0.49) {
        $input_data['fef2575Flag']      = 'Moderately Reduced';
        $input_data['fef2575FlagColor'] = 'primary';
        $input_data['fef2575RangeCode']    = '#FFC107';
    } 
    else if ($input_data['fef2575'] < 0.3) {
        $input_data['fef2575Flag']      = 'Severely Reduced';
        $input_data['fef2575FlagColor'] = 'danger';
        $input_data['fef2575RangeCode']    = '#FF0000';
    }

    }

if($years >= 5 && $years <= 12){


if ($input_data['fef2575'] >= 1.5) {
$input_data['fef2575Flag']      = 'Normal';
$input_data['fef2575FlagColor'] = 'success';
$input_data['fef2575RangeCode']    = '#008000';
} else if ($input_data['fef2575'] >= 1.2 && $input_data['fef2575'] <= 1.49) {
$input_data['fef2575Flag']      = 'Mildly Reduced';
$input_data['fef2575FlagColor'] = 'primary';
$input_data['fef2575RangeCode']    = '#fff707';
}
else if ($input_data['fef2575'] >= 0.8 && $input_data['fef2575'] <= 1.19) {
$input_data['fef2575Flag']      = 'Moderately Reduced';
$input_data['fef2575FlagColor'] = 'primary';
$input_data['fef2575RangeCode']    = '#FFC107';
} 
else if ($input_data['fef2575'] < 0.8) {
$input_data['fef2575Flag']      = 'Severely Reduced6';
$input_data['fef2575FlagColor'] = 'danger';
$input_data['fef2575RangeCode']    = '#FF0000';
}

}
if($years >= 13 && $years <= 19){


if ($input_data['fef2575'] >= 2.0) {
$input_data['fef2575Flag']      = 'Normal';
$input_data['fef2575FlagColor'] = 'success';
$input_data['fef2575RangeCode']    = '#008000';
} else if ($input_data['fef2575'] >= 1.5 && $input_data['fef2575'] <= 1.99) {
$input_data['fef2575Flag']      = 'Mildly Reduced';
$input_data['fef2575FlagColor'] = 'primary';
$input_data['fef2575RangeCode']    = '#fff707';
}
else if ($input_data['fef2575'] >= 1.0 && $input_data['fef2575'] <= 1.49) {
$input_data['fef2575Flag']      = 'Moderately Reduced';
$input_data['fef2575FlagColor'] = 'primary';
$input_data['fef2575RangeCode']    = '#FFC107';
} 
else if ($input_data['fef2575'] < 1.0) {
$input_data['fef2575Flag']      = 'Severely Reduced';
$input_data['fef2575FlagColor'] = 'danger';
$input_data['fef2575RangeCode']    = '#FF0000';
}

}
if($years >= 20 && $years <= 59){

if ($input_data['fef2575'] >= 2.0) {
$input_data['fef2575Flag']      = 'Normal';
$input_data['fef2575FlagColor'] = 'success';
$input_data['fef2575RangeCode']    = '#008000';
} else if ($input_data['fef2575'] >= 1.5 && $input_data['fef2575'] <= 1.99) {
$input_data['fef2575Flag']      = 'Mildly Reduced';
$input_data['fef2575FlagColor'] = 'primary';
$input_data['fef2575RangeCode']    = '#fff707';
}
else if ($input_data['fef2575'] >= 1.0 && $input_data['fef2575'] <= 1.49) {
$input_data['fef2575Flag']      = 'Moderately Reduced';
$input_data['fef2575FlagColor'] = 'primary';
$input_data['fef2575RangeCode']    = '#FFC107';
} 
else if ($input_data['fef2575'] < 1.0) {
$input_data['fef2575Flag']      = 'Severely Reduced';
$input_data['fef2575FlagColor'] = 'danger';
$input_data['fef2575RangeCode']    = '#FF0000';
}

}
if($years >= 60){

if ($input_data['fef2575'] >= 1.5) {
$input_data['fef2575Flag']      = 'Normal';
$input_data['fef2575FlagColor'] = 'success';
$input_data['fef2575RangeCode']    = '#008000';
} else if ($input_data['fef2575'] >= 1.0 && $input_data['fef2575'] <= 1.49) {
$input_data['fef2575Flag']      = 'Mildly Reduced';
$input_data['fef2575FlagColor'] = 'primary';
$input_data['fef2575RangeCode']    = '#fff707';
}
else if ($input_data['fef2575'] >= 0.5 && $input_data['fef2575'] <= 0.99) {
$input_data['fef2575Flag']      = 'Moderately Reduced';
$input_data['fef2575FlagColor'] = 'primary';
$input_data['fef2575RangeCode']    = '#FFC107';
} 
else if ($input_data['fef2575'] < 0.5) {
$input_data['fef2575Flag']      = 'Severely Reduced';
$input_data['fef2575FlagColor'] = 'danger';
$input_data['fef2575RangeCode']    = '#FF0000';
}
}
}
}
        
return $input_data;
}
        // fvc(%)
//         if(!empty($input_data['fvc(%)'])) {
//             if($years >= 0 && $years <= 4){
//                     $value = $input_data['fvc(%)'];

//             if ($value >= 80) {
//                 $flags['fvcFlag']      = 'Normal';
//                 $flags['fvcFlagColor'] = 'success';
//                 $flags['fvcRangeCode']    = '#008000';
//             } else if ($value >= 70 && $value <= 79) {
//                 $flags['fvcFlag']      = 'Mildly Reduced';
//                 $flags['fvcFlagColor'] = 'primary';
//                 $flags['fvcRangeCode']    = '#fff707';
//             }
//             else if ($value >= 50 && $value <= 69) {
//                 $flags['fvcFlag']      = 'Moderately Reduced';
//                 $flags['fvcFlagColor'] = 'primary';
//                 $flags['fvcRangeCode']    = '#FFC107';
//             } 
//             else if ($value < 50) {
//                 $flags['fvcFlag']      = 'Severely Reduced';
//                 $flags['fvcFlagColor'] = 'danger';
//                 $flags['fvcRangeCode']    = '#FF0000';
//             }
//             $fvcFlag = $flags['fvcFlag'];
//             }

//             if($years >= 5 && $years <= 12){
//                 $value = $input_data['fvc(%)'];

//         if ($value >= 80) {
//             $flags['fvcFlag']      = 'Normal';
//             $flags['fvcFlagColor'] = 'success';
//             $flags['fvcRangeCode']    = '#008000';
//         } else if ($value >= 70 && $value <= 79) {
//             $flags['fvcFlag']      = 'Mildly Reduced';
//             $flags['fvcFlagColor'] = 'primary';
//             $flags['fvcRangeCode']    = '#fff707';
//         }
//         else if ($value >= 50 && $value <= 69) {
//             $flags['fvcFlag']      = 'Moderately Reduced';
//             $flags['fvcFlagColor'] = 'primary';
//             $flags['fvcRangeCode']    = '#FFC107';
//         } 
//         else if ($value < 50) {
//             $flags['fvcFlag']      = 'Severely Reduced';
//             $flags['fvcFlagColor'] = 'danger';
//             $flags['fvcRangeCode']    = '#FF0000';
//         }
//         $pefFlag = $flags['fvcFlag'];
//         }
//         if($years >= 13 && $years <= 19){
//             $value = $input_data['fvc(%)'];

//     if ($value >= 80) {
//         $flags['fvcFlag']      = 'Normal';
//         $flags['fvcFlagColor'] = 'success';
//         $flags['fvcRangeCode']    = '#008000';
//     } else if ($value >= 70 && $value <= 79) {
//         $flags['fvcFlag']      = 'Mildly Reduced';
//         $flags['fvcFlagColor'] = 'primary';
//         $flags['fvcRangeCode']    = '#fff707';
//     }
//     else if ($value >= 50 && $value <= 69) {
//         $flags['fvcFlag']      = 'Moderately Reduced';
//         $flags['fvcFlagColor'] = 'primary';
//         $flags['fvcRangeCode']    = '#FFC107';
//     } 
//     else if ($value < 50) {
//         $flags['fvcFlag']      = 'Severely Reduced';
//         $flags['fvcFlagColor'] = 'danger';
//         $flags['fvcRangeCode']    = '#FF0000';
//     }
//     $pefFlag = $flags['fvcFlag'];
//     }
//     if($years >= 20 && $years <= 39){
  
//         $value = $input_data['fvc(%)'];

// if ($value >= 80) {
//     $flags['fvcFlag']      = 'Normal';
//     $flags['fvcFlagColor'] = 'success';
//     $flags['fvcRangeCode']    = '#008000';
// } else if ($value >= 70 && $value <= 79) {
//     $flags['fvcFlag']      = 'Mildly Reduced';
//     $flags['fvcFlagColor'] = 'primary';
//     $flags['fvcRangeCode']    = '#fff707';
// }
// else if ($value >= 50 && $value <= 69) {
//     $flags['fvcFlag']      = 'Moderately Reduced';
//     $flags['fvcFlagColor'] = 'primary';
//     $flags['fvcRangeCode']    = '#FFC107';
// } 
// else if ($value < 50) {
//     $flags['fvcFlag']      = 'Severely Reduced';
//     $flags['fvcFlagColor'] = 'danger';
//     $flags['fvcRangeCode']    = '#FF0000';
// }
// $pefFlag = $flags['fvcFlag'];


//         }
//         if($years >= 40 && $years <= 59){
       
//             $value = $input_data['fvc(%)'];
    
//     if ($value >= 80) {
//         $flags['fvcFlag']      = 'Normal';
//         $flags['fvcFlagColor'] = 'success';
//         $flags['fvcRangeCode']    = '#008000';
//     } else if ($value >= 70 && $value <= 79) {
//         $flags['fvcFlag']      = 'Mildly Reduced';
//         $flags['fvcFlagColor'] = 'primary';
//         $flags['fvcRangeCode']    = '#fff707';
//     }
//     else if ($value >= 50 && $value <= 69) {
//         $flags['fvcFlag']      = 'Moderately Reduced';
//         $flags['fvcFlagColor'] = 'primary';
//         $flags['fvcRangeCode']    = '#FFC107';
//     } 
//     else if ($value < 50) {
//         $flags['fvcFlag']      = 'Severely Reduced';
//         $flags['fvcFlagColor'] = 'danger';
//         $flags['fvcRangeCode']    = '#FF0000';
//     }
//     $pefFlag = $flags['fvcFlag'];
//             }

//             if($years >= 60){
//                 $value = $input_data['fvc(%)'];

//         if ($value >= 70) {
//             $flags['fvcFlag']      = 'Normal';
//             $flags['fvcFlagColor'] = 'success';
//             $flags['fvcRangeCode']    = '#008000';
//         } else if ($value >= 60 && $value <= 69) {
//             $flags['fvcFlag']      = 'Mildly Reduced';
//             $flags['fvcFlagColor'] = 'primary';
//             $flags['fvcRangeCode']    = '#fff707';
//         }
//         else if ($value >= 50 && $value <= 59) {
//             $flags['fvcFlag']      = 'Moderately Reduced';
//             $flags['fvcFlagColor'] = 'primary';
//             $flags['fvcRangeCode']    = '#FFC107';
//         } 
//         else if ($value < 50) {
//             $flags['fvcFlag']      = 'Severely Reduced';
//             $flags['fvcFlagColor'] = 'danger';
//             $flags['fvcRangeCode']    = '#FF0000';
//         }
//         $pefFlag = $flags['fvcFlag'];
//         }
//         }


        // fev1(%)
//         if(!empty($input_data['fev1(%)'])) {
//             if($years >= 0 && $years <= 4){
//                     $value = $input_data['fev1(%)'];

//             if ($value >= 70) {
//                 $flags['fev1Flag']      = 'Normal';
//                 $flags['fev1FlagColor'] = 'success';
//                 $flags['fev1RangeCode']    = '#008000';
//             } else if ($value >= 60 && $value <= 69) {
//                 $flags['fev1Flag']      = 'Mildly Reduced';
//                 $flags['fev1FlagColor'] = 'primary';
//                 $flags['fev1RangeCode']    = '#fff707';
//             }
//             else if ($value >= 50 && $value <= 59 ) {
//                 $flags['fev1Flag']      = 'Moderately Reduced';
//                 $flags['fev1FlagColor'] = 'primary';
//                 $flags['fev1RangeCode']    = '#FFC107';
//             } 
//             else if ($value < 50) {
//                 $flags['fev1Flag']      = 'Severely Reduced';
//                 $flags['fev1FlagColor'] = 'danger';
//                 $flags['fev1RangeCode']    = '#FF0000';
//             }
//             $pefFlag = $flags['fvcFlag'];
//             }

//             if($years >= 5 && $years <= 12){
//                 $value = $input_data['fev1(%)'];

//         if ($value >= 80) {
//             $flags['fev1Flag']      = 'Normal';
//             $flags['fev1FlagColor'] = 'success';
//             $flags['fev1RangeCode']    = '#008000';
//         } else if ($value >= 70 && $value <= 79) {
//             $flags['fev1Flag']      = 'Mildly Reduced';
//             $flags['fev1FlagColor'] = 'primary';
//             $flags['fev1RangeCode']    = '#fff707';
//         }
//         else if ($value >= 50 && $value <= 69) {
//             $flags['fev1Flag']      = 'Moderately Reduced';
//             $flags['fev1FlagColor'] = 'primary';
//             $flags['fev1RangeCode']    = '#FFC107';
//         } 
//         else if ($value < 50) {
//             $flags['fev1Flag']      = 'Severely Reduced';
//             $flags['fev1FlagColor'] = 'danger';
//             $flags['fev1RangeCode']    = '#FF0000';
//         }
//         $pefFlag = $flags['fvcFlag'];
//         }
//         if($years >= 13 && $years <= 19){
//             $value = $input_data['fev1(%)'];

//     if ($value >= 80) {
//         $flags['fev1Flag']      = 'Normal';
//         $flags['fev1FlagColor'] = 'success';
//         $flags['fev1RangeCode']    = '#008000';
//     } else if ($value >= 70 && $value <= 79) {
//         $flags['fev1Flag']      = 'Mildly Reduced';
//         $flags['fev1FlagColor'] = 'primary';
//         $flags['fev1RangeCode']    = '#fff707';
//     }
//     else if ($value >= 50 && $value <= 69) {
//         $flags['fev1Flag']      = 'Moderately Reduced';
//         $flags['fev1FlagColor'] = 'primary';
//         $flags['fev1RangeCode']    = '#FFC107';
//     } 
//     else if ($value < 50) {
//         $flags['fev1Flag']      = 'Severely Reduced';
//         $flags['fev1FlagColor'] = 'danger';
//         $flags['fev1RangeCode']    = '#FF0000';
//     }
//     $pefFlag = $flags['fev1Flag'];
//     }
//     if($years >= 20 && $years <= 39){
  
//         $value = $input_data['fev1(%)'];

// if ($value >= 80) {
//     $flags['fev1Flag']      = 'Normal';
//     $flags['fev1FlagColor'] = 'success';
//     $flags['fev1RangeCode']    = '#008000';
// } else if ($value >= 70 && $value <= 79) {
//     $flags['fev1Flag']      = 'Mildly Reduced';
//     $flags['fev1FlagColor'] = 'primary';
//     $flags['fev1RangeCode']    = '#fff707';
// }
// else if ($value >= 50 && $value <= 69) {
//     $flags['fev1Flag']      = 'Moderately Reduced';
//     $flags['fev1FlagColor'] = 'primary';
//     $flags['fev1RangeCode']    = '#FFC107';
// } 
// else if ($value < 50) {
//     $flags['fev1Flag']      = 'Severely Reduced';
//     $flags['fev1FlagColor'] = 'danger';
//     $flags['fev1RangeCode']    = '#FF0000';
// }
// $pefFlag = $flags['fev1Flag'];


//         }
//         if($years >= 40 && $years <= 59){
       
//             $value = $input_data['fev1(%)'];
    
//     if ($value >= 80) {
//         $flags['fev1Flag']      = 'Normal';
//         $flags['fev1FlagColor'] = 'success';
//         $flags['fev1RangeCode']    = '#008000';
//     } else if ($value >= 70 && $value <= 79) {
//         $flags['fev1Flag']      = 'Mildly Reduced';
//         $flags['fev1FlagColor'] = 'primary';
//         $flags['fev1RangeCode']    = '#fff707';
//     }
//     else if ($value >= 50 && $value <= 69) {
//         $flags['fev1Flag']      = 'Moderately Reduced';
//         $flags['fev1FlagColor'] = 'primary';
//         $flags['fev1RangeCode']    = '#FFC107';
//     } 
//     else if ($value < 50) {
//         $flags['fev1Flag']      = 'Severely Reduced';
//         $flags['fev1FlagColor'] = 'danger';
//         $flags['fev1RangeCode']    = '#FF0000';
//     }
//     $pefFlag = $flags['fev1Flag'];
//             }

//             if($years >= 60){
//                 $value = $input_data['fev1(%)'];

//         if ($value >= 70) {
//             $flags['fev1Flag']      = 'Normal';
//             $flags['fev1FlagColor'] = 'success';
//             $flags['fev1RangeCode']    = '#008000';
//         } else if ($value >= 60 && $value <= 69) {
//             $flags['fev1Flag']      = 'Mildly Reduced';
//             $flags['fev1FlagColor'] = 'primary';
//             $flags['fev1RangeCode']    = '#fff707';
//         }
//         else if ($value >= 50 && $value <= 59) {
//             $flags['fev1Flag']      = 'Moderately Reduced';
//             $flags['fev1FlagColor'] = 'primary';
//             $flags['fev1RangeCode']    = '#FFC107';
//         } 
//         else if ($value < 50) {
//             $flags['fev1Flag']      = 'Severely Reduced';
//             $flags['fev1FlagColor'] = 'danger';
//             $flags['fev1RangeCode']    = '#FF0000';
//         }
//         $pefFlag = $flags['fvcFlag'];
//         }
//         }
 
 
        // PEF (%)
//         if(!empty($input_data['pef(%)'])) {
//             if($years >= 0 && $years <= 4){
//                     $value = $input_data['pef(%)'];

//             if ($value >= 80) {
//                 $flags['pefFlag']      = 'Normal';
//                 $flags['pefFlagColor'] = 'success';
//                 $flags['pefRangeCode']    = '#008000';
//             } else if ($value >= 60 && $value <= 79) {
//                 $flags['pefFlag']      = 'Mildly Reduced';
//                 $flags['pefFlagColor'] = 'primary';
//                 $flags['pefRangeCode']    = '#fff707';
//             }
//             else if ($value >= 40 && $value <= 59) {
//                 $flags['pefFlag']      = 'Moderately Reduced';
//                 $flags['pefFlagColor'] = 'primary';
//                 $flags['pefRangeCode']    = '#FFC107';
//             } 
//             else if ($value < 40) {
//                 $flags['pefFlag']      = 'Severely Reduced';
//                 $flags['pefFlagColor'] = 'danger';
//                 $flags['pefRangeCode']    = '#FF0000';
//             }
//             $pefFlag = $flags['pefFlag'];
//             }
//             if($years >= 5 && $years <= 19){
//                 $value = $input_data['pef(%)'];

//         if ($value >= 80) {
//             $flags['pefFlag']      = 'Normal';
//             $flags['pefFlagColor'] = 'success';
//             $flags['pefRangeCode']    = '#008000';
//         } else if ($value >= 60 && $value <= 79) {
//             $flags['pefFlag']      = 'Mildly Reduced';
//             $flags['pefFlagColor'] = 'primary';
//             $flags['pefRangeCode']    = '#fff707';
//         }
//         else if ($value >= 40 && $value <= 59) {
//             $flags['pefFlag']      = 'Moderately Reduced';
//             $flags['pefFlagColor'] = 'primary';
//             $flags['pefRangeCode']    = '#FFC107';
//         } 
//         else if ($value < 40) {
//             $flags['pefFlag']      = 'Severely Reduced';
//             $flags['pefFlagColor'] = 'danger';
//             $flags['pefRangeCode']    = '#FF0000';
//         }
//         $pefFlag = $flags['pefFlag'];
//         }
//         if($years >= 20 && $years <= 59){
//             $value = $input_data['pef(%)'];

//     if ($value >= 80) {
//         $flags['pefFlag']      = 'Normal';
//         $flags['pefFlagColor'] = 'success';
//         $flags['pefRangeCode']    = '#008000';
//     } else if ($value >= 70 && $value <= 79) {
//         $flags['pefFlag']      = 'Mildly Reduced';
//         $flags['pefFlagColor'] = 'primary';
//         $flags['pefRangeCode']    = '#fff707';
//     }
//     else if ($value >= 50 && $value <= 69) {
//         $flags['pefFlag']      = 'Moderately Reduced';
//         $flags['pefFlagColor'] = 'primary';
//         $flags['pefRangeCode']    = '#FFC107';
//     } 
//     else if ($value < 50) {
//         $flags['pefFlag']      = 'Severely Reduced';
//         $flags['pefFlagColor'] = 'danger';
//         $flags['pefRangeCode']    = '#FF0000';
//     }
//     $pefFlag = $flags['pefFlag'];
//     }
//     if($years >= 60){
//         $value = $input_data['pef(%)'];

// if ($value >= 70) {
//     $flags['pefFlag']      = 'Normal';
//     $flags['pefFlagColor'] = 'success';
//     $flags['pefRangeCode']    = '#008000';
// } else if ($value >= 60 && $value <= 69) {
//     $flags['pefFlag']      = 'Mildly Reduced';
//     $flags['pefFlagColor'] = 'primary';
//     $flags['pefRangeCode']    = '#fff707';
// }
// else if ($value >= 50 && $value <= 59) {
//     $flags['pefFlag']      = 'Moderately Reduced';
//     $flags['pefFlagColor'] = 'primary';
//     $flags['pefRangeCode']    = '#FFC107';
// } 
// else if ($value < 50) {
//     $flags['pefFlag']      = 'Severely Reduced';
//     $flags['pefFlagColor'] = 'danger';
//     $flags['pefRangeCode']    = '#FF0000';
// }
// $pefFlag = $flags['pefFlag'];
// }
//         }
      
        // FEF25(%)
//         if(!empty($input_data['fef25(%)'])) {
//             if($years >= 0 && $years <= 4){
//                     $value = $input_data['fef25(%)'];

//             if ($value >= 80) {
//                 $flags['fef25Flag']      = 'Normal';
//                 $flags['fef25FlagColor'] = 'success';
//                 $flags['fef25RangeCode']    = '#008000';
//             } else if ($value >= 60 && $value <= 79) {
//                 $flags['Flag']      = 'Mildly Reduced';
//                 $flags['fef25FlagColor'] = 'primary';
//                 $flags['fef25RangeCode']    = '#fff707';
//             }
//             else if ($value >= 40 && $value <= 59) {
//                 $flags['fef25Flag']      = 'Moderately Reduced';
//                 $flags['fef25FlagColor'] = 'primary';
//                 $flags['fef25RangeCode']    = '#FFC107';
//             } 
//             else if ($value < 40) {
//                 $flags['fef25Flag']      = 'Severely Reduced';
//                 $flags['fef25FlagColor'] = 'danger';
//                 $flags['fef25RangeCode']    = '#FF0000';
//             }
//             $pefFlag = $flags['fef25Flag'];
//             }
//             if($years >= 5 && $years <= 12){
//                 $value = $input_data['fef25(%)'];

//         if ($value >= 80) {
//             $flags['fef25Flag']      = 'Normal';
//             $flags['fef25FlagColor'] = 'success';
//             $flags['fef25RangeCode']    = '#008000';
//         } else if ($value >= 60 && $value <= 79) {
//             $flags['fef25Flag']      = 'Mildly Reduced';
//             $flags['fef25FlagColor'] = 'primary';
//             $flags['fef25RangeCode']    = '#fff707';
//         }
//         else if ($value >= 40 && $value <= 59) {
//             $flags['fef25Flag']      = 'Moderately Reduced';
//             $flags['fef25FlagColor'] = 'primary';
//             $flags['fef25RangeCode']    = '#FFC107';
//         } 
//         else if ($value < 40) {
//             $flags['fef25Flag']      = 'Severely Reduced';
//             $flags['fef25FlagColor'] = 'danger';
//             $flags['fef25RangeCode']    = '#FF0000';
//         }
//         $pefFlag = $flags['fef25Flag'];
//         }
//         if($years >= 13 && $years <= 19){
//             $value = $input_data['fef25(%)'];

//     if ($value >= 80) {
//         $flags['fef25Flag']      = 'Normal';
//         $flags['fef25FlagColor'] = 'success';
//         $flags['fef25RangeCode']    = '#008000';
//     } else if ($value >=60 && $value <= 79) {
//         $flags['fef25Flag']      = 'Mildly Reduced';
//         $flags['fef25FlagColor'] = 'primary';
//         $flags['fef25RangeCode']    = '#fff707';
//     }
//     else if ($value >= 40  && $value <= 59) {
//         $flags['fef25Flag']      = 'Moderately Reduced';
//         $flags['fef25FlagColor'] = 'primary';
//         $flags['fef25RangeCode']    = '#FFC107';
//     } 
//     else if ($value < 40) {
//         $flags['fef25Flag']      = 'Severely Reduced';
//         $flags['fef25FlagColor'] = 'danger';
//         $flags['fef25RangeCode']    = '#FF0000';
//     }
//     $pefFlag = $flags['fef25Flag'];
//     }
//         if($years >= 20 && $years <= 39){
//             $value = $input_data['fef25(%)'];

//     if ($value >= 80) {
//         $flags['fef25Flag']      = 'Normal';
//         $flags['fef25FlagColor'] = 'success';
//         $flags['fef25RangeCode']    = '#008000';
//     } else if ($value >= 70 && $value <= 79) {
//         $flags['fef25Flag']      = 'Mildly Reduced';
//         $flags['fef25FlagColor'] = 'primary';
//         $flags['fef25RangeCode']    = '#fff707';
//     }
//     else if ($value >= 50 && $value <= 69) {
//         $flags['fef25Flag']      = 'Moderately Reduced';
//         $flags['fef25FlagColor'] = 'primary';
//         $flags['fef25RangeCode']    = '#FFC107';
//     } 
//     else if ($value < 50) {
//         $flags['fef25Flag']      = 'Severely Reduced';
//         $flags['fef25FlagColor'] = 'danger';
//         $flags['fef25RangeCode']    = '#FF0000';
//     }
//     $pefFlag = $flags['fef25Flag'];
//     }
//     if($years >= 40 && $years <= 59){
//         $value = $input_data['fef25(%)'];

// if ($value >= 80) {
//     $flags['fef25Flag']      = 'Normal';
//     $flags['fef25FlagColor'] = 'success';
//     $flags['fef25RangeCode']    = '#008000';
// } else if ($value >= 70 && $value <= 79) {
//     $flags['fef25Flag']      = 'Mildly Reduced';
//     $flags['fef25FlagColor'] = 'primary';
//     $flags['fef25RangeCode']    = '#fff707';
// }
// else if ($value >= 50 && $value <= 69) {
//     $flags['fef25Flag']      = 'Moderately Reduced';
//     $flags['fef25FlagColor'] = 'primary';
//     $flags['fef25RangeCode']    = '#FFC107';
// } 
// else if ($value < 50) {
//     $flags['fef25Flag']      = 'Severely Reduced';
//     $flags['fef25FlagColor'] = 'danger';
//     $flags['fef25RangeCode']    = '#FF0000';
// }
// $pefFlag = $flags['fef25Flag'];
// }
//     if($years >= 60){
//         $value = $input_data['fef25(%)'];

// if ($value >= 70) {
//     $flags['fef25Flag']      = 'Normal';
//     $flags['fef25FlagColor'] = 'success';
//     $flags['fef25RangeCode']    = '#008000';
// } else if ($value >= 60 && $value <= 69) {
//     $flags['fef25Flag']      = 'Mildly Reduced';
//     $flags['fef25FlagColor'] = 'primary';
//     $flags['fef25RangeCode']    = '#fff707';
// }
// else if ($value >= 50 && $value <= 59) {
//     $flags['fef25Flag']      = 'Moderately Reduced';
//     $flags['fef25FlagColor'] = 'primary';
//     $flags['fef25RangeCode']    = '#FFC107';
// } 
// else if ($value < 50) {
//     $flags['fef25Flag']      = 'Severely Reduced';
//     $flags['fef25FlagColor'] = 'danger';
//     $flags['fef25RangeCode']    = '#FF0000';
// }
// $pefFlag = $flags['fef25Flag'];
// }
        // }
 
        // FEF50 (%)
//         if(!empty($input_data['fef50 (%)'])) {
//             if($years >= 0 && $years <= 4){
//                     $value = $input_data['fef50 (%)'];

//             if ($value >= 80) {
//                 $flags['fef50 Flag']      = 'Normal';
//                 $flags['fef50 FlagColor'] = 'success';
//                 $flags['fef50 RangeCode']    = '#008000';
//             } else if ($value >= 60 && $value <= 79) {
//                 $flags['fef50 Flag']      = 'Mildly Reduced';
//                 $flags['fef50 FlagColor'] = 'primary';
//                 $flags['fef50 RangeCode']    = '#fff707';
//             }
//             else if ($value >= 40 && $value <= 59) {
//                 $flags['fef50 Flag']      = 'Moderately Reduced';
//                 $flags['fef50 FlagColor'] = 'primary';
//                 $flags['fef50 RangeCode']    = '#FFC107';
//             } 
//             else if ($value < 40) {
//                 $flags['fef50 Flag']      = 'Severely Reduced';
//                 $flags['fef50 FlagColor'] = 'danger';
//                 $flags['fef50 RangeCode']    = '#FF0000';
//             }
//             $fef50Flag = $flags['fef50 Flag'];
//             }

//             if($years >= 5 && $years <= 12){
//                 $value = $input_data['fef50 (%)'];

//         if ($value >= 80) {
//             $flags['fef50 Flag']      = 'Normal';
//             $flags['fef50 FlagColor'] = 'success';
//             $flags['fef50 RangeCode']    = '#008000';
//         } else if ($value >= 60 && $value <= 79) {
//             $flags['fef50 Flag']      = 'Mildly Reduced';
//             $flags['fef50 FlagColor'] = 'primary';
//             $flags['fef50 RangeCode']    = '#fff707';
//         }
//         else if ($value >= 40 && $value <= 50) {
//             $flags['fef50 Flag']      = 'Moderately Reduced';
//             $flags['fef50 FlagColor'] = 'primary';
//             $flags['fef50 RangeCode']    = '#FFC107';
//         } 
//         else if ($value < 40) {
//             $flags['fef50 Flag']      = 'Severely Reduced';
//             $flags['fef50 FlagColor'] = 'danger';
//             $flags['fef50 RangeCode']    = '#FF0000';
//         }
//         $pefFlag = $flags['fef50 Flag'];
//         }
//         if($years >= 13 && $years <= 19){
//             $value = $input_data['fef50 (%)'];

//     if ($value >= 80) {
//         $flags['fef50 Flag']      = 'Normal';
//         $flags['fef50 FlagColor'] = 'success';
//         $flags['fef50 RangeCode']    = '#008000';
//     } else if ($value >= 60 && $value <= 79) {
//         $flags['fef50 Flag']      = 'Mildly Reduced';
//         $flags['fef50 FlagColor'] = 'primary';
//         $flags['fef50 RangeCode']    = '#fff707';
//     }
//     else if ($value >= 40 && $value <= 59) {
//         $flags['fef50 Flag']      = 'Moderately Reduced';
//         $flags['fef50 FlagColor'] = 'primary';
//         $flags['fef50 RangeCode']    = '#FFC107';
//     } 
//     else if ($value < 40) {
//         $flags['fef50 Flag']      = 'Severely Reduced';
//         $flags['fef50 FlagColor'] = 'danger';
//         $flags['fef50 RangeCode']    = '#FF0000';
//     }
//     $fef50Flag = $flags['fef50Flag'];
//     }
//     if($years >= 20 && $years <= 39){
  
//         $value = $input_data['fef50 (%)'];

// if ($value >= 80) {
//     $flags['fef50 Flag']      = 'Normal';
//     $flags['fef50 FlagColor'] = 'success';
//     $flags['fef50 RangeCode']    = '#008000';
// } else if ($value >= 70 && $value <= 79) {
//     $flags['fef50 Flag']      = 'Mildly Reduced';
//     $flags['fef50 FlagColor'] = 'primary';
//     $flags['fef50 RangeCode']    = '#fff707';
// }
// else if ($value >= 50 && $value <= 69) {
//     $flags['fef50 Flag']      = 'Moderately Reduced';
//     $flags['fef50 FlagColor'] = 'primary';
//     $flags['fef50 RangeCode']    = '#FFC107';
// } 
// else if ($value < 50) {
//     $flags['fef50 Flag']      = 'Severely Reduced';
//     $flags['fef50 FlagColor'] = 'danger';
//     $flags['fef50 RangeCode']    = '#FF0000';
// }
// $fef50Flag = $flags['fef50Flag'];


//         }
//         if($years >= 40 && $years <= 59){
       
//             $value = $input_data['fef50(%)'];
    
//     if ($value >= 80) {
//         $flags['fef50 Flag']      = 'Normal';
//         $flags['fef50 FlagColor'] = 'success';
//         $flags['fef50 RangeCode']    = '#008000';
//     } else if ($value >= 70 && $value <= 79) {
//         $flags['fef50 Flag']      = 'Mildly Reduced';
//         $flags['fef50 FlagColor'] = 'primary';
//         $flags['fef50 RangeCode']    = '#fff707';
//     }
//     else if ($value >= 50 && $value <= 69) {
//         $flags['fef50 Flag']      = 'Moderately Reduced';
//         $flags['fef50 FlagColor'] = 'primary';
//         $flags['fef50 RangeCode']    = '#FFC107';
//     } 
//     else if ($value < 50) {
//         $flags['fef50 Flag']      = 'Severely Reduced';
//         $flags['fef50 FlagColor'] = 'danger';
//         $flags['fef50 RangeCode']    = '#FF0000';
//     }
//     $fef50fFlag = $flags['fef50Flag'];
//             }

//             if($years >= 60){
//                 $value = $input_data['fef50(%)'];

//         if ($value >= 70) {
//             $flags['fef50 Flag']      = 'Normal';
//             $flags['fef50 FlagColor'] = 'success';
//             $flags['fef50 RangeCode']    = '#008000';
//         } else if ($value >= 60 && $value <= 69) {
//             $flags['fef50 Flag']      = 'Mildly Reduced';
//             $flags['fef50 FlagColor'] = 'primary';
//             $flags['fef50 RangeCode']    = '#fff707';
//         }
//         else if ($value >= 50 && $value <= 59) {
//             $flags['fef50 Flag']      = 'Moderately Reduced';
//             $flags['fef50 FlagColor'] = 'primary';
//             $flags['fef50 RangeCode']    = '#FFC107';
//         } 
//         else if ($value < 50) {
//             $flags['fef50 Flag']      = 'Severely Reduced';
//             $flags['fef50 FlagColor'] = 'danger';
//             $flags['fef50 RangeCode']    = '#FF0000';
//         }
//         $fef50Flag = $flags['fef50Flag'];
//         }
//         }

        // fef75 (%)
//         if(!empty($input_data['fef75 (%)'])) {
//             if($years >= 0 && $years <= 4){
//                     $value = $input_data['fef75 (%)'];

//             if ($value >= 80) {
//                 $flags['fef75 Flag']      = 'Normal';
//                 $flags['fef75 FlagColor'] = 'success';
//                 $flags['fef75 RangeCode']    = '#008000';
//             } else if ($value >= 60 && $value <= 79) {
//                 $flags['fef75 Flag']      = 'Mildly Reduced';
//                 $flags['fef75 FlagColor'] = 'primary';
//                 $flags['fef75 RangeCode']    = '#fff707';
//             }
//             else if ($value >= 40 && $value <= 59) {
//                 $flags['fef75 Flag']      = 'Moderately Reduced';
//                 $flags['fef75 FlagColor'] = 'primary';
//                 $flags['fef75 RangeCode']    = '#FFC107';
//             } 
//             else if ($value < 40) {
//                 $flags['fef75 Flag']      = 'Severely Reduced';
//                 $flags['fef75 FlagColor'] = 'danger';
//                 $flags['fef75 RangeCode']    = '#FF0000';
//             }
//             $fef75Flag = $flags['fef75 Flag'];
//             }

//             if($years >= 5 && $years <= 12){
//                 $value = $input_data['fef75 (%)'];

//         if ($value >= 80) {
//             $flags['fef75 Flag']      = 'Normal';
//             $flags['fef75 FlagColor'] = 'success';
//             $flags['fef75 RangeCode']    = '#008000';
//         } else if ($value >= 60 && $value <= 79) {
//             $flags['fef75 Flag']      = 'Mildly Reduced';
//             $flags['fef75 FlagColor'] = 'primary';
//             $flags['fef75 RangeCode']    = '#fff707';
//         }
//         else if ($value >= 40 && $value <= 59) {
//             $flags['fef75 Flag']      = 'Moderately Reduced';
//             $flags['fef75 FlagColor'] = 'primary';
//             $flags['fef75 RangeCode']    = '#FFC107';
//         } 
//         else if ($value < 40) {
//             $flags['fef75 Flag']      = 'Severely Reduced';
//             $flags['fef75 FlagColor'] = 'danger';
//             $flags['fef75 RangeCode']    = '#FF0000';
//         }
//         $pefFlag = $flags['fef75 Flag'];
//         }
//         if($years >= 13 && $years <= 19){
//             $value = $input_data['fef75 (%)'];

//     if ($value >= 80) {
//         $flags['fef75 Flag']      = 'Normal';
//         $flags['fef75 FlagColor'] = 'success';
//         $flags['fef75 RangeCode']    = '#008000';
//     } else if ($value >= 60 && $value <= 79) {
//         $flags['fef75 Flag']      = 'Mildly Reduced';
//         $flags['fef75 FlagColor'] = 'primary';
//         $flags['fef75 RangeCode']    = '#fff707';
//     }
//     else if ($value >= 40 && $value <= 59) {
//         $flags['fef75 Flag']      = 'Moderately Reduced';
//         $flags['fef75 FlagColor'] = 'primary';
//         $flags['fef75 RangeCode']    = '#FFC107';
//     } 
//     else if ($value < 40) {
//         $flags['fef75 Flag']      = 'Severely Reduced';
//         $flags['fef75 FlagColor'] = 'danger';
//         $flags['fef75 RangeCode']    = '#FF0000';
//     }
//     $fef75Flag = $flags['fef75Flag'];
//     }
//     if($years >= 20 && $years <= 39){
  
//         $value = $input_data['fef75 (%)'];

// if ($value >= 80) {
//     $flags['fef75 Flag']      = 'Normal';
//     $flags['fef75 FlagColor'] = 'success';
//     $flags['fef75 RangeCode']    = '#008000';
// } else if ($value >= 70 && $value <= 79) {
//     $flags['fef75 Flag']      = 'Mildly Reduced';
//     $flags['fef75 FlagColor'] = 'primary';
//     $flags['fef75 RangeCode']    = '#fff707';
// }
// else if ($value >= 50 && $value <= 69) {
//     $flags['fef75 Flag']      = 'Moderately Reduced';
//     $flags['fef75 FlagColor'] = 'primary';
//     $flags['fef75 RangeCode']    = '#FFC107';
// } 
// else if ($value < 50) {
//     $flags['fef75 Flag']      = 'Severely Reduced';
//     $flags['fef75 FlagColor'] = 'danger';
//     $flags['fef75 RangeCode']    = '#FF0000';
// }
// $fef75Flag = $flags['fef75Flag'];


//         }
//         if($years >= 40 && $years <= 59){
       
//             $value = $input_data['fef75(%)'];
    
//     if ($value >= 80) {
//         $flags['fef75 Flag']      = 'Normal';
//         $flags['fef75 FlagColor'] = 'success';
//         $flags['fef75 RangeCode']    = '#008000';
//     } else if ($value >= 70 && $value <= 79) {
//         $flags['fef75 Flag']      = 'Mildly Reduced';
//         $flags['fef75 FlagColor'] = 'primary';
//         $flags['fef75 RangeCode']    = '#fff707';
//     }
//     else if ($value >= 50 && $value <= 69) {
//         $flags['fef75 Flag']      = 'Moderately Reduced';
//         $flags['fef75 FlagColor'] = 'primary';
//         $flags['fef75 RangeCode']    = '#FFC107';
//     } 
//     else if ($value < 50) {
//         $flags['fef75 Flag']      = 'Severely Reduced';
//         $flags['fef75 FlagColor'] = 'danger';
//         $flags['fef75 RangeCode']    = '#FF0000';
//     }
//     $fef75fFlag = $flags['fef75Flag'];
//             }

//             if($years >= 60){
//                 $value = $input_data['fef75 (%)'];

//         if ($value >= 70) {
//             $flags['fef75 Flag']      = 'Normal';
//             $flags['fef75 FlagColor'] = 'success';
//             $flags['fef75 RangeCode']    = '#008000';
//         } else if ($value >= 60 && $value <= 69) {
//             $flags['fef75 Flag']      = 'Mildly Reduced';
//             $flags['fef75 FlagColor'] = 'primary';
//             $flags['fef75 RangeCode']    = '#fff707';
//         }
//         else if ($value >= 50 && $value <= 59) {
//             $flags['fef75 Flag']      = 'Moderately Reduced';
//             $flags['fef75 FlagColor'] = 'primary';
//             $flags['fef75 RangeCode']    = '#FFC107';
//         } 
//         else if ($value < 50) {
//             $flags['fef75 Flag']      = 'Severely Reduced';
//             $flags['fef75 FlagColor'] = 'danger';
//             $flags['fef75 RangeCode']    = '#FF0000';
//         }
//         $fef75Flag = $flags['fef75Flag'];
//         }
//         }

 
        // fef2575 (%)
//         if(!empty($input_data['fef2575 (%)'])) {
//             if($years >= 0 && $years <= 4){
//                     $value = $input_data['fef2575 (%)'];

//             if ($value >= 80) {
//                 $flags['fef2575 Flag']      = 'Normal';
//                 $flags['fef2575 FlagColor'] = 'success';
//                 $flags['fef2575 RangeCode']    = '#008000';
//             } else if ($value >= 60 && $value <= 79) {
//                 $flags['fef2575 Flag']      = 'Mildly Reduced';
//                 $flags['fef2575 FlagColor'] = 'primary';
//                 $flags['fef2575 RangeCode']    = '#fff707';
//             }
//             else if ($value >= 40 && $value <= 59) {
//                 $flags['fef2575 Flag']      = 'Moderately Reduced';
//                 $flags['fef2575 FlagColor'] = 'primary';
//                 $flags['fef2575 RangeCode']    = '#FFC107';
//             } 
//             else if ($value < 40) {
//                 $flags['fef2575 Flag']      = 'Severely Reduced';
//                 $flags['fef2575 FlagColor'] = 'danger';
//                 $flags['fef2575 RangeCode']    = '#FF0000';
//             }
//             $fef2575Flag = $flags['fef2575 Flag'];
//             }

//             if($years >= 5 && $years <= 12){
//                 $value = $input_data['fef2575 (%)'];

//         if ($value >= 80) {
//             $flags['fef2575 Flag']      = 'Normal';
//             $flags['fef2575 FlagColor'] = 'success';
//             $flags['fef2575 RangeCode']    = '#008000';
//         } else if ($value >= 60 && $value <= 79) {
//             $flags['fef2575 Flag']      = 'Mildly Reduced';
//             $flags['fef2575 FlagColor'] = 'primary';
//             $flags['fef2575 RangeCode']    = '#fff707';
//         }
//         else if ($value >= 40 && $value <= 50) {
//             $flags['fef2575 Flag']      = 'Moderately Reduced';
//             $flags['fef2575 FlagColor'] = 'primary';
//             $flags['fef2575 RangeCode']    = '#FFC107';
//         } 
//         else if ($value < 40) {
//             $flags['fef2575 Flag']      = 'Severely Reduced';
//             $flags['fef2575 FlagColor'] = 'danger';
//             $flags['fef2575 RangeCode']    = '#FF0000';
//         }
//         $pefFlag = $flags['fef2575 Flag'];
//         }
//         if($years >= 13 && $years <= 19){
//             $value = $input_data['fef2575 (%)'];

//     if ($value >= 80) {
//         $flags['fef2575 Flag']      = 'Normal';
//         $flags['fef2575 FlagColor'] = 'success';
//         $flags['fef2575 RangeCode']    = '#008000';
//     } else if ($value >= 60 && $value <= 79) {
//         $flags['fef2575 Flag']      = 'Mildly Reduced';
//         $flags['fef2575 FlagColor'] = 'primary';
//         $flags['fef2575 RangeCode']    = '#fff707';
//     }
//     else if ($value >= 40 && $value <= 59) {
//         $flags['fef2575 Flag']      = 'Moderately Reduced';
//         $flags['fef2575 FlagColor'] = 'primary';
//         $flags['fef2575 RangeCode']    = '#FFC107';
//     } 
//     else if ($value < 40) {
//         $flags['fef2575 Flag']      = 'Severely Reduced';
//         $flags['fef2575 FlagColor'] = 'danger';
//         $flags['fef2575 RangeCode']    = '#FF0000';
//     }
//     $fef2575Flag = $flags['fef2575Flag'];
//     }
//     if($years >= 20 && $years <= 39){
  
//         $value = $input_data['fef2575 (%)'];

// if ($value >= 80) {
//     $flags['fef2575 Flag']      = 'Normal';
//     $flags['fef2575 FlagColor'] = 'success';
//     $flags['fef2575 RangeCode']    = '#008000';
// } else if ($value >= 70 && $value <= 79) {
//     $flags['fef2575 Flag']      = 'Mildly Reduced';
//     $flags['fef2575 FlagColor'] = 'primary';
//     $flags['fef2575 RangeCode']    = '#fff707';
// }
// else if ($value >= 50 && $value <= 69) {
//     $flags['fef2575 Flag']      = 'Moderately Reduced';
//     $flags['fef2575 FlagColor'] = 'primary';
//     $flags['fef2575 RangeCode']    = '#FFC107';
// } 
// else if ($value < 50) {
//     $flags['fef2575 Flag']      = 'Severely Reduced';
//     $flags['fef2575 FlagColor'] = 'danger';
//     $flags['fef2575 RangeCode']    = '#FF0000';
// }
// $fef2575Flag = $flags['fef2575Flag'];


//         }
//         if($years >= 40 && $years <= 59){
       
//             $value = $input_data['fef2575(%)'];
    
//     if ($value >= 80) {
//         $flags['fef2575 Flag']      = 'Normal';
//         $flags['fef2575 FlagColor'] = 'success';
//         $flags['fef2575 RangeCode']    = '#008000';
//     } else if ($value >= 70 && $value <= 79) {
//         $flags['fef2575 Flag']      = 'Mildly Reduced';
//         $flags['fef2575 FlagColor'] = 'primary';
//         $flags['fef2575 RangeCode']    = '#fff707';
//     }
//     else if ($value >= 50 && $value <= 69) {
//         $flags['fef2575 Flag']      = 'Moderately Reduced';
//         $flags['fef2575 FlagColor'] = 'primary';
//         $flags['fef2575 RangeCode']    = '#FFC107';
//     } 
//     else if ($value < 50) {
//         $flags['fef2575 Flag']      = 'Severely Reduced';
//         $flags['fef2575 FlagColor'] = 'danger';
//         $flags['fef2575 RangeCode']    = '#FF0000';
//     }
//     $fef2575fFlag = $flags['fef2575Flag'];
//             }

//             if($years >= 60){
//                 $value = $input_data['fef2575 (%)'];

//         if ($value >= 70) {
//             $flags['fef2575 Flag']      = 'Normal';
//             $flags['fef2575 FlagColor'] = 'success';
//             $flags['fef2575 RangeCode']    = '#008000';
//         } else if ($value >= 60 && $value <= 69) {
//             $flags['fef2575 Flag']      = 'Mildly Reduced';
//             $flags['fef2575 FlagColor'] = 'primary';
//             $flags['fef2575 RangeCode']    = '#fff707';
//         }
//         else if ($value >= 50 && $value <= 59) {
//             $flags['fef2575 Flag']      = 'Moderately Reduced';
//             $flags['fef2575 FlagColor'] = 'primary';
//             $flags['fef2575 RangeCode']    = '#FFC107';
//         } 
//         else if ($value < 50) {
//             $flags['fef2575 Flag']      = 'Severely Reduced';
//             $flags['fef2575 FlagColor'] = 'danger';
//             $flags['fef2575 RangeCode']    = '#FF0000';
//         }
//         $fef2575Flag = $flags['fef2575Flag'];
//         }
//         }

        // FEF2575 (%)
        // if(!empty($input_data['fvc'])) {
        //     $value = $input_data['fvc'];

        //     if ($value >= 4) {
        //         $flags['fvcFlag']      = 'Normal';
        //         $flags['fvcFlagColor'] = 'success';
        //         $flags['fvcRangeCode']    = '#008000';
        //     } else if ($value >= 3 && $value <= 3.9) {
        //         $flags['fvcFlag']      = 'Slightly Below Normal';
        //         $flags['fvcFlagColor'] = 'primary';
        //         $flags['fvcRangeCode']    = '#0000ff';
        //     } else if ($value < 3) {
        //         $flags['fvcFlag']      = 'Potential Concern';
        //         $flags['fvcFlagColor'] = 'danger';
        //         $flags['fvcRangeCode']    = '#ff0000';
        //     }
        //     $pefFlag = $flags['fvcFlag'];
        // }

        // if(!empty($input_data['fev1'])) {
        //     $value = $input_data['fev1'];

        //     if ($value >= 3) {
        //         $flags['fev1Flag']      = 'Normal';
        //         $flags['fev1FlagColor'] = 'success';
        //         $flags['fev1RangeCode']    = '#008000';
        //     } else if ($value >= 2 && $value <= 2.9) {
        //         $flags['fev1Flag']      = 'Slightly Below Normal';
        //         $flags['fev1FlagColor'] = 'primary';
        //         $flags['fev1RangeCode']    = '#0000ff';
        //     } else if ($value < 2) {
        //         $flags['fev1Flag']      = 'Potential Concern';
        //         $flags['fev1FlagColor'] = 'danger';
        //         $flags['fev1RangeCode']    = '#ff0000';
        //     }
        //     $fev1Flag = $flags['fev1Flag'];
        // }

        // if(!empty($input_data['fev1_fvc'])){
        //     $value = $input_data['fev1_fvc'];

        //     if ($value >= 70) {
        //         $flags['fev1FvcFlag']      = 'Normal';
        //         $flags['fev1FvcFlagColor'] = 'success';
        //         $flags['fev1FvcRangeCode']    = '#008000';
        //     } else if ($value >= 60 && $value <= 69) {
        //         $flags['fev1FvcFlag']      = 'Slightly Below Normal';
        //         $flags['fev1FvcFlagColor'] = 'primary';
        //         $flags['fev1FvcRangeCode']    = '#0000ff';
        //     } else if ($value < 60) {
        //         $flags['fev1FvcFlag']      = 'Potential Concern';
        //         $flags['fev1FvcFlagColor'] = 'danger';
        //         $flags['fev1FvcRangeCode']    = '#ff0000';
        //     }
        //     $fev1FvcFlag = $flags['fev1FvcFlag'];
        // }

        // if(!empty($input_data['pef'])) {
        //     $value = $input_data['pef'];

        //     if ($value >= 500) {
        //         $flags['pefFlag']      = 'Normal';
        //         $flags['pefFlagColor'] = 'success';
        //         $flags['pefRangeCode']    = '#008000';
        //     } else if ($value >= 300 && $value >= 499) {
        //         $flags['pefFlag']      = 'Slightly Below Normal';
        //         $flags['pefFlagColor'] = 'primary';
        //         $flags['pefRangeCode']    = '#0000ff';
        //     } else if ($value < 300) {
        //         $flags['pefFlag']      = 'Potential Concern';
        //         $flags['pefFlagColor'] = 'danger';
        //         $flags['pefRangeCode']    = '#ff0000';
        //     }
        //     $pefFlag = $flags['pefFlag'];
        // }

        // if($fvcFlag == 'Normal' && $fev1Flag == 'Normal' && $fev1FvcFlag == 'Normal' && $pefFlag == 'Normal') {
        //     $input_data['spirometerFlag']      = 'Normal';
        //     $input_data['spirometerFlagColor'] = 'success';
        //     $input_data['range_code']    = '#008000';
        // } else if(in_array('Potential Concern', [$fvcFlag, $fev1Flag, $fev1FvcFlag, $pefFlag])) {
        //     $input_data['spirometerFlag']      = 'Potential Concern';
        //     $input_data['spirometerFlagColor'] = 'danger';
        //     $input_data['range_code']    = '#ff0000';
        // } else if(!empty($fvcFlag) && !empty($fev1Flag) && !empty($fev1FvcFlag) && !empty($pefFlag)) {
        //     $input_data['spirometerFlag']      = 'Slightly Below Normal';
        //     $input_data['spirometerFlagColor'] = 'primary';
        //     $input_data['range_code']    = '#0000ff';
        // }

    //     $input_data['flags'] = $flags;

    //    
    // }
//      return $input_data;
// }
    

    public static function urea_flag($input_data, $years, $months, $days)
    {
        $input_data['ureaFlag']      = '';
        $input_data['ureaFlagColor'] = '';
        $input_data['range_code']    = '';

        if (!empty($input_data['urea'])) {
            if($years >= 0 && $years <= 5){
                if ($input_data['urea'] <= 5) {
                            $input_data['ureaFlag']      = 'Low';
                            $input_data['ureaFlagColor'] = 'warning';
                            $input_data['range_code']    = '#0000ff';
                        }
            
                        if (($input_data['urea'] > 5) && ($input_data['urea'] <= 15)) {
                            $input_data['ureaFlag']      = 'Normal ';
                            $input_data['ureaFlagColor'] = 'success';
                            $input_data['range_code']    = '#008000';
                        }
                        
                        if (($input_data['urea'] >= 16) && ($input_data['urea'] <= 20)) {
                            $input_data['ureaFlag']      = 'Elevated';
                            $input_data['ureaFlagColor'] = 'warning';
                            $input_data['range_code']    = '#FFC107';
                        }
            
                        if ($input_data['urea'] > 20) {
                            $input_data['ureaFlag']      = 'Severe Risk';
                            $input_data['ureaFlagColor'] = 'danger';
                            $input_data['range_code']    = '#FF0000';
                        }
            }
            if($years >= 6 && $years <= 19){
                if ($input_data['urea'] <= 7) {
                            $input_data['ureaFlag']      = 'Low';
                            $input_data['ureaFlagColor'] = 'success';
                            $input_data['range_code']    = '#0000ff';
                        }
            
                        if (($input_data['urea'] > 7) && ($input_data['urea'] <= 20)) {
                            $input_data['ureaFlag']      = 'Normal ';
                            $input_data['ureaFlagColor'] = 'success';
                            $input_data['range_code']    = '#008000';
                        }
                        
                        if (($input_data['urea'] >= 21) && ($input_data['urea'] <= 25)) {
                            $input_data['ureaFlag']      = 'Elevated';
                            $input_data['ureaFlagColor'] = 'warning';
                            $input_data['range_code']    = '#FFC107';
                        }
            
                        if ($input_data['urea'] > 25) {
                            $input_data['ureaFlag']      = 'Severe Risk';
                            $input_data['ureaFlagColor'] = 'danger';
                            $input_data['range_code']    = '#FF0000';
                        }
            }
            if($years >= 20 && $years <= 59){
                if ($input_data['urea'] <= 7) {
                            $input_data['ureaFlag']      = 'Low';
                            $input_data['ureaFlagColor'] = 'success';
                            $input_data['range_code']    = '#0000ff';
                        }
            
                        if (($input_data['urea'] > 7) && ($input_data['urea'] <= 25)) {
                            $input_data['ureaFlag']      = 'Normal ';
                            $input_data['ureaFlagColor'] = 'success';
                            $input_data['range_code']    = '#008000';
                        }
                        
                        if (($input_data['urea'] >= 26) && ($input_data['urea'] <= 30)) {
                            $input_data['ureaFlag']      = 'Elevated';
                            $input_data['ureaFlagColor'] = 'warning';
                            $input_data['range_code']    = '#FFC107';
                        }
            
                        if ($input_data['urea'] > 30) {
                            $input_data['ureaFlag']      = 'Severe Risk';
                            $input_data['ureaFlagColor'] = 'danger';
                            $input_data['range_code']    = '#FF0000';
                        }
            }
            if($years >= 60){
                if ($input_data['urea'] <= 7) {
                            $input_data['ureaFlag']      = 'Low';
                            $input_data['ureaFlagColor'] = 'success';
                            $input_data['range_code']    = '#0000ff';
                        }
            
                        if (($input_data['urea'] > 7) && ($input_data['urea'] <= 30)) {
                            $input_data['ureaFlag']      = 'Normal ';
                            $input_data['ureaFlagColor'] = 'success';
                            $input_data['range_code']    = '#008000';
                        }
                        
                        if (($input_data['urea'] >= 31) && ($input_data['urea'] <= 40)) {
                            $input_data['ureaFlag']      = 'Elevated';
                            $input_data['ureaFlagColor'] = 'warning';
                            $input_data['range_code']    = '#FFC107';
                        }
            
                        if ($input_data['urea'] > 40) {
                            $input_data['ureaFlag']      = 'Severe Risk';
                            $input_data['ureaFlagColor'] = 'danger';
                            $input_data['range_code']    = '#FF0000';
                        }
            }
        }

        // if (!empty($input_data['urea'])) {

        //     if ($input_data['urea'] <= 20) {
        //         $input_data['ureaFlag']      = 'Normal';
        //         $input_data['ureaFlagColor'] = 'success';
        //         $input_data['range_code']    = '#008000';
        //     }

        //     if (($input_data['urea'] > 20) && ($input_data['urea'] <= 30)) {
        //         $input_data['ureaFlag']      = 'Moderate';
        //         $input_data['ureaFlagColor'] = 'warning';
        //         $input_data['range_code']    = '#ffc107';
        //     }

        //     if ($input_data['urea'] > 30) {
        //         $input_data['ureaFlag']      = 'Severe';
        //         $input_data['ureaFlagColor'] = 'danger';
        //         $input_data['range_code']    = '#ff0000';
        //     }
        // }

        return $input_data;
    }

    public static function creatinine_flag($input_data, $years, $months, $days, $gender)
    {
        $input_data['creatinineFlag']      = '';
        $input_data['creatinineFlagColor'] = '';
        $input_data['range_code']    = '';
        if (!empty($input_data['creatinine'])) {
            if($years >= 0 && $years <= 1){
                if ($input_data['creatinine'] <= 0.2) {
                            $input_data['creatinineFlag']      = 'Low';
                            $input_data['creatinineFlagColor'] = 'success';
                            $input_data['range_code']    = '#0000ff';
                        }

                        if (($input_data['creatinine'] > 0.2) && ($input_data['creatinine'] <= 0.5)) {
                                    $input_data['creatinineFlag']      = 'Normal ';
                                    $input_data['creatinineFlagColor'] = 'warning';
                                    $input_data['range_code']    = '#008000';
                                }

                                if (($input_data['creatinine'] >= 0.6) && ($input_data['creatinine'] <= 1.0)) {
                                    $input_data['creatinineFlag']      = 'High';
                                    $input_data['creatinineFlagColor'] = 'warning';
                                    $input_data['range_code']    = '#FFC107';
                                }
                    
                                if ($input_data['creatinine'] > 1.0) {
                                    $input_data['creatinineFlag']      = 'Very High';
                                    $input_data['creatinineFlagColor'] = 'danger';
                                    $input_data['range_code']    = '#FF0000';
                                }
            }
            if($years >= 2 && $years <= 5){
                if ($input_data['creatinine'] <= 0.3) {
                            $input_data['creatinineFlag']      = 'Low';
                            $input_data['creatinineFlagColor'] = 'success';
                            $input_data['range_code']    = '#0000ff';
                        }

                        if (($input_data['creatinine'] > 0.3) && ($input_data['creatinine'] <= 0.7)) {
                                    $input_data['creatinineFlag']      = 'Normal ';
                                    $input_data['creatinineFlagColor'] = 'warning';
                                    $input_data['range_code']    = '#008000';
                                }

                                if (($input_data['creatinine'] >= 0.8) && ($input_data['creatinine'] <= 1.2)) {
                                    $input_data['creatinineFlag']      = 'High';
                                    $input_data['creatinineFlagColor'] = 'warning';
                                    $input_data['range_code']    = '#FFC107';
                                }
                    
                                if ($input_data['creatinine'] > 1.2) {
                                    $input_data['creatinineFlag']      = 'Very High';
                                    $input_data['creatinineFlagColor'] = 'danger';
                                    $input_data['range_code']    = '#FF0000';
                                }
            }
            if($years >= 6 && $years <= 19){
                if ($input_data['creatinine'] <= 0.5) {
                            $input_data['creatinineFlag']      = 'Low';
                            $input_data['creatinineFlagColor'] = 'success';
                            $input_data['range_code']    = '#0000ff';
                        }

                        if (($input_data['creatinine'] > 0.5) && ($input_data['creatinine'] <= 1.0)) {
                                    $input_data['creatinineFlag']      = 'Normal';
                                    $input_data['creatinineFlagColor'] = 'warning';
                                    $input_data['range_code']    = '#008000';
                                }

                                if (($input_data['creatinine'] >= 1.1) && ($input_data['creatinine'] <= 1.5)) {
                                    $input_data['creatinineFlag']      = 'High';
                                    $input_data['creatinineFlagColor'] = 'warning';
                                    $input_data['range_code']    = '#FFC107';
                                }
                    
                                if ($input_data['creatinine'] > 1.5) {
                                    $input_data['creatinineFlag']      = 'Very High';
                                    $input_data['creatinineFlagColor'] = 'danger';
                                    $input_data['range_code']    = '#FF0000';
                                }
            }
            if($years >= 20 && $years <= 59){
                if($gender == 'Male'){

                if ($input_data['creatinine'] <= 0.6) {
                            $input_data['creatinineFlag']      = 'Low';
                            $input_data['creatinineFlagColor'] = 'success';
                            $input_data['range_code']    = '#0000ff';
                        }

                        if (($input_data['creatinine'] > 0.6) && ($input_data['creatinine'] <= 1.3)) {
                                    $input_data['creatinineFlag']      = 'Normal ';
                                    $input_data['creatinineFlagColor'] = 'warning';
                                    $input_data['range_code']    = '#008000';
                                }

                                if (($input_data['creatinine'] >= 1.4) && ($input_data['creatinine'] <= 1.8)) {
                                    $input_data['creatinineFlag']      = 'High';
                                    $input_data['creatinineFlagColor'] = 'warning';
                                    $input_data['range_code']    = '#FFC107';
                                }
                    
                                if ($input_data['creatinine'] > 1.8) {
                                    $input_data['creatinineFlag']      = 'Very High';
                                    $input_data['creatinineFlagColor'] = 'danger';
                                    $input_data['range_code']    = '#FF0000';
                                }
                            }
            }
            if($years >= 20 && $years <= 59){
                if($gender == 'Female'){

                if ($input_data['creatinine'] <= 0.5) {
                            $input_data['creatinineFlag']      = 'Low';
                            $input_data['creatinineFlagColor'] = 'success';
                            $input_data['range_code']    = '#0000ff';
                        }

                        if (($input_data['creatinine'] > 0.5) && ($input_data['creatinine'] <= 1.1)) {
                                    $input_data['creatinineFlag']      = 'Normal ';
                                    $input_data['creatinineFlagColor'] = 'warning';
                                    $input_data['range_code']    = '#008000';
                                }

                                if (($input_data['creatinine'] >= 1.2) && ($input_data['creatinine'] <= 1.6)) {
                                    $input_data['creatinineFlag']      = 'High';
                                    $input_data['creatinineFlagColor'] = 'warning';
                                    $input_data['range_code']    = '#FFC107';
                                }
                    
                                if ($input_data['creatinine'] > 1.6) {
                                    $input_data['creatinineFlag']      = 'Very High';
                                    $input_data['creatinineFlagColor'] = 'danger';
                                    $input_data['range_code']    = '#FF0000';
                                }
                            }
            }
            if($years >= 60 ){
                if($gender == 'Male'){

                if ($input_data['creatinine'] <= 0.6) {
                            $input_data['creatinineFlag']      = 'Low';
                            $input_data['creatinineFlagColor'] = 'success';
                            $input_data['range_code']    = '#0000ff';
                        }

                        if (($input_data['creatinine'] > 0.6) && ($input_data['creatinine'] <= 1.4)) {
                                    $input_data['creatinineFlag']      = 'Normal ';
                                    $input_data['creatinineFlagColor'] = 'warning';
                                    $input_data['range_code']    = '#008000';
                                }

                                if (($input_data['creatinine'] >= 1.5) && ($input_data['creatinine'] <= 2.0)) {
                                    $input_data['creatinineFlag']      = 'High';
                                    $input_data['creatinineFlagColor'] = 'warning';
                                    $input_data['range_code']    = '#FFC107';
                                }
                    
                                if ($input_data['creatinine'] > 2.0) {
                                    $input_data['creatinineFlag']      = 'Very High';
                                    $input_data['creatinineFlagColor'] = 'danger';
                                    $input_data['range_code']    = '#FF0000';
                                }
                            }
            }
            if($years >= 60 ){
                if($gender == 'Female'){

                if ($input_data['creatinine'] <= 0.5) {
                            $input_data['creatinineFlag']      = 'Low';
                            $input_data['creatinineFlagColor'] = 'success';
                            $input_data['range_code']    = '#0000ff';
                        }

                        if (($input_data['creatinine'] > 0.5) && ($input_data['creatinine'] <= 1.3)) {
                                    $input_data['creatinineFlag']      = 'Normal ';
                                    $input_data['creatinineFlagColor'] = 'warning';
                                    $input_data['range_code']    = '#008000';
                                }

                                if (($input_data['creatinine'] >= 1.4) && ($input_data['creatinine'] <= 1.8)) {
                                    $input_data['creatinineFlag']      = 'High';
                                    $input_data['creatinineFlagColor'] = 'warning';
                                    $input_data['range_code']    = '#FFC107';
                                }
                    
                                if ($input_data['creatinine'] > 1.8) {
                                    $input_data['creatinineFlag']      = 'Very High';
                                    $input_data['creatinineFlagColor'] = 'danger';
                                    $input_data['range_code']    = '#FF0000';
                                }
                            }
            }
        }

        // if (!empty($input_data['creatinine'])) {

        //     if ($input_data['creatinine'] <= 1.2) {
        //         $input_data['creatinineFlag']      = 'Normal';
        //         $input_data['creatinineFlagColor'] = 'success';
        //         $input_data['range_code']    = '#008000';
        //     }

        //     if (($input_data['creatinine'] > 1.2) && ($input_data['creatinine'] <= 1.5)) {
        //         $input_data['creatinineFlag']      = 'Moderate';
        //         $input_data['creatinineFlagColor'] = 'warning';
        //         $input_data['range_code']    = '#ffc107';
        //     }

        //     if ($input_data['creatinine'] > 1.5) {
        //         $input_data['creatinineFlag']      = 'Severe';
        //         $input_data['creatinineFlagColor'] = 'danger';
        //         $input_data['range_code']    = '#ff0000';
        //     }
        // }

        return $input_data;
    }

    public static function gfr_flag($input_data, $years, $months, $days, $gender)
    {
        $input_data['gfrFlag']      = '';
        $input_data['gfrFlagColor'] = '';
        $input_data['range_code']    = '';

        if (!empty($input_data['gfr'])) {
            if($years >= 0 && $years <= 1){
                if ($input_data['gfr'] < 15) {
                            $input_data['gfrFlag']      = 'Low';
                            $input_data['gfrFlagColor'] = 'warning';
                            $input_data['range_code']    = '#0000ff';
                        }
            
                        if (($input_data['gfr'] >= 50) && ($input_data['gfr'] <= 100)) {
                            $input_data['gfrFlag']      = 'Normal ';
                            $input_data['gfrFlagColor'] = 'success';
                            $input_data['range_code']    = '#008000';
                        }

                        if (($input_data['gfr'] >= 30) && ($input_data['gfr'] <= 49)) {
                            $input_data['gfrFlag']      = 'Moderate Reduction';
                            $input_data['gfrFlagColor'] = 'warning';
                            $input_data['range_code']    = '#FFC107';
                        }
            
                        if (($input_data['gfr'] >= 15) && ($input_data['gfr'] <= 29)) {
                            $input_data['gfrFlag']      = 'Severe Reduction';
                            $input_data['gfrFlagColor'] = 'warning';
                            $input_data['range_code']    = '#FF0000';
                        }
            
                        if ($input_data['gfr'] > 100) {
                            $input_data['gfrFlag']      = 'Elevated GFR';
                            $input_data['gfrFlagColor'] = 'danger';
                            $input_data['range_code']    = '#00FFFF';
                        } 
            }
            if($years >= 2 && $years <= 5){
                if ($input_data['gfr'] <= 15) {
                            $input_data['gfrFlag']      = 'Low';
                            $input_data['gfrFlagColor'] = 'warning';
                            $input_data['range_code']    = '#0000ff';
                        }
            
                        if (($input_data['gfr'] >= 75) && ($input_data['gfr'] <= 140)) {
                            $input_data['gfrFlag']      = 'Normal ';
                            $input_data['gfrFlagColor'] = 'success';
                            $input_data['range_code']    = '#008000';
                        }

                        if (($input_data['gfr'] >= 60) && ($input_data['gfr'] <= 74)) {
                            $input_data['gfrFlag']      = 'Moderate Reduction ';
                            $input_data['gfrFlagColor'] = 'warning';
                            $input_data['range_code']    = '#FFC107';
                        }
            
                        if (($input_data['gfr'] >= 30) && ($input_data['gfr'] <= 59)) {
                            $input_data['gfrFlag']      = 'Severe Reduction';
                            $input_data['gfrFlagColor'] = 'warning';
                            $input_data['range_code']    = '#FF0000';
                        }
            
                        if ($input_data['gfr'] > 140) {
                            $input_data['gfrFlag']      = 'Elevated GFR';
                            $input_data['gfrFlagColor'] = 'danger';
                            $input_data['range_code']    = '#00FFFF';
                        } 
            }
            if($years >= 6 && $years <= 19){
                if ($input_data['gfr'] <= 15) {
                            $input_data['gfrFlag']      = 'Low';
                            $input_data['gfrFlagColor'] = 'warning';
                            $input_data['range_code']    = '#0000ff';
                        }
            
                        if (($input_data['gfr'] >= 90) && ($input_data['gfr'] <= 140)) {
                            $input_data['gfrFlag']      = 'Normal';
                            $input_data['gfrFlagColor'] = 'success';
                            $input_data['range_code']    = '#008000';
                        }

                        if (($input_data['gfr'] >= 60) && ($input_data['gfr'] <= 89)) {
                            $input_data['gfrFlag']      = 'Moderate Reduction ';
                            $input_data['gfrFlagColor'] = 'warning';
                            $input_data['range_code']    = '#FFC107';
                        }
            
                        if (($input_data['gfr'] >= 30) && ($input_data['gfr'] <= 59)) {
                            $input_data['gfrFlag']      = 'Severe Reduction';
                            $input_data['gfrFlagColor'] = 'warning';
                            $input_data['range_code']    = '#FF0000';
                        }
            
                        if ($input_data['gfr'] > 140) {
                            $input_data['gfrFlag']      = 'Elevated GFR';
                            $input_data['gfrFlagColor'] = 'danger';
                            $input_data['range_code']    = '#00FFFF';
                        } 
            }
            if($years >= 20 && $years <= 59){
                if($gender == 'Male'){
                if ($input_data['gfr'] <= 15) {
                            $input_data['gfrFlag']      = 'Low';
                            $input_data['gfrFlagColor'] = 'warning';
                            $input_data['range_code']    = '#0000ff';
                        }
            
                        if (($input_data['gfr'] >= 90) && ($input_data['gfr'] <= 130)) {
                            $input_data['gfrFlag']      = 'Normal ';
                            $input_data['gfrFlagColor'] = 'success';
                            $input_data['range_code']    = '#008000';
                        }

                        if (($input_data['gfr'] >= 60) && ($input_data['gfr'] <= 89)) {
                            $input_data['gfrFlag']      = 'Moderate Reduction ';
                            $input_data['gfrFlagColor'] = 'warning';
                            $input_data['range_code']    = '#FFC107';
                        }
            
                        if (($input_data['gfr'] >= 30) && ($input_data['gfr'] <= 59)) {
                            $input_data['gfrFlag']      = 'Severe Reduction';
                            $input_data['gfrFlagColor'] = 'warning';
                            $input_data['range_code']    = '#FF0000';
                        }
            
                        if ($input_data['gfr'] > 130) {
                            $input_data['gfrFlag']      = 'Elevated GFR';
                            $input_data['gfrFlagColor'] = 'danger';
                            $input_data['range_code']    = '#00FFFF';
                        } 
                    }
            }
            if($years >= 20 && $years <= 59){
                if($gender == 'Female'){
                if ($input_data['gfr'] <= 15) {
                            $input_data['gfrFlag']      = 'Low';
                            $input_data['gfrFlagColor'] = 'warning';
                            $input_data['range_code']    = '#0000ff';
                        }
            
                        if (($input_data['gfr'] >= 90) && ($input_data['gfr'] <= 120)) {
                            $input_data['gfrFlag']      = 'Normal ';
                            $input_data['gfrFlagColor'] = 'success';
                            $input_data['range_code']    = '#008000';
                        }

                        if (($input_data['gfr'] >= 60) && ($input_data['gfr'] <= 89)) {
                            $input_data['gfrFlag']      = 'Moderate Reduction ';
                            $input_data['gfrFlagColor'] = 'warning';
                            $input_data['range_code']    = '#FFC107';
                        }
            
                        if (($input_data['gfr'] >= 30) && ($input_data['gfr'] <= 59)) {
                            $input_data['gfrFlag']      = 'Severe Reduction';
                            $input_data['gfrFlagColor'] = 'warning';
                            $input_data['range_code']    = '#FF0000';
                        }
            
                        if ($input_data['gfr'] > 120) {
                            $input_data['gfrFlag']      = 'Elevated GFR';
                            $input_data['gfrFlagColor'] = 'danger';
                            $input_data['range_code']    = '#00FFFF';
                        } 
                    }
            }
            if($years >60){
                if($gender == 'Male'){
                    if ($input_data['gfr'] < 15) {
                        $input_data['gfrFlag']      = 'Low';
                        $input_data['gfrFlagColor'] = 'warning';
                        $input_data['range_code']    = '#0000ff';
                    }
        
                    if (($input_data['gfr'] >= 60) && ($input_data['gfr'] <= 90)) {
                        $input_data['gfrFlag']      = 'Normal ';
                        $input_data['gfrFlagColor'] = 'success';
                        $input_data['range_code']    = '#008000';
                    }

                    if (($input_data['gfr'] >= 45) && ($input_data['gfr'] <= 59)) {
                        $input_data['gfrFlag']      = 'Moderate Reduction ';
                        $input_data['gfrFlagColor'] = 'warning';
                        $input_data['range_code']    = '#FFC107';
                    }
        
                    if (($input_data['gfr'] >= 15) && ($input_data['gfr'] <= 44)) {
                        $input_data['gfrFlag']      = 'Severe Reduction';
                        $input_data['gfrFlagColor'] = 'warning';
                        $input_data['range_code']    = '#FF0000';
                    }
        
                    if ($input_data['gfr'] > 90) {
                        $input_data['gfrFlag']      = 'Elevated GFR';
                        $input_data['gfrFlagColor'] = 'danger';
                        $input_data['range_code']    = '#00FFFF';
                    } 
                }
                if($gender == 'Female'){
                    if ($input_data['gfr'] < 15) {
                        $input_data['gfrFlag']      = 'Low';
                        $input_data['gfrFlagColor'] = 'warning';
                        $input_data['range_code']    = '#0000ff';
                    }
        
                    if (($input_data['gfr'] >= 60) && ($input_data['gfr'] <= 80)) {
                        $input_data['gfrFlag']      = 'Normal ';
                        $input_data['gfrFlagColor'] = 'success';
                        $input_data['range_code']    = '#008000';
                    }

                    if (($input_data['gfr'] >= 45) && ($input_data['gfr'] <= 59)) {
                        $input_data['gfrFlag']      = 'Moderate Reduction ';
                        $input_data['gfrFlagColor'] = 'warning';
                        $input_data['range_code']    = '#FFC107';
                    }
        
                    if (($input_data['gfr'] >= 15) && ($input_data['gfr'] <= 44)) {
                        $input_data['gfrFlag']      = 'Severe Reduction';
                        $input_data['gfrFlagColor'] = 'warning';
                        $input_data['range_code']    = '#FF0000';
                    }
        
                    if ($input_data['gfr'] > 80) {
                        $input_data['gfrFlag']      = 'Elevated GFR';
                        $input_data['gfrFlagColor'] = 'danger';
                        $input_data['range_code']    = '#00FFFF';
                    } 
                }
            }
        }

        // if (!empty($input_data['gfr'])) {

        //     if ($input_data['gfr'] >= 90) {
        //         $input_data['gfrFlag']      = 'Normal';
        //         $input_data['gfrFlagColor'] = 'success';
        //         $input_data['range_code']    = '#008000';
        //     }

        //     if (($input_data['gfr'] >= 60) && ($input_data['gfr'] < 90)) {
        //         $input_data['gfrFlag']      = 'Moderate';
        //         $input_data['gfrFlagColor'] = 'warning';
        //         $input_data['range_code']    = '#ffc107';
        //     }

        //     if ($input_data['gfr'] < 60) {
        //         $input_data['gfrFlag']      = 'Severe';
        //         $input_data['gfrFlagColor'] = 'danger';
        //         $input_data['range_code']    = '#ff0000';
        //     }
        // }

        return $input_data;
    }


}
