<?php

namespace App\Services;

use App\Entities\User;
use App\Entities\Vital;
use App\Entities\Doc;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use SimpleXMLElement;
use App\Utils\AuthHelper;
use Carbon\Carbon;

class Hms6500 extends BaseService
{
    use AuthHelper;

    /***
        HMS LOGIN
    ***/
    public function loginBackground(Request $request){
        try{
            $input = $request->all();

            $input['password']  = preg_replace("/[\n\r]/","",$input['password']);
            // $details            = json_encode($input);

            $patient_login = User::where('id', $input['username'])
                                 ->whereJsonContains('address->peripheral_password', $input['password'])
                                 ->first();

            if(!empty($patient_login)){
                $peripheral_secret = md5($patient_login['id']);
                $patient_login->peripheral_secret = $peripheral_secret;
                $patient_login->save();

                $patient_login_info = 'HTTP_SUCCESS:PHPSESSID='.$peripheral_secret;
                $patient_login_info .= '<?xml version=\"1.0\" encoding=\"GBK\"?>';
                $patient_login_info .= ' \n ';
                $patient_login_info .= "<downinfo>";
                $patient_login_info .= ' \n ';
                $patient_login_info .= "<used>2</used> \n";
                $patient_login_info .= "<total>1000</total> \n";
                $patient_login_info .= "<username>".$patient_login->getFullName()."</username> \n";
                $patient_login_info .= "<age>".Carbon::parse($patient_login['dob'])->age."</age> \n";
                $patient_login_info .= "<sex>".($patient_login['gender'] == 'Female' ? 1 : 0 )."</sex> \n"; // 0-If Male 1-If Female
                $patient_login_info .= "<birthday>".$patient_login['dob']."</birthday> \n";
                $patient_login_info .= "<phone>".$patient_login['mobile']."</phone> \n";
                $patient_login_info .= "<height>1600</height> \n";
                $patient_login_info .= "<weight>600</weight> \n";
                $patient_login_info .= "</downinfo>";
                $patient_login_info .= ' \n ';

                return $patient_login_info;

            } else {
                return "ERR_INVALID_LOGIN_ID:Invalid account details.";
            }

        }catch(Exception $e){
            // Exception
            Log::error($e->getMessage()); die;
        }
    }

    /***
        ECG FIles
     ***/
     public function physicalReport(Request $request){
        try{
            $input      = $request->all();
            $details    = json_encode($input);

            $sessionId  = $request["PHPSESSID"];
            $fileName   = substr($sessionId,0,10);

            $peripheral_secret  = $request["PHPSESSID"];
            $patient_details    = User::where('peripheral_secret', $peripheral_secret)->first()->toArray();            

            $file = $request->file('filename');
            // Log::info($input);

            if(!empty($input['content'])){
                $xml            = simplexml_load_string($input['content'], "SimpleXMLElement", LIBXML_NOCDATA);
                $patient_vitals = (array) $xml;
            }

            if (!empty($file)){

                $insert_data = array();
                $insert_data['created_by']      = $patient_details['id'];
                $insert_data['user_id']         = $patient_details['id'];
                $insert_data['document_source'] = 'imaging';

                $insert_data['addition_info']['title']          = 'ECG';
                $insert_data['addition_info']['notes']          = 'ECG';
                
                $imageName  = $patient_details['id'].'_'. \Carbon\Carbon::now()->timestamp .'.png';
            
                $destinationPath = storage_path('/app/uploadDocs');
                $request->file('filename')->move($destinationPath, $imageName);

                $insert_data['properties']['file_path'] = $imageName;
                $insert_data['properties']['file_name'] = $imageName;

                Doc::create($insert_data);
            }

            return "HTTP_SUCCESS:";

        }catch(Exception $e){
            // Exception
        }
    }


