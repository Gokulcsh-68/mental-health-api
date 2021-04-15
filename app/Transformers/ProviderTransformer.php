<?php

namespace App\Transformers;

use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Facades\DB;

class ProviderTransformer extends JsonResource
{

    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */

    public function toArray($request): array
    {

        $unavailable = [];
        $advance_available = [];
        if($request->get('cdate')){
            if($request->get('cdate') != ''){
                $condition_date = date('Y-m-d',strtotime($request->get('cdate')));
                $unavailable = $this->providerUnavailability->where('from_date',$condition_date)->toArray();
                $advance_available = $this->customAvailabilityDetail
                                    ->where('from_date','<=',$condition_date)
                                    ->where('to_date','>=',$condition_date)
                                    ->toArray();
            }
        }

        return [
            'id' => $this->id,
            'user_id' => $this->user_id,
            'hospital_id' => $this->hospital_id,
            'group_id' => $this->group_id,
            'practicing_since' => $this->practicing_since,
            'license_no' => $this->license_no,
            'specialities' => $this->specialities,
            'additional_info' => $this->additional_info,
            'availabilities' => $this->availabilities,
            'provider_speciality' => $this->providerSpeciality,
            'unavailabilities' => $unavailable,
            'advance_available' => $advance_available,
            'user' => (new UserTransformer($this->user)),
        ];
    }
}
