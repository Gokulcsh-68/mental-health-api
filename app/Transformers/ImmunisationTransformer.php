<?php

namespace App\Transformers;

use App\Services\MasterService;
use Illuminate\Http\Resources\Json\JsonResource;

class ImmunisationTransformer extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
    */

    public function toArray($request): array
    {
        global $collection;
        $collection = [];

        $master_service = new MasterService;
        $immunisation_master = $master_service->getMasterData('immunisation');
        $patient_dosages = $this->details;

        $immunisation_master->each(function($item, $key) use ($patient_dosages) {
            global $collection;
            $values = []; 
            foreach ($item->attributes->values as $index => $value) {
                $newValue = $value;
                $newValue->status = in_array($value->periods, $patient_dosages);
                $values[] = $newValue;
            }
            
            $item['attributes'] = ["values"=>$values];
            $collection[] = $item;
        });

        // dd($collection);

        return [
            'details' => $collection,
            // 'patient_id' =>  $this->patient_id,
            // 'slug' =>  $this->slug
        ];
    }
}