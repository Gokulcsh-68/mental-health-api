<?php

namespace App\Enums;

use App\Entities\Master;
use App\Entities\ProviderSpeciality;
use App\Entities\User;

abstract class EnumAnalyticsChart {


    public static function SpecialityCounts($value) 
    {

        $chart_result = [];


            $getSpeciality = ProviderSpeciality::WhereIn('provider_id',$value)
                ->get()->groupBy('speciality')->toArray(); 


            $MasterSpeciality = Master::WhereIn('slug',array_keys($getSpeciality))
                ->pluck('name','slug'); 

            foreach ($getSpeciality as $key => $value) {
                $chart_result[$MasterSpeciality[$key]]=count($value);
            }
            
            return $chart_result;

    }

    public static function AgeCounts($value) 
    {

        $chart_result = [];
        foreach ($value as $k => $v) {
            $chart_result['child'] = !empty($chart_result['child'])?$chart_result['child']:0;
            $chart_result['adolescence'] = !empty($chart_result['adolescence'])?$chart_result['adolescence']:0;
            $chart_result['adult'] = !empty($chart_result['adult'])?$chart_result['adult']:0;
            $chart_result['senior'] = !empty($chart_result['senior'])?$chart_result['senior']:0;

            $getAge = User::Where('id',$v)->selectRaw("TIMESTAMPDIFF(YEAR, DATE(dob), current_date) AS age")
                ->value('age');

                if($getAge >= '0' && $getAge <= '12'){
                    $chart_result['child'] = !empty($chart_result['child'])?$chart_result['child']+1:1;
                }

                if($getAge >= '13' && $getAge <= '18'){
                    $chart_result['adolescence'] = !empty($chart_result['adolescence'])?$chart_result['adolescence']+1:1;
                }

                if($getAge >= '19' && $getAge <= '59'){
                    $chart_result['adult'] = !empty($chart_result['adult'])?$chart_result['adult']+1:1;
                }

                if($getAge >= '60'){
                    $chart_result['senior'] = !empty($chart_result['senior'])?$chart_result['senior']+1:1;
                }

        }

            return $chart_result;

    }
    
