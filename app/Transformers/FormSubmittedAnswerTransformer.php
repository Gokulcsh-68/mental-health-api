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
        // These should now be available as properties thanks to the trait
        $score           = $this->overall_score ?? 0;
        $max_score       = $this->max_possible_score ?? 0;
        $severity_level  = $this->severity_level ?? 'Unknown';
        $severity_color  = $this->severity_color ?? '#6B7280';
        $message         = $this->message ?? 'No assessment data available';
        $type_label      = $this->assessment_type_label ?? 'Unknown Screening';
        $has_critical    = $this->has_critical_flag ?? false;

        return [
            'form_id'           => $this->form_id,
            'patient_id'        => $this->patient_id,
            'form_name'         => $this->form?->name ?? 'Unknown Form',
            'form_slug'         => $this->form?->slug ?? null,

            // ─── Updated fields using new accessors ───────────────────────
            'assessment_type'   => $type_label,
            'clinical_domain'   => $this->clinical_domain ?? 'General',

            'answers'           => $this->answers ?? [],

            'created_at'        => $this->created_at?->format('d-m-Y'),

            'score'             => (int) $score,
            'max_possible_score' => (int) $max_score,

            'severity_level'    => $severity_level,
            'severity_color'    => $severity_color,
            'recommendation'    => $message,           // was called 'message' in trait
            'has_critical_flag' => $has_critical,
        ];
    }
}