    /***
        Vitals storing
    ***/
    public function originalData(Request $request){
        try{
            $input       = $request->all();
            $sessionId   = $request["PHPSESSID"];
            $fileName    = substr($sessionId,0,10);
            $peripheral_secret  = $request["PHPSESSID"];

            if(!empty($input['content'])){
                $xml = simplexml_load_string($input['content'], "SimpleXMLElement", LIBXML_NOCDATA);

                $patient_details = User::where('peripheral_secret', $peripheral_secret)->first()->toArray();    
                $patient_vitals  = (array) $xml;
                $data            = array();

                // Log::info($patient_details);
                // Log::info($patient_vitals);

                if(!empty($patient_details) && !empty($patient_vitals)){

                    if(empty($patient_vitals['FileInfo'])){

                        if(!empty($patient_vitals['date'])){
                            $data['details']['date'] = $patient_vitals['date'];
                            $data['details']['time'] = Carbon::parse($data['details']['date'])->format('H:i');
                        }

                        $data['user_id'] = $patient_details['id'];

                        // BloodPressure
                        if( !empty($patient_vitals['sys']) || !empty($patient_vitals['dia']) ){
                        
                            if(!empty($patient_vitals['sys'])){
                                $data['details']['systolic'] = $patient_vitals['sys'];
                            }

                            if(!empty($patient_vitals['dia'])){
                                $data['details']['diastolic']  = $patient_vitals['dia'];
                            }

                            if(!empty($patient_vitals['mean'])){
                                $data['details']['mean']   = $patient_vitals['mean'];
                            }

                            if(!empty($patient_vitals['pulserate'])){
                                $data['details']['pulse']   = $patient_vitals['pulserate'];
                                $data['details']['status']  = 'Don`t know';
                            }

                            $data['slug']       = 'blood-pressure';
                            Vital::createModel($request, $data);
                        }

                        // Blood sugar
                        if(!empty($patient_vitals['bloodsuger'])){
                            $data['details']['blood_sugar']    = $patient_vitals['bloodsuger'];
                            $data['details']['unit']      = 'mmol/L';
                            $data['details']['type']      = 'Random';
                            $data['slug']       = 'blood-sugar';
                            Vital::createModel($request, $data);
                        }

                        // Spo2
                        if(!empty($patient_vitals['spo2'])){
                            $data['details']['spo2']    = $patient_vitals['spo2'];
                            $data['details']['unit']    = '%';
                            $data['slug']       = 'spO2';
                            Vital::createModel($request, $data);
                        }

                        // HeartRate
                        if(!empty($patient_vitals['heartrate'])){
                            $data['details']['heart']    = $patient_vitals['heartrate'];
                            $data['details']['unit']     = 'Bpm';
                            $data['slug']       = 'heartrate';
                            Vital::createModel($request, $data);
                        }

                        // Temperature
                        if(!empty($patient_vitals['temp'])){
                            $data['details']['temperature'] = $patient_vitals['temp'];
                            $data['details']['unit']        = 'Celsius';
                            $data['details']['type']        = 'Oral';
                            $data['slug']       = 'temperature';
                            Vital::createModel($request, $data);
                        }

                        // Urine
                        if(!empty($patient_vitals['urine_ph_value'])){
                            $data['details']['urine']         = $patient_vitals['urine_ph_value'];
                            $data['details']['sugar']         =  0;
                            $data['details']['sugar_unit']    = 'mg/dL';

                            $data['details']['leukocytes']  = '+';
                            $data['details']['protein']     = '+';
                            $data['details']['rbc']         = '+';

                            $data['slug']       = 'urine';
                            Vital::createModel($request, $data);
                        }

                    } // File Info Ends

                    

                } // Vitals and Patient Ends

            } // Input need Content

            return "HTTP_SUCCESS:filename=123.txt;";
            // echo "HTTP_SUCCESS:filename=123.txt;";

        }catch(Exception $e){
            // Exception
        }
    }


    public function basicInfo(Request $request){
        try{
            $input      = $request->all();
            $details    = json_encode($input);

            $sessionId  = $request["PHPSESSID"];
            $fileName   = substr($sessionId,0,10);

            return "HTTP_SUCCESS:uniquecode=0000000001;hospitalid=1";
        }catch(Exception $e){
            // Exception
        }
    }


    public function trendData(Request $request){
        $input = $request->all();
        return "HTTP_SUCCESS:filename=123.txt;";
    }

    public function controlFile(Request $request){
        try{
            $input = $request->all();
            return "HTTP_SUCCESS:filename=123.txt;";
        }catch(Exception $e){
            // Exception
        }
    }

}