    public static function ChartCounts($value,$key) 
    {

        $chart_result = [];
        foreach ($value as $k => $v) {
            $flag = '';

            if($key == 'bmi'){
                $flag = $v->details->bmiFlag;
            }

            if($key == 'temperature'){
                $flag = $v->details->temperatureFlag;
            }

            if($key == 'spO2'){
                $flag = $v->details->spo2Flag;
            }

            if($key == 'blood-pressure'){
                $flag = $v->details->bpFlag;
            }
            
            if($key == 'blood-sugar'){
                $flag = $v->details->bsFlag;
            }
            
            if($key == 'heart-rate'){
                $flag = $v->details->heartRateFlag;
            }

            if($key == 'urine_leukocytes'){
                $flag = $v->details->leukocytes_message;
            }

            if($key == 'urine_protein'){
                $flag = $v->details->protein_message;
            }

            if($key == 'urine_rbc'){
                $flag = $v->details->rbc_message;
            }

            if($key == 'urine_sugar'){
                $flag = $v->details->sugar_message;
            }

            if($key == 'urine'){
                $flag = $v->details->value;
            }

            if($key == 'lipid_ldl'){
                $flag = $v->details->ldl_message;
            }

            if($key == 'lipid_hdl'){
                $flag = $v->details->hdl_message;
            }

            if($key == 'lipid_vldl'){
                $flag = $v->details->vldl_message;
            }

            if($key == 'lipid_hdl_ldl'){
                $flag = $v->details->hdl_ldl_message;
            }

            if($key == 'lipid_tri'){
                $flag = $v->details->triglycerides_message;
            }

            if($key == 'lipid_total'){
                $flag = $v->details->total_message;
            }

            if($key == 'respiration'){
                $flag = $v->details->respirationFlag;
            }

            if($key == 'keytone'){
                $flag = $v->details->keytoneFlag;
            }

            if($key == 'hct' || $key == 'hemoglobin' || $key == 'uric_acid'){
                $flag = $key;
            }

            switch ($flag) {

                case 'hct':
                    $chart_result['male']['normal'] = !empty($chart_result['male']['normal'])?$chart_result['male']['normal']:0;
                   

                    $chart_result['male']['danger'] = !empty($chart_result['male']['danger'])?$chart_result['male']['danger']:0;

                    $chart_result['female']['normal'] = !empty($chart_result['female']['normal'])?$chart_result['female']['normal']:0;
                    $chart_result['female']['danger'] = !empty($chart_result['female']['danger'])?$chart_result['female']['danger']:0;

                    $getGender = User::Where('id',$v->user_id)->value('gender');

                    if($getGender == 'Male'){

                        if($v->details->hct >= '41' && $v->details->hct <= '50'){
                            $chart_result['male']['normal'] = !empty($chart_result['male']['normal'])?$chart_result['male']['normal']+1:1;
                        }else{
                            $chart_result['male']['danger'] = !empty($chart_result['male']['danger'])?$chart_result['male']['danger']+1:1;
                        }
                    }

                    if($getGender == 'Female'){

                        if($v->details->hct >= '36' && $v->details->hct <= '48'){
                            $chart_result['female']['normal'] = !empty($chart_result['female']['normal'])?$chart_result['female']['normal']+1:1;
                        }else{
                            $chart_result['female']['danger'] = !empty($chart_result['female']['danger'])?$chart_result['female']['danger']+1:1;
                        }                    
                    }

                    break;

                case 'hemoglobin':
                    $chart_result['male']['normal'] = !empty($chart_result['male']['normal'])?$chart_result['male']['normal']:0;
                   

                    $chart_result['male']['danger'] = !empty($chart_result['male']['danger'])?$chart_result['male']['danger']:0;

                    $chart_result['female']['normal'] = !empty($chart_result['female']['normal'])?$chart_result['female']['normal']:0;
                    $chart_result['female']['danger'] = !empty($chart_result['female']['danger'])?$chart_result['female']['danger']:0;

                    $getGender = User::Where('id',$v->user_id)->value('gender');

                    if($getGender == 'Male'){

                        if($v->details->hemoglobin >= '13.8' && $v->details->hemoglobin <= '17.2'){
                            $chart_result['male']['normal'] = !empty($chart_result['male']['normal'])?$chart_result['male']['normal']+1:1;
                        }else{
                            $chart_result['male']['danger'] = !empty($chart_result['male']['danger'])?$chart_result['male']['danger']+1:1;
                        }
                    }

                    if($getGender == 'Female'){

                        if($v->details->hemoglobin >= '12.1' && $v->details->hemoglobin <= '15.1'){
                            $chart_result['female']['normal'] = !empty($chart_result['female']['normal'])?$chart_result['female']['normal']+1:1;
                        }else{
                            $chart_result['female']['danger'] = !empty($chart_result['female']['danger'])?$chart_result['female']['danger']+1:1;
                        }                    
                    }

                    break;

                
                case 'uric_acid':
                    $chart_result['male']['normal'] = !empty($chart_result['male']['normal'])?$chart_result['male']['normal']:0;
                   

                    $chart_result['male']['danger'] = !empty($chart_result['male']['danger'])?$chart_result['male']['danger']:0;

                    $chart_result['female']['normal'] = !empty($chart_result['female']['normal'])?$chart_result['female']['normal']:0;
                    $chart_result['female']['danger'] = !empty($chart_result['female']['danger'])?$chart_result['female']['danger']:0;

                    $getGender = User::Where('id',$v->user_id)->value('gender');

                    if($getGender == 'Male'){

                        if($v->details->uric_acid >= '4.0' && $v->details->uric_acid <= '8.5'){
                            $chart_result['male']['normal'] = !empty($chart_result['male']['normal'])?$chart_result['male']['normal']+1:1;
                        }else{
                            $chart_result['male']['danger'] = !empty($chart_result['male']['danger'])?$chart_result['male']['danger']+1:1;
                        }
                    }

                    if($getGender == 'Female'){

                        if($v->details->uric_acid >= '2.7' && $v->details->uric_acid <= '7.3'){
                            $chart_result['female']['normal'] = !empty($chart_result['female']['normal'])?$chart_result['female']['normal']+1:1;
                        }else{
                            $chart_result['female']['danger'] = !empty($chart_result['female']['danger'])?$chart_result['female']['danger']+1:1;
                        }                    
                    }

                    break;

                
                case 'Below normal weight':

                $chart_result['below_weight'][$v->details->date] = !empty($chart_result['below_weight'][$v->details->date])?$chart_result['below_weight'][$v->details->date]+1:1;

                    break;

                case 'Normal weight':

                $chart_result['normal_weight'][$v->details->date] = !empty($chart_result['normal_weight'][$v->details->date])?$chart_result['normal_weight'][$v->details->date]+1:1;

                    break;

                case 'Overweight':

                $chart_result['over_weight'][$v->details->date] = !empty($chart_result['over_weight'][$v->details->date])?$chart_result['over_weight'][$v->details->date]+1:1;

                    break;

                case 'Class I Obesity':

                $chart_result['class_one'][$v->details->date] = !empty($chart_result['class_one'][$v->details->date])?$chart_result['class_one'][$v->details->date]+1:1;
                    break;

                case 'Class II Obesity':

                $chart_result['class_two'][$v->details->date] = !empty($chart_result['class_two'][$v->details->date])?$chart_result['class_two'][$v->details->date]+1:1;

                    break;

                case 'Class III Obesity':

                $chart_result['class_three'][$v->details->date] = !empty($chart_result['class_three'][$v->details->date])?$chart_result['class_three'][$v->details->date]+1:1;
                
                    break;

                case 'Hypothermia':

                $chart_result['hypothermia'][$v->details->date] = !empty($chart_result['hypothermia'][$v->details->date])?$chart_result['hypothermia'][$v->details->date]+1:1;
                
                    break;

                case 'NORMAL':
                case 'Toddler':
                case 'Pre-School':
                case 'Neonate':
                case 'Optimal':
                case 'Normal':

                $chart_result['normal'][$v->details->date] = !empty($chart_result['normal'][$v->details->date])?$chart_result['normal'][$v->details->date]+1:1;
                
                    break;

                

                case 'Fever / Hyperthermia':

                $chart_result['fever'][$v->details->date] = !empty($chart_result['fever'][$v->details->date])?$chart_result['fever'][$v->details->date]+1:1;
                
                    break;

                case 'Very High Fever':

                $chart_result['high_fever'][$v->details->date] = !empty($chart_result['high_fever'][$v->details->date])?$chart_result['high_fever'][$v->details->date]+1:1;
                
                    break;

                case 'Hyperpyrexia':

                $chart_result['hyperpyrexia'][$v->details->date] = !empty($chart_result['hyperpyrexia'][$v->details->date])?$chart_result['hyperpyrexia'][$v->details->date]+1:1;
                
                    break;

                case 'Severe Hypoxemia':

                $chart_result['severe'][$v->details->date] = !empty($chart_result['severe'][$v->details->date])?$chart_result['severe'][$v->details->date]+1:1;
                
                    break;

                case 'Moderate Hypoxemia':
                case 'Moderate':
                case 'Warning':
                case 'Intermediate':

                $chart_result['moderate'][$v->details->date] = !empty($chart_result['moderate'][$v->details->date])?$chart_result['moderate'][$v->details->date]+1:1;
                
                    break;

                case 'Mild Hypoxemia':

                $chart_result['mild'][$v->details->date] = !empty($chart_result['mild'][$v->details->date])?$chart_result['mild'][$v->details->date]+1:1;
                
                    break;

                case 'LOW BLOOD PRESSURE':
                case 'Low':

                $chart_result['low'][$v->details->date] = !empty($chart_result['low'][$v->details->date])?$chart_result['low'][$v->details->date]+1:1;
                
                    break;

                case 'Elevated':

                $chart_result['elevated'][$v->details->date] = !empty($chart_result['elevated'][$v->details->date])?$chart_result['elevated'][$v->details->date]+1:1;
                
                    break;

                case 'HIGH BLOOD PRESSURE(HYPERTENSION) STAGE 1':

                $chart_result['high_bp'][$v->details->date] = !empty($chart_result['high_bp'][$v->details->date])?$chart_result['high_bp'][$v->details->date]+1:1;
                
                    break;

                case 'HIGH BLOOD PRESSURE(HYPERTENSION) STAGE 2':

                $chart_result['very_high_bp'][$v->details->date] = !empty($chart_result['very_high_bp'][$v->details->date])?$chart_result['very_high_bp'][$v->details->date]+1:1;
                
                    break;

                case 'HYPERTENSIVE CRISIS(consult your doctor immediately)':

                $chart_result['bp_crisis'][$v->details->date] = !empty($chart_result['bp_crisis'][$v->details->date])?$chart_result['bp_crisis'][$v->details->date]+1:1;
                
                    break;

                case 'Border Line':

                $chart_result['border_line'][$v->details->date] = !empty($chart_result['border_line'][$v->details->date])?$chart_result['border_line'][$v->details->date]+1:1;
                
                    break;

                case 'High':
                case 'Large':

                $chart_result['high'][$v->details->date] = !empty($chart_result['high'][$v->details->date])?$chart_result['high'][$v->details->date]+1:1;
                
                    break;

                case 'Very High':

                $chart_result['very_high'][$v->details->date] = !empty($chart_result['very_high'][$v->details->date])?$chart_result['very_high'][$v->details->date]+1:1;
                
                    break;

                case 'Acidic':

                $chart_result['acidic'][$v->details->date] = !empty($chart_result['acidic'][$v->details->date])?$chart_result['acidic'][$v->details->date]+1:1;
                
                    break;

                case 'Very Acidic':

                $chart_result['very_acidic'][$v->details->date] = !empty($chart_result['very_acidic'][$v->details->date])?$chart_result['very_acidic'][$v->details->date]+1:1;
                
                    break;

                case 'Too Alkaline':

                $chart_result['too_alkaline'][$v->details->date] = !empty($chart_result['too_alkaline'][$v->details->date])?$chart_result['too_alkaline'][$v->details->date]+1:1;
                
                    break;

                case 'Dangerous':

                $chart_result['dangerous'][$v->details->date] = !empty($chart_result['dangerous'][$v->details->date])?$chart_result['dangerous'][$v->details->date]+1:1;
                
                    break;

                case 'Dangerously low':

                $chart_result['dangerous_low'][$v->details->date] = !empty($chart_result['dangerous_low'][$v->details->date])?$chart_result['dangerous_low'][$v->details->date]+1:1;
                
                    break;

                case 'Small':

                $chart_result['small'][$v->details->date] = !empty($chart_result['small'][$v->details->date])?$chart_result['small'][$v->details->date]+1:1;
                
                    break;
                
                default:
                    // code...
                    break;
            }
        }
        
        return $chart_result;
    }
    

}