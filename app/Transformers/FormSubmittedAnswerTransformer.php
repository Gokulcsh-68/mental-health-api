<?php

namespace App\Transformers;

use Illuminate\Http\Resources\Json\JsonResource;

class FormSubmittedAnswerTransformer extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request): array
    {
        $score = $this->overall_score ?? 0;
        $slug = $this->form?->slug;

        return [
            'form_id'          => $this->form_id,
            'patient_id'       => $this->patient_id,
            'form_name'        => $this->form?->name ?? 'Unknown Form',
            'form_slug'        => $slug,
            'assessment_type'  => $this->getAssessmentTypeLabel($slug),
            'answers'          => $this->answers ?? [],
            'created_at'       => $this->created_at?->format('d-m-Y'),
            // 'raw_score'            => number_format($score, 2),
            'score'        => (int) $score,
            // 'max_possible'     => $this->getMaxPossibleScore($slug),
            'severity_level'   => $this->severity_level ?? 'Unknown',
            'severity_color'   => $this->severity_color ?? 'Gray',
            'recommendation'   => $this->message ?? 'No assessment data',
            'has_critical_flag' => $this->hasCriticalFlag(),
        ];
    }
}
