<?php
namespace App\Services;

use App\Entities\Doc;
use App\Entities\User;
use App\Entities\Vital;
use App\Traits\S3;
use App\Utils\AuthHelper;
use Illuminate\Http\Request;
use Log;

class BluetoothPeripheralService extends BaseService
{

    use AuthHelper, S3;

    private $_model;

    private $_user_id;

    public function __construct()
    {
        parent::__construct();
        $this->_model = new Vital;
    }

    public function autoLogin(Request $request)
    {
        $requestedData = $request->json()->all();
        $model = User::where('id', $requestedData['user_id'])
            ->whereHas('role', function ($query) {
                $query->where('code', 'folio');
            })
            ->first();

        if (isset($model->id)) {
            $result = [];
            $result['info'] = array_only($model->getBasicInfo(), ['id', 'name', 'gender']);
            $Authorization = $result['token'] = $this->getAuthorization(['userId' => $model->id]);
            $token_details = $this->decodeJwt($Authorization);
            if ($token_details->exp) {
                $result['token_expiration_time'] = $token_details->exp;
            }

            $result['status'] = 'verified_user';

            return $this->httpResponse->setHttpData($result)
                ->setHttpHeader(['Authorization' => $Authorization])
                ->jsonResponse();
        } else {
            $message = trans('auth.failed');
            return $this->httpResponse->setHttpMessage($message)->setHttpCode(401)->jsonResponse();
        }
    }

    public function login(Request $request)
    {
        $requestedData = $request->json()->all();
        $model = User::where('id', $requestedData['username'])
            ->whereHas('role', function ($query) {
                $query->where('code', 'folio');
            })
            ->first();

        if (isset($model->id) && $model->isValidPeripheralPassword($requestedData)) {
            $result = [];
            $result['info'] = array_only($model->getBasicInfo(), ['id', 'name', 'gender']);

            $Authorization = $result['token'] = $this->getAuthorization(['userId' => $model->id]);

            $token_details = $this->decodeJwt($Authorization);

            if ($token_details->exp) {
                $result['token_expiration_time'] = $token_details->exp;
            }

            $result['status'] = 'verified_user';

            return $this->httpResponse->setHttpData($result)
                ->setHttpHeader(['Authorization' => $Authorization])
                ->jsonResponse();

        } else {
            $message = trans('auth.failed');
            return $this->httpResponse->setHttpMessage($message)->setHttpCode(401)->jsonResponse();
        }
    }

    public function capture(Request $request)
    {          
        Log::channel('evaitals')->debug('EVITALZ', ['data' => $request->all()]);
        $this->_user_id = $request->user()->id;

        if($request->has('vitals')) {
            $vitals = $request->input('vitals');

            foreach($vitals as $vital_name => $vital_data) {

                switch ($vital_name) {
                    case 'Pulse Oximeter':
                        $this->savePulseOximeter($vital_data);
                        break;

                    case 'Blood Pressure':
                        $this->saveBP($vital_data);
                        break;
                    
                    case 'Temperature':
                        $this->saveTemperature($vital_data);
                        break;

                    case 'Blood Glucose':
                        $this->saveBloodGlucose($vital_data);
                        break;

                    case 'Cholesterol':
                        $this->saveCholesterol($vital_data);
                        break;

                    case 'ECG':
                        $this->saveECG($vital_data);
                        break;

                    case 'HCT':
                        $this->saveHCT($vital_data);
                        break;

                    case 'Hemoglobin':
                        $this->saveHemoglobin($vital_data);
                        break;

                    case 'Ketone':
                        $this->saveKeytone($vital_data);
                        break;

                    case 'Uric Acid':
                        $this->saveUricAcid($vital_data);
                        break;
                }
            }

            return $this->httpResponse->setHttpMessage('Data Captured Successfully')->jsonResponse();
        }
    }

    private function saveUricAcid($data)
    {
        $vitalData = [
            'user_id' => $this->_user_id,
            'slug' => 'uric_acid',
            'details' => [
                'date' => $data['date_time'],
                'time' => date('H:i', strtotime($data['date_time'])),
                'uric_acid' => $data['uric_acid'],
            ],
        ];

        $this->saveVitalData($vitalData);
    }

    private function saveKeytone($data)
    {
        $vitalData = [
            'user_id' => $this->_user_id,
            'slug' => 'keytone',
            'details' => [
                'date' => $data['date_time'],
                'time' => date('H:i', strtotime($data['date_time'])),
                'keytone' => $data['ketone'],
            ],
        ];

        $this->saveVitalData($vitalData);
    }

