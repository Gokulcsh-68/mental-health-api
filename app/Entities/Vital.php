<?php

namespace App\Entities;
use DB;

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


    protected function createModel($request)
    {
        $data = $this->getModelAttributes($request);

        if(isset($data['details']['date'])){
            $data['details']['date'] = date('Y-m-d',strtotime($data['details']['date']));
        }

        if(isset($data['details']['bmi'])){
            $data['details'] += self::bmi_flag($data['details']['bmi']);
        }


        if(isset($data['details']['temperature'])){
            $data['details'] += self::temp_flag($data['details']);
        }

       
        return $this->create($data);
    }


    protected function updateModel($id, $request, $only = [])
    {
        $data = $this->getModelAttributes($request, $only);

        if(isset($data['details']['date'])){
            $data['details']['date'] = date('Y-m-d',strtotime($data['details']['date']));
        }


        if(isset($data['details']['bmi'])){
            unset($data['details']['bmiFlag'], $data['details']['bmiFlagColor'], $data['details']['range_code']);
            $data['details'] += self::bmi_flag($data['details']['bmi']);
        }


        if(isset($data['details']['temperature'])){
            unset($data['details']['temperatureFlag'], $data['details']['temperatureFlagColor'], $data['details']['range_code']);

            $data['details'] += self::temp_flag($data['details']);
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


        if($request->get('from') && $request->get('to')){

            $from = date('Y-m-d',strtotime($request->get('from')));
                $to = date('Y-m-d',strtotime($request->get('to')));
            $model->whereBetween('details->date', [$from,$to]);
        }


        if ($request->get('searchkey')) {

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
    
   
    
    public static function temp_flag($input_data)
    {
        if (!empty($input_data['unit']) && !empty($input_data['temperature'])) {
            $input_data['temperatureFlag']      = '';
            $input_data['temperatureFlagColor'] = '';
            
            switch ($input_data['unit']) {
                case 'Fahrenheit':
                    if ($input_data['temperature'] <= 95) {
                        $input_data['temperatureFlag']      = 'Hypothermia';
                        $input_data['temperatureFlagColor'] = 'primary';
                        $input_data['range_code']    = '#0000ff';
                    }
                    
                    if (($input_data['temperature'] > 95) && ($input_data['temperature'] <= 99.5)) {
                        $input_data['temperatureFlag']      = 'Normal';
                        $input_data['temperatureFlagColor'] = 'success';
                        $input_data['range_code']    = '#008000';
                    }
                    
                    if (($input_data['temperature'] > 99.5) && ($input_data['temperature'] <= 100.9)) {
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
                    break;
                
                case 'Celsius':
                    if ($input_data['temperature'] <= 35) {
                        $input_data['temperatureFlag']      = 'Hypothermia';
                        $input_data['temperatureFlagColor'] = 'primary';
                        $input_data['range_code']    = '#0000ff';
                    }
                    
                    if (($input_data['temperature'] > 36.5) && ($input_data['temperature'] <= 37.5)) {
                        $input_data['temperatureFlag']      = 'Normal';
                        $input_data['temperatureFlagColor'] = 'success';
                        $input_data['range_code']    = '#008000';
                    }
                    
                    if (($input_data['temperature'] > 37.5) && ($input_data['temperature'] <= 41.5)) {
                        $input_data['temperatureFlag']      = 'Fever / Hyperthermia';
                        $input_data['temperatureFlagColor'] = 'danger';
                        $input_data['range_code']    = '#ff0000';
                    }
                    
                    if ($input_data['temperature'] > 41.5) {
                        $input_data['temperatureFlag']      = 'Hyperpyrexia';
                        $input_data['temperatureFlagColor'] = 'danger';
                        $input_data['range_code']    = '#ff0000';
                    }
                    break;
            }
            
        }
        
        
        return $input_data;
    }
    
   
    
    public static function blood_sugar_flag($input_data)
    {
        $input_data['bloodSugarFlag']      = '';
        $input_data['bloodSugarFlagColor'] = '';
        if (!empty($input_data['blood_glucose_value'])) {
            
            if ($input_data['blood_glucose_value'] < 50) {
                $input_data['bloodSugarFlag']      = 'Dangerously';
                $input_data['bloodSugarFlagColor'] = 'rangeBlue';
                $input_data['range_code']          = '#0000ff';
            }
            
            if (($input_data['blood_glucose_value'] > 70) && ($input_data['blood_glucose_value'] <= 90)) {
                $input_data['bloodSugarFlag']      = 'Possibly too low';
                $input_data['bloodSugarFlagColor'] = 'rangeBlue';
                $input_data['range_code']          = '#0000ff';
            }
            
            if (($input_data['blood_glucose_value'] > 90) && ($input_data['blood_glucose_value'] <= 120)) {
                $input_data['bloodSugarFlag']      = 'Normal';
                $input_data['bloodSugarFlagColor'] = 'rangeGreen';
                $input_data['range_code']          = '#008000';
            }
            
            if (($input_data['blood_glucose_value'] > 120) && ($input_data['blood_glucose_value'] <= 160)) {
                $input_data['bloodSugarFlag']      = 'Medium';
                $input_data['bloodSugarFlagColor'] = 'rangeRed';
                $input_data['range_code']          = '#ff0000';
            }
            
            if (($input_data['blood_glucose_value'] > 160) && ($input_data['blood_glucose_value'] <= 240)) {
                $input_data['bloodSugarFlag']      = 'Too high';
                $input_data['bloodSugarFlagColor'] = 'rangeRed';
                $input_data['range_code']          = '#ff0000';
            }
            
            if (($input_data['blood_glucose_value'] > 240) && ($input_data['blood_glucose_value'] <= 300)) {
                $input_data['bloodSugarFlag']      = 'Much too high';
                $input_data['bloodSugarFlagColor'] = 'rangeRed';
                $input_data['range_code']          = '#ff0000';
            }
            
            if ($input_data['blood_glucose_value'] > 300) {
                $input_data['bloodSugarFlag']      = 'Very high';
                $input_data['bloodSugarFlagColor'] = 'rangeRed';
                $input_data['range_code']          = '#ff0000';
            }
        }
        
        return $input_data;
    }
    
    
    
    public static function spo2_flag($input_data)
    {
        $input_data['spO2Flag']      = '';
        $input_data['spO2FlagColor'] = '';
        if (!empty($input_data['spo2_value'])) {
            
            if ($input_data['spo2_value'] < 75) {
                $input_data['spO2Flag']      = 'Severe Hypoxemia';
                $input_data['spO2FlagColor'] = 'rangeBlue';
                $input_data['range_code']    = '#0000ff';
            }
            
            if (($input_data['spo2_value'] >= 75) && ($input_data['spo2_value'] <= 89)) {
                $input_data['spO2Flag']      = 'Moderate Hypoxemia';
                $input_data['spO2FlagColor'] = 'rangeBlue';
                $input_data['range_code']    = '#0000ff';
            }
            
            if (($input_data['spo2_value'] >= 90) && ($input_data['spo2_value'] <= 94)) {
                $input_data['spO2Flag']      = 'Mild Hypoxemia';
                $input_data['spO2FlagColor'] = 'rangeBlue';
                $input_data['range_code']    = '#0000ff';
            }
            
            if ($input_data['spo2_value'] >= 95) {
                $input_data['spO2Flag']      = 'Normal';
                $input_data['spO2FlagColor'] = 'rangeGreen';
                $input_data['range_code']    = '#008000';
            }
        }

        return $input_data;
    }
    
   
    
    public static function urine_flag($input_data)
    {
        $input_data['leukocytes']            = '';
        $input_data['leukocytes_flag']       = '';
        $input_data['leukocytes_range_code'] = '';
        $input_data['protein']               = '';
        $input_data['protein_flag']          = '';
        $input_data['protein_range_code']    = '';
        $input_data['urinerbc']              = '';
        $input_data['urinerbc_flag']         = '';
        $input_data['urinerbc_range_code']   = '';
        $input_data['urineval']              = '';
        $input_data['urineval_flag']         = '';
        $input_data['urineval_range_code']   = '';
        
        if (!empty($input_data['urine_leukocytes'])) {
            
            switch ($input_data['urine_leukocytes']) {
                case 'Plus':
                    $input_data['leukocytes']            = 'Small';
                    $input_data['leukocytes_flag']       = 'rangeGreen';
                    $input_data['leukocytes_range_code'] = '#008000';
                    break;
                case 'Double Plus':
                    $input_data['leukocytes']            = 'Moderate';
                    $input_data['leukocytes_flag']       = 'rangeGreen';
                    $input_data['leukocytes_range_code'] = '#008000';
                    break;
                case 'Triple Plus':
                    $input_data['leukocytes']            = 'Large';
                    $input_data['leukocytes_flag']       = 'rangeRed';
                    $input_data['leukocytes_range_code'] = '#ff0000';
                    break;
            }
        }
        
        if (!empty($input_data['urine_protein'])) {
            
            switch ($input_data['urine_protein']) {
                case 'Plus':
                    $input_data['protein']            = 'Small';
                    $input_data['protein_flag']       = 'rangeGreen';
                    $input_data['protein_range_code'] = '#008000';
                    break;
                case 'Double Plus':
                    $input_data['protein']            = 'Moderate';
                    $input_data['protein_flag']       = 'rangeGreen';
                    $input_data['protein_range_code'] = '#008000';
                    break;
                case 'Triple Plus':
                    $input_data['protein']            = 'Large';
                    $input_data['protein_flag']       = 'rangeRed';
                    $input_data['protein_range_code'] = '#ff0000';
                    break;
            }
        }
        
        if (!empty($input_data['urine_rbc'])) {
            
            switch ($input_data['urine_rbc']) {
                case 'Plus':
                    $input_data['urinerbc']            = 'Small';
                    $input_data['urinerbc_flag']       = 'rangeGreen';
                    $input_data['urinerbc_range_code'] = '#008000';
                    break;
                case 'Double Plus':
                    $input_data['urinerbc']            = 'Moderate';
                    $input_data['urinerbc_flag']       = 'rangeGreen';
                    $input_data['urinerbc_range_code'] = '#008000';
                    break;
                case 'Triple Plus':
                    $input_data['urinerbc']            = 'Large';
                    $input_data['urinerbc_flag']       = 'rangeRed';
                    $input_data['urinerbc_range_code'] = '#ff0000';
                    break;
            }
        }

        if (!empty($input_data['urine_value'])) {
        
            if ($input_data['urine_value'] > 0) {
                
                if ($input_data['urine_value'] < 3.0) {
                    $input_data['urineval']            = 'Low';
                    $input_data['urineval_flag']       = 'rangeRed';
                    $input_data['urineval_range_code'] = '#ff0000';
                }
                
                if (($input_data['urine_value'] > 4.0) && ($input_data['urine_value'] < 6.0)) {
                    $input_data['urineval']            = 'Acidic';
                    $input_data['urineval_flag']       = 'rangeRed';
                    $input_data['urineval_range_code'] = '#ff0000';
                }

                if (($input_data['urine_value'] >= 6.0) && ($input_data['urine_value'] < 7.0)) {
                    $input_data['urineval']            = 'Neutral';
                    $input_data['urineval_flag']       = 'rangeRed';
                    $input_data['urineval_range_code'] = '#ff0000';
                }
                
                if (($input_data['urine_value'] >= 7.0) && ($input_data['urine_value'] < 10.5)) {
                    $input_data['urineval']            = 'Neutral';
                    $input_data['urineval_flag']       = 'rangeRed';
                    $input_data['urineval_range_code'] = '#ff0000';
                }
                
            }
        }

        return $input_data;
    }
    
    
    public static function blood_pressure_flag($input_data)
    {
        $input_data['bloodPressureFlag']      = '';
        $input_data['bloodPressureFlagColor'] = '';
        if (!empty($input_data['bp_sys']) && !empty($input_data['bp_dia'])) {
            if (($input_data['bp_sys'] < 120) && ($input_data['bp_dia'] <= 80)) {
                $input_data['bloodPressureFlag']      = 'NORMAL';
                $input_data['bloodPressureFlagColor'] = 'rangeGreen';
                $input_data['range_code']             = '#008000';
            }
            
            if ((($input_data['bp_sys'] < 120) && ($input_data['bp_sys'] <= 129)) && ($input_data['bp_dia'] <= 80)) {
                $input_data['bloodPressureFlag']      = 'Elevated';
                $input_data['bloodPressureFlagColor'] = 'rangeGreen';
                $input_data['range_code']             = '#008000';
            }
            
            if ((($input_data['bp_sys'] > 130) && ($input_data['bp_sys'] <= 139)) || (($input_data['bp_dia'] < 80) && ($input_data['bp_dia'] < 89))) {
                $input_data['bloodPressureFlag']      = 'HIGH BLOOD PRESSURE(HYPERTENSION) STAGE 1';
                $input_data['bloodPressureFlagColor'] = 'rangeRed';
                $input_data['range_code']             = '#ff0000';
            }
            
            if (($input_data['bp_sys'] >= 140) || ($input_data['bp_dia'] >= 90)) {
                $input_data['bloodPressureFlag']      = 'HIGH BLOOD PRESSURE(HYPERTENSION) STAGE 2';
                $input_data['bloodPressureFlagColor'] = 'rangeRed';
                $input_data['range_code']             = '#ff0000';
            }
            
            if (($input_data['bp_sys'] > 180) || ($input_data['bp_dia'] > 120)) {
                $input_data['bloodPressureFlag']      = 'HYPERTENSIVE CRISIS(consult your doctor immediately)';
                $input_data['bloodPressureFlagColor'] = 'rangeRed';
                $input_data['range_code']             = '#ff0000';
            }
            
        }
        
        return $input_data;
    }
    
        
    public static function heart_rate_flag($input_data, $years, $months, $days)
    {
        $input_data['heartRateFlag']      = '';
        $input_data['heartRateFlagColor'] = '';
        
        if (!empty($input_data['pulse_value'])) {
            
            if ($years <= 12) {
                
                if (($years >= 1) && ($years <= 2)) {
                    
                    if (($input_data['pulse_value'] < 98)) {
                        $input_data['heartRateFlag']      = 'Low';
                        $input_data['heartRateFlagColor'] = 'rangeBlue';
                        $input_data['range_code']         = '#0000ff';
                    }
                    
                    if (($input_data['pulse_value'] >= 98) && ($input_data['pulse_value'] <= 140)) {
                        $input_data['heartRateFlag']      = 'Toddler';
                        $input_data['heartRateFlagColor'] = 'rangeGreen';
                        $input_data['range_code']         = '#008000';
                    }
                    
                    if (($input_data['pulse_value'] > 140)) {
                        $input_data['heartRateFlag']      = 'High';
                        $input_data['heartRateFlagColor'] = 'rangeRed';
                        $input_data['range_code']         = '#ff0000';
                    }
                }
                
                if (($years >= 3) && ($years <= 5)) {
                    if (($input_data['pulse_value'] < 80)) {
                        $input_data['heartRateFlag']      = 'Low';
                        $input_data['heartRateFlagColor'] = 'rangeBlue';
                        $input_data['range_code']         = '#0000ff';
                    }
                    
                    if (($input_data['pulse_value'] >= 80) && ($input_data['pulse_value'] <= 120)) {
                        $input_data['heartRateFlag']      = 'Pre-School';
                        $input_data['heartRateFlagColor'] = 'rangeGreen';
                        $input_data['range_code']         = '#008000';
                        // return "Pre-School"; // green 90 - 140
                    }
                    
                    if (($input_data['pulse_value'] > 120)) {
                        $input_data['heartRateFlag']      = 'High';
                        $input_data['heartRateFlagColor'] = 'rangeRed';
                        $input_data['range_code']         = '#ff0000';
                    }
                }
                
                if (($years >= 6) && ($years <= 11)) {
                    if (($input_data['pulse_value'] < 75)) {
                        $input_data['heartRateFlag']      = 'Low';
                        $input_data['heartRateFlagColor'] = 'rangeBlue';
                        $input_data['range_code']         = '#0000ff';
                    }
                    
                    if (($input_data['pulse_value'] >= 75) && ($input_data['pulse_value'] <= 118)) {
                        $input_data['heartRateFlag']      = 'Normal';
                        $input_data['heartRateFlagColor'] = 'rangeGreen';
                        $input_data['range_code']         = '#008000';
                        // return "Normal"; // green
                    }
                    
                    if (($input_data['pulse_value'] > 118)) {
                        $input_data['heartRateFlag']      = 'High';
                        $input_data['heartRateFlagColor'] = 'rangeRed';
                        $input_data['range_code']         = '#ff0000';
                    }
                }
                
                if (($years == 12)) {
                    if (($input_data['pulse_value'] < 60)) {
                        $input_data['heartRateFlag']      = 'Low';
                        $input_data['heartRateFlagColor'] = 'rangeBlue';
                        $input_data['range_code']         = '#0000ff';
                    }
                    
                    if (($input_data['pulse_value'] >= 60) && ($input_data['pulse_value'] <= 100)) {
                        $input_data['heartRateFlag']      = 'Adult';
                        $input_data['heartRateFlagColor'] = 'rangeGreen';
                        $input_data['range_code']         = '#008000';
                    }
                    
                    if (($input_data['pulse_value'] > 100)) {
                        $input_data['heartRateFlag']      = 'High';
                        $input_data['heartRateFlagColor'] = 'rangeRed';
                        $input_data['range_code']         = '#ff0000';
                    }
                }
            }

            if ($years > 12) {
                    if (($input_data['pulse_value'] < 40)) {
                        $input_data['heartRateFlag']      = 'Low';
                        $input_data['heartRateFlagColor'] = 'rangeBlue';
                        $input_data['range_code']         = '#0000ff';
                    }
                    
                    if (($input_data['pulse_value'] > 40) && ($input_data['pulse_value'] <= 60)) {
                        $input_data['heartRateFlag']      = 'Athlete';
                        $input_data['heartRateFlagColor'] = 'rangeGreen';
                        $input_data['range_code']         = '#008000';
                    }
                    
                    if (($input_data['pulse_value'] > 60)) {
                        $input_data['heartRateFlag']      = 'High';
                        $input_data['heartRateFlagColor'] = 'rangeRed';
                        $input_data['range_code']         = '#ff0000';
                    }
            }
             
            if ($years == 0 && $months > 0) {
                if (($months >= 1) && ($months < 12)) {
                    if (($input_data['pulse_value'] < 100)) {
                        $input_data['heartRateFlag']      = 'Low';
                        $input_data['heartRateFlagColor'] = 'rangeBlue';
                        $input_data['range_code']         = '#0000ff';
                    }
                    
                    if (($input_data['pulse_value'] >= 100) && ($input_data['pulse_value'] <= 190)) {
                        $input_data['heartRateFlag']      = 'Low';
                        $input_data['heartRateFlagColor'] = 'rangeBlue';
                        $input_data['range_code']         = '#0000ff';
                    }
                    
                    if (($input_data['pulse_value'] > 190)) {
                        $input_data['heartRateFlag']      = 'High';
                        $input_data['heartRateFlagColor'] = 'rangeRed';
                        $input_data['range_code']         = '#ff0000';
                    }
                }
            }
            
            if ($years == 0 && $months == 0) {
                if ($days <= 28) {
                    if (($input_data['pulse_value'] < 100)) {
                        $input_data['heartRateFlag']      = 'Low';
                        $input_data['heartRateFlagColor'] = 'rangeBlue';
                        $input_data['range_code']         = '#0000ff';
                    }
                    
                    if (($input_data['pulse_value'] > 100) && ($input_data['pulse_value'] <= 205)) {
                        $input_data['heartRateFlag']      = 'Neonate';
                        $input_data['heartRateFlagColor'] = 'rangeGreen';
                        $input_data['range_code']         = '#008000';
                    }
                    
                    if (($input_data['pulse_value'] > 205)) {
                        $input_data['heartRateFlag']      = 'High';
                        $input_data['heartRateFlagColor'] = 'rangeRed';
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
        
        $input_data['hdl_message']      = '';
        $input_data['hdl_message_flag'] = '';
        
        $input_data['tri_message']      = '';
        $input_data['tri_message_flag'] = '';
        
        $input_data['hdlr_message']      = '';
        $input_data['hdlr_message_flag'] = '';
        
        $input_data['tot_message']      = '';
        $input_data['tot_message_flag'] = '';
        
        
        if (!empty($input_data['total_cholestrol']) && !empty($input_data['total_cholestrol_unit'])) {
            
            if ($input_data['total_cholestrol_unit'] == 'mg/dL') {
                
                if ($input_data['total_cholestrol'] < 200) {
                    $input_data['tot_message'] = 'Optimal'; // green
                }

                if (($input_data['total_cholestrol'] >= 200) && ($input_data['total_cholestrol'] < 239)) {
                    $input_data['tot_message'] = 'Intermediate'; // green
                }

                if ($input_data['total_cholestrol'] > 239) {
                    $input_data['tot_message'] = 'High'; // red
                }
            }
            
            if ($input_data['total_cholestrol_unit'] == 'mmol/L') {
                
                if ($input_data['total_cholestrol'] < 5.2) {
                    $input_data['tot_message'] = 'Optimal';
                }

                if (($input_data['total_cholestrol'] >= 5.3) && ($input_data['total_cholestrol'] < 6.2)) {
                    $input_data['tot_message'] = 'Intermediate';
                }

                if ($input_data['total_cholestrol'] > 6.2) {
                    $input_data['tot_message'] = 'High';
                }
            }
            
            switch ($input_data['tot_message']) {
                case 'Optimal':
                    $input_data['tot_message_flag'] = 'rangeGreen';
                    $input_data['tot_range_code']   = '#008000';
                    break;
                case 'Intermediate':
                    $input_data['tot_message_flag'] = 'rangeGreen';
                    $input_data['tot_range_code']   = '#008000';
                    break;
                case 'High':
                    $input_data['tot_message_flag'] = 'rangeRed';
                    $input_data['tot_range_code']   = '#ff0000';
                    break;
            }
        }
        
        if (!empty($input_data['ldl']) && !empty($input_data['ldl_unit'])) {
            
            if ($input_data['ldl_unit'] == 'mg/dL') {
                
                if ($input_data['ldl'] < 130) {
                    $input_data['ldl_message'] = 'Optimal';
                }

                if (($input_data['ldl'] >= 130) && ($input_data['ldl'] < 159)) {
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

                if (($input_data['ldl'] >= 3.36) && ($input_data['ldl'] < 4.11)) {
                    $input_data['ldl_message'] = 'Intermediate';
                }

                if ($input_data['ldl'] > 4.11) {
                    $input_data['ldl_message'] = 'High';
                }
            }
            
            switch ($input_data['ldl_message']) {
                case 'Optimal':
                    $input_data['ldl_message_flag'] = 'rangeGreen';
                    $input_data['ldl_range_code']   = '#008000';
                    break;
                case 'Intermediate':
                    $input_data['ldl_message_flag'] = 'rangeGreen';
                    $input_data['ldl_range_code']   = '#008000';
                    break;
                case 'High':
                    $input_data['ldl_message_flag'] = 'rangeRed';
                    $input_data['ldl_range_code']   = '#ff0000';
                    break;
            }
        }
        
        if (!empty($input_data['hdl']) && !empty($input_data['hdl_unit'])) {
            
            if ($input_data['hdl_unit'] == 'mg/dL') {
                
                if ($input_data['hdl'] < 40) {
                    $input_data['hdl_message'] = 'Optimal';
                }

                if (($input_data['hdl'] >= 40) && ($input_data['hdl'] < 60)) {
                    $input_data['hdl_message'] = 'Intermediate';
                }

                if ($input_data['hdl'] > 60) {
                    $input_data['hdl_message'] = 'High';
                }
            }
            
            if ($input_data['hdl_unit'] == 'mmol/L') {
                
                if ($input_data['hdl'] < 1.03) {
                    $input_data['hdl_message'] = 'Optimal';
                }

                if (($input_data['hdl'] >= 1.03) && ($input_data['hdl'] < 1.55)) {
                    $input_data['hdl_message'] = 'Intermediate';
                }

                if ($input_data['hdl'] > 1.55) {
                    $input_data['hdl_message'] = 'High';
                }
            }
            
            switch ($input_data['hdl_message']) {
                case 'Optimal':
                    $input_data['hdl_message_flag'] = 'rangeGreen';
                    $input_data['hdl_range_code']   = '#008000';
                    break;
                case 'Intermediate':
                    $input_data['hdl_message_flag'] = 'rangeGreen';
                    $input_data['hdl_range_code']   = '#008000';
                    break;
                case 'High':
                    $input_data['hdl_message_flag'] = 'rangeRed';
                    $input_data['hdl_range_code']   = '#ff0000';
                    break;
            }
        }
        
        if (!empty($input_data['triglycerides']) && !empty($input_data['triglycerides_unit'])) {
            if ($input_data['triglycerides_unit'] == 'mg/dL') {
                
                if ($input_data['triglycerides'] < 150) {
                    $input_data['tri_message'] = 'Optimal';
                }

                if (($input_data['triglycerides'] >= 150) && ($input_data['triglycerides'] < 199)) {
                    $input_data['tri_message'] = 'Intermediate';
                }

                if ($input_data['triglycerides'] > 199) {
                    $input_data['tri_message'] = 'High';
                }
            }
            
            if ($input_data['triglycerides_unit'] == 'mmol/L') {
                
                if ($input_data['triglycerides'] < 1.69) {
                    $input_data['tri_message'] = 'Optimal';
                }

                if (($input_data['triglycerides'] >= 1.69) && ($input_data['triglycerides'] < 2.25)) {
                    $input_data['tri_message'] = 'Intermediate';
                }

                if ($input_data['triglycerides'] > 2.25) {
                    $input_data['tri_message'] = 'High';
                }
            }
            
            switch ($input_data['tri_message']) {
                case 'Optimal':
                    $input_data['tri_message_flag'] = 'rangeGreen';
                    $input_data['tri_range_code']   = '#008000';
                    break;
                case 'Intermediate':
                    $input_data['tri_message_flag'] = 'rangeGreen';
                    $input_data['tri_range_code']   = '#008000';
                    break;
                case 'High':
                    $input_data['tri_message_flag'] = 'rangeRed';
                    $input_data['tri_range_code']   = '#ff0000';
                    break;
            }
        }
        
        if (!empty($input_data['hdl_ldl_ratio'])) {
            
            if ($input_data['hdl_ldl_ratio'] < 3) {
                $input_data['hdlr_message'] = 'Optimal';
            }

            if (($input_data['hdl_ldl_ratio'] >= 3.1) && ($input_data['hdl_ldl_ratio'] < 3.8)) {
                $input_data['hdlr_message'] = 'Intermediate';
            }

            if ($input_data['hdl_ldl_ratio'] > 3.8) {
                $input_data['hdlr_message'] = 'High';
            }
            
            switch ($input_data['hdlr_message']) {
                case 'Optimal':
                    $input_data['hdlr_message_flag'] = 'rangeGreen';
                    $input_data['hdlr_range_code']   = '#008000';
                    break;
                case 'Intermediate':
                    $input_data['hdlr_message_flag'] = 'rangeGreen';
                    $input_data['hdlr_range_code']   = '#008000';
                    break;
                case 'High':
                    $input_data['hdlr_message_flag'] = 'rangeRed';
                    $input_data['hdlr_range_code']   = '#ff0000';
                    break;
            }
        }

        return $input_data;
    }
    
}
