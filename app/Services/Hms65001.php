<?php
namespace App\Http\Controllers;
use Log;
use SimpleXMLElement;

class Hms6500 extends BaseService
{   

    private $user;
    public function __construct(User $user){

    }

    public function arrayToXml($array, $rootElement = null, $xml = null) {
	    $_xml = $xml; 
	      
	    // If there is no Root Element then insert root 
	    if ($_xml === null) { 
	        $_xml = new SimpleXMLElement($rootElement !== null ? $rootElement : '<downinfo/>'); 
	    } 
	      
	    // Visit all key value pair 
	    foreach ($array as $k => $v) { 
	          
	        // If there is nested array then 
	        if (is_array($v)) {
	            // Call function for nested array 
	            arrayToXml($v, $k, $_xml->addChild($k)); 
	        } else { 
	            // Simply add child element.  
	            $_xml->addChild($k, $v); 
	        } 
	    } 
      
    	return $_xml->asXML(); 
	} 

    public function loginBackground()
    {
        $input = Input::all();
        Log::Info($input); die;
    }

    /***
        HMS LOGIN
    **/
    public function loginBackground1(Request $request){
    	try{

    		$input 				= $request->all();

            Log::Info($input); die;

    		$input['password'] 	= preg_replace("/[\n\r]/","",$input['password']);
			$details 			= json_encode($input);
 
        	$mydata['log_details'] 	= $details;
        	$mydata['url']			= 'login';
			TechDetailsLog::create($mydata);
        	$patient_login = PeripheralUsers::where('patient_id',$input['username'])
                                            ->where('one_time_password',$input['password'])->first();


            $downlinfo = array();

            if(!empty($patient_login)){

            	// $patient_details = Patient::where('patient_id',1)->first();
            	$patient_details = Patient::where('patient_id', $input['username'])->first();

            	// echo '<pre>'.print_r($patient_details , true).'</pre>'; die;

            	// $downlinfo = 'HTTP_SUCCESS:PHPSESSID='.session()->getId();
                $downlinfo = 'HTTP_SUCCESS:PHPSESSID='.$patient_details['patient_id'];
            	$downlinfo .= '<?xml version=\"1.0\" encoding=\"GBK\"?>';
            	$downlinfo .= ' \n ';
                $downlinfo .= "<downinfo>";
                $downlinfo .= ' \n ';
                $downlinfo .= "<used>2</used> \n";
                $downlinfo .= "<total>1000</total> \n";
                $downlinfo .= "<username>".$patient_details['first_name'].' '.$patient_details['last_name']."</username> \n";
                $downlinfo .= "<age>".Carbon::parse($patient_details['dob'])->age."</age> \n";
                $downlinfo .= "<sex>".($patient_details['gender'] == 'Female' ? 1 : 0 )."</sex> \n"; // 0-If Male 1-If Female
                $downlinfo .= "<birthday>".$patient_details['dob']."</birthday> \n";
                $downlinfo .= "<phone>".$patient_details['mobile']."</phone> \n";
                $downlinfo .= "<height>1600</height> \n";
                $downlinfo .= "<weight>600</weight> \n";
                $downlinfo .= "</downinfo>";
                $downlinfo .= ' \n ';

                // dd($downlinfo);

                return $downlinfo;

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

            $patient_id  = $request["PHPSESSID"];

            $mydata['log_details']  = $details;
            $mydata['url']          = 'physical report';
            TechDetailsLog::create($mydata);

            $patient_details       = Patient::where('patient_id', $patient_id)->first()->toArray();
            // $peripheral_details    = PeripheralUsers::where('patient_id',$patient_id)->first();

            $file  = $request->file('filename');

            $xml = simplexml_load_string($input['content'], "SimpleXMLElement", LIBXML_NOCDATA);
            $patient_vitals         = (array) $xml;

            $peripheral_details  = PeripheralUsers::where('patient_id', $patient_vitals['user'])
                                            ->where('one_time_password', $patient_vitals['pass'])->first();

            /* if (!empty($file)){

                $ldi_data['patient_id']         = $patient_id;
                $ldi_data['consult_id']         = $peripheral_details['consult_id'];
                $ldi_data['peripheral_id']      = $peripheral_details['serial'];
                $ldi_data['notes']              = 'ECG';
                $ldi_data['page_id']            = 786;

                // $filename                   =  $ldi_data['patient_id'].'_'. \Carbon\Carbon::now()->timestamp .'.' .'.png';

                $filename  = str_replace(' ','',$patient_details['patient_id'].'_'. \Carbon\Carbon::now()->timestamp .'.png';

                $ldi_data['file_path']  = 'https://'.FTPService::getPhysicalPath(). 'FolioPatient'. $patient_details['patient_id']. '/'. $filename;

                // $ldi_data['file_path']      = 'https://'.FTPService::getPhysicalPath().'FolioPatient'. '/'.$patient_details['patient_id']. '/'. $filename;
                $path                   = FTPService::getPhysicalPath().'FolioPatient'. '/'.$patient_details['patient_id'];

                $fileProcess            = FileProcess::uploadFile($ldi_data, $filename, $path);

                $ldi_data['docs_type'] = ".png";
                $ldi_data['file_name'] = $filename;

                $patient = Document::saveall($ldi_data);
            }*/

            if (!empty($file)){

                $filename  = $patient_details['patient_id'].'_'. \Carbon\Carbon::now()->timestamp .'.png';

                $ldi_data['docs_type']      = "png";
                $ldi_data['title']          = 'ECG';
                $ldi_data['notes']          = 'ECG';
                $ldi_data['file_name']      = $filename;
                $ldi_data['page_id']        = 786;
                $ldi_data['patient_id']     = $patient_details['patient_id'];
                $path                       = FTPService::getPhysicalPath().'FolioPatient'. '/'.$patient_details['patient_id'];

                $url = $path.'/'.$filename;
                $ldi_data['file_path']  = 'https://'.FTPService::getPhysicalPath(). 'FolioPatient/'. $patient_details['patient_id']. '/'. $filename;
                $successful_upload  = FTPService::upload($file, $url);
                $saveEntry          = Document::saveall($ldi_data);
            }

            return "HTTP_SUCCESS:";

        }catch(Exception $e){
            // Exception
        }
    }

    /***
        VItals storing
    ***/
    public function originalData(Request $request){
        try{

            $input      = $request->all();

            $sessionId  = $request["PHPSESSID"];
            $fileName   = substr($sessionId,0,10);

            // $patient_id  = 83;
            $patient_id  = $request["PHPSESSID"];

            if(!empty($input['content'])){
                $xml = simplexml_load_string($input['content'], "SimpleXMLElement", LIBXML_NOCDATA);
               
                $patient_details        = Patient::where('patient_id', $patient_id)->first()->toArray();
                $patient_vitals         = (array) $xml;

                // $patient_vitals = array("user" => "83","pass"=>"000","spo2"=>"94","weight"=>"60.0","height"=>"160.0","pulserate"=>"105","date"=>"2020-06-01 18:11:43");


                if(!empty($patient_details) && !empty($patient_vitals)){

                    $details                = json_encode($patient_vitals);
                    $mydata['log_details']  = $details;
                    $mydata['url']          = 'original Data';
                    TechDetailsLog::create($mydata);

                    // $peripheral_details  = PeripheralUsers::where('patient_id',$patient_id)->first();

                    $peripheral_details  = PeripheralUsers::where('patient_id', $patient_vitals['user'])
                                            ->where('one_time_password', $patient_vitals['pass'])->first();

                    $data['patient_id']         = $patient_details['patient_id'];
                    $data['consult_id']         = $peripheral_details['consult_id'];
                    $data['peripheral_id']      = $peripheral_details['serial'];

                    if(empty($patient_vitals['FileInfo'])){

                        $data['page_id'] = 786;

                        if(!empty($patient_vitals['date'])){
                            $data['vital_record_time'] = $patient_vitals['date'];
                        }

                        // BloodPressure
                        if( (!empty($patient_vitals['sys']) || !empty($patient_vitals['dia'])) 
                            && !empty($peripheral_details['blood_pressure']) ){
                        
                            if(!empty($patient_vitals['sys'])){
                                $data['bp_sys'] = $patient_vitals['sys'];
                            }

                            if(!empty($patient_vitals['dia'])){
                                $data['bp_dia']  = $patient_vitals['dia'];
                            }

                            if(!empty($patient_vitals['mean'])){
                                $data['bp_mean']   = $patient_vitals['mean'];
                            }

                            if(!empty($patient_vitals['pulserate'])){
                                $data['bp_pulse']   = $patient_vitals['pulserate'];
                                $data['bp_status']  = 'Don`t know';
                            }

                            $created = BloodPressure::saveall($data,  $request);
                        }

                        // Blood sugar
                        if(!empty($patient_vitals['bloodsuger']) && !empty($peripheral_details['blood_sugar'])){
                            $data['blood_glucose_value']    = $patient_vitals['bloodsuger'];
                            $data['blood_glucose_uom']      = 'mmol/L';
                            $created = BloodSugar::saveall($data,  $request);
                        }


                        // Spo2
                        if(!empty($patient_vitals['spo2']) && !empty($peripheral_details['spo2'])){
                            $data['spo2_value']                 = $patient_vitals['spo2'];
                            $data['spo2_value_measurement']     = '%';
                            $created = Spo2::saveall($data,  $request);
                        }


                        // HeartRate
                        if(!empty($patient_vitals['heartrate']) && !empty($peripheral_details['heart_rate'])){
                            $data['pulse_value']    = $patient_vitals['heartrate'];
                            $data['pulse_uom']      = 'Bpm';
                            $created = HeartRate::saveall($data,  $request);
                        }



                      /*  if(!empty($patient_vitals['weight'])){
                            $data['wt_value']          = $patient_vitals['weight'];
                            $data['wt_measurement']    = 'Kg';
                        }

                        if(!empty($patient_vitals['height'])){
                            $data['ht_value']           = $patient_vitals['height'];
                            $data['ht_measurement']     = "cm";
                        }

                        if(!empty($patient_vitals['weight']) && !empty($patient_vitals['height'])){
                            $metre          = $patient_vitals['height'] * 0.01;
                            $bmi            = $patient_vitals['weight'] / ($metre * $metre);
                            $data['BMI']    = $bmi;
                        }*/


                        // Temperature
                        if(!empty($patient_vitals['temp']) && !empty($peripheral_details['temperature']) ){
                            $data['value']       = $patient_vitals['temp'];
                            $data['unit']        = '°C';
                            $data['type']        = 'oral';
                            $created = Temperature::saveall($data,$request);
                        }

                        // Urine
                        if(!empty($patient_vitals['urine_ph_value']) && !empty($peripheral_details['urine']) ){
                            $data['urine_value']                = $patient_vitals['urine_ph_value'];
                            $data['urine_value_measurement']    = 'oral';
                            $created = Urine::saveall($data, $request);
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


            $mydata['log_details']  = $details;
            $mydata['url']          = 'basicInfo';
            TechDetailsLog::create($mydata);

            // ob_flush();
            // return "HTTP_SUCCESS:uniquecode=".$fileName. ";";
            return "HTTP_SUCCESS:uniquecode=0000000001;hospitalid=1";

            // return response()->json("HTTP_SUCCESS:uniquecode=".$fileName. ";");

        }catch(Exception $e){
            // Exception
        }
    }


    public function trendData(Request $request){
    	// try{

            $mydata['log_details'] = 'incoming';
            TechDetailsLog::create($mydata);

    		$input 			= $request->all();
        	$details 		= json_encode($input);

        	$mydata['log_details'] 	= $details;
        	$mydata['url']			= 'trend data';
        	TechDetailsLog::create($mydata);

            return "HTTP_SUCCESS:filename=123.txt;";

   //  	}catch(Exception $e){
			// // Exception
   //  	}
    }


    public function controlFile(Request $request){
        try{

            $input      = $request->all();
            $details    = json_encode($input);

            $sessionId  = $request["PHPSESSID"];
            $fileName   = substr($sessionId,0,10);


            $mydata['log_details']  = $details;
            $mydata['url']          = 'control File';
            TechDetailsLog::create($mydata);

            return "HTTP_SUCCESS:filename=123.txt;";

        }catch(Exception $e){
            // Exception
        }
    }


}