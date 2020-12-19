<?php

namespace App\Entities;
use DB;

use Carbon\Carbon;
use App\Enums\ConsultStatusTypeEnum;
use App\Entities\AvailabilityDetail;

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

    protected function createModel($request)
    {
        $data = $this->getModelAttributes($request);

        DB::beginTransaction();
        try {

            $data['status'] = ConsultStatusTypeEnum::FRESH;
            $data['unique_id'] = chr(rand(65, 90)) . $data['provider_id'] . time() . rand(999999, 99999999) . $data['patient_id'];
            $data['school_id'] = $request->get('staff')->school_id;

            $data['unit'] = 1;
            if (is_array($data['slots'])) {

                $selectedSlots = $data['slots'];
                $data['unit'] = count($data['slots']);
                $data['slots'] = json_encode($data['slots']);

                AvailabilityDetail::where('provider_id', $data['provider_id'])
                                ->whereIn('id', $selectedSlots)
                                ->update(array("slot_status" => 'Booked'));
            } else {
                $data['slots'] = json_encode(["slot_id"=>"custom"]);
            }

            $model = $this->create($data);

            DB::commit();

            return $model;
        } catch(Exception $e) {
            exceptionLogger("Consult Create Rollback", $e);
            DB::rollback();
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
