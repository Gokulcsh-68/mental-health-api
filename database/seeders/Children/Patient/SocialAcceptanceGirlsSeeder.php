<?php

namespace Database\Seeders\Children\Patient;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use App\Entities\Question;
use App\Entities\Answer;
use App\Entities\Form;

class SocialAcceptanceGirlsSeeder extends Seeder
{
    private string $radioType = 'radio';

    public function run(): void
    {
        $this->createAssessmentGroup();
        $forms = $this->createForms();

        $questions = Question::pluck('id', 'name')->toArray();
        $answers   = Answer::pluck('id', 'name')->toArray();

        $this->addSocialAcceptanceQuestions($forms['social_acceptance_girls'], $questions, $answers);

        $this->command->info(
            'DSM-5 aligned Social Acceptance & Peer Approval Concerns (Girls, 6–12 years) seeded successfully!'
        );
    }

    private function createAssessmentGroup(): void
    {
        DB::table('masters')->updateOrInsert(
            [
                'master_type_slug' => 'assessment-group',
                'slug'             => 'children-social-acceptance-girls',
            ],
            [
                'master_type_slug' => 'assessment-group',
                'slug'             => 'children-social-acceptance-girls',
                'name'             => 'Social Acceptance & Peer Approval Concerns (Girls)',
                'attributes'       => json_encode([
                    'age_group'  => '6-12',
                    'category'   => 'childhood',
                    'role'       => 'patient',
                    'reporter'   => 'self/parent',
                    'framework'  => 'DSM-5 (Social & Emotional Domains)',
                    'domain'     => 'Peer Relationships & Social Approval',
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
            'name'             => 'Children – Social Acceptance & Peer Approval Concerns (Girls)',
            'desc'             => 'Self or parent-reported concerns related to social acceptance, peer approval, and social comparison in girls (DSM-5 aligned, non-diagnostic)',
            'assessment_group' => 'children-social-acceptance-girls',
            'type'             => 'score',
            'slug'             => 'children-social-acceptance-girls',
            'is_active'        => 1,
            'role_code'        => $rolesJson,
        ];

        DB::table('forms')->updateOrInsert(['slug' => $data['slug']], $data);

        return [
            'social_acceptance_girls' => Form::where('slug', $data['slug'])->value('id')
        ];
    }

    private function addSocialAcceptanceQuestions(int $formId, array &$questions, array &$answers): void
    {
        $questionsData = [
            'Feels pressure to be liked by peers',
            'Worries about being excluded or rejected',
            'Seeks approval before making decisions',
            'Compares self with others frequently',
            'Feels distressed when peers criticize or ignore her',
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
