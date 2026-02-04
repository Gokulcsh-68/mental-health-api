<?php

namespace Database\Seeders\Children\Patient;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use App\Entities\Question;
use App\Entities\Answer;
use App\Entities\Form;

class ImpulsivityBehaviorBoysSeeder extends Seeder
{
    private string $radioType = 'radio';

    public function run(): void
    {
        $this->createAssessmentGroup();
        $forms = $this->createForms();

        $questions = Question::pluck('id', 'name')->toArray();
        $answers   = Answer::pluck('id', 'name')->toArray();

        $this->addImpulsivityQuestions(
            $forms['impulsivity_boys'],
            $questions,
            $answers
        );

        $this->command->info(
            'DSM-5 aligned Impulsivity & Behavioral Regulation (Boys, 6–12 years) seeded successfully!'
        );
    }

    /**
     * Assessment Group – Children (6–12, Boys)
     */
    private function createAssessmentGroup(): void
    {
        DB::table('masters')->updateOrInsert(
            [
                'master_type_slug' => 'assessment-group',
                'slug'             => 'children-impulsivity-behavior-boys',
            ],
            [
                'master_type_slug' => 'assessment-group',
                'slug'             => 'children-impulsivity-behavior-boys',
                'name'             => 'Impulsivity & Behavioral Regulation (Boys)',
                'attributes'       => json_encode([
                    'age_group'  => '6-12',
                    'category'   => 'childhood',
                    'role'       => 'patient',
                    'reporter'   => 'self/parent',
                    'framework'  => 'DSM-5 (Behavioral Regulation Domains)',
                    'domain'     => 'Impulsivity & Behavioral Regulation',
                    'type'       => 'non-diagnostic',
                    'gender'     => 'male',
                    'visibility' => 'patient',
                ]),
                'is_active' => 1,
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
            'name'             => 'Children – Impulsivity & Behavioral Regulation (Boys)',
            'desc'             => 'Self or parent-reported impulsivity and behavioral regulation patterns in boys (DSM-5 aligned, non-diagnostic)',
            'assessment_group' => 'children-impulsivity-behavior-boys',
            'type'             => 'score',
            'slug'             => 'children-impulsivity-behavior-boys',
            'is_active'        => 1,
            'role_code'        => $rolesJson,
        ];

        DB::table('forms')->updateOrInsert(
            ['slug' => $data['slug']],
            $data
        );

        return [
            'impulsivity_boys' => Form::where('slug', $data['slug'])->value('id')
        ];
    }

    /**
     * Impulsivity & Behavioral Regulation Questions (6–12 Boys)
     */
    private function addImpulsivityQuestions(
        int $formId,
        array &$questions,
        array &$answers
    ): void {

        $questionsData = [
            'Acts without thinking about consequences',
            'Has trouble waiting for turns or standing in line',
            'Interrupts others frequently in conversations',
            'Gets easily frustrated or loses temper',
            'Engages in risky or unsafe behaviors',
            'Struggles to follow rules or instructions',
            'Displays aggressive behaviors when upset',
            'Finds it difficult to stay seated or calm',
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
