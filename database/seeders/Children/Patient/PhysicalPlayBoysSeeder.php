<?php

namespace Database\Seeders\Children\Patient;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use App\Entities\Question;
use App\Entities\Answer;
use App\Entities\Form;

class PhysicalPlayBoysSeeder extends Seeder
{
    private string $radioType = 'radio';

    public function run(): void
    {
        $this->createAssessmentGroup();
        $forms = $this->createForms();

        $questions = Question::pluck('id', 'name')->toArray();
        $answers   = Answer::pluck('id', 'name')->toArray();

        $this->addPhysicalPlayQuestions($forms['physical_play_boys'], $questions, $answers);

        $this->command->info(
            'DSM-5 aligned Physical Play, Risk-Taking & Peer Conflicts (Boys, 6–12 years) seeded successfully!'
        );
    }

    private function createAssessmentGroup(): void
    {
        DB::table('masters')->updateOrInsert(
            [
                'master_type_slug' => 'assessment-group',
                'slug'             => 'children-physical-play-boys',
            ],
            [
                'master_type_slug' => 'assessment-group',
                'slug'             => 'children-physical-play-boys',
                'name'             => 'Physical Play, Risk-Taking & Peer Conflicts (Boys)',
                'attributes'       => json_encode([
                    'age_group'  => '6-12',
                    'category'   => 'childhood',
                    'role'       => 'patient',
                    'reporter'   => 'self/parent',
                    'framework'  => 'DSM-5 (Behavioral Regulation Domains)',
                    'domain'     => 'Physical Play & Peer Interactions',
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
            'name'             => 'Children – Physical Play, Risk-Taking & Peer Conflicts (Boys)',
            'desc'             => 'Self or parent-reported physical play, risk-taking, and peer conflict patterns in boys (DSM-5 aligned, non-diagnostic)',
            'assessment_group' => 'children-physical-play-boys',
            'type'             => 'score',
            'slug'             => 'children-physical-play-boys',
            'is_active'        => 1,
            'role_code'        => $rolesJson,
        ];

        DB::table('forms')->updateOrInsert(['slug' => $data['slug']], $data);

        return [
            'physical_play_boys' => Form::where('slug', $data['slug'])->value('id')
        ];
    }

    private function addPhysicalPlayQuestions(int $formId, array &$questions, array &$answers): void
    {
        $questionsData = [
            'Engages in rough or high-energy physical play',
            'Takes risks that could lead to injury',
            'Gets into conflicts with peers during play',
            'Has difficulty sharing or taking turns',
            'Pushes or challenges peers physically',
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
