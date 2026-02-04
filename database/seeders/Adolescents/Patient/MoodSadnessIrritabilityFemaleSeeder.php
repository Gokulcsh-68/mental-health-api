<?php

namespace Database\Seeders\Adolescents\Patient;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use App\Entities\Question;
use App\Entities\Answer;
use App\Entities\Form;

class MoodSadnessIrritabilityFemaleSeeder extends Seeder
{
    private string $radioType = 'radio';

    public function run(): void
    {
        $this->createAssessmentGroup();
        $forms = $this->createForms();

        $questions = Question::pluck('id', 'name')->toArray();
        $answers   = Answer::pluck('id', 'name')->toArray();

        $this->addMoodQuestions($forms['female_adolescents_mood'], $questions, $answers);

        $this->command->info(
            'DSM-5 aligned Mood, Sadness & Irritability (Female Adolescents 13–17 years) seeded successfully!'
        );
    }

    private function createAssessmentGroup(): void
    {
        DB::table('masters')->updateOrInsert(
            [
                'master_type_slug' => 'assessment-group',
                'slug'             => 'female-adolescents-mood',
            ],
            [
                'master_type_slug' => 'assessment-group',
                'slug'             => 'female-adolescents-mood',
                'name'             => 'Mood, Sadness & Irritability (Female Adolescents, 13–17 years)',
                'attributes'       => json_encode([
                    'age_group'  => '13-17',
                    'category'   => 'adolescence',
                    'role'       => 'patient',
                    'reporter'   => 'self/parent',
                    'framework'  => 'DSM-5 (Mood, Anxiety, Emotional Domains)',
                    'domain'     => 'Mood, Sadness & Irritability',
                    'type'       => 'non-diagnostic',
                    'gender'     => 'female',
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
            'name'             => 'Female Adolescents – Mood, Sadness & Irritability',
            'desc'             => 'Self or parent-reported mood changes, sadness, irritability, and emotional dysregulation in female adolescents (13–17 years, DSM-5 aligned, non-diagnostic)',
            'assessment_group' => 'female-adolescents-mood',
            'type'             => 'score',
            'slug'             => 'female-adolescents-mood',
            'is_active'        => 1,
            'role_code'        => $rolesJson,
        ];

        DB::table('forms')->updateOrInsert(['slug' => $data['slug']], $data);

        return [
            'female_adolescents_mood' => Form::where('slug', $data['slug'])->value('id')
        ];
    }

    private function addMoodQuestions(int $formId, array &$questions, array &$answers): void
    {
        $questionsData = [
            'Feels sad or down most of the day',
            'Frequently irritable or easily frustrated',
            'Loses interest in activities once enjoyed',
            'Experiences mood swings or emotional outbursts',
            'Complains of fatigue or low energy',
            'Has difficulty concentrating or focusing',
            'Feels hopeless or pessimistic about the future',
            'Expresses feelings of guilt or worthlessness',
            'Withdraws from friends or family',
            'Displays anger or aggression disproportionate to situations',
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
