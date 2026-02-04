<?php

namespace Database\Seeders\Children\Patient;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use App\Entities\Question;
use App\Entities\Answer;
use App\Entities\Form;

class AngerIrritabilityBoysSeeder extends Seeder
{
    private string $radioType = 'radio';

    public function run(): void
    {
        $this->createAssessmentGroup();
        $forms = $this->createForms();

        $questions = Question::pluck('id', 'name')->toArray();
        $answers   = Answer::pluck('id', 'name')->toArray();

        $this->addAngerQuestions(
            $forms['anger_boys'],
            $questions,
            $answers
        );

        $this->command->info(
            'DSM-5 aligned Anger, Irritability & Frustration Expression (Boys, 6–12 years) seeded successfully!'
        );
    }

    private function createAssessmentGroup(): void
    {
        DB::table('masters')->updateOrInsert(
            [
                'master_type_slug' => 'assessment-group',
                'slug'             => 'children-anger-irritability-boys',
            ],
            [
                'master_type_slug' => 'assessment-group',
                'slug'             => 'children-anger-irritability-boys',
                'name'             => 'Anger, Irritability & Frustration Expression (Boys)',
                'attributes'       => json_encode([
                    'age_group'  => '6-12',
                    'category'   => 'childhood',
                    'role'       => 'patient',
                    'reporter'   => 'self/parent',
                    'framework'  => 'DSM-5 (Emotional Regulation Domains)',
                    'domain'     => 'Anger, Irritability & Frustration Expression',
                    'type'       => 'non-diagnostic',
                    'gender'     => 'male',
                    'visibility' => 'patient',
                ]),
                'is_active' => 1,
            ]
        );
    }

    private function createForms(): array
    {
        $rolesJson = json_encode(['patient', 'doctor', 'hospital']);

        $data = [
            'name'             => 'Children – Anger, Irritability & Frustration Expression (Boys)',
            'desc'             => 'Self or parent-reported anger, irritability and frustration patterns in boys (DSM-5 aligned, non-diagnostic)',
            'assessment_group' => 'children-anger-irritability-boys',
            'type'             => 'score',
            'slug'             => 'children-anger-irritability-boys',
            'is_active'        => 1,
            'role_code'        => $rolesJson,
        ];

        DB::table('forms')->updateOrInsert(
            ['slug' => $data['slug']],
            $data
        );

        return [
            'anger_boys' => Form::where('slug', $data['slug'])->value('id')
        ];
    }

    private function addAngerQuestions(int $formId, array &$questions, array &$answers): void
    {
        $questionsData = [
            'Gets easily annoyed or frustrated',
            'Has temper outbursts',
            'Shows irritability with peers or adults',
            'Complains about being treated unfairly',
            'Displays signs of anger inappropriately',
        ];

        $answersData = ['Not at all', 'Occasionally', 'Often', 'Very often'];

        $this->addQuestionsAndAnswers($questionsData, $answersData, $questions, $answers);
        $this->linkQuestionsToForm($formId, $questionsData, $answersData, $questions, $answers, fn($q, $score) => $score);
    }

    private function addQuestionsAndAnswers(array $questionsData, array $answersData, array &$questionsCache, array &$answersCache): void
    {
        foreach ($questionsData as $text) {
            DB::table('questions')->updateOrInsert(['name' => $text], ['name' => $text, 'type' => $this->radioType, 'is_active' => 1]);
        }

        foreach ($answersData as $text) {
            DB::table('answers')->updateOrInsert(['name' => $text], ['name' => $text, 'is_active' => 1]);
        }

        $questionsCache = Question::pluck('id', 'name')->toArray();
        $answersCache   = Answer::pluck('id', 'name')->toArray();
    }

    private function linkQuestionsToForm(int $formId, array $questionsData, array $answersData, array $questions, array $answers, callable $scoreCalculator): void
    {
        foreach ($questionsData as $qName) {
            $qId = $questions[$qName] ?? null;
            if (!$qId) continue;

            DB::table('form_questions')->updateOrInsert(['form_id' => $formId, 'question_id' => $qId]);

            foreach ($answersData as $rawScore => $aName) {
                $aId = $answers[$aName] ?? null;
                if (!$aId) continue;

                DB::table('form_question_answers')->updateOrInsert(
                    ['question_id' => $qId, 'answer_id' => $aId],
                    ['score' => $scoreCalculator($qName, $rawScore), 'jump_to_question_id' => null]
                );
            }
        }
    }
}
