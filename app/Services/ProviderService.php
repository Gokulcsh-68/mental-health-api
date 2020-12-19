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

        $providersData = app(Provider::class); //->with('user','availabilityDetail');

        if (!empty($request->school_id)) {
            $providersData = $providersData->where('school_id', $request->school_id);
        }

        if (!empty($request->provider_id)) {
            $providersData = $providersData->where('id', $request->provider_id);
        }

        if(!empty($request->provider_name)){
            $providerName = $request->provider_name;
            $providersData = $providersData->whereHas('user', function ($query) use ($providerName) {
                $query->where('first_name', 'like', '%' . $providerName . '%')
                      ->orWhere('last_name', 'like', '%' . $providerName . '%');
            });
        }


        if (!empty($request->speciality)) {
            $speciality = $request->speciality;
            $providersData = $providersData->whereHas('providerSpeciality', function ($query) use ($speciality) {
                $query->where('speciality','=', $speciality);
            });
        }
        
        $result = $providersData->get();

        return $this->httpResponse->setHttpData($result)->jsonResponse();
    }
}
