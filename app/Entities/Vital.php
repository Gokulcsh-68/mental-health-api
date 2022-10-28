<?php

namespace App\Entities;
use App\Entities\User;
use App\Services\BluetoothPeripheralService;
use Carbon\Carbon;
use DB;
use Illuminate\Support\Facades\Log;

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

        if(isset($data['details']['date'])){
            $data['details']['date'] = date('Y-m-d',strtotime($data['details']['date']));
        }

        if($data['slug'] == 'bmi'){
            $data['details'] += self::bmi_flag($data['details']['bmi']);
        }

        if($data['slug'] == 'temperature'){
            $dateOfBirth = user::Where('id',$data['user_id'])->first(['dob','gender']);
            $years = Carbon::parse($dateOfBirth->dob)->diff(Carbon::now())->format('%y');
            $data['details'] += self::temp_flag($data['details'], $years, $dateOfBirth->dob);
        }

        if($data['slug'] == 'blood-sugar'){
            $data['details'] += self::blood_sugar_flag($data['details']);
        }

        if($data['slug'] == 'blood-pressure'){
            $data['details'] += self::blood_pressure_flag($data['details']);
        }

        if($data['slug'] == 'lipid-profile'){
            $data['details'] += self::cholesterol_flag($data['details']);
        }

        if($data['slug'] == 'spO2'){
            $data['details'] += self::spo2_flag($data['details']);
        }

        if($data['slug'] == 'urine'){
            $data['details'] += self::urine_flag($data['details']);
        }

        if($data['slug'] == 'respiration'){
            $dateOfBirth = user::Where('id',$data['user_id'])->first(['dob','gender']);
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
           }
        }


        if($data['slug'] == 'keytone'){
            $data['details'] += self::keytone_flag($data['details']);
        }

        if($data['slug'] == 'hemoglobin'){
            $gender = User::Where('id', $data['user_id'])->value('gender');
            $data['details'] += self::hemoglobin_flag($data['details'],$gender);
        }

        if($data['slug'] == 'hct'){
            $gender = User::Where('id', $data['user_id'])->value('gender');
            $data['details'] += self::hct_flag($data['details'],$gender);
        }

        if($data['slug'] == 'uric_acid'){
            $gender = User::Where('id', $data['user_id'])->value('gender');
            $data['details'] += self::uric_acid_flag($data['details'],$gender);
        }

        if($data['slug'] == 'spirometer'){
            $data['details'] += self::spirometer_flag($data['details']);
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
            $data['details'] += self::bmi_flag($data['details']['bmi']);
        }


        if($data['slug'] == 'temperature'){
            unset($data['details']['temperatureFlag'], $data['details']['temperatureFlagColor'], $data['details']['range_code']);

            $dateOfBirth = user::Where('id',$data['user_id'])->first(['dob','gender']);
            $years = Carbon::parse($dateOfBirth->dob)->diff(Carbon::now())->format('%y');
            $data['details'] += self::temp_flag($data['details'], $years, $dateOfBirth->dob);
        }

        if($data['slug'] == 'blood-sugar'){
            unset($data['details']['bsFlag'], $data['details']['bsFlagColor'], $data['details']['range_code']);

            $data['details'] += self::blood_sugar_flag($data['details']);
        }


        if($data['slug'] == 'blood-pressure'){
            unset($data['details']['bpFlag'], $data['details']['bpFlagColor'], $data['details']['range_code']);

            $data['details'] += self::blood_pressure_flag($data['details']);
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

            $data['details'] += self::cholesterol_flag($data['details']);
        }

        if($data['slug'] == 'heart-rate'){
            unset($data['details']['heartRateFlag'],
                $data['details']['heartRateFlagColor'],
                $data['details']['range_code']);

            $dateOfBirth = user::Where('id',$data['user_id'])->value('dob');

            $years = Carbon::parse($dateOfBirth)->diff(Carbon::now())->format('%y');
            $months = Carbon::parse($dateOfBirth)->diff(Carbon::now())->format('%m');
            $days = Carbon::parse($dateOfBirth)->diff(Carbon::now())->format('%d');
           
           $data['details'] += self::heart_rate_flag($data['details'], $years, $months, $days);
        }

        if($data['slug'] == 'spO2'){
            unset($data['details']['spo2Flag'],
                $data['details']['spo2FlagColor'],
                $data['details']['range_code']);

            $data['details'] += self::spo2_flag($data['details']);
        }

        if($data['slug'] == 'respiration'){
            unset($data['details']['respirationFlag'],
                $data['details']['respirationFlagColor'],
                $data['details']['range_code']);

            $dateOfBirth = user::Where('id',$data['user_id'])->first(['dob','gender']);
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
            $data['details'] += self::hemoglobin_flag($data['details'],$gender);
        }

        if($data['slug'] == 'hct'){
            unset($data['details']['hctFlag'], $data['details']['hctFlagColor'], $data['details']['range_code']);
            $gender = User::Where('id', $data['user_id'])->value('gender');
            $data['details'] += self::hct_flag($data['details'],$gender);
        }

        if($data['slug'] == 'uric_acid'){
            unset($data['details']['uricFlag'], $data['details']['uricFlagColor'], $data['details']['range_code']);
            $gender = User::Where('id', $data['user_id'])->value('gender');
            $data['details'] += self::uric_acid_flag($data['details'],$gender);
        }

        if($data['slug'] == 'spirometer'){
            unset($data['details']['spirometerFlag'], $data['details']['spirometerFlagColor'], $data['details']['range_code']);
            $data['details'] += self::spirometer_flag($data['details']);
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

        
        if ($request->get('consult_id') || $request->get('consult_id') == '-1') {
            $model->where('vitals.consult_id', $request->get('consult_id') == '-1'? null: $request->get('consult_id'));
        }

        if($request->get('from') && $request->get('to')){

            $from = date('Y-m-d',strtotime($request->get('from')));
                $to = date('Y-m-d',strtotime($request->get('to')));
            $model->whereBetween('details->date', [$from,$to]);
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


    public static function bmi_flag($bmi_value)
    {
        $input_data['bmiFlag']      = '';
        $input_data['bmiFlagColor'] = '';
        $input_data['range_code'] = '';

        if (!empty($bmi_value)) {
            if ($bmi_value < 18.5) {
                $input_data['bmiFlag']      = 'Below normal weight';
                $input_data['bmiFlagColor'] = 'primary';
                $input_data['range_code']   = '#0000ff';
            }
            
            if (($bmi_value >= 18.5) && ($bmi_value < 25)) {
                $input_data['bmiFlag']      = 'Normal weight';
                $input_data['bmiFlagColor'] = 'success';
                $input_data['range_code']   = '#008000';
            }
            
            if (($bmi_value >= 25) && ($bmi_value < 30)) {
                $input_data['bmiFlag']      = 'Overweight';
                $input_data['bmiFlagColor'] = 'danger';
                $input_data['range_code']   = '#ff0000';
            }
            
            if (($bmi_value >= 30) && ($bmi_value < 35)) {
                $input_data['bmiFlag']      = 'Class I Obesity';
                $input_data['bmiFlagColor'] = 'danger';
                $input_data['range_code']   = '#ff0000';
            }
            
            if (($bmi_value >= 35) && ($bmi_value < 40)) {
                $input_data['bmiFlag']      = 'Class II Obesity';
                $input_data['bmiFlagColor'] = 'danger';
                $input_data['range_code']   = '#ff0000';
            }

            if ($bmi_value >= 40) {
                $input_data['bmiFlag']      = 'Class III Obesity';
                $input_data['bmiFlagColor'] = 'danger';
                $input_data['range_code']   = '#ff0000';
            }
        }
        
        return $input_data;
    }
    
   
    
    public static function temp_flag($input_data, $years, $dob)
    {
        if (!empty($input_data['unit']) && !empty($input_data['temperature'])) {
            $input_data['temperatureFlag']      = '';
            $input_data['temperatureFlagColor'] = '';
            $input_data['range_code'] = '';
            
            if(empty($dob) || $dob == '0000-00-00'){
                $years = 20;
            }

            switch ($input_data['unit']) {
                case 'Fahrenheit':
                    
                    if ($input_data['temperature'] <= 97.6) {
                        $input_data['temperatureFlag']      = 'Hypothermia';
                        $input_data['temperatureFlagColor'] = 'primary';
                        $input_data['range_code']    = '#0000ff';
                    }
                    
                    if (($input_data['temperature'] >= 98) && ($input_data['temperature'] <= 100.9)) {
                        $input_data['temperatureFlag']      = 'Fever / Hyperthermia';
                        $input_data['temperatureFlagColor'] = 'danger';
                        $input_data['range_code']    = '#ff0000';
                    }
                    
                    if (($input_data['temperature'] > 100.9) && ($input_data['temperature'] <= 106.7)) {
                        $input_data['temperatureFlag']      = 'Very High Fever';
                        $input_data['temperatureFlagColor'] = 'danger';
                        $input_data['range_code']    = '#ff0000';
                    }

                    if ($input_data['temperature'] > 106.7) {
                        $input_data['temperatureFlag']      = 'Hyperpyrexia';
                        $input_data['temperatureFlagColor'] = 'danger';
                        $input_data['range_code']    = '#ff0000';
                    }


                    if($years < 1){                    
                        if (($input_data['temperature'] >= 95.8) && ($input_data['temperature'] <= 99.3)) {
                            $input_data['temperatureFlag']      = 'Normal';
                            $input_data['temperatureFlagColor'] = 'success';
                            $input_data['range_code']    = '#008000';
                        }
                    }

                    if($years >= 1 && $years <= 17){                    
                        if (($input_data['temperature'] >= 97.6) && ($input_data['temperature'] <= 99.3)) {
                            $input_data['temperatureFlag']      = 'Normal';
                            $input_data['temperatureFlagColor'] = 'success';
                            $input_data['range_code']    = '#008000';
                        }
                    }

                    if($years >= 18 && $years <= 64){                    
                        if (($input_data['temperature'] >= 96) && ($input_data['temperature'] <= 98)) {
                            $input_data['temperatureFlag']      = 'Normal';
                            $input_data['temperatureFlagColor'] = 'success';
                            $input_data['range_code']    = '#008000';
                        }
                    }

                    if($years >= 65){                    
                        if (($input_data['temperature'] >= 93) && ($input_data['temperature'] <= 98.6)) {
                            $input_data['temperatureFlag']      = 'Normal';
                            $input_data['temperatureFlagColor'] = 'success';
                            $input_data['range_code']    = '#008000';
                        }
                    }

                    break;
                
                case 'Celsius':
                    if ($input_data['temperature'] <= 36.7) {
                        $input_data['temperatureFlag']      = 'Hypothermia';
                        $input_data['temperatureFlagColor'] = 'primary';
                        $input_data['range_code']    = '#0000ff';
                    }

                    if (($input_data['temperature'] >= 36.7) && ($input_data['temperature'] <= 41.5)) {
                        $input_data['temperatureFlag']      = 'Fever / Hyperthermia';
                        $input_data['temperatureFlagColor'] = 'danger';
                        $input_data['range_code']    = '#ff0000';
                    }
                    
                    if ($input_data['temperature'] > 41.5) {
                        $input_data['temperatureFlag']      = 'Hyperpyrexia';
                        $input_data['temperatureFlagColor'] = 'danger';
                        $input_data['range_code']    = '#ff0000';
                    }



                    if($years < 1){                    
                        if (($input_data['temperature'] >= 36.7) && ($input_data['temperature'] <= 37.3)) {
                            $input_data['temperatureFlag']      = 'Normal';
                            $input_data['temperatureFlagColor'] = 'success';
                            $input_data['range_code']    = '#008000';
                        }
                    }

                    if($years >= 1 && $years <= 17){                    
                        if (($input_data['temperature'] >= 36.4) && ($input_data['temperature'] <= 37.4)) {
                            $input_data['temperatureFlag']      = 'Normal';
                            $input_data['temperatureFlagColor'] = 'success';
                            $input_data['range_code']    = '#008000';
                        }
                    }

                    if($years >= 18 && $years <= 64){                    
                        if (($input_data['temperature'] >= 35.6) && ($input_data['temperature'] <= 36.7)) {
                            $input_data['temperatureFlag']      = 'Normal';
                            $input_data['temperatureFlagColor'] = 'success';
                            $input_data['range_code']    = '#008000';
                        }
                    }

                    if($years >= 65){                    
                        if (($input_data['temperature'] >= 33.9) && ($input_data['temperature'] <= 37)) {
                            $input_data['temperatureFlag']      = 'Normal';
                            $input_data['temperatureFlagColor'] = 'success';
                            $input_data['range_code']    = '#008000';
                        }
                    }

                    break;
            }
            
        }
        
        
        return $input_data;
    }
    
   
    
    public static function blood_sugar_flag($input_data)
    {
        $input_data['bsFlag']      = '';
        $input_data['bsFlagColor'] = '';
        $input_data['range_code']  = '';
        if (!empty($input_data['blood_sugar'])) {
            
        if ($input_data['unit'] == 'mg/dL') {


            if ($input_data['blood_sugar'] >= 20 && $input_data['blood_sugar'] <= 70) {
                $input_data['bsFlag']      = 'Low';
                $input_data['bsFlagColor'] = 'danger';
                $input_data['range_code']  = '#ff0000';
            }


            if ($input_data['blood_sugar'] >= 71 && $input_data['blood_sugar'] <= 127) {
                $input_data['bsFlag']      = 'Normal';
                $input_data['bsFlagColor'] = 'success';
                $input_data['range_code']  = '#008000';
            }

            if ($input_data['blood_sugar'] >= 128 && $input_data['blood_sugar'] <= 180) {
                $input_data['bsFlag']      = 'Border Line';
                $input_data['bsFlagColor'] = 'warning';
                $input_data['range_code']  = '#ffc107';
            }


            if ($input_data['blood_sugar'] >= 181 && $input_data['blood_sugar'] <= 248) {
                $input_data['bsFlag']      = 'High';
                $input_data['bsFlagColor'] = 'danger';
                $input_data['range_code']  = '#ff0000';
            }

            if ($input_data['blood_sugar'] >= 249) {
                $input_data['bsFlag']      = 'Dangerous';
                $input_data['bsFlagColor'] = 'danger';
                $input_data['range_code']  = '#ff0000';
            }

            if ($input_data['blood_sugar'] < 20) {
                $input_data['bsFlag']      = 'Dangerously low';
                $input_data['bsFlagColor'] = 'danger';
                $input_data['range_code']  = '#ff0000';
            }


        }

        if ($input_data['unit'] == 'mmol/L') {


            if ($input_data['blood_sugar'] >= 1.1 && $input_data['blood_sugar'] <= 3.9) {
                $input_data['bsFlag']      = 'Low';
                $input_data['bsFlagColor'] = 'danger';
                $input_data['range_code']  = '#ff0000';
            }


            if ($input_data['blood_sugar'] >= 4.0 && $input_data['blood_sugar'] <= 7.0) {
                $input_data['bsFlag']      = 'Normal';
                $input_data['bsFlagColor'] = 'success';
                $input_data['range_code']  = '#008000';
            }

            if ($input_data['blood_sugar'] >= 7.1 && $input_data['blood_sugar'] <= 10) {
                $input_data['bsFlag']      = 'Border Line';
                $input_data['bsFlagColor'] = 'warning';
                $input_data['range_code']  = '#ffc107';
            }


            if ($input_data['blood_sugar'] >= 10.1 && $input_data['blood_sugar'] <= 13.8) {
                $input_data['bsFlag']      = 'High';
                $input_data['bsFlagColor'] = 'danger';
                $input_data['range_code']  = '#ff0000';
            }

            if ($input_data['blood_sugar'] >= 13.9) {
                $input_data['bsFlag']      = 'Dangerous';
                $input_data['bsFlagColor'] = 'danger';
                $input_data['range_code']  = '#ff0000';
            }

            if ($input_data['blood_sugar'] < 1.1) {
                $input_data['bsFlag']      = 'Dangerously low';
                $input_data['bsFlagColor'] = 'danger';
                $input_data['range_code']  = '#ff0000';
            }


        }


        }
        
        return $input_data;
    }
    
    
    
    public static function spo2_flag($input_data)
    {
        $input_data['spo2Flag']      = '';
        $input_data['spo2FlagColor'] = '';
        $input_data['range_code']    = '';
        if (!empty($input_data['spo2'])) {
            
            if ($input_data['spo2'] < 75) {
                $input_data['spo2Flag']      = 'Severe Hypoxemia';
                $input_data['spo2FlagColor'] = 'danger';
                $input_data['range_code']    = '#ff0000';
            }
            
            if (($input_data['spo2'] >= 75) && ($input_data['spo2'] <= 89)) {
                $input_data['spo2Flag']      = 'Moderate Hypoxemia';
                $input_data['spo2FlagColor'] = 'warning';
                $input_data['range_code']    = '#ffc107';
            }
            
            if (($input_data['spo2'] >= 90) && ($input_data['spo2'] <= 94)) {
                $input_data['spo2Flag']      = 'Mild Hypoxemia';
                $input_data['spo2FlagColor'] = 'primary';
                $input_data['range_code']    = '#0000ff';
            }
            
            if ($input_data['spo2'] >= 95) {
                $input_data['spo2Flag']      = 'Normal';
                $input_data['spo2FlagColor'] = 'success';
                $input_data['range_code']    = '#008000';
            }
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
                    $input_data['respirationFlag']      = 'Low';
                    $input_data['respirationFlagColor'] = 'warning';
                    $input_data['range_code']    = '#ffc107';
                }
                
                if (($input_data['respiration'] >= 30) && ($input_data['respiration'] <= 40)) {
                    $input_data['respirationFlag']      = 'Normal';
                    $input_data['respirationFlagColor'] = 'success';
                    $input_data['range_code']    = '#008000';
                }
                
                if ($input_data['respiration'] > 40) {
                    $input_data['respirationFlag']      = 'High';
                    $input_data['respirationFlagColor'] = 'danger';
                    $input_data['range_code']    = '#ff0000';
                }
            }
            
            if($years >= 2 && $years <= 5){
                if ($input_data['respiration'] < 20) {
                    $input_data['respirationFlag']      = 'Low';
                    $input_data['respirationFlagColor'] = 'warning';
                    $input_data['range_code']    = '#ffc107';
                }
                
                if (($input_data['respiration'] >= 20) && ($input_data['respiration'] <= 40)) {
                    $input_data['respirationFlag']      = 'Normal';
                    $input_data['respirationFlagColor'] = 'success';
                    $input_data['range_code']    = '#008000';
                }
                
                if ($input_data['respiration'] > 40) {
                    $input_data['respirationFlag']      = 'High';
                    $input_data['respirationFlagColor'] = 'danger';
                    $input_data['range_code']    = '#ff0000';
                }
            }
            
            if($years >= 6 && $years <= 10){
                if ($input_data['respiration'] < 15) {
                    $input_data['respirationFlag']      = 'Low';
                    $input_data['respirationFlagColor'] = 'warning';
                    $input_data['range_code']    = '#ffc107';
                }
                
                if (($input_data['respiration'] >= 15) && ($input_data['respiration'] <= 25)) {
                    $input_data['respirationFlag']      = 'Normal';
                    $input_data['respirationFlagColor'] = 'success';
                    $input_data['range_code']    = '#008000';
                }
                
                if ($input_data['respiration'] > 25) {
                    $input_data['respirationFlag']      = 'High';
                    $input_data['respirationFlagColor'] = 'danger';
                    $input_data['range_code']    = '#ff0000';
                }
            }
            
            if(($years >= 11 && $years <= 18) || ($years > 70)){
                if ($input_data['respiration'] < 15) {
                    $input_data['respirationFlag']      = 'Low';
                    $input_data['respirationFlagColor'] = 'warning';
                    $input_data['range_code']    = '#ffc107';
                }
                
                if (($input_data['respiration'] >= 15) && ($input_data['respiration'] <= 20)) {
                    $input_data['respirationFlag']      = 'Normal';
                    $input_data['respirationFlagColor'] = 'success';
                    $input_data['range_code']    = '#008000';
                }
                
                if ($input_data['respiration'] > 20) {
                    $input_data['respirationFlag']      = 'High';
                    $input_data['respirationFlagColor'] = 'danger';
                    $input_data['range_code']    = '#ff0000';
                }
            }
            
            if($years >= 18 && $years <= 70){
                if ($input_data['respiration'] < 12) {
                    $input_data['respirationFlag']      = 'Low';
                    $input_data['respirationFlagColor'] = 'warning';
                    $input_data['range_code']    = '#ffc107';
                }
                
                if (($input_data['respiration'] >= 12) && ($input_data['respiration'] <= 20)) {
                    $input_data['respirationFlag']      = 'Normal';
                    $input_data['respirationFlagColor'] = 'success';
                    $input_data['range_code']    = '#008000';
                }
                
                if ($input_data['respiration'] > 20) {
                    $input_data['respirationFlag']      = 'High';
                    $input_data['respirationFlagColor'] = 'danger';
                    $input_data['range_code']    = '#ff0000';
                }
            }
        }

        return $input_data;
    }
    
   
    
    public static function urine_flag($input_data)
    {
        $input_data['leukocytes_message']    = '';
        $input_data['leukocytes_flag']       = '';
        $input_data['leukocytes_range_code'] = '';

        $input_data['protein_message']               = '';
        $input_data['protein_flag']          = '';
        $input_data['protein_range_code']    = '';

        $input_data['rbc_message']              = '';
        $input_data['rbc_flag']         = '';
        $input_data['rbc_range_code']   = '';

        $input_data['value']              = '';
        $input_data['value_flag']         = '';
        $input_data['value_range_code']   = '';

        $input_data['sugar_message']    = '';
        $input_data['sugar_flag']       = '';
        $input_data['sugar_range_code'] = '';
        
        if (!empty($input_data['leukocytes'])) {
            
            switch ($input_data['leukocytes']) {
                case '+':
                    $input_data['leukocytes_message']            = 'Small';
                    $input_data['leukocytes_flag']       = 'success';
                    $input_data['leukocytes_range_code'] = '#008000';
                    break;
                case '++':
                    $input_data['leukocytes_message']            = 'Moderate';
                    $input_data['leukocytes_flag']       = 'warning';
                    $input_data['leukocytes_range_code'] = '#ffc107';
                    break;
                case '+++':
                    $input_data['leukocytes_message']            = 'Large';
                    $input_data['leukocytes_flag']       = 'danger';
                    $input_data['leukocytes_range_code'] = '#ff0000';
                    break;
            }
        }
        
        if (!empty($input_data['protein'])) {
            
            switch ($input_data['protein']) {
                case '+':
                    $input_data['protein_message']    = 'Small';
                    $input_data['protein_flag']       = 'success';
                    $input_data['protein_range_code'] = '#008000';
                    break;
                case '++':
                    $input_data['protein_message']            = 'Moderate';
                    $input_data['protein_flag']       = 'warning';
                    $input_data['protein_range_code'] = '#ffc107';
                    break;
                case '+++':
                    $input_data['protein_message']            = 'Large';
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
                    $input_data['value']            = 'Acidic';
                    $input_data['value_flag']       = 'danger';
                    $input_data['value_range_code'] = '#ff0000';
                }
                
                if (($input_data['urine'] >= 6.5) && ($input_data['urine'] < 7)) {
                    $input_data['value']            = 'Moderate';
                    $input_data['value_flag']       = 'warning';
                    $input_data['value_range_code'] = '#ffc107';
                }


                if (($input_data['urine'] >= 7) && ($input_data['urine'] < 8)) {
                    $input_data['value']            = 'Optimal';
                    $input_data['value_flag']       = 'success';
                    $input_data['value_range_code'] = '#008000';
                }

                if ($input_data['urine'] >= 8) {
                    $input_data['value']            = 'Too Alkaline';
                    $input_data['value_flag']       = 'danger';
                    $input_data['value_range_code'] = '#ff0000';
                }
                
            }
        }

        return $input_data;
    }
    
    
    public static function blood_pressure_flag($input_data)
    {
       $input_data['bpFlag']      = 'LOW BLOOD PRESSURE';
        $input_data['bpFlagColor'] = 'danger';
        $input_data['range_code']  = '#ff0000';
        if (!empty($input_data['systolic']) && !empty($input_data['diastolic'])) {
            if ((($input_data['systolic'] >= 90) && ($input_data['systolic'] <= 120)) && (($input_data['diastolic'] >= 60) && ($input_data['diastolic'] <= 80))) {
                $input_data['bpFlag']      = 'NORMAL';
                $input_data['bpFlagColor'] = 'success';
                $input_data['range_code']  = '#008000';
            }
            
            if ((($input_data['systolic'] > 120) && ($input_data['systolic'] <= 129)) && ($input_data['diastolic'] < 80)) {
                $input_data['bpFlag']      = 'Elevated';
                $input_data['bpFlagColor'] = 'success';
                $input_data['range_code']  = '#008000';
            }
            
            if ((($input_data['systolic'] >= 130) && ($input_data['systolic'] <= 139)) || (($input_data['diastolic'] >= 80) && ($input_data['diastolic'] <= 89))) {
                $input_data['bpFlag']      = 'HIGH BLOOD PRESSURE(HYPERTENSION) STAGE 1';
                $input_data['bpFlagColor'] = 'danger';
                $input_data['range_code']  = '#ff0000';
            }
            
            if (($input_data['systolic'] >= 140) || ($input_data['diastolic'] >= 90)) {
                $input_data['bpFlag']      = 'HIGH BLOOD PRESSURE(HYPERTENSION) STAGE 2';
                $input_data['bpFlagColor'] = 'danger';
                $input_data['range_code']  = '#ff0000';
            }
            
            if (($input_data['systolic'] >= 180) || ($input_data['diastolic'] >= 120)) {
                $input_data['bpFlag']      = 'HYPERTENSIVE CRISIS(consult your doctor immediately)';
                $input_data['bpFlagColor'] = 'danger';
                $input_data['range_code']  = '#ff0000';
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
            
            if ($years <= 12) {
                
                if (($years >= 1) && ($years <= 2)) {
                    
                    if (($input_data['heart'] < 98)) {
                        $input_data['heartRateFlag']      = 'Low';
                        $input_data['heartRateFlagColor'] = 'primary';
                        $input_data['range_code']         = '#0000ff';
                    }
                    
                    if (($input_data['heart'] >= 98) && ($input_data['heart'] <= 140)) {
                        $input_data['heartRateFlag']      = 'Toddler';
                        $input_data['heartRateFlagColor'] = 'success';
                        $input_data['range_code']         = '#008000';
                    }
                    
                    if (($input_data['heart'] > 140)) {
                        $input_data['heartRateFlag']      = 'High';
                        $input_data['heartRateFlagColor'] = 'danger';
                        $input_data['range_code']         = '#ff0000';
                    }
                }
                
                if (($years >= 3) && ($years <= 5)) {
                    if (($input_data['heart'] < 80)) {
                        $input_data['heartRateFlag']      = 'Low';
                        $input_data['heartRateFlagColor'] = 'primary';
                        $input_data['range_code']         = '#0000ff';
                    }
                    
                    if (($input_data['heart'] >= 80) && ($input_data['heart'] <= 120)) {
                        $input_data['heartRateFlag']      = 'Pre-School';
                        $input_data['heartRateFlagColor'] = 'success';
                        $input_data['range_code']         = '#008000';
                        // return "Pre-School"; // green 90 - 140
                    }
                    
                    if (($input_data['heart'] > 120)) {
                        $input_data['heartRateFlag']      = 'High';
                        $input_data['heartRateFlagColor'] = 'danger';
                        $input_data['range_code']         = '#ff0000';
                    }
                }
                
                if (($years >= 6) && ($years <= 11)) {
                    if (($input_data['heart'] < 75)) {
                        $input_data['heartRateFlag']      = 'Low';
                        $input_data['heartRateFlagColor'] = 'primary';
                        $input_data['range_code']         = '#0000ff';
                    }
                    
                    if (($input_data['heart'] >= 75) && ($input_data['heart'] <= 118)) {
                        $input_data['heartRateFlag']      = 'Normal';
                        $input_data['heartRateFlagColor'] = 'success';
                        $input_data['range_code']         = '#008000';
                        // return "Normal"; // green
                    }
                    
                    if (($input_data['heart'] > 118)) {
                        $input_data['heartRateFlag']      = 'High';
                        $input_data['heartRateFlagColor'] = 'danger';
                        $input_data['range_code']         = '#ff0000';
                    }
                }
                
                if (($years == 12)) {
                    if (($input_data['heart'] < 60)) {
                        $input_data['heartRateFlag']      = 'Low';
                        $input_data['heartRateFlagColor'] = 'primary';
                        $input_data['range_code']         = '#0000ff';
                    }
                    
                    if (($input_data['heart'] >= 60) && ($input_data['heart'] <= 100)) {
                        $input_data['heartRateFlag']      = 'Normal';
                        $input_data['heartRateFlagColor'] = 'success';
                        $input_data['range_code']         = '#008000';
                    }
                    
                    if (($input_data['heart'] > 100)) {
                        $input_data['heartRateFlag']      = 'High';
                        $input_data['heartRateFlagColor'] = 'danger';
                        $input_data['range_code']         = '#ff0000';
                    }
                }
            }

            if ($years > 12) {

                    if (($input_data['heart'] < 60)) {
                        $input_data['heartRateFlag']      = 'Low';
                        $input_data['heartRateFlagColor'] = 'primary';
                        $input_data['range_code']         = '#0000ff';
                    }
                    
                    if (($input_data['heart'] >= 60) && ($input_data['heart'] <= 100)) {
                        $input_data['heartRateFlag']      = 'Normal';
                        $input_data['heartRateFlagColor'] = 'success';
                        $input_data['range_code']         = '#008000';
                    }
                    
                    if (($input_data['heart'] > 100)) {
                        $input_data['heartRateFlag']      = 'High';
                        $input_data['heartRateFlagColor'] = 'danger';
                        $input_data['range_code']         = '#ff0000';
                    }

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
             
            if ($years == 0 && $months > 0) {
                if (($months >= 1) && ($months < 12)) {
                    if (($input_data['heart'] < 100)) {
                        $input_data['heartRateFlag']      = 'Low';
                        $input_data['heartRateFlagColor'] = 'primary';
                        $input_data['range_code']         = '#0000ff';
                    }
                    
                    if (($input_data['heart'] >= 100) && ($input_data['heart'] <= 190)) {
                        $input_data['heartRateFlag']      = 'Low';
                        $input_data['heartRateFlagColor'] = 'primary';
                        $input_data['range_code']         = '#0000ff';
                    }
                    
                    if (($input_data['heart'] > 190)) {
                        $input_data['heartRateFlag']      = 'High';
                        $input_data['heartRateFlagColor'] = 'danger';
                        $input_data['range_code']         = '#ff0000';
                    }
                }
            }
            
            if ($years == 0 && $months == 0) {
                if ($days <= 28) {
                    if (($input_data['heart'] < 100)) {
                        $input_data['heartRateFlag']      = 'Low';
                        $input_data['heartRateFlagColor'] = 'primary';
                        $input_data['range_code']         = '#0000ff';
                    }
                    
                    if (($input_data['heart'] >= 100) && ($input_data['heart'] <= 205)) {
                        $input_data['heartRateFlag']      = 'Neonate';
                        $input_data['heartRateFlagColor'] = 'success';
                        $input_data['range_code']         = '#008000';
                    }
                    
                    if (($input_data['heart'] > 205)) {
                        $input_data['heartRateFlag']      = 'High';
                        $input_data['heartRateFlagColor'] = 'danger';
                        $input_data['range_code']         = '#ff0000';
                    }
                }
            }
        }
        
        return $input_data;
    }
        
    
    public static function cholesterol_flag($input_data)
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
        
        
        if (!empty($input_data['total']) && !empty($input_data['total_unit'])) {
            
            if ($input_data['total_unit'] == 'mg/dL') {
                
                if ($input_data['total'] < 200) {
                    $input_data['total_message'] = 'Optimal'; // green
                }

                if (($input_data['total'] >= 200) && ($input_data['total'] <= 239)) {
                    $input_data['total_message'] = 'Intermediate'; // green
                }

                if ($input_data['total'] > 239) {
                    $input_data['total_message'] = 'High'; // red
                }
            }
            
            if ($input_data['total_unit'] == 'mmol/L') {
                
                if ($input_data['total'] < 5.3) {
                    $input_data['total_message'] = 'Optimal';
                }

                if (($input_data['total'] >= 5.3) && ($input_data['total'] <= 6.2)) {
                    $input_data['total_message'] = 'Intermediate';
                }

                if ($input_data['total'] > 6.2) {
                    $input_data['total_message'] = 'High';
                }
            }
            
            switch ($input_data['total_message']) {
                case 'Optimal':
                    $input_data['total_message_flag'] = 'success';
                    $input_data['total_range_code']   = '#008000';
                break;
                case 'Intermediate':
                    $input_data['total_message_flag'] = 'success';
                    $input_data['total_range_code']   = '#008000';
                break;
                case 'High':
                    $input_data['total_message_flag'] = 'danger';
                    $input_data['total_range_code']   = '#ff0000';
                break;
            }
        }


        
        if (!empty($input_data['vldl']) && !empty($input_data['vldl_unit'])) {
            
            if ($input_data['vldl_unit'] == 'mg/dL') {
                
                if ($input_data['vldl'] <= 30) {
                    $input_data['vldl_message'] = 'Optimal';
                }


                if ($input_data['vldl'] > 30) {
                    $input_data['vldl_message'] = 'High';
                }
            }
            
            if ($input_data['vldl_unit'] == 'mmol/L') {
                
                if ($input_data['vldl'] <= 0.76) {
                    $input_data['vldl_message'] = 'Optimal';
                }

                if ($input_data['vldl'] > 0.76) {
                    $input_data['vldl_message'] = 'High';
                }
            }
            
            switch ($input_data['vldl_message']) {
                case 'Optimal':
                    $input_data['vldl_message_flag'] = 'success';
                    $input_data['vldl_range_code']   = '#008000';
                    break;
                case 'Intermediate':
                    $input_data['vldl_message_flag'] = 'success';
                    $input_data['vldl_range_code']   = '#008000';
                    break;
                case 'High':
                    $input_data['vldl_message_flag'] = 'danger';
                    $input_data['vldl_range_code']   = '#ff0000';
                    break;
            }
        }
        
        if (!empty($input_data['ldl']) && !empty($input_data['ldl_unit'])) {
            
            if ($input_data['ldl_unit'] == 'mg/dL') {
                
                if ($input_data['ldl'] < 130) {
                    $input_data['ldl_message'] = 'Optimal';
                }

                if (($input_data['ldl'] >= 130) && ($input_data['ldl'] <= 159)) {
                    $input_data['ldl_message'] = 'Intermediate';
                }

                if ($input_data['ldl'] > 159) {
                    $input_data['ldl_message'] = 'High';
                }
            }
            
            if ($input_data['ldl_unit'] == 'mmol/L') {
                
                if ($input_data['ldl'] < 3.36) {
                    $input_data['ldl_message'] = 'Optimal';
                }

                if (($input_data['ldl'] >= 3.36) && ($input_data['ldl'] <= 4.11)) {
                    $input_data['ldl_message'] = 'Intermediate';
                }

                if ($input_data['ldl'] > 4.11) {
                    $input_data['ldl_message'] = 'High';
                }
            }
            
            switch ($input_data['ldl_message']) {
                case 'Optimal':
                    $input_data['ldl_message_flag'] = 'success';
                    $input_data['ldl_range_code']   = '#008000';
                    break;
                case 'Intermediate':
                    $input_data['ldl_message_flag'] = 'success';
                    $input_data['ldl_range_code']   = '#008000';
                    break;
                case 'High':
                    $input_data['ldl_message_flag'] = 'danger';
                    $input_data['ldl_range_code']   = '#ff0000';
                    break;
            }
        }
        
        if (!empty($input_data['hdl']) && !empty($input_data['hdl_unit'])) {
            
            if ($input_data['hdl_unit'] == 'mg/dL') {
                
                if ($input_data['hdl'] < 40) {
                    $input_data['hdl_message'] = 'High';
                }

                if (($input_data['hdl'] >= 40) && ($input_data['hdl'] <= 60)) {
                    $input_data['hdl_message'] = 'Intermediate';
                }

                if ($input_data['hdl'] > 60) {
                    $input_data['hdl_message'] = 'Optimal';
                }
            }
            
            if ($input_data['hdl_unit'] == 'mmol/L') {
                
                if ($input_data['hdl'] < 1.03) {
                    $input_data['hdl_message'] = 'High';
                }

                if (($input_data['hdl'] >= 1.03) && ($input_data['hdl'] <= 1.55)) {
                    $input_data['hdl_message'] = 'Intermediate';
                }

                if ($input_data['hdl'] > 1.55) {
                    $input_data['hdl_message'] = 'Optimal';
                }
            }
            
            switch ($input_data['hdl_message']) {
                case 'Optimal':
                    $input_data['hdl_message_flag'] = 'success';
                    $input_data['hdl_range_code']   = '#008000';
                    break;
                case 'Intermediate':
                    $input_data['hdl_message_flag'] = 'success';
                    $input_data['hdl_range_code']   = '#008000';
                    break;
                case 'High':
                    $input_data['hdl_message_flag'] = 'danger';
                    $input_data['hdl_range_code']   = '#ff0000';
                    break;
            }
        }
        
        if (!empty($input_data['triglycerides']) && !empty($input_data['triglycerides_unit'])) {
            if ($input_data['triglycerides_unit'] == 'mg/dL') {
                
                if ($input_data['triglycerides'] < 150) {
                    $input_data['triglycerides_message'] = 'Optimal';
                }

                if (($input_data['triglycerides'] >= 150) && ($input_data['triglycerides'] <= 199)) {
                    $input_data['triglycerides_message'] = 'Intermediate';
                }

                if ($input_data['triglycerides'] > 199) {
                    $input_data['triglycerides_message'] = 'High';
                }
            }
            
            if ($input_data['triglycerides_unit'] == 'mmol/L') {
                
                if ($input_data['triglycerides'] < 1.69) {
                    $input_data['triglycerides_message'] = 'Optimal';
                }

                if (($input_data['triglycerides'] >= 1.69) && ($input_data['triglycerides'] <= 2.25)) {
                    $input_data['triglycerides_message'] = 'Intermediate';
                }

                if ($input_data['triglycerides'] > 2.25) {
                    $input_data['triglycerides_message'] = 'High';
                }
            }
            
            switch ($input_data['triglycerides_message']) {
                case 'Optimal':
                    $input_data['triglycerides_message_flag'] = 'success';
                    $input_data['triglycerides_range_code']   = '#008000';
                    break;
                case 'Intermediate':
                    $input_data['triglycerides_message_flag'] = 'success';
                    $input_data['triglycerides_range_code']   = '#008000';
                    break;
                case 'High':
                    $input_data['triglycerides_message_flag'] = 'danger';
                    $input_data['triglycerides_range_code']   = '#ff0000';
                    break;
            }
        }
        
        if (!empty($input_data['hdl_ldl'])) {
            
        if ($input_data['hdl_ldl_unit'] == 'mg/dL') {
            if ($input_data['hdl_ldl'] <= 3) {
                $input_data['hdl_ldl_message'] = 'Optimal';
            }

            if (($input_data['hdl_ldl'] >= 3.1) && ($input_data['hdl_ldl'] <= 3.8)) {
                $input_data['hdl_ldl_message'] = 'Intermediate';
            }

            if ($input_data['hdl_ldl'] > 3.8) {
                $input_data['hdl_ldl_message'] = 'High';
            }
        }

        if ($input_data['hdl_ldl_unit'] == 'mmol/L') {
            if ($input_data['hdl_ldl'] <= 1.33) {
                $input_data['hdl_ldl_message'] = 'Optimal';
            }

            if (($input_data['hdl_ldl'] >= 1.34) && ($input_data['hdl_ldl'] <= 1.68)) {
                $input_data['hdl_ldl_message'] = 'Intermediate';
            }

            if ($input_data['hdl_ldl'] > 1.68) {
                $input_data['hdl_ldl_message'] = 'High';
            }
        }
            
            switch ($input_data['hdl_ldl_message']) {
                case 'Optimal':
                    $input_data['hdl_ldl_message_flag'] = 'success';
                    $input_data['hdl_ldl_range_code']   = '#008000';
                break;
                case 'Intermediate':
                    $input_data['hdl_ldl_message_flag'] = 'success';
                    $input_data['hdl_ldl_range_code']   = '#008000';
                break;
                case 'High':
                    $input_data['hdl_ldl_message_flag'] = 'danger';
                    $input_data['hdl_ldl_range_code']   = '#ff0000';
                break;
            }
        }

        return $input_data;
    }
    


    public static function keytone_flag($input_data)
    {
        $input_data['keytoneFlag']      = 'Warning';
        $input_data['keytoneFlagColor'] = 'warning';
        $input_data['range_code']    = '#ffc107';
        if (!empty($input_data['keytone'])) {
            
            if ($input_data['keytone'] < 0.6) {
                $input_data['keytoneFlag']      = 'Normal';
                $input_data['keytoneFlagColor'] = 'success';
                $input_data['range_code']    = '#008000';
            }
            
            if (($input_data['keytone'] >= 0.6) && ($input_data['keytone'] <= 1.5)) {
                $input_data['keytoneFlag']      = 'Warning';
                $input_data['keytoneFlagColor'] = 'warning';
                $input_data['range_code']    = '#ffc107';
            }
            
             if (($input_data['keytone'] >= 1.6)) {
                $input_data['keytoneFlag']      = 'High';
                $input_data['keytoneFlagColor'] = 'danger';
                $input_data['range_code']    = '#ff0000';
            }
        }

        return $input_data;
    }

    public static function hemoglobin_flag($input_data, $gender)
    {
        $input_data['hemoglobinFlag']      = 'Danger';
        $input_data['hemoglobinFlagColor'] = 'danger';
        $input_data['range_code']    = '#ff0000';
        if (!empty($input_data['hemoglobin'])) {

            if($gender == 'Male'){
            
                if (($input_data['hemoglobin'] >= '13.8') && ($input_data['hemoglobin'] <= '17.2')) {
                    $input_data['hemoglobinFlag']      = 'Normal';
                    $input_data['hemoglobinFlagColor'] = 'success';
                    $input_data['range_code']    = '#008000';
                }
            }
            if($gender == 'Female'){
            
                if (($input_data['hemoglobin'] >= '12.1') && ($input_data['hemoglobin'] <= '15.1')) {
                    $input_data['hemoglobinFlag']      = 'Normal';
                    $input_data['hemoglobinFlagColor'] = 'success';
                    $input_data['range_code']    = '#008000';
                }
            }
        }

        return $input_data;
    }

    public static function hct_flag($input_data, $gender)
    {
        $input_data['hctFlag']      = 'Danger';
        $input_data['hctFlagColor'] = 'danger';
        $input_data['range_code']    = '#ff0000';
        if (!empty($input_data['hct'])) {

            if (($input_data['hct'] >= '41') && ($input_data['hct'] <= '50')) {
                    $input_data['hctFlag']      = 'Normal';
                    $input_data['hctFlagColor'] = 'success';
                    $input_data['range_code']    = '#008000';
                }
                
            if($gender == 'Male'){
            
                if (($input_data['hct'] >= '41') && ($input_data['hct'] <= '50')) {
                    $input_data['hctFlag']      = 'Normal';
                    $input_data['hctFlagColor'] = 'success';
                    $input_data['range_code']    = '#008000';
                }
            }
            if($gender == 'Female'){
            
                if (($input_data['hct'] >= '36') && ($input_data['hct'] <= '48')) {
                    $input_data['hctFlag']      = 'Normal';
                    $input_data['hctFlagColor'] = 'success';
                    $input_data['range_code']    = '#008000';
                }
            }
        }

        return $input_data;
    }


    public static function uric_acid_flag($input_data, $gender)
    {
        $input_data['uricFlag']      = 'Danger';
        $input_data['uricFlagColor'] = 'danger';
        $input_data['range_code']    = '#ff0000';
        if (!empty($input_data['uric_acid'])) {

            if($gender == 'Male'){
            
                if (($input_data['uric_acid'] >= '4.0') && ($input_data['uric_acid'] <= '8.5')) {
                    $input_data['uricFlag']      = 'Normal';
                    $input_data['uricFlagColor'] = 'success';
                    $input_data['range_code']    = '#008000';
                }
            }
            if($gender == 'Female'){
            
                if (($input_data['uric_acid'] >= '2.7') && ($input_data['uric_acid'] <= '7.3')) {
                    $input_data['uricFlag']      = 'Normal';
                    $input_data['uricFlagColor'] = 'success';
                    $input_data['range_code']    = '#008000';
                }
            }
        }

        return $input_data;
    }

    public static function spirometer_flag($input_data)
    {
        $input_data['spirometerFlag']      = 'Normal';
        $input_data['spirometerFlagColor'] = 'primary';
        $input_data['range_code']    = '#0000ff';
        

        return $input_data;
    }


}