    private function saveHemoglobin($data)
    {
        $vitalData = [
            'user_id' => $this->_user_id,
            'slug' => 'hemoglobin',
            'details' => [
                'date' => $data['date_time'],
                'time' => date('H:i', strtotime($data['date_time'])),
                'hemoglobin' => $data['HB'],
            ],
        ];

        $this->saveVitalData($vitalData);
    }

    private function saveHCT($data)
    {
        $vitalData = [
            'user_id' => $this->_user_id,
            'slug' => 'hct',
            'details' => [
                'date' => $data['date_time'],
                'time' => date('H:i', strtotime($data['date_time'])),
                'hct' => $data['HCT'],
            ],
        ];

        $this->saveVitalData($vitalData);
    }

    private function saveECG($data)
    {
        $image = $data['image'];
        $temp_file_name = time() . '.png';
        \Storage::disk('public')->put($temp_file_name, base64_decode($image)); 
        $file_location = \Storage::disk('public')->path($temp_file_name);

        $path_parts = pathinfo($file_location);

        $file = new \Illuminate\Http\UploadedFile(
            $file_location,
            $path_parts['basename'],
            getimagesize($file_location)['mime'],
            filesize($file_location),
            TRUE,
            TRUE
        );

        $prefix = 'EV_' . $data['placement'];
        $path = config('api.fileSystem.peripheral') . 'ECG';

        $response = $this->diskStorage($file, $path, $prefix, 'private');

        if($response['success']) {

            $insert_data = [
                'user_id' => $this->_user_id,
                'created_by' => $this->_user_id,
                'document_source' => 'imaging',
                'addition_info' => [
                    'date' => $data['date_time'],
                    'title' => 'ECG - ' . $data['placement'],
                    'notes' => $data['case'],
                    'pulse_rate' => $data['PR'],
                ],
                'properties' => [
                    'file_path' => $response['fullPath'],
                    'file_name' => $response['filename'],
                ],
            ];

            Doc::create($insert_data);

            \Storage::disk('public')->delete($temp_file_name);
        }

        $vitalData = [
            'user_id' => $this->_user_id,
            'slug' => 'heart-rate',
            'details' => [
                'date' => $data['date_time'],
                'time' => date('H:i', strtotime($data['date_time'])),
                'heart' => $data['PR'],
                'unit' => 'Bpm',
            ],
        ];

        $this->saveVitalData($vitalData);
    }

    private function savePulseOximeter($data)
    {
        $vitalData = [
            'user_id' => $this->_user_id,
            'slug' => 'spO2',
            'details' => [
                'date' => $data['date_time'],
                'time' => date('H:i', strtotime($data['date_time'])),
                'spo2' => $data['SpO2'],
                'pulse_rate' => $data['PR'],
                'unit' => '%',
            ],
        ];

        $this->saveVitalData($vitalData);
    }

    private function saveBP($data)
    {
        $vitalData = [
            'user_id' => $this->_user_id,
            'slug' => 'blood-pressure',
            'details' => [
                'date' => $data['date_time'],
                'time' => date('H:i', strtotime($data['date_time'])),
                'systolic' => $data['SYS'],
                'diastolic' => $data['DIA'],
                'pulse' => $data['PR'],
            ],
        ];

        $this->saveVitalData($vitalData);
    }

    private function saveTemperature($data)
    {
        $vitalData = [
            'user_id' => $this->_user_id,
            'slug' => 'temperature',
            'details' => [
                'date' => $data['date_time'],
                'time' => date('H:i', strtotime($data['date_time'])),
                'temperature' => $data['Tempf'],
                'unit' => 'Fahrenheit',
            ],
        ];

        $this->saveVitalData($vitalData);
    }

    private function saveBloodGlucose($data)
    {
        $vitalData = [
            'user_id' => $this->_user_id,
            'slug' => 'blood-sugar',
            'details' => [
                'date' => $data['date_time'],
                'time' => date('H:i', strtotime($data['date_time'])),
                'blood_sugar' => $data['mmol'],
                'unit' => 'mmol/L',
            ],
        ];

        $this->saveVitalData($vitalData);
    }

    private function saveCholesterol($data)
    {
        $vitalData = [
            'user_id' => $this->_user_id,
            'slug' => 'lipid-profile',
            'details' => [
                'date' => $data['date_time'],
                'time' => date('H:i', strtotime($data['date_time'])),
                'total' => $data['cholesterol'],
                'total_unit' => 'mg/d',
            ],
        ];

        $this->saveVitalData($vitalData);
    }

    protected function saveVitalData($data) {
        $new_request = new Request();
        $new_request->merge($data);
        $new_request->setJson($new_request);

        $this->_model->modelCreateProcess($new_request);
    }
}
