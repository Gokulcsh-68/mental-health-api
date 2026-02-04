<?php

namespace Database\Seeders\Infants\Patient;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use App\Entities\Question;
use App\Entities\Answer;
use App\Entities\Form;

class BehavioralResponsesIrritabilitySeeder extends Seeder
{
    private string $radioType = 'radio';

    public function run(): void
    {
        $this->createAssessmentGroup();
        $forms = $this->createForms();

        $questions = Question::pluck('id', 'name')->toArray();
        $answers   = Answer::pluck('id', 'name')->toArray();

        $this->addBehavioralQuestions(
            $forms['behavior_irritability'],
            $questions,
            $answers
        );

        $this->command->info(
            'DSM-5 aligned Behavioral Responses & Irritability (0–5 years) seeded successfully!'
        );
    }

    /**
     * Assessment Group (DSM-5 aligned, non-diagnostic)
     */
    private function createAssessmentGroup(): void
    {
        DB::table('masters')->updateOrInsert(
            [
                'master_type_slug' => 'assessment-group',
                'slug' => 'infants-toddlers-behavior-irritability'
            ],
            [
                'master_type_slug' => 'assessment-group',
                'slug'       => 'infants-toddlers-behavior-irritability',
                'name'       => 'Behavioral Responses & Irritability',
                'attributes' => json_encode([
                    'age_group' => '0-5',
                    'role'      => 'patient',
                    'reporter'  => 'parent',
                    'framework' => 'DSM-5 (Neurodevelopmental & Self-Regulation)',
                    'domain'    => 'Behavioral Regulation',
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
            'name'             => 'Infants & Toddlers – Behavioral Responses & Irritability',
            'desc'             => 'Parent-reported behavioral regulation and irritability patterns aligned with DSM-5 (non-diagnostic)',
            'assessment_group' => 'infants-toddlers-behavior-irritability',
            'type'             => 'score',
            'slug'             => 'infants-toddlers-behavior-irritability',
            'is_active'        => 1,
            'role_code'        => $rolesJson,
        ];

        DB::table('forms')->updateOrInsert(
            ['slug' => $data['slug']],
            $data
        );

        return [
            'behavior_irritability' => Form::where('slug', $data['slug'])->value('id')
        ];
    }

    /**
     * DSM-5 aligned behavioral regulation questions
     */
    private function addBehavioralQuestions(
        int $formId,
        array &$questions,
        array &$answers
    ): void {

        $questionsData = [
            'Child becomes easily frustrated during routine activities',
            'Frequent intense crying or tantrums beyond age expectations',
            'Difficulty calming down without caregiver support',
            'Strong emotional reactions to minor changes or limits',
            'Appears irritable for most of the day',
            'Overreacts to sensory input (noise, touch, textures)',
            'Difficulty waiting or tolerating brief delays',
            'Behavior disrupts feeding, sleep, or play routines',
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
            fn ($question, $score) => $score // 0–3 scoring
        );
    }

    /**
     * Insert Questions & Answers
     * (No `order` column used)
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
                [
                    'form_id'     => $formId,
                    'question_id' => $qId,
                ]
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
