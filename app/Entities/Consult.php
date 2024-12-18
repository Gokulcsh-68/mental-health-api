<?php

namespace App\Entities;
use App\Entities\AvailabilityDetail;
use App\Entities\Provider;
use App\Enums\ConsultStatusTypeEnum;
use App\Services\CureselectApis\TeleConsultApiService;
use Carbon\Carbon;
use DB;

class Consult extends BaseModel
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
        "unique_id", "patient_in_room", "provider_in_room", "patient_id", "provider_id", "hospital_id", "consult_type", "consult_slot_type", "consult_date_time", "consult_duration", "speciality", "unit", "slots", "started_date_time", "ended_date_time", "consent", "camera_id", "consult_notes", "Addendum_notes", "reason_for_consult", "status"
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        
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
        'patient_in_room', 'provider_in_room', 'started_date_time', 'ended_date_time',
        'consent', 'consult_notes', 'Addendum_notes', 'status'
        
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

    protected $_teleconsult_service;

    public function __construct()
    {
        parent::__construct();
        $this->_teleconsult_service = new TeleConsultApiService;
    }

    public function getModelList()
    {
        $request = app('request');

        $filters = [];



        if($request->get('participant_ref_number')){
            $filters['participant_ref_number'] = [$request->get('participant_ref_number')];

        }else if($request->user()->role->code == 'hospitalgroup'){
            
             $provider_id = Provider::where('group_id',$request->user()->staff->group_id)->pluck('user_id')->toArray();
             $filters['participant_ref_number'] = $provider_id;
             if(empty($provider_id)){

                $model = new Consult;
                return $model;

             }

        }else if($request->user()->role->code == 'hospital'){
            
             $provider_id = Provider::where('hospital_id',$request->user()->staff->hospital_id)->pluck('user_id')->toArray();
             $filters['participant_ref_number'] = $provider_id;
             if(empty($provider_id)){

                $model = new Consult;
                return $model;

             }

        }else{

            $filters['participant_ref_number'] = [$request->user()->id];
        }

        if ($request->query('from_date')) {
            $filters['scheduled_from_date'] = date('Y-m-d',strtotime($request->get("from_date")));
            $filters['scheduled_from_date'] = $filters['scheduled_from_date'].' 00:00:00';
        }

        if ($request->query('to_date')) {
            $filters['scheduled_to_date'] = date('Y-m-d',strtotime($request->get("to_date")));
            $filters['scheduled_to_date'] = $filters['scheduled_to_date'].' 23:59:59';
        }


        if ($request->query('consult_status')) {
            $filters['consult_status'] = $request->query('consult_status');
        }
        
        if ($request->query('consult_id')) {
            $filters['consult_id'] = $request->query('consult_id');
        }
        
        $limit = $this->getResourceDataFetchLimit();
        $page = app('request')->get('page') ? app('request')->get('page') : 1;

        // dd(app('request')->all(), $limit, $page);

        return $this->_teleconsult_service->fetch($filters, $limit, $page);
    }

    protected function createModel($request)
    {
        $data = $this->getModelAttributes($request);

        DB::beginTransaction();
        try {

            $patient = User::find($data['patient_id']);
            $provider = User::find($data['provider_id']);

            $teleconsult_config = config('api.teleconsult');

               
            $addition_value = [
                        'consult_speciality' => $data['speciality'],
                        'api_end_point' => $teleconsult_config['api_return_url'],
                        'api_end_version' => $teleconsult_config['api_return_url_version'],
                        'x_name' => 'garuda'
                    ];


            $consult_additional_info = null;
            $hospital_id = '';
            if($request->user()->role->code == 'hospital'){
                $hospital_id = $request->user()->staff->hospital_id;
                $consult_additional_info['organization_id'] = $request->user()->staff->hospital_id;
            }

            // Added Additional info for Consult
            $patient_addition_value = $addition_value;

            if (!empty($data['patient_name']['additional_info'])) {
                $patient_addition_value = array_merge($patient_addition_value, $data['patient_name']['additional_info']);
            }

            if (!empty($data['patient_name']['peripheral_credentials'])) {
                $patient_addition_value['peripheral_credentials'] = $data['patient_name']['peripheral_credentials'];
            }

            $payload = [
                'consult_date_time' => $data['consult_date_time'],
                'consult_type' => 'virtual',
                'consult_reason' => $data['reason_for_consult'],
                'service_provider' => $teleconsult_config['default_service_provider'],
                'additional_info' => $consult_additional_info,
                'patient' => [
                    'id' => $patient->id,
                    'name' => $patient->getFullName(),
                    'email' => $patient->email,
                    'phone' => $patient->mobile_number,
                    'gender' => $patient->gender,
                    'profile_pic' => $patient->profile_image,
                    'additional_info' => $patient_addition_value
                ],
            ];

           
            if($request->get('teleType') == '1'){

               $payload['provider'] =  [
                        'id' => $request->get('provider_id'),
                        'name' =>$request->get('consult_doctor'),
                        'email' => null,
                        'phone' => $request->get('doctor_mobile'),
                        'gender' => null,
                        'profile_pic' => null,
                        'additional_info' => $addition_value,
                    ];

                }else if($request->get('teleType') == '3'){


               $payload['provider'] =  [
                        'id' => $request->get('provider_id'),
                        'name' =>$request->get('consult_doctor'),
                        'email' => $request->get('doctor_email'),
                        'phone' => null,
                        'gender' => null,
                        'profile_pic' => null,
                        'additional_info' => $addition_value,
                    ];

                }else{

                  $payload['provider'] = [
                        'id' => $provider->id,
                        'name' =>$provider->getFullName(),
                        'email' => $provider->email,
                        'phone' => $provider->mobile_number,
                        'gender' => $provider->gender,
                        'profile_pic' => $provider->profile_image,
                        'additional_info' => $addition_value,
                    ];
                }


            if(!empty($data['cart_camera']))
            {
                $payload['additional_info'] += ['camera'=>$data['cart_camera']];
            }

            $teleconsult_response = $this->_teleconsult_service->create($payload);
            // dd($teleconsult_response);

            if(isset($teleconsult_response['consult_id']) && is_array($request->get('slots'))) {
                $selectedSlots = $data['slots'];
                $data['unit'] = count($data['slots']);
                $data['slots'] = json_encode($data['slots']);

                // AvailabilityDetail::where('provider_id', $data['provider_id'])
                //     ->whereIn('id', $selectedSlots)
                //     ->update(array("slot_status" => 'Booked'));

                // DB::commit();
            }

            $model = new Consult;
            $model->id = $teleconsult_response['consult_id'];

            return $model;

        } catch(Exception $e) {
            exceptionLogger("Consult Create Rollback", $e);
            // DB::rollback();
        }
        
        return null;
    }

    protected function updateModel($id, $request, $only = [])
    {

        $data = $this->getModelAttributes($request);

        DB::beginTransaction();
        try {

            if ($request->get('started_date_time')) {
                $request->merge(['started_date_time' => Carbon::parse($data['started_date_time'])->toDateTimeString()]);
            }

            if ($request->get('ended_date_time')) {
                $request->merge(['ended_date_time' => Carbon::parse($data['ended_date_time'])->toDateTimeString()]);
            }
            if ($request->get('status')) {
                switch (strtoupper($data['status'])) {

                    case 'STARTED':
                        $request->merge(['status' => ConsultStatusTypeEnum::STARTED]);
                        break;

                    case 'WAITING':
                        $request->merge(['status' => ConsultStatusTypeEnum::WAITING]);
                        break;

                    case 'ENDED':
                        $request->merge(['status' => ConsultStatusTypeEnum::ENDED]);
                        break;

                    case 'FAILED':
                        $request->merge(['status' => ConsultStatusTypeEnum::FAILED]);
                        break;

                    case 'CANCELLED':
                        $request->merge(['status' => ConsultStatusTypeEnum::CANCELLED]);
                        break;
                    
                    default:
                        $request->merge(['status' => ConsultStatusTypeEnum::FRESH]);
                        break;
                }
            }

            $request->merge(['id' => $id]);

            $teleconsult_response = $this->_teleconsult_service->patch($request);
            

            // if (!empty($data['consent'])) {

            //     $consentData = Consult::find($request->id)->toArray();
                
            //     if (!empty($consentData['consent'])) {

            //         $alreadyConsentData = json_decode($consentData['consent'], true);
            //         $alreadyConsentData[$data['approved_by']] = $data['consent'][$data['approved_by']];
            //         $request->merge(['consent' => json_encode($alreadyConsentData)]);
            //     }
            // }

            // $model = parent::updateModel($id, $request, $only);

            // DB::commit();

            $model = new Consult;
            $model->id = $teleconsult_response['consult_id'];

            return $model;

        } catch(Exception $e) {
            exceptionLogger("Provider Update Rollback", $e);
            DB::rollback();
        }

        return null;
    }

    protected function bookedSlotChecked($request) {
        $responseData = [
            'status' => true
        ];

        $data = $this->getModelAttributes($request);

        if (is_array($data['slots'])) {

            $selectedSlots = $data['slots'];

            $bookedSlots = AvailabilityDetail::where('provider_id', $data['provider_id'])
                                ->whereIn('id', $selectedSlots)
                                ->where('slot_status', "Booked")
                                ->get();
            if (count($bookedSlots) > 0) {
                return $responseData = [
                    'status' => false,
                    'message' => "Slot already booked!.",
                ];
            }
        }
        return $responseData;
    }
}
