<?php

namespace App\Transformers;

use App\Entities\User;
use Illuminate\Http\Resources\Json\JsonResource;

class DocTransformer extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
    */

    public function toArray($request): array
    {

        $addition_info = $this->addition_info;
        if(isset($addition_info->scan_centre_id)) {
            $scan_centre = User::find($addition_info->scan_centre_id);
            if($scan_centre) {
                $addition_info->scan_centre_name = $scan_centre->first_name . ' ' . $scan_centre->last_name;
            }
        }

        $data = [
            'id'                =>  $this->id,
            'addition_info'     =>  $addition_info,
            'consult_id'        =>  $this->consult_id,
            'document_source'   =>  $this->document_source,
            'properties'        =>  $this->properties,
            'user_id'           =>  $this->user_id,
            'freeze'            =>  $this->freeze,
            'created_at'        =>  $this->created_at,
            'updated_at'        =>  $this->updated_at,
            'user' => (new UserTransformer($this->user))
        ];

        return $data;
    }
}