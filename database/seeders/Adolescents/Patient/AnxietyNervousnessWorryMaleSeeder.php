<?php

namespace Database\Seeders\Adolescents\Patient;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use App\Entities\Question;
use App\Entities\Answer;
use App\Entities\Form;

class AnxietyNervousnessWorryMaleSeeder extends Seeder
{
    private string $radioType = 'radio';

    public function run(): void
    {
        $this->createAssessmentGroup();
        $forms = $this->createForms();

        $questions = Question::pluck('id', 'name')->toArray();
        $answers   = Answer::pluck('id', 'name')->toArray();

        $this->addAnxietyQuestions($forms['male_adolescents_anxiety'], $questions, $answers);

        $this->command->info(
            'DSM-5 aligned Anxiety, Nervousness & Worry (Male Adolescents 13–17 years) seeded successfully!'
        );
    }

    private function createAssessmentGroup(): void
    {
        DB::table('masters')->updateOrInsert(
            [
                'master_type_slug' => 'assessment-group',
                'slug'             => 'male-adolescents-anxiety',
            ],
            [
                'master_type_slug' => 'assessment-group',
                'slug'             => 'male-adolescents-anxiety',
                'name'             => 'Anxiety, Nervousness & Worry (Male Adolescents, 13–17 years)',
                'attributes'       => json_encode([
                    'age_group'  => '13-17',
                    'category'   => 'adolescence',
                    'role'       => 'patient',
                    'reporter'   => 'self/parent',
                    'framework'  => 'DSM-5 (Anxiety & Emotional Domains)',
                    'domain'     => 'Anxiety, Nervousness & Worry',
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
            'name'             => 'Male Adolescents – Anxiety, Nervousness & Worry',
            'desc'             => 'Self or parent-reported anxiety, nervousness, and worry in male adolescents (13–17 years, DSM-5 aligned, non-diagnostic)',
            'assessment_group' => 'male-adolescents-anxiety',
            'type'             => 'score',
            'slug'             => 'male-adolescents-anxiety',
            'is_active'        => 1,
            'role_code'        => $rolesJson,
        ];

        DB::table('forms')->updateOrInsert(['slug' => $data['slug']], $data);

        return [
            'male_adolescents_anxiety' => Form::where('slug', $data['slug'])->value('id')
        ];
    }

    private function addAnxietyQuestions(int $formId, array &$questions, array &$answers): void
    {
        $questionsData = [
            'Feels anxious or nervous most of the time',
            'Worries excessively about future events',
            'Experiences restlessness or difficulty relaxing',
            'Has trouble sleeping due to worry',
            'Feels tense or on edge frequently',
            'Difficulty concentrating due to anxiety',
            'Avoids situations due to fear or worry',
            'Has physical symptoms of anxiety (e.g., racing heart, sweating)',
            'Feels irritable or agitated because of worry',
            'Overthinks minor problems or setbacks'
        ];

        $answersData = ['Not at all', 'Occasionally', 'Often', 'Very often'];

        $this->addQuestionsAndAnswers($questionsData, $answersData, $questions, $answers);
        $this->linkQuestionsToForm($formId, $questionsData, $answersData, $questions, $answers, fn($q, $score) => $score);
    }

    private function addQuestionsAndAnswers(array $questionsData, array $answersData, array &$questionsCache, array &$answersCache): void
    {
        foreach ($questionsData as $text) {
            DB::table('questions')->updateOrInsert(
                ['name' => $text],
                ['name' => $text, 'type' => $this->radioType, 'is_active' => 1]
            );
        }

        foreach ($answersData as $text) {
            DB::table('answers')->updateOrInsert(
                ['name' => $text],
                ['name' => $text, 'is_active' => 1]
            );
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
