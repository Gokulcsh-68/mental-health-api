<?php

namespace App\Services;

use Carbon\Carbon;
use App\Entities\Provider;
use App\Entities\ProviderSpeciality;
use App\Requests\ConsultProviderListRequest;
use Illuminate\Http\Request;
use App\Enums\UserTypeEnum;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Collection;

class ProviderService extends BaseService
{  
    /**
     * Bulk Master list.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return json
     */

    public function list(ConsultProviderListRequest $request): JsonResponse
    {

        // $providersData = app(Provider::class); //->with('user','customAvailabilityDetail');

        $providersData = app(Provider::class)->where('school_id', $request->get('staff')->school_id);

        // Speciality 
        if (!empty($request->speciality)) {
            $speciality = $request->speciality;
            $providersData = $providersData->whereHas('providerSpeciality', function ($query) use ($speciality) {
                $query->where('speciality','=', $speciality);
            });
        }

        // Provider name
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

        // Provider id
        if (!empty($request->provider_id)) {
            $providersData = $providersData->where('id', $request->provider_id);
            $result = $providersData->get();
        }

        // Consult date
        if (!empty($request->consult_date)) {
            $consultDate  = Carbon::parse($request->consult_date)->toDateString();
            $day = date('l', strtotime($consultDate));
            $providersData = $providersData->with('customAvailabilityDetail', function ($query) use ($consultDate) {
                $query->where('from_date', '<=', $consultDate)
                      ->where('to_date', '>=', $consultDate);
            });

            $result = [];
            foreach ($providersData->get()->toArray() as $key => $value) {
                
                $value['slots'] = '';
                if (!empty($value['custom_availability_detail'])) {
                    foreach ($value['custom_availability_detail'] as $keys => $values) {
                        $value['slots'] = $values['timing'];
                        $value['availabilities'] = $values['timing'];
                    }
                } else {
                    $availabilities = json_decode($value['availabilities'], true);
                    $value['slots'] = (!empty($availabilities[$day])) ? json_encode($availabilities[$day]) : '';
                    $value['availabilities'] = (!empty($availabilities[$day])) ? json_encode($availabilities[$day]) : '';
                }
                unset($value['custom_availability_detail']);

                if (!empty($value['slots'])) {
                    $result[] = $value;
                }

                $result = collect($result)->map(function ($result) {
                    return (object) $result;
                });
            }
            /*echo "<pre>"; print_r($result);
            exit();*/
        } else {

            
        }

        return $this->httpResponse->setHttpData($result)->jsonResponse();
    }
}
