<?php

namespace App\Enums;

abstract class EnumAnalyticsChart {

    
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

            switch ($flag) {
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
                    $chart_result['class_one'] = 1;
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