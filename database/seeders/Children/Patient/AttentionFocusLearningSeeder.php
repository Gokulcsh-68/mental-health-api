<?php

namespace Database\Seeders\Children\Patient;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use App\Entities\Question;
use App\Entities\Answer;
use App\Entities\Form;

class AttentionFocusLearningSeeder extends Seeder
{
    private string $radioType = 'radio';

    public function run(): void
    {
        $this->createAssessmentGroup();
        $forms = $this->createForms();

        $questions = Question::pluck('id', 'name')->toArray();
        $answers   = Answer::pluck('id', 'name')->toArray();

        $this->addAttentionQuestions(
            $forms['attention_focus_learning'],
            $questions,
            $answers
        );

        $this->command->info(
            'DSM-5 aligned Attention, Focus & Learning (6–12 years) seeded successfully!'
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
                'slug'             => 'children-attention-focus-learning',
            ],
            [
                'master_type_slug' => 'assessment-group',
                'slug'             => 'children-attention-focus-learning',
                'name'             => 'Attention, Focus & Learning',
                'attributes'       => json_encode([
                    'age_group'  => ['6-12'], // array for auto-age mapping
                    'category'   => 'childhood',
                    'role'       => 'patient',
                    'reporter'   => 'self/parent',
                    'framework'  => 'DSM-5 (ADHD & Learning Domains)',
                    'domain'     => 'Attention, Focus & Learning',
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
            'name'             => 'Children – Attention, Focus & Learning',
            'desc'             => 'Self or parent-reported attention, focus, and learning patterns (DSM-5 aligned, non-diagnostic)',
            'assessment_group' => 'children-attention-focus-learning',
            'type'             => 'score',
            'slug'             => 'children-attention-focus-learning',
            'is_active'        => 1,
            'role_code'        => $rolesJson,
        ];

        DB::table('forms')->updateOrInsert(
            ['slug' => $data['slug']],
            $data
        );

        return [
            'attention_focus_learning' =>
                Form::where('slug', $data['slug'])->value('id')
        ];
    }

    /**
     * Attention, Focus & Learning Questions (6–12)
     */
    private function addAttentionQuestions(
        int $formId,
        array &$questions,
        array &$answers
    ): void {

        $questionsData = [
            'Has difficulty paying attention to tasks or schoolwork',
            'Easily distracted during activities',
            'Trouble following instructions',
            'Often forgets to complete tasks or chores',
            'Struggles with organizing schoolwork or materials',
            'Difficulty completing homework on time',
            'Has trouble focusing during group activities',
            'Seems inattentive or “daydreams” often',
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
