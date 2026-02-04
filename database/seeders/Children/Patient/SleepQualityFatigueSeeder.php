<?php

namespace Database\Seeders\Children\Patient;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use App\Entities\Question;
use App\Entities\Answer;
use App\Entities\Form;

class SleepQualityFatigueSeeder extends Seeder
{
    private string $radioType = 'radio';

    public function run(): void
    {
        $this->createAssessmentGroup();
        $forms = $this->createForms();

        $questions = Question::pluck('id', 'name')->toArray();
        $answers   = Answer::pluck('id', 'name')->toArray();

        $this->addSleepQualityQuestions(
            $forms['sleep_quality_fatigue'],
            $questions,
            $answers
        );

        $this->command->info(
            'DSM-5 aligned Sleep Quality & Fatigue (6–12 years) seeded successfully!'
        );
    }

    /**
     * Assessment Group – Children (6–12)
     */
    private function createAssessmentGroup(): void
    {
        DB::table('masters')->updateOrInsert(
            [
                'master_type_slug' => 'assessment-group',
                'slug'             => 'children-sleep-quality-fatigue',
            ],
            [
                'master_type_slug' => 'assessment-group',
                'slug'             => 'children-sleep-quality-fatigue',
                'name'             => 'Sleep Quality & Fatigue',
                'attributes'       => json_encode([
                    'age_group'  => '6-12',
                    'category'   => 'childhood',
                    'role'       => 'patient',
                    'reporter'   => 'self/parent',
                    'framework'  => 'DSM-5 (Sleep & Fatigue Domains)',
                    'domain'     => 'Sleep & Fatigue',
                    'type'       => 'non-diagnostic',
                    'gender'     => 'all',
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
            'name'             => 'Children – Sleep Quality & Fatigue',
            'desc'             => 'Self or parent-reported sleep patterns and fatigue levels (DSM-5 aligned, non-diagnostic)',
            'assessment_group' => 'children-sleep-quality-fatigue',
            'type'             => 'score',
            'slug'             => 'children-sleep-quality-fatigue',
            'is_active'        => 1,
            'role_code'        => $rolesJson,
        ];

        DB::table('forms')->updateOrInsert(
            ['slug' => $data['slug']],
            $data
        );

        return [
            'sleep_quality_fatigue' =>
                Form::where('slug', $data['slug'])->value('id')
        ];
    }

    /**
     * Sleep Quality & Fatigue Questions (6–12)
     */
    private function addSleepQualityQuestions(
        int $formId,
        array &$questions,
        array &$answers
    ): void {

        $questionsData = [
            'Has trouble falling asleep at night',
            'Wakes up frequently during the night',
            'Feels tired or drowsy during the day',
            'Difficulty staying awake during school or activities',
            'Complains of low energy or fatigue',
            'Needs frequent naps during the day',
            'Sleep patterns interfere with daily routines',
            'Difficulty concentrating due to tiredness',
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
