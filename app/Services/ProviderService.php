<?php

namespace App\Services;

use Carbon\Carbon;
use App\Entities\Provider;
use App\Entities\ProviderSpeciality;
use Illuminate\Http\Request;
use App\Enums\UserTypeEnum;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Cache;

class ProviderService extends BaseService
{  
    /**
     * Bulk Master list.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return json
     */

    public function list(Request $request): JsonResponse
    {

        // $providersData = app(Provider::class); //->with('user','availabilityDetail');

        $providersData = app(Provider::class)->where('school_id', $request->get('staff')->school_id);

        if (!empty($request->speciality)) {
            $speciality = $request->speciality;
            $providersData = $providersData->whereHas('providerSpeciality', function ($query) use ($speciality) {
                $query->where('speciality','=', $speciality);
            });
        }

        if(!empty($request->provider_name)){
            $providerName = $request->provider_name;
            $providersData = $providersData->whereHas('user', function ($query) use ($providerName) {
                $query->where('first_name', 'like', '%' . $providerName . '%')
                      ->orWhere('last_name', 'like', '%' . $providerName . '%');
            })
            ->with('user', function ($query) use ($providerName) {
                $query->where('first_name', 'like', '%' . $providerName . '%')
                      ->orWhere('last_name', 'like', '%' . $providerName . '%');
            });
        } else {
            $providersData = $providersData->with('user');
        }

        if (!empty($request->availability_date)) {
            $data = [
                'fromDate' => '',
                'toDate' => '',
                'slotGroup' => $request->slot_group
            ];

            $requestDateDetail = Carbon::parse($request->availability_date)->toDate();
            $timeZone = $requestDateDetail->getTimezone()->getName();
            $chooseDay = Carbon::parse($request->availability_date)->toDateString();

            $today = Carbon::now($timeZone)->toDateString();
            $nowTime = Carbon::now($timeZone)->toTimeString();


            if ($chooseDay == $today) {
                $data['fromDate']  = Carbon::parse($request->availability_date)->toDateString(). " ".$nowTime;
            } else {
                $data['fromDate']  = Carbon::parse($request->availability_date)->toDateString(). " 00:00:00";
            }

            $data['toDate'] = Carbon::parse($request->availability_date)->toDateString(). " 23:59:59";

            $providersData = $providersData->whereHas('availabilityDetail', function ($query) use ($data) {
                $query->whereBetween('to_date_time', [$data['fromDate'], $data['toDate']])
                        ->where('slot_status', 'Open')
                        ->where('slot_group', $data['slotGroup']);
            })
            ->with('availabilityDetail', function ($query) use ($data) {
                $query->whereBetween('to_date_time', [$data['fromDate'], $data['toDate']])
                        ->where('slot_status', 'Open')
                        ->where('slot_group', $data['slotGroup']);
            });
        }

        if (!empty($request->provider_id)) {
            $providersData = $providersData->where('id', $request->provider_id);
            $result = $providersData->first();
        } else {
            $result = $providersData->get();
        }

        return $this->httpResponse->setHttpData($result)->jsonResponse();
    }
}
