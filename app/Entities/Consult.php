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
        "unique_id", "patient_in_room", "provider_in_room", "patient_id", "provider_id", "school_id", "class_id", "consult_type", "consult_slot_type", "consult_date_time", "consult_duration", "speciality", "unit", "slots", "started_date_time", "ended_date_time", "consent", "camera_id", "consult_notes", "Addendum_notes", "reason_for_consult", "status"
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
            $filters['participant_ref_number'] = $request->get('participant_ref_number');

        }else if($request->user()->role->code == 'school'){
            
             $provider_id = Provider::where('school_id',$request->get('staff')->school_id)->pluck('user_id')->toArray();
             $filters['participant_ref_number'] = $provider_id;

        }else{

            $filters['participant_ref_number'] = $request->user()->id;
        }


        if ($request->query('from_date')) {
            $filters['scheduled_from_date'] = $request->get("from_date");
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

            $payload = [
                'consult_date_time' => $data['consult_date_time'],
                'consult_type' => 'virtual',
                'consult_reason' => $data['reason_for_consult'],
                'service_provider' => 'jitsi',

                'provider' => [
                    'id' => $provider->id,
                    'name' => $provider->getFullName(),
                    'email' => $provider->email,
                    'phone' => $provider->mobile_number,
                    'gender' => $provider->gender,
                    'profile_pic' => $provider->profile_image,
                    'additional_info' => [
                        'consult_speciality' => $data['speciality']
                    ],
                ],

                'patient' => [
                    'id' => $patient->id,
                    'name' => $patient->getFullName(),
                    'email' => $patient->email,
                    'phone' => $patient->mobile_number,
                    'gender' => $patient->gender,
                    'profile_pic' => $patient->profile_image,
                ],
            ];

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

            if (!empty($data['started_date_time'])) {
                $request->merge(['started_date_time' => Carbon::parse($data['started_date_time'])->toDateTimeString()]);
            }

            if (!empty($data['ended_date_time'])) {
                $request->merge(['ended_date_time' => Carbon::parse($data['ended_date_time'])->toDateTimeString()]);
            }

            if (!empty($data['status'])) {
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
                    
                    default:
                        $request->merge(['status' => ConsultStatusTypeEnum::FRESH]);
                        break;
                }
            }

            if (!empty($data['consent'])) {

                $consentData = Consult::find($request->id)->toArray();
                
                if (!empty($consentData['consent'])) {

                    $alreadyConsentData = json_decode($consentData['consent'], true);
                    $alreadyConsentData[$data['approved_by']] = $data['consent'][$data['approved_by']];
                    $request->merge(['consent' => json_encode($alreadyConsentData)]);
                }
            }
            $model = parent::updateModel($id, $request, $only);

            DB::commit();

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
