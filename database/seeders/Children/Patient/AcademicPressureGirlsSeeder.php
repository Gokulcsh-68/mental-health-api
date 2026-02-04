<?php

namespace Database\Seeders\Children\Patient;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use App\Entities\Question;
use App\Entities\Answer;
use App\Entities\Form;

class AcademicPressureGirlsSeeder extends Seeder
{
    private string $radioType = 'radio';

    public function run(): void
    {
        $this->createAssessmentGroup();
        $forms = $this->createForms();

        $questions = Question::pluck('id', 'name')->toArray();
        $answers   = Answer::pluck('id', 'name')->toArray();

        $this->addAcademicPressureQuestions($forms['academic_pressure_girls'], $questions, $answers);

        $this->command->info(
            'DSM-5 aligned Academic Pressure & Performance Stress (Girls, 6–12 years) seeded successfully!'
        );
    }

    private function createAssessmentGroup(): void
    {
        DB::table('masters')->updateOrInsert(
            [
                'master_type_slug' => 'assessment-group',
                'slug'             => 'children-academic-pressure-girls',
            ],
            [
                'master_type_slug' => 'assessment-group',
                'slug'             => 'children-academic-pressure-girls',
                'name'             => 'Academic Pressure & Performance Stress (Girls)',
                'attributes'       => json_encode([
                    'age_group'  => '6-12',
                    'category'   => 'childhood',
                    'role'       => 'patient',
                    'reporter'   => 'self/parent',
                    'framework'  => 'DSM-5 (Stress & Coping Domains)',
                    'domain'     => 'Academic Pressure & Stress',
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
            'name'             => 'Children – Academic Pressure & Performance Stress (Girls)',
            'desc'             => 'Self or parent-reported stress and anxiety related to academic pressure in girls (DSM-5 aligned, non-diagnostic)',
            'assessment_group' => 'children-academic-pressure-girls',
            'type'             => 'score',
            'slug'             => 'children-academic-pressure-girls',
            'is_active'        => 1,
            'role_code'        => $rolesJson,
        ];

        DB::table('forms')->updateOrInsert(['slug' => $data['slug']], $data);

        return [
            'academic_pressure_girls' => Form::where('slug', $data['slug'])->value('id')
        ];
    }

    private function addAcademicPressureQuestions(int $formId, array &$questions, array &$answers): void
    {
        $questionsData = [
            'Feels stressed about school performance',
            'Worries about failing or getting poor grades',
            'Feels pressure to meet expectations from teachers or parents',
            'Gets anxious before exams or tests',
            'Spends excessive time on homework or study due to fear of failure',
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
