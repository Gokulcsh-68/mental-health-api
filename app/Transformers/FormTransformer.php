<?php

namespace App\Transformers;

use DB;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Facades\Log;

class FormTransformer extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */

    public function toArray($request): array
    {
        // Log full request (optional)
        Log::info('FormTransformer Request Data', $request->all());

        // Log only patient/user id
        Log::info('Patient ID from request', [
            'patient_id' => $request->get('patient_id')
        ]);

        $latestSubmission = $this->FormSubmittedAnswer()
            ->where('patient_id', $request->get('patient_id'))
            ->latest('created_at')
            ->first();

        return [
            'id'                    => $this->id,
            'patient_id' => $latestSubmission?->patient_id,
            'slug'                  => $this->slug,
            'name'                  => $this->name,
            'desc'                  => $this->desc,
            'assessment_group'      => $this->assessment_group,
            'type'                  => $this->type,
            'images'                => $this->images,
            'is_active'             => $this->is_active,
            'order'                 => $this->order,
            'latest_form_submisson' => $latestSubmission
                ? new FormSubmittedAnswerTransformer($latestSubmission)
                : null,
            'questions'             => FormQuestionTransformer::collection($this->formQuestions),
        ];
    }
}
