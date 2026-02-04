<?php

namespace Database\Seeders\Adolescents\Patient;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use App\Entities\Question;
use App\Entities\Answer;
use App\Entities\Form;

class RiskTakingAdolescentsSeeder extends Seeder
{
    private string $radioType = 'radio';

    public function run(): void
    {
        $this->createAssessmentGroup();
        $forms = $this->createForms();

        $questions = Question::pluck('id', 'name')->toArray();
        $answers   = Answer::pluck('id', 'name')->toArray();

        $this->addRiskTakingQuestions($forms['risk_taking_adolescents'], $questions, $answers);

        $this->command->info(
            'DSM-5 aligned Risk-Taking & Substance-Related Behaviors (Adolescents 13–17 years) seeded successfully!'
        );
    }

    private function createAssessmentGroup(): void
    {
        DB::table('masters')->updateOrInsert(
            [
                'master_type_slug' => 'assessment-group',
                'slug'             => 'adolescents-risk-taking-general',
            ],
            [
                'master_type_slug' => 'assessment-group',
                'slug'             => 'adolescents-risk-taking-general',
                'name'             => 'Risk-Taking & Substance-Related Behaviors (Adolescents, 13–17 years)',
                'attributes'       => json_encode([
                    'age_group'  => '13-17',
                    'category'   => 'adolescence',
                    'role'       => 'patient',
                    'reporter'   => 'self/parent',
                    'framework'  => 'DSM-5 (Mood, Anxiety, Risk & Developmental Domains)',
                    'domain'     => 'Risk-Taking & Substance-Related Behaviors',
                    'type'       => 'non-diagnostic',
                    'gender'     => 'all',
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
            'name'             => 'Adolescents – Risk-Taking & Substance-Related Behaviors (General)',
            'desc'             => 'Self or parent-reported risk-taking behaviors, impulsivity, and substance-related concerns in adolescents (13–17 years, DSM-5 aligned, non-diagnostic)',
            'assessment_group' => 'adolescents-risk-taking-general',
            'type'             => 'score',
            'slug'             => 'adolescents-risk-taking-general',
            'is_active'        => 1,
            'role_code'        => $rolesJson,
        ];

        DB::table('forms')->updateOrInsert(['slug' => $data['slug']], $data);

        return [
            'risk_taking_adolescents' => Form::where('slug', $data['slug'])->value('id')
        ];
    }

    private function addRiskTakingQuestions(int $formId, array &$questions, array &$answers): void
    {
        $questionsData = [
            'Engages in risky behaviors without considering consequences',
            'Uses alcohol, tobacco, or other substances',
            'Feels thrill-seeking or impulsive',
            'Breaks rules or laws frequently',
            'Acts without thinking about personal safety',
            'Skips school or important responsibilities',
            'Engages in unsafe sexual behaviors',
            'Gets involved in physical fights or aggressive acts',
            'Drives recklessly or takes transport risks',
            'Engages in online or social media risks (sharing sensitive info)',
            'Uses substances to cope with stress or sadness',
            'Stays out late without parental knowledge',
            'Shows disregard for rules or authority figures',
            'Makes sudden financial or property-related risks (gambling, stealing)',
            'Engages in self-harming behaviors or extreme challenges',
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
