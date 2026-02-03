<?php

namespace App\Traits;

trait AssessmentScoreAccessors
{
    /**
     * Central assessment configuration
     * DSM-5 & ICD-11 aligned (NON-diagnostic screening only)
     */
    protected function assessmentConfig(): array
    {
        return [
            'infants-toddlers-mood-behavior' => [
                'label' => 'Early Childhood Emotional & Behavioral Screening (0–5 Years)',

                // DSM-5 / ICD-11 aligned domain
                'clinical_domain' => 'Emotional & Behavioral Regulation',

                // Seeder: 8 questions × max score 3
                'max_score' => 24,

                'severity' => [
                    [
                        'min' => 0,
                        'max' => 6,
                        'label' => 'Within Expected Range',
                        'color' => '#22C55E',
                        'message' =>
                            "Observed behaviors fall within expected developmental limits.\n\n" .
                            "• Emotional responses are age-appropriate\n" .
                            "• No significant functional impairment noted\n\n" .
                            "✔ Continue supportive routines and caregiver engagement.",
                    ],
                    [
                        'min' => 7,
                        'max' => 12,
                        'label' => 'Mild Concern',
                        'color' => '#EAB308',
                        'message' =>
                            "Mild emotional or behavioral features observed.\n\n" .
                            "• Occasional difficulty with emotional regulation\n" .
                            "• Symptoms may be situational or transient\n\n" .
                            "Clinical guidance:\n" .
                            "• Monitor patterns over time\n" .
                            "• Maintain consistent routines\n" .
                            "• Re-screen if concerns persist",
                    ],
                    [
                        'min' => 13,
                        'max' => 18,
                        'label' => 'Moderate Concern',
                        'color' => '#F97316',
                        'message' =>
                            "Moderate emotional or behavioral concerns identified.\n\n" .
                            "• Symptoms may interfere with daily functioning\n" .
                            "• Emotional dysregulation appears more frequent\n\n" .
                            "Clinical guidance:\n" .
                            "• Discuss findings with a pediatric clinician\n" .
                            "• Consider early developmental or behavioral support\n" .
                            "• Ongoing monitoring is recommended",
                    ],
                    [
                        'min' => 19,
                        'max' => 24,
                        'label' => 'High Concern',
                        'color' => '#EF4444',
                        'message' =>
                            "Significant emotional or behavioral dysregulation detected.\n\n" .
                            "• High level of distress or developmental impact likely\n\n" .
                            " Clinical guidance:\n" .
                            "• Prompt comprehensive evaluation is strongly recommended\n" .
                            "• Early intervention can substantially improve outcomes\n\n" .
                            "⚠ This screening result is NOT a diagnosis.",
                    ],
                ],
            ],
        ];
    }

    /**
     * Sum of all answer scores
     */
    public function getScoreAttribute(): int
    {
        return collect($this->answers ?? [])
            ->sum(fn ($a) => (int) ($a['score'] ?? 0));
    }

    /**
     * Overall score alias
     */
    public function getOverallScoreAttribute(): int
    {
        return $this->score;
    }

    /**
     * Resolve assessment config by slug
     */
    protected function getAssessmentConfig(): array
    {
        $slug = $this->form?->slug;

        return $this->assessmentConfig()[$slug] ?? [];
    }

    /**
     * Assessment label
     */
    public function getAssessmentTypeLabel(?string $slug = null): string
    {
        $slug ??= $this->form?->slug;

        return $this->assessmentConfig()[$slug]['label']
            ?? 'Mental Health Screening';
    }

    /**
     * Clinical domain
     */
    public function getClinicalDomainAttribute(): string
    {
        return $this->getAssessmentConfig()['clinical_domain']
            ?? 'General Mental Health';
    }

    /**
     * Max possible score
     */
    public function getMaxPossibleScore(?string $slug): int
    {
        return $this->assessmentConfig()[$slug]['max_score'] ?? 0;
    }

    /**
     * Resolve severity safely (score clamped)
     */
    protected function resolveSeverity(): array
    {
        $config   = $this->getAssessmentConfig();
        $maxScore = $config['max_score'] ?? 0;

        // Clamp score to avoid config mismatch
        $score = min($this->overall_score, $maxScore);

        foreach ($config['severity'] ?? [] as $severity) {
            if ($score >= $severity['min'] && $score <= $severity['max']) {
                return $severity;
            }
        }

        return [
            'label'   => 'Unclassified',
            'color'   => '#6B7280',
            'message' => 'Unable to determine severity for this screening.',
        ];
    }

    /**
     * Severity label
     */
    public function getSeverityLevelAttribute(): string
    {
        return $this->resolveSeverity()['label'];
    }

    /**
     * Severity color
     */
    public function getSeverityColorAttribute(): string
    {
        return $this->resolveSeverity()['color'];
    }

    /**
     * Final screening message
     */
    public function getMessageAttribute(): string
    {
        $severity = $this->resolveSeverity();

        $header  = $this->getAssessmentTypeLabel() . "\n";
        $header .= "Clinical Domain: {$this->getClinicalDomainAttribute()}\n";
        $header .= "Score: {$this->overall_score}/{$this->getMaxPossibleScore($this->form?->slug)}\n";
        $header .= "Severity Level: {$severity['label']}\n\n";

        return $header . ($severity['message'] ?? '');
    }

    /**
     * High-risk flag
     */
    public function hasCriticalFlag(): bool
    {
        return $this->getSeverityLevelAttribute() === 'High Concern';
    }
}
