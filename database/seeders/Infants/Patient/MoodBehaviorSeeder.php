<?php

namespace Database\Seeders\Infants\Patient;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use App\Entities\Question;
use App\Entities\Answer;
use App\Entities\Form;

class MoodBehaviorSeeder extends Seeder
{
    private string $radioType = 'radio';

    public function run(): void
    {
        $this->createAssessmentGroup();
        $forms = $this->createForms();

        $questions = Question::pluck('id', 'name')->toArray();
        $answers   = Answer::pluck('id', 'name')->toArray();

        $this->addMoodBehaviorQuestions(
            $forms['mood_behavior'],
            $questions,
            $answers
        );

        $this->command->info('DSM-5 aligned Mood & Behavior (0–5 years) seeded successfully!');
    }

    /**
     * Assessment Group (DSM-5 aligned, non-diagnostic)
     */
    private function createAssessmentGroup(): void
    {
        DB::table('masters')->updateOrInsert(
            [
                'master_type_slug' => 'assessment-group',
                'slug' => 'infants-toddlers-mood-behavior'
            ],
            [
                'master_type_slug' => 'assessment-group',
                'slug'       => 'infants-toddlers-mood-behavior',
                'name'       => 'Mood & Behavior Changes',
                'attributes' => json_encode([
                    'age_group' => '0-5',
                    'role'      => 'patient',
                    'reporter'  => 'parent',
                    'framework' => 'DSM-5 (symptom-aligned)',
                    'type'      => 'non-diagnostic',
                    'gender'    => 'all',
                ]),
                'is_active'  => 1,
            ]
        );
    }

    /**
     * Form creation
     */
    private function createForms(): array
    {
        $rolesJson = json_encode(['patient', 'doctor', 'hospital']);

        $data = [
            'name'             => 'Infants & Toddlers – Mood & Behavior',
            'desc'             => 'Parent-reported observations of mood and behavior (DSM-5 aligned, non-diagnostic)',
            'assessment_group' => 'infants-toddlers-mood-behavior',
            'type'             => 'score',
            'slug'             => 'infants-toddlers-mood-behavior',
            'is_active'        => 1,
            'role_code'        => $rolesJson,
        ];

        DB::table('forms')->updateOrInsert(
            ['slug' => $data['slug']],
            $data
        );

        return [
            'mood_behavior' => Form::where('slug', $data['slug'])->value('id')
        ];
    }

    /**
     * DSM-5 symptom-aligned questions
     */
    private function addMoodBehaviorQuestions(
        int $formId,
        array &$questions,
        array &$answers
    ): void {
        $questionsData = [
            'Child appears irritable or easily upset',
            'Frequent mood changes without clear reason',
            'Difficulty calming down when distressed',
            'Sleep difficulties affecting daily routine',
            'Feeding difficulties or reduced appetite',
            'Limited interest in social interaction or play',
            'Reduced response to caregivers or surroundings',
            'Developmental skills progressing slower than expected',
        ];

        $answersData = [
            'Not at all',
            'Occasionally',
            'Often',
            'Very often',
        ];

        $this->addQuestionsAndAnswers(
            $questionsData,
            $answersData,
            $questions,
            $answers
        );

        $this->linkQuestionsToForm(
            $formId,
            $questionsData,
            $answersData,
            $questions,
            $answers,
            fn ($q, $score) => $score // simple 0–3 scoring
        );
    }

    /**
     * Insert Questions & Answers (NO order column)
     */
    private function addQuestionsAndAnswers(
        array $questionsData,
        array $answersData,
        array &$questionsCache,
        array &$answersCache
    ): void {
        foreach ($questionsData as $text) {
            DB::table('questions')->updateOrInsert(
                ['name' => $text],
                [
                    'name'      => $text,
                    'type'      => $this->radioType,
                    'is_active' => 1,
                ]
            );
        }

        foreach ($answersData as $text) {
            DB::table('answers')->updateOrInsert(
                ['name' => $text],
                [
                    'name'      => $text,
                    'is_active' => 1,
                ]
            );
        }

        $questionsCache = Question::pluck('id', 'name')->toArray();
        $answersCache   = Answer::pluck('id', 'name')->toArray();
    }

    /**
     * Link Questions ↔ Answers ↔ Form
     */
    private function linkQuestionsToForm(
        int $formId,
        array $questionsData,
        array $answersData,
        array $questions,
        array $answers,
        callable $scoreCalculator
    ): void {
        foreach ($questionsData as $qName) {
            $qId = $questions[$qName] ?? null;
            if (!$qId) continue;

            DB::table('form_questions')->updateOrInsert(
                ['form_id' => $formId, 'question_id' => $qId]
            );

            foreach ($answersData as $rawScore => $aName) {
                $aId = $answers[$aName] ?? null;
                if (!$aId) continue;

                DB::table('form_question_answers')->updateOrInsert(
                    [
                        'question_id' => $qId,
                        'answer_id'   => $aId,
                    ],
                    [
                        'score'               => $scoreCalculator($qName, $rawScore),
                        'jump_to_question_id' => null,
                    ]
                );
            }
        }
    }
}
