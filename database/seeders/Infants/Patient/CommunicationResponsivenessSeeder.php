<?php

namespace Database\Seeders\Infants\Patient;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use App\Entities\Question;
use App\Entities\Answer;
use App\Entities\Form;

class CommunicationResponsivenessSeeder extends Seeder
{
    private string $radioType = 'radio';

    public function run(): void
    {
        $this->createAssessmentGroup();
        $forms = $this->createForms();

        $questions = Question::pluck('id', 'name')->toArray();
        $answers   = Answer::pluck('id', 'name')->toArray();

        $this->addCommunicationResponsivenessQuestions(
            $forms['communication_responsiveness'],
            $questions,
            $answers
        );

        $this->command->info(
            'DSM-5 aligned Communication & Responsiveness (0–5 years) seeded successfully!'
        );
    }

    /**
     * Assessment Group
     */
    private function createAssessmentGroup(): void
    {
        DB::table('masters')->updateOrInsert(
            [
                'master_type_slug' => 'assessment-group',
                'slug' => 'infants-toddlers-communication-responsiveness'
            ],
            [
                'master_type_slug' => 'assessment-group',
                'slug'       => 'infants-toddlers-communication-responsiveness',
                'name'       => 'Communication & Responsiveness',
                'attributes' => json_encode([
                    'age_group' => '0-5',
                    'role'      => 'patient',
                    'reporter'  => 'parent',
                    'framework' => 'DSM-5 (Communication & Language)',
                    'domain'    => 'Communication & Responsiveness',
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
            'name'             => 'Infants & Toddlers – Communication & Responsiveness',
            'desc'             => 'Parent-reported communication and responsiveness behaviors aligned with DSM-5 (non-diagnostic)',
            'assessment_group' => 'infants-toddlers-communication-responsiveness',
            'type'             => 'score',
            'slug'             => 'infants-toddlers-communication-responsiveness',
            'is_active'        => 1,
            'role_code'        => $rolesJson,
        ];

        DB::table('forms')->updateOrInsert(
            ['slug' => $data['slug']],
            $data
        );

        return [
            'communication_responsiveness' =>
                Form::where('slug', $data['slug'])->value('id')
        ];
    }

    /**
     * Communication & Responsiveness Questions
     */
    private function addCommunicationResponsivenessQuestions(
        int $formId,
        array &$questions,
        array &$answers
    ): void {

        $questionsData = [
            'Responds to sounds or voices',
            'Uses gestures to communicate needs (pointing, waving)',
            'Babbles or makes age-appropriate vocal sounds',
            'Attempts to imitate sounds or words',
            'Uses words or signs meaningfully for age',
            'Follows simple verbal instructions',
            'Shows understanding of familiar words',
            'Appears unresponsive to communication attempts',
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
            fn ($question, $score) => $score
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
