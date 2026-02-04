<?php

namespace Database\Seeders\Adolescents\Patient;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use App\Entities\Question;
use App\Entities\Answer;
use App\Entities\Form;

class PeerRelationshipsSeeder extends Seeder
{
    private string $radioType = 'radio';

    public function run(): void
    {
        $this->createAssessmentGroup();
        $forms = $this->createForms();

        $questions = Question::pluck('id', 'name')->toArray();
        $answers   = Answer::pluck('id', 'name')->toArray();

        $this->addPeerRelationshipQuestions($forms['peer_relationships'], $questions, $answers);

        $this->command->info(
            'DSM-5 aligned Peer Relationships & Social Belonging (Children, 6–12 years) seeded successfully!'
        );
    }

    private function createAssessmentGroup(): void
    {
        DB::table('masters')->updateOrInsert(
            [
                'master_type_slug' => 'assessment-group',
                'slug'             => 'children-peer-relationships',
            ],
            [
                'master_type_slug' => 'assessment-group',
                'slug'             => 'children-peer-relationships',
                'name'             => 'Peer Relationships & Social Belonging (Children)',
                'attributes'       => json_encode([
                    'age_group'  => '13-17',
                    'category'   => 'adolescence',
                    'role'       => 'patient',
                    'reporter'   => 'self/parent',
                    'framework'  => 'DSM-5 (Social & Emotional Domains)',
                    'domain'     => 'Peer Relationships & Social Belonging',
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
            'name'             => 'Children – Peer Relationships & Social Belonging',
            'desc'             => 'Self or parent-reported concerns related to peer relationships, social inclusion, and belonging in children (DSM-5 aligned, non-diagnostic)',
            'assessment_group' => 'children-peer-relationships',
            'type'             => 'score',
            'slug'             => 'children-peer-relationships',
            'is_active'        => 1,
            'role_code'        => $rolesJson,
        ];

        DB::table('forms')->updateOrInsert(['slug' => $data['slug']], $data);

        return [
            'peer_relationships' => Form::where('slug', $data['slug'])->value('id')
        ];
    }

    private function addPeerRelationshipQuestions(int $formId, array &$questions, array &$answers): void
    {
        $questionsData = [
            'Feels included and accepted by peers',
            'Has close friendships and supportive relationships',
            'Experiences being left out or excluded by friends',
            'Feels anxious in social situations with peers',
            'Has difficulty sharing or cooperating with others',
            'Feels pressure to conform to peer expectations',
            'Experiences conflicts or disagreements with friends frequently',
            'Feels distressed when peers criticize or ignore them',
            'Finds it easy to make new friends',
            'Feels a sense of belonging at school or in social groups'
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
