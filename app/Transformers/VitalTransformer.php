<?php

namespace App\Transformers;
use App\Entities\Doc;

use Illuminate\Http\Resources\Json\JsonResource;

class VitalTransformer extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
    */

    public function toArray($request): array
    {
        $doc_id = null;
        if(isset($this->details->doc_id)){
            $doc_id = $this->details->doc_id;
        }
        return [
            'id' =>  $this->id,
            'user_id' =>  $this->user_id,
            'consult_id' =>  $this->consult_id,
            'peripheral_id' =>  $this->peripheral_id,
            'slug' =>  $this->slug,
            'details' =>  $this->details,
            'freeze' =>  $this->freeze,
            'document' => Doc::where('id',$doc_id)->first()
        ];
    }
}
