<?php

namespace App\Entities;
use DB;
use Carbon\Carbon;
use Carbon\CarbonPeriod;
use App\Entities\Provider;

class AvailabilityDetail extends BaseModel
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
        "provider_id", "from_date_time", "to_date_time", "duration", "slot_group", "available_type", "slot_type", "slot_status", "available_status", "created_by",
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

    protected function createModel($request) {
        $data = $this->getModelAttributes($request);

        DB::beginTransaction();
        try {

            if(!empty($data['from_date'])) 
                $data['from_date']   = Carbon::parse($data['from_date'])->toDateTimeString();
            if(!empty($data['to_date'])) 
                $data['to_date']   = Carbon::parse($data['to_date'])->toDateTimeString();

            $createdBy = $request->user()->id;
            if (isset($data['provider_id'])) {
                $data['provider_id']    = $data['provider_id'];
            }else{
                $data['provider_id'] = $request->user()->provider->id;
                $createdBy = $request->user()->provider->user_id;
            }


            $start  = Carbon::parse($data['from_date']);
            $end    = Carbon::parse($data['to_date']);
            $period = CarbonPeriod::create($start, $end);
                
            $dates = $period->toArray();


            if($data['available_type'] == 'weekdays'){

                $week = [
                    'week_days_sun' => 'Sunday',
                    'week_days_mon' => 'Monday',
                    'week_days_tue' => 'Tuesday',
                    'week_days_wed' => 'Wednesday',
                    'week_days_thu' => 'Thursday',
                    'week_days_fri' => 'Friday',
                    'week_days_sat' => 'Saturday'
                ];

                $weekday_filter =[];
                foreach ($week as $key => $value) {
                    if(isset($data[$key]) && $data[$key]){
                        $weekday_filter[] = $value;
                    }
                }

                foreach ($dates as $key => $value) {
                    if(!in_array($value->format('l') , $weekday_filter)){
                        unset($dates[$key]);
                    }
                }
            }

            $dataSlotGroup = $dataQueue  = [];

            foreach ($dates as $key => $value) {
                
                if ($data['slot_type']==2) {
                    if(is_array($data['queue_slots']) && sizeof($data['queue_slots'])) {
                        foreach ($data['queue_slots'] as $queueSlot) {

                            $availabiltyData = [];
                            $availabiltyData['provider_id']     = $provider;
                            $availabiltyData['from_date_time']  = $value->format('Y-m-d') . ' '. $queueSlot['from_time'] . ':00';
                            $availabiltyData['to_date_time']    = $value->format('Y-m-d') . ' ' . $queueSlot['to_time'] . ':59';
                            $availabiltyData['slot_status']     = 'Open';
                            $availabiltyData['slot_group']      = 1;
                            $availabiltyData['slot_type']       = $data['slot_type'];
                            $availabiltyData['available_type']  = $data['available_type'];
                            $availabiltyData['created_by']       = $createdBy;
                            array_push($dataQueue, $availabiltyData);
                        }
                    }
                }

                if ($data['slot_type']==1) {
                    foreach ($data['queue_slots'] as $queueSlot) {

                        $duration =  preg_replace("/[^0-9]/", '', $queueSlot['duration_display']);

                        if(isset($queueSlot['from_time']) && $queueSlot['from_time']){

                            $date_start_time    = Carbon::parse($value)->toDateString() . ' ' . Carbon::parse($queueSlot['from_time'])->toTimeString();
                            $date_end_time      = Carbon::parse(Carbon::parse($value)->toDateString() . ' ' . Carbon::parse($queueSlot['to_time'])->toTimeString())->toDateTimeString();
                            $new_slot =  Carbon::parse($date_start_time);

                            $avail_info = AvailabilityDetail::where('from_date_time',$date_start_time)
                                             ->where('to_date_time', $date_end_time)->first();

                            $st_time = date("H:i:s",strtotime($date_start_time));
                            $st_t_time = date("H:i:01",strtotime($date_start_time));
                            $ed_time = date("H:i:s",strtotime($date_end_time));
                                            
                            $st_date = date("Y-m-d",strtotime($date_start_time));
                            $ed_date = date("Y-m-d",strtotime($date_end_time));

                            $details = AvailabilityDetail::where('provider_id',$data['provider_id'])
                            ->whereRaw("DATE(from_date_time) BETWEEN '".$st_date."' AND '".$ed_date."'")
                            ->whereRaw("TIME(from_date_time) BETWEEN '".$st_time."' AND '".$ed_time."'")
                            ->get();
                            
                            
                            $details_totime = AvailabilityDetail::where('provider_id',$data['provider_id'])
                            //->whereBetween("from_date_time",[$date_start_time, $date_end_time])
                            ->whereRaw("DATE(to_date_time) BETWEEN '".$st_date."' AND '".$ed_date."'")
                            ->whereRaw("TIME(to_date_time) BETWEEN '".$st_t_time."' AND '".$ed_time."'")
                            ->get();

                            if(sizeof($details)>0){
                                return response()->json([
                                    'status'    => false,
                                    'message'   => 'Above selected time is already exist.',
                                ], 401);
                            }
                            if(sizeof($details_totime)>0){
                                
                                return response()->json([
                                    'status'    => false,
                                    'message'   => 'Above selected time is already exist.',
                                ], 401);
                            }
                            
                            while($new_slot < $date_end_time) {
                                $new_slot = $new_slot->addMinutes($duration);
                                $availabiltyData = [];
                                $availabiltyData['provider_id']     = $data['provider_id'];
                                $availabiltyData['from_date_time']  = Carbon::parse($new_slot)->toDateTimeString();
                                $availabiltyData['to_date_time']    = Carbon::parse($new_slot)->toDateTimeString();
                                $availabiltyData['slot_status']     = 'Open';
                                $availabiltyData['duration']        = $duration;
                                $availabiltyData['slot_group']      = 2;
                                $availabiltyData['available_type']  = $data['available_type'];
                                $availabiltyData['slot_type']       = $data['slot_type'];
                                $availabiltyData['created_by']       = $createdBy;
                                array_push($dataSlotGroup, $availabiltyData);
                            }
                            
                        }   
                    }
                }
            }
            $model = $this->insert($dataSlotGroup);


            // $model = Provider::class;

            // $model->availabilityDetail()->createMany($dataSlotGroup);

            /*echo "<pre>"; print_r($model);
            exit();
            if(sizeof($dataSlotGroup)) {
                $availability_detail = AvailabilityDetail::bulkupload($dataSlotGroup, $data);
             }
            if(sizeof($dataQueue)) {
                AvailabilityDetail::bulkupload($dataQueue, $data);
            }*/



            $model = $this->create($data);

            DB::commit();

            return $model;
        } catch(Exception $e) {
            exceptionLogger("Availability Detail Create Rollback", $e);
            DB::rollback();
        }
        
        return null;
    }
}
