<?php

namespace App\Traits;

trait AssessmentScoreAccessors
{
    /**
     * Central place for ALL assessment configurations
     * Add new screening tools here when you create them
     */
    protected function assessmentConfig(): array
    {
        return [
            'infants-toddlers-mood-behavior' => [
                'label'         => 'Early Childhood Emotional & Behavioral Screening (0–5 Years)',
                'clinical_domain' => 'Emotional & Behavioral Regulation',
                'max_score'     => 24,
                'severity'      => [
                    ['min' => 0,  'max' => 6,  'label' => 'Within Expected Range',  'color' => '#22C55E', /* ... message ... */],
                    ['min' => 7,  'max' => 12, 'label' => 'Mild Concern',           'color' => '#EAB308', /* ... */],
                    ['min' => 13, 'max' => 18, 'label' => 'Moderate Concern',       'color' => '#F97316', /* ... */],
                    ['min' => 19, 'max' => 24, 'label' => 'High Concern',           'color' => '#EF4444', /* ... */],
                ],
            ],
            'infants-toddlers-behavior-irritability' => [
                'label'         => 'Behavioral Responses & Irritability (0–5 Years)',
                'clinical_domain' => 'Behavioral Regulation & Irritability',
                'max_score'     => 24,           // 8 questions × 0–3
                'severity'      => [
                    [
                        'min' => 0,
                        'max' => 6,
                        'label' => 'Within Expected Range',
                        'color' => '#22C55E',
                        'message' => "Irritability and behavioral responses appear age-appropriate.\n\n"
                            . "• Frustration is manageable\n"
                            . "• Tantrums are brief and situational\n\n"
                            . "Continue with consistent, responsive caregiving.",
                    ],
                    [
                        'min' => 7,
                        'max' => 12,
                        'label' => 'Mild Concern',
                        'color' => '#EAB308',
                        'message' => "Mildly elevated irritability or regulatory difficulty.\n\n"
                            . "• Occasional intense reactions\n"
                            . "• May respond to structure and soothing\n\n"
                            . "Monitor frequency, duration, and triggers.",
                    ],
                    [
                        'min' => 13,
                        'max' => 18,
                        'label' => 'Moderate Concern',
                        'color' => '#F97316',
                        'message' => "Noticeable challenges in behavioral regulation.\n\n"
                            . "• Frequent or prolonged distress\n"
                            . "• Impacts daily routines\n\n"
                            . "Consider discussing with pediatrician or early intervention specialist.",
                    ],
                    [
                        'min' => 19,
                        'max' => 24,
                        'label' => 'High Concern',
                        'color' => '#EF4444',
                        'message' => "Significant and frequent behavioral dysregulation.\n\n"
                            . "• Intense, prolonged, or very frequent episodes\n"
                            . "• Likely affecting development and family functioning\n\n"
                            . "⚠ Prompt professional evaluation is strongly recommended.\n"
                            . "This is a screening result — not a diagnosis.",
                    ],
                ],
            ],
            'infants-toddlers-sleep-feeding-regulation' => [
                'label'           => 'Sleep & Feeding Regulation Screening (0–5 Years)',
                'clinical_domain' => 'Sleep-Wake & Feeding Regulation',
                'max_score'       => 24, // 8 questions × 0–3 points
                'severity'        => [
                    [
                        'min'     => 0,
                        'max'     => 6,
                        'label'   => 'Within Expected Range',
                        'color'   => '#22C55E',
                        'message' => "Sleep and feeding patterns appear age-appropriate.\n\n"
                            . "• Consistent routines are generally established\n"
                            . "• Minimal distress during transitions\n\n"
                            . "Continue with current supportive caregiving strategies.",
                    ],
                    [
                        'min'     => 7,
                        'max'     => 12,
                        'label'   => 'Mild Concern',
                        'color'   => '#EAB308',
                        'message' => "Mild difficulties with sleep onset, night waking, or feeding.\n\n"
                            . "• Occasional resistance or inconsistency\n"
                            . "• May respond well to structure and soothing\n\n"
                            . "Monitor patterns and consider minor routine adjustments.",
                    ],
                    [
                        'min'     => 13,
                        'max'     => 18,
                        'label'   => 'Moderate Concern',
                        'color'   => '#F97316',
                        'message' => "Noticeable challenges in sleep and/or feeding regulation.\n\n"
                            . "• Frequent night waking or significant resistance\n"
                            . "• Impacts family routines and caregiver well-being\n\n"
                            . "Consider consulting a pediatrician or early childhood specialist.",
                    ],
                    [
                        'min'     => 19,
                        'max'     => 24,
                        'label'   => 'High Concern',
                        'color'   => '#EF4444',
                        'message' => "Significant and persistent sleep and/or feeding dysregulation.\n\n"
                            . "• Severe, frequent, or prolonged difficulties\n"
                            . "• Likely affecting development, growth, and family functioning\n\n"
                            . "⚠ Prompt professional evaluation is strongly recommended.\n"
                            . "Early targeted support can significantly improve outcomes.\n"
                            . "This is a screening result — not a diagnosis.",
                    ],
                ],
            ],
            'infants-toddlers-social-engagement-attachment' => [
                'label'           => 'Social Engagement & Attachment Screening (0–5 Years)',
                'clinical_domain' => 'Social Engagement & Attachment',
                'max_score'       => 24,  // 8 questions × 0–3
                'severity'        => [
                    [
                        'min'     => 0,
                        'max'     => 6,
                        'label'   => 'Within Expected Range',
                        'color'   => '#22C55E',
                        'message' => "Social engagement and attachment behaviors appear age-appropriate.\n\n"
                            . "• Consistent eye contact, name response, and comfort-seeking\n"
                            . "• Enjoys social play and shared positive affect with caregivers\n\n"
                            . "✔ Continue nurturing responsive interactions and play.",
                    ],
                    [
                        'min'     => 7,
                        'max'     => 12,
                        'label'   => 'Mild Concern',
                        'color'   => '#EAB308',
                        'message' => "Mildly reduced or inconsistent social engagement.\n\n"
                            . "• Occasional eye contact / response to name\n"
                            . "• Some interest in social games, but may be fleeting\n\n"
                            . "Monitor social initiations and reciprocity.\n"
                            . "Encourage frequent face-to-face play and turn-taking activities.",
                    ],
                    [
                        'min'     => 13,
                        'max'     => 18,
                        'label'   => 'Moderate Concern',
                        'color'   => '#F97316',
                        'message' => "Noticeable limitations in social engagement or attachment behaviors.\n\n"
                            . "• Infrequent eye contact, limited comfort-seeking, or reduced shared enjoyment\n"
                            . "• May show signs of withdrawal or lack of social interest\n\n"
                            . "Discuss with pediatrician or early intervention specialist.\n"
                            . "Prioritize responsive, warm, and predictable caregiver interactions.",
                    ],
                    [
                        'min'     => 19,
                        'max'     => 24,
                        'label'   => 'High Concern',
                        'color'   => '#EF4444',
                        'message' => "Significant difficulties in social engagement and/or attachment.\n\n"
                            . "• Minimal eye contact, rare response to name, little comfort-seeking\n"
                            . "• Marked withdrawal, lack of social interest, or atypical responses to caregivers\n\n"
                            . "⚠ Prompt professional evaluation is strongly recommended (consider developmental / autism / attachment specialist).\n"
                            . "Early, intensive, relationship-focused support is critical.\n"
                            . "This is a screening result — not a diagnosis.",
                    ],
                ],
            ],
            'infants-toddlers-communication-responsiveness' => [
                'label'           => 'Communication & Responsiveness Screening (0–5 Years)',
                'clinical_domain' => 'Communication & Responsiveness',
                'max_score'       => 24,  // 8 questions × 0–3
                'severity'        => [
                    [
                        'min'     => 0,
                        'max'     => 6,
                        'label'   => 'Within Expected Range',
                        'color'   => '#22C55E',
                        'message' => "Communication and responsiveness skills appear age-appropriate.\n\n"
                            . "• Responds reliably to sounds, voices, and name\n"
                            . "• Uses gestures, babbling, imitation, and early words meaningfully\n"
                            . "• Follows simple instructions and shows understanding of familiar words\n\n"
                            . "✔ Continue rich language exposure, responsive talking, reading, and play.",
                    ],
                    [
                        'min'     => 7,
                        'max'     => 12,
                        'label'   => 'Mild Concern',
                        'color'   => '#EAB308',
                        'message' => "Mild delays or inconsistencies in communication and responsiveness.\n\n"
                            . "• Occasional response to name/sounds, limited variety in babbling/gestures\n"
                            . "• Some imitation or early words, but less frequent than expected\n\n"
                            . "Monitor progress closely.\n"
                            . "Increase responsive interactions: narrate daily routines, use simple gestures, read daily.",
                    ],
                    [
                        'min'     => 13,
                        'max'     => 18,
                        'label'   => 'Moderate Concern',
                        'color'   => '#F97316',
                        'message' => "Noticeable delays in communication responsiveness.\n\n"
                            . "• Limited or inconsistent response to sounds/voice/name\n"
                            . "• Reduced babbling, gestures, imitation, words, or following instructions\n"
                            . "• May appear unresponsive to many communication attempts\n\n"
                            . "Discuss with pediatrician or speech-language pathologist soon.\n"
                            . "Prioritize frequent face-to-face talk, gestures, songs, and turn-taking play.",
                    ],
                    [
                        'min'     => 19,
                        'max'     => 24,
                        'label'   => 'High Concern',
                        'color'   => '#EF4444',
                        'message' => "Significant difficulties in communication and responsiveness.\n\n"
                            . "• Minimal response to sounds, name, or communication attempts\n"
                            . "• Little to no babbling, gestures, imitation, words/signs, or understanding\n"
                            . "• Appears largely unresponsive or withdrawn from communicative interactions\n\n"
                            . "⚠ Prompt professional evaluation is strongly recommended (consider speech-language pathologist, developmental pediatrician, or early intervention team).\n"
                            . "Early, intensive language-rich support is critical for outcomes.\n"
                            . "This is a screening result — not a diagnosis.",
                    ],
                ],
            ],

            'infants-toddlers-dev-milestones-play' => [
                'label'           => 'Developmental Milestones & Play Skills Screening (0–5 Years)',
                'clinical_domain' => 'Developmental Milestones & Play Skills',
                'max_score'       => 24,  // 8 questions × 0–3
                'severity'        => [
                    [
                        'min'     => 0,
                        'max'     => 6,
                        'label'   => 'Within Expected Range',
                        'color'   => '#22C55E',
                        'message' => "Developmental progress and play skills appear age-appropriate.\n\n"
                            . "• Shows age-expected curiosity, exploration, and toy use\n"
                            . "• Engages in pretend play, fine/gross motor activities, and learns at expected pace\n\n"
                            . "✔ Continue providing varied, safe play opportunities and responsive encouragement.",
                    ],
                    [
                        'min'     => 7,
                        'max'     => 12,
                        'label'   => 'Mild Concern',
                        'color'   => '#EAB308',
                        'message' => "Mild delays or inconsistencies in developmental milestones or play.\n\n"
                            . "• Occasional difficulty with age-expected play, exploration, or motor skills\n"
                            . "• Learning pace slightly slower in some areas\n\n"
                            . "Monitor development over the next 1–2 months.\n"
                            . "Increase opportunities for varied play, movement, and skill-building activities.",
                    ],
                    [
                        'min'     => 13,
                        'max'     => 18,
                        'label'   => 'Moderate Concern',
                        'color'   => '#F97316',
                        'message' => "Noticeable delays in multiple developmental domains or play engagement.\n\n"
                            . "• Limited pretend/imaginative play, reduced exploration or toy use\n"
                            . "• Fine/gross motor skills or new skill acquisition behind age expectations\n\n"
                            . "Discuss with pediatrician or early intervention team.\n"
                            . "Focus on structured play, motor practice, and enriched learning environments.",
                    ],
                    [
                        'min'     => 19,
                        'max'     => 24,
                        'label'   => 'High Concern',
                        'color'   => '#EF4444',
                        'message' => "Significant global or domain-specific developmental delay.\n\n"
                            . "• Very limited or absent age-appropriate play, exploration, or motor skills\n"
                            . "• Minimal engagement in pretend play, toy use, or new skill acquisition\n"
                            . "• Clear difficulty participating in developmentally expected activities\n\n"
                            . "⚠ Prompt comprehensive developmental evaluation is strongly recommended (pediatrician + early intervention / developmental specialist).\n"
                            . "Early, intensive, individualized support can substantially improve long-term outcomes.\n"
                            . "This is a screening result — not a diagnosis.",
                    ],
                ],
            ],

            'children-mood-emotional-expression' => [
                'label'           => 'Mood & Emotional Expression Screening (6–12 Years)',
                'clinical_domain' => 'Mood & Emotional Expression',
                'max_score'       => 24,  // 8 questions × 0–3
                'severity'        => [
                    [
                        'min'     => 0,
                        'max'     => 5,
                        'label'   => 'Within Expected Range',
                        'color'   => '#22C55E',
                        'message' => "Emotional expression and mood appear age-appropriate.\n\n"
                            . "• Occasional sadness, frustration or worry is normal and manageable\n"
                            . "• Emotions are expressed in proportion to situations\n"
                            . "• No significant interference with school, friendships or home life\n\n"
                            . "✔ Continue open communication, emotional coaching, and stable routines.",
                    ],
                    [
                        'min'     => 6,
                        'max'     => 11,
                        'label'   => 'Mild Concern',
                        'color'   => '#EAB308',
                        'message' => "Mildly elevated or frequent emotional difficulties.\n\n"
                            . "• More frequent low mood, tearfulness, frustration, or worry than peers\n"
                            . "• Emotions sometimes feel intense or hard to manage\n"
                            . "• Minimal impact on daily functioning\n\n"
                            . "Monitor closely over the next few weeks.\n"
                            . "Use emotion naming, calming strategies, and increased positive attention.",
                    ],
                    [
                        'min'     => 12,
                        'max'     => 17,
                        'label'   => 'Moderate Concern',
                        'color'   => '#F97316',
                        'message' => "Noticeable and frequent difficulties with mood and emotional regulation.\n\n"
                            . "• Frequent sadness, irritability, intense reactions, or excessive worry\n"
                            . "• Emotions often feel overwhelming or disproportionate\n"
                            . "• Some interference with school performance, peer relationships or family life\n\n"
                            . "Discuss with school counselor, pediatrician or child mental health professional.\n"
                            . "Implement structured emotional regulation tools and consistent support.",
                    ],
                    [
                        'min'     => 18,
                        'max'     => 24,
                        'label'   => 'High Concern',
                        'color'   => '#EF4444',
                        'message' => "Significant and persistent difficulties in mood and emotional expression.\n\n"
                            . "• Frequent or intense sadness, anger, anxiety, or emotional lability\n"
                            . "• Strong difficulty identifying, expressing or regulating feelings\n"
                            . "• Clear negative impact on school, social relationships, family functioning and/or self-esteem\n\n"
                            . "⚠ Prompt professional assessment is strongly recommended (child psychologist, psychiatrist or mental health clinic).\n"
                            . "Early evidence-based intervention can greatly improve outcomes.\n"
                            . "This is a screening result — not a diagnosis.",
                    ],
                ],
            ],
            'children-energy-motivation-activity' => [
                'label'           => 'Energy, Motivation & Activity Level Screening (6–12 Years)',
                'clinical_domain' => 'Energy, Motivation & Activity Level',
                'max_score'       => 24,  // 8 questions × 0–3
                'severity'        => [
                    [
                        'min'     => 0,
                        'max'     => 5,
                        'label'   => 'Within Expected Range',
                        'color'   => '#22C55E',
                        'message' => "Energy, motivation and activity levels appear age-appropriate.\n\n"
                            . "• Typical energy for school, play and daily routines\n"
                            . "• Sustains interest and effort in most activities\n"
                            . "• Completes tasks without excessive prompting\n\n"
                            . "✔ Continue supporting balanced routines, physical activity and positive reinforcement.",
                    ],
                    [
                        'min'     => 6,
                        'max'     => 11,
                        'label'   => 'Mild Concern',
                        'color'   => '#EAB308',
                        'message' => "Mildly reduced energy, motivation or engagement.\n\n"
                            . "• Occasionally appears tired, unmotivated or loses interest quickly\n"
                            . "• May need some extra encouragement for tasks or activities\n"
                            . "• Minimal impact on school performance or daily functioning\n\n"
                            . "Monitor over the next few weeks.\n"
                            . "Encourage regular physical activity, adequate sleep, healthy eating and small achievable goals.",
                    ],
                    [
                        'min'     => 12,
                        'max'     => 17,
                        'label'   => 'Moderate Concern',
                        'color'   => '#F97316',
                        'message' => "Noticeable and frequent difficulties with energy, motivation or activity.\n\n"
                            . "• Often appears low-energy, avoids effortful tasks, or loses interest rapidly\n"
                            . "• Needs frequent prompting to start or complete activities\n"
                            . "• Some interference with schoolwork, homework completion or participation in activities\n\n"
                            . "Discuss with pediatrician, school counselor or child mental health professional.\n"
                            . "Implement structured daily routines, reward systems, and increased physical/outdoor time.",
                    ],
                    [
                        'min'     => 18,
                        'max'     => 24,
                        'label'   => 'High Concern',
                        'color'   => '#EF4444',
                        'message' => "Significant and persistent problems with energy, motivation and activity level.\n\n"
                            . "• Frequently appears very tired, lacks motivation, avoids most activities\n"
                            . "• Marked slowing of movement/thinking or extreme difficulty initiating/completing tasks\n"
                            . "• Clear negative impact on academic performance, social participation and family functioning\n\n"
                            . "⚠ Prompt professional evaluation is strongly recommended (pediatrician + child psychologist/psychiatrist).\n"
                            . "May indicate underlying mood, attention, medical or neurodevelopmental concern.\n"
                            . "Early intervention and support can significantly improve functioning.\n"
                            . "This is a screening result — not a diagnosis.",
                    ],
                ],
            ],
            'children-sleep-quality-fatigue' => [
                'label'           => 'Sleep Quality & Fatigue Screening (6–12 Years)',
                'clinical_domain' => 'Sleep & Fatigue',
                'max_score'       => 24,
                'severity'        => [
                    [
                        'min'     => 0,
                        'max'     => 5,
                        'label'   => 'Within Expected Range',
                        'color'   => '#22C55E',
                        'message' => "Sleep quality and daytime energy levels appear age-appropriate.\n\n"
                            . "• Falls asleep within reasonable time most nights\n"
                            . "• Minimal night wakings and wakes feeling rested\n"
                            . "• Sustains good energy and alertness during school and activities\n\n"
                            . "✔ Maintain consistent bedtime routines, limit screens before bed, and encourage physical activity during the day.",
                    ],
                    [
                        'min'     => 6,
                        'max'     => 11,
                        'label'   => 'Mild Concern',
                        'color'   => '#EAB308',
                        'message' => "Mild difficulties with sleep quality or daytime fatigue.\n\n"
                            . "• Occasional trouble falling asleep, night wakings, or feeling tired during the day\n"
                            . "• Some impact on concentration or mood, but still manages most activities\n\n"
                            . "Monitor sleep patterns for 2–4 weeks.\n"
                            . "Strengthen sleep hygiene: fixed bedtime/wake time, calm pre-bed routine, no caffeine after lunch, limit electronics 1 hour before bed.",
                    ],
                    [
                        'min'     => 12,
                        'max'     => 17,
                        'label'   => 'Moderate Concern',
                        'color'   => '#F97316',
                        'message' => "Noticeable and frequent problems with sleep or daytime fatigue.\n\n"
                            . "• Regular difficulty falling/staying asleep or significant daytime sleepiness/fatigue\n"
                            . "• Impacts school performance, concentration, mood or participation in activities\n"
                            . "• May complain often of being tired or need extra rest\n\n"
                            . "Discuss with pediatrician soon — consider sleep diary and possible referral to pediatric sleep specialist.\n"
                            . "Implement stricter sleep schedule, relaxation techniques, and daytime activity/exercise.",
                    ],
                    [
                        'min'     => 18,
                        'max'     => 24,
                        'label'   => 'High Concern',
                        'color'   => '#EF4444',
                        'message' => "Significant and persistent sleep disruption and/or severe daytime fatigue.\n\n"
                            . "• Frequent or prolonged difficulty initiating or maintaining sleep\n"
                            . "• Marked daytime sleepiness, low energy, difficulty staying awake in class/activities\n"
                            . "• Clear negative effects on learning, behavior, mood, social functioning and/or physical health\n\n"
                            . "⚠ Prompt professional evaluation is strongly recommended (pediatrician + possible sleep study / pediatric sleep specialist).\n"
                            . "Chronic sleep problems in children can affect growth, learning and emotional health.\n"
                            . "Early identification and treatment usually lead to good outcomes.\n"
                            . "This is a screening result — not a diagnosis.",
                    ],
                ],
            ],
            'children-attention-focus-learning' => [
                'label'           => 'Attention, Focus & Learning Screening (6–12 Years)',
                'clinical_domain' => 'Attention, Focus & Learning',
                'max_score'       => 24,  // assuming 8 questions × 0–3 (standard pattern in your project)
                'severity'        => [
                    [
                        'min'     => 0,
                        'max'     => 5,
                        'label'   => 'Within Expected Range',
                        'color'   => '#22C55E',
                        'message' => "Attention, focus and learning behaviors appear age-appropriate.\n\n"
                            . "• Sustains attention during school tasks and activities appropriate for age\n"
                            . "• Follows multi-step instructions with reasonable accuracy\n"
                            . "• Completes work and learns new material at expected pace\n\n"
                            . "✔ Continue consistent routines, clear expectations, positive reinforcement and breaks as needed.",
                    ],
                    [
                        'min'     => 6,
                        'max'     => 11,
                        'label'   => 'Mild Concern',
                        'color'   => '#EAB308',
                        'message' => "Mild difficulties with sustained attention, focus or learning efficiency.\n\n"
                            . "• Occasional distractibility, daydreaming or trouble completing tasks\n"
                            . "• Needs some extra reminders or structure to stay on task\n"
                            . "• Minor impact on school performance or homework completion\n\n"
                            . "Monitor over the next 4–6 weeks.\n"
                            . "Try environmental adjustments: reduce distractions, use visual timers, break tasks into smaller steps, increase movement breaks.",
                    ],
                    [
                        'min'     => 12,
                        'max'     => 17,
                        'label'   => 'Moderate Concern',
                        'color'   => '#F97316',
                        'message' => "Noticeable and frequent challenges with attention, focus or academic efficiency.\n\n"
                            . "• Regular distractibility, difficulty sustaining attention, or frequent off-task behavior\n"
                            . "• Struggles to follow instructions, organize work or complete assignments independently\n"
                            . "• Clear impact on school grades, homework completion or teacher feedback\n\n"
                            . "Discuss with classroom teacher and pediatrician / school counselor soon.\n"
                            . "Implement structured supports: daily planners, seating adjustments, task checklists, frequent feedback.",
                    ],
                    [
                        'min'     => 18,
                        'max'     => 24,
                        'label'   => 'High Concern',
                        'color'   => '#EF4444',
                        'message' => "Significant and persistent difficulties with attention, focus and/or learning.\n\n"
                            . "• Severe distractibility, very short attention span, or extreme difficulty staying on task\n"
                            . "• Major challenges following directions, organizing materials, completing work\n"
                            . "• Substantial negative impact on academic progress, classroom behavior and self-esteem\n\n"
                            . "⚠ Prompt professional evaluation is strongly recommended (pediatrician + possible referral to child psychologist, neuropsychologist or ADHD/learning specialist).\n"
                            . "May indicate ADHD, learning difficulty or other neurodevelopmental concern.\n"
                            . "Early identification and appropriate supports/interventions usually lead to much better outcomes.\n"
                            . "This is a screening result — not a diagnosis.",
                    ],
                ],
            ],
            'children-social-interaction-peer' => [
                'label'           => 'Social Interaction & Peer Relationships Screening (6–12 Years)',
                'clinical_domain' => 'Social Interaction & Peer Relationships',
                'max_score'       => 24,  // 8 questions × 0–3
                'severity'        => [
                    [
                        'min'     => 0,
                        'max'     => 5,
                        'label'   => 'Within Expected Range',
                        'color'   => '#22C55E',
                        'message' => "Social interaction and peer relationships appear age-appropriate.\n\n"
                            . "• Makes and keeps friends with relative ease\n"
                            . "• Understands basic social cues and cooperates in group settings\n"
                            . "• Handles disagreements and participates in team activities normally\n\n"
                            . "✔ Continue encouraging group play, team activities, and open conversations about friendships.",
                    ],
                    [
                        'min'     => 6,
                        'max'     => 11,
                        'label'   => 'Mild Concern',
                        'color'   => '#EAB308',
                        'message' => "Mild difficulties in social interactions or peer relationships.\n\n"
                            . "• Occasionally prefers playing alone or has minor trouble joining groups\n"
                            . "• Some shyness, occasional misunderstandings of social cues, or minor conflicts\n"
                            . "• Friendships exist but may be limited in number or depth\n\n"
                            . "Monitor over the next few months.\n"
                            . "Encourage structured playdates, social skills practice (role-playing, turn-taking), and praise for positive peer interactions.",
                    ],
                    [
                        'min'     => 12,
                        'max'     => 17,
                        'label'   => 'Moderate Concern',
                        'color'   => '#F97316',
                        'message' => "Noticeable and frequent challenges in social engagement or peer relationships.\n\n"
                            . "• Regularly withdraws from groups, struggles to make/maintain friends\n"
                            . "• Frequent misunderstandings of social cues, body language or conversational norms\n"
                            . "• Repeated conflicts, difficulty cooperating, or feeling excluded by peers\n\n"
                            . "Discuss with school counselor / teacher and pediatrician.\n"
                            . "Consider structured social skills groups, explicit teaching of social rules, and close monitoring of bullying or exclusion.",
                    ],
                    [
                        'min'     => 18,
                        'max'     => 24,
                        'label'   => 'High Concern',
                        'color'   => '#EF4444',
                        'message' => "Significant and persistent difficulties in social interaction and peer relationships.\n\n"
                            . "• Very limited or absent friendships, strong preference for solitary activities\n"
                            . "• Marked difficulty reading social cues, understanding others' perspectives, or engaging reciprocally\n"
                            . "• Frequent or intense conflicts, inappropriate social behaviors, or social isolation\n\n"
                            . "⚠ Prompt professional evaluation is strongly recommended (child psychologist, developmental pediatrician or specialist in social communication / neurodevelopmental disorders).\n"
                            . "May indicate social communication challenges, autism spectrum traits, anxiety or other concerns.\n"
                            . "Early, targeted social skills intervention and support can substantially improve peer relationships and confidence.\n"
                            . "This is a screening result — not a diagnosis.",
                    ],
                ],
            ],
            'children-appetite-eating' => [
                'label'           => 'Appetite & Eating Patterns Screening (6–12 Years)',
                'clinical_domain' => 'Appetite & Eating Patterns',
                'max_score'       => 24,
                'severity'        => [
                    [
                        'min'     => 0,
                        'max'     => 5,
                        'label'   => 'Within Expected Range',
                        'color'   => '#22C55E',
                        'message' => "Appetite and eating patterns appear age-appropriate.\n\n"
                            . "• Regular meal patterns with good variety\n"
                            . "• Appropriate hunger/fullness cues and portion sizes\n"
                            . "• Minimal mealtime conflicts or emotional eating\n\n"
                            . "✔ Continue family meals, balanced food exposure, and positive mealtime environment.",
                    ],
                    [
                        'min'     => 6,
                        'max'     => 11,
                        'label'   => 'Mild Concern',
                        'color'   => '#EAB308',
                        'message' => "Mild irregularities or concerns in appetite/eating behaviors.\n\n"
                            . "• Occasional skipped meals, picky eating, or emotional eating\n"
                            . "• Some reluctance with new foods or minor mealtime tension\n"
                            . "• Little impact on growth, energy or daily functioning\n\n"
                            . "Monitor eating patterns for 4–6 weeks.\n"
                            . "Try consistent meal/snack times, no pressure tactics, gradual exposure to new foods, and calm mealtime atmosphere.",
                    ],
                    [
                        'min'     => 12,
                        'max'     => 17,
                        'label'   => 'Moderate Concern',
                        'color'   => '#F97316',
                        'message' => "Noticeable and frequent difficulties with appetite or eating patterns.\n\n"
                            . "• Regular meal skipping, overeating episodes, or strong food avoidance\n"
                            . "• Frequent emotional eating, slow eating, or significant mealtime conflicts\n"
                            . "• Some effect on energy, concentration, growth trajectory or family stress\n\n"
                            . "Discuss with pediatrician soon — track growth chart and consider referral to pediatric dietitian or feeding specialist.\n"
                            . "Implement structured meal plan, hunger/fullness awareness activities, and reduce emotional food associations.",
                    ],
                    [
                        'min'     => 18,
                        'max'     => 24,
                        'label'   => 'High Concern',
                        'color'   => '#EF4444',
                        'message' => "Significant and persistent problems with appetite, eating behaviors or mealtime dynamics.\n\n"
                            . "• Severe restriction, frequent binge-like eating, or extreme pickiness\n"
                            . "• Major mealtime battles, emotional dependence on food, or very irregular patterns\n"
                            . "• Clear impact on physical growth, energy, school performance, mood or family functioning\n\n"
                            . "⚠ Prompt professional evaluation is strongly recommended (pediatrician + possible referral to pediatric eating/feeding specialist, psychologist or multidisciplinary eating disorder team).\n"
                            . "Eating and appetite issues in children can affect nutrition, growth and emotional health.\n"
                            . "Early, supportive, non-punitive intervention usually leads to good outcomes.\n"
                            . "This is a screening result — not a diagnosis.",
                    ],
                ],
            ],
            'children-stress-response-coping' => [
                'label'           => 'Stress Response & Coping Skills Screening (6–12 Years)',
                'clinical_domain' => 'Stress Response & Coping Skills',
                'max_score'       => 24,  // 8 questions × 0–3
                'severity'        => [
                    [
                        'min'     => 0,
                        'max'     => 5,
                        'label'   => 'Within Expected Range',
                        'color'   => '#22C55E',
                        'message' => "Stress response and coping skills appear age-appropriate.\n\n"
                            . "• Handles everyday stressors and changes with reasonable calm\n"
                            . "• Uses adaptive strategies (talking, deep breathing, seeking help) at least sometimes\n"
                            . "• Recovers quickly from frustrations or disappointments\n\n"
                            . "✔ Continue modeling calm responses, teaching simple coping tools, and praising effort in difficult moments.",
                    ],
                    [
                        'min'     => 6,
                        'max'     => 11,
                        'label'   => 'Mild Concern',
                        'color'   => '#EAB308',
                        'message' => "Mild difficulties in managing stress or using healthy coping strategies.\n\n"
                            . "• Occasionally feels overwhelmed by small changes or frustrations\n"
                            . "• Relies more on avoidance or less helpful behaviors when stressed\n"
                            . "• Takes longer to calm down but still manages most situations\n\n"
                            . "Monitor for 4–6 weeks.\n"
                            . "Teach and practice simple calming techniques (counting to 10, deep breaths, naming feelings) and encourage asking for help.",
                    ],
                    [
                        'min'     => 12,
                        'max'     => 17,
                        'label'   => 'Moderate Concern',
                        'color'   => '#F97316',
                        'message' => "Noticeable and frequent challenges in stress response or coping.\n\n"
                            . "• Often feels overwhelmed by typical school/home stressors\n"
                            . "• Frequently uses maladaptive coping (tantrums, withdrawal, aggression)\n"
                            . "• Difficulty adapting to changes or recovering from upsetting events\n\n"
                            . "Discuss with school counselor or pediatrician.\n"
                            . "Implement structured coping plan: daily check-ins, emotion charts, calm-down corner, consistent consequences + positive reinforcement for adaptive behavior.",
                    ],
                    [
                        'min'     => 18,
                        'max'     => 24,
                        'label'   => 'High Concern',
                        'color'   => '#EF4444',
                        'message' => "Significant and persistent difficulties with stress response and coping skills.\n\n"
                            . "• Becomes extremely overwhelmed by minor stressors or routine changes\n"
                            . "• Predominantly uses unhealthy or disruptive coping strategies\n"
                            . "• Very slow or unable to calm down; high emotional reactivity or shutdown\n"
                            . "• Clear negative impact on school performance, peer relationships and family functioning\n\n"
                            . "⚠ Prompt professional evaluation is strongly recommended (child psychologist, school counselor or mental health specialist).\n"
                            . "May indicate underlying anxiety, emotional regulation challenges or neurodevelopmental concerns.\n"
                            . "Early, structured, evidence-based support (e.g., CBT-based coping skills training) can significantly improve regulation and resilience.\n"
                            . "This is a screening result — not a diagnosis.",
                    ],
                ],
            ],
            'children-emotional-regulation-adaptability' => [
                'label'           => 'Emotional Regulation & Adaptability Screening (6–12 Years)',
                'clinical_domain' => 'Emotional Regulation & Adaptability',
                'max_score'       => 24,
                'severity'        => [
                    [
                        'min'     => 0,
                        'max'     => 5,
                        'label'   => 'Within Expected Range',
                        'color'   => '#22C55E',
                        'message' => "Emotional regulation and adaptability appear age-appropriate.\n\n"
                            . "• Manages frustration and strong emotions effectively most of the time\n"
                            . "• Calms down reasonably quickly after upsetting events\n"
                            . "• Adapts to routine changes and new situations with minimal distress\n\n"
                            . "✔ Continue modeling calm responses, naming emotions, and praising use of coping strategies.",
                    ],
                    [
                        'min'     => 6,
                        'max'     => 11,
                        'label'   => 'Mild Concern',
                        'color'   => '#EAB308',
                        'message' => "Mild difficulties with emotional regulation or adaptability.\n\n"
                            . "• Occasionally becomes very frustrated, has trouble calming down, or resists changes\n"
                            . "• Uses some adaptive strategies but inconsistently\n"
                            . "• Minor impact on school participation, peer interactions or family routines\n\n"
                            . "Monitor closely for 4–6 weeks.\n"
                            . "Practice simple regulation tools daily: deep breathing, counting, emotion naming, and previewing changes in advance.",
                    ],
                    [
                        'min'     => 12,
                        'max'     => 17,
                        'label'   => 'Moderate Concern',
                        'color'   => '#F97316',
                        'message' => "Noticeable and frequent challenges in emotional regulation or adaptability.\n\n"
                            . "• Often overwhelmed by emotions, has prolonged upset, or strong reactions to change\n"
                            . "• Rarely uses helpful coping strategies independently\n"
                            . "• Affects classroom behavior, peer relationships, homework completion or family harmony\n\n"
                            . "Discuss with school counselor / teacher and pediatrician.\n"
                            . "Create a personalized regulation plan: emotion check-ins, calm-down space, visual schedules, consistent responses to big feelings.",
                    ],
                    [
                        'min'     => 18,
                        'max'     => 24,
                        'label'   => 'High Concern',
                        'color'   => '#EF4444',
                        'message' => "Significant and persistent difficulties with emotional regulation and adaptability.\n\n"
                            . "• Frequently loses control of strong emotions (anger, anxiety, sadness)\n"
                            . "• Very slow or unable to calm down; extreme difficulty with transitions or unexpected changes\n"
                            . "• Marked impact on academic performance, social functioning, self-esteem and family life\n\n"
                            . "⚠ Prompt professional evaluation is strongly recommended (child psychologist, school-based mental health support or developmental/behavioral pediatrician).\n"
                            . "May indicate challenges with emotional dysregulation, anxiety, ADHD, trauma-related responses or other neurodevelopmental concerns.\n"
                            . "Evidence-based interventions (e.g., CBT, DBT skills adapted for children, emotion coaching programs) can significantly improve regulation and resilience.\n"
                            . "This is a screening result — not a diagnosis.",
                    ],
                ],
            ],
            'children-impulsivity-behavior-boys' => [
                'label'           => 'Impulsivity & Behavioral Regulation Screening (Boys, 6–12 Years)',
                'clinical_domain' => 'Impulsivity & Behavioral Regulation',
                'max_score'       => 24,
                'severity'        => [
                    [
                        'min'     => 0,
                        'max'     => 5,
                        'label'   => 'Within Expected Range',
                        'color'   => '#22C55E',
                        'message' => "Impulsivity and behavioral regulation appear age-appropriate for a boy of this age.\n\n"
                            . "• Generally thinks before acting and waits for turns appropriately\n"
                            . "• Manages frustration and follows rules/instructions most of the time\n"
                            . "• Minimal unsafe, aggressive or disruptive behavior\n\n"
                            . "✔ Continue clear expectations, positive reinforcement for self-control, and consistent routines.",
                    ],
                    [
                        'min'     => 6,
                        'max'     => 11,
                        'label'   => 'Mild Concern',
                        'color'   => '#EAB308',
                        'message' => "Mildly elevated impulsivity or challenges with behavioral regulation.\n\n"
                            . "• Occasionally acts without thinking, interrupts, or has trouble waiting\n"
                            . "• Some frustration outbursts or minor rule-breaking\n"
                            . "• Little overall impact on school performance or peer relationships\n\n"
                            . "Monitor over the next 4–8 weeks.\n"
                            . "Use visual reminders, teach pause-and-think strategies (count to 5, deep breath), reward waiting/turn-taking, and provide extra structure during high-demand times.",
                    ],
                    [
                        'min'     => 12,
                        'max'     => 17,
                        'label'   => 'Moderate Concern',
                        'color'   => '#F97316',
                        'message' => "Noticeable and frequent difficulties with impulse control and behavioral regulation.\n\n"
                            . "• Regularly interrupts, acts without thinking, struggles to wait or follow rules\n"
                            . "• Frequent frustration, temper outbursts or minor aggressive behaviors\n"
                            . "• Impacts classroom behavior, peer interactions, homework completion or family routines\n\n"
                            . "Discuss with teacher/school counselor and pediatrician soon.\n"
                            . "Implement structured behavior plan: clear rules + immediate positive/negative consequences, daily behavior chart, movement breaks, explicit teaching of self-regulation skills.",
                    ],
                    [
                        'min'     => 18,
                        'max'     => 24,
                        'label'   => 'High Concern',
                        'color'   => '#EF4444',
                        'message' => "Significant and persistent challenges with impulsivity and behavioral regulation.\n\n"
                            . "• Very frequent impulsive actions, extreme difficulty waiting or inhibiting responses\n"
                            . "• Intense or frequent frustration outbursts, aggression, or unsafe/risky behaviors\n"
                            . "• Major negative impact on school functioning, peer relationships, safety and family life\n\n"
                            . "⚠ Prompt professional evaluation is strongly recommended (pediatrician + child psychologist, behavioral specialist or developmental pediatrician).\n"
                            . "May indicate ADHD (combined/hyperactive presentation), oppositional behavior patterns, emotional dysregulation or other neurodevelopmental/behavioral concerns.\n"
                            . "Early, evidence-based intervention (behavioral therapy, parent training, possible school supports or medication evaluation) usually leads to substantial improvement.\n"
                            . "This is a screening result — not a diagnosis.",
                    ],
                ],
            ],
            'children-anger-irritability-boys' => [
                'label'           => 'Anger, Irritability & Frustration Expression Screening (Boys, 6–12 Years)',
                'clinical_domain' => 'Anger, Irritability & Frustration Expression',
                'max_score'       => 15,  // 5 questions × 0–3
                'severity'        => [
                    [
                        'min'     => 0,
                        'max'     => 3,
                        'label'   => 'Within Expected Range',
                        'color'   => '#22C55E',
                        'message' => "Anger, irritability and frustration expression appear age-appropriate for a boy of this age.\n\n"
                            . "• Occasional frustration is normal and managed reasonably well\n"
                            . "• Temper outbursts are brief, infrequent and proportionate\n"
                            . "• No significant pattern of irritability or inappropriate anger display\n\n"
                            . "✔ Continue consistent boundaries, emotional coaching and praise for calm responses.",
                    ],
                    [
                        'min'     => 4,
                        'max'     => 7,
                        'label'   => 'Mild Concern',
                        'color'   => '#EAB308',
                        'message' => "Mildly elevated irritability, frustration tolerance or anger expression.\n\n"
                            . "• Gets annoyed or frustrated more easily than most peers\n"
                            . "• Occasional temper outbursts or irritability with others\n"
                            . "• Minimal overall impact on school, friendships or family life\n\n"
                            . "Monitor closely over the next 4–8 weeks.\n"
                            . "Teach simple calming strategies (deep breaths, counting, walking away), use consistent consequences, and reinforce positive behavior frequently.",
                    ],
                    [
                        'min'     => 8,
                        'max'     => 11,
                        'label'   => 'Moderate Concern',
                        'color'   => '#F97316',
                        'message' => "Noticeable and frequent difficulties with anger, irritability or frustration tolerance.\n\n"
                            . "• Often becomes very annoyed, irritable or angry quickly\n"
                            . "• Regular temper outbursts or inappropriate expression of frustration\n"
                            . "• Affects peer relationships, classroom behavior, family interactions or school performance\n\n"
                            . "Discuss with teacher/school counselor and pediatrician.\n"
                            . "Implement structured anger management plan: emotion check-ins, calm-down steps, daily behavior tracking, clear rules + immediate consistent consequences.",
                    ],
                    [
                        'min'     => 12,
                        'max'     => 15,
                        'label'   => 'High Concern',
                        'color'   => '#EF4444',
                        'message' => "Significant and persistent problems with anger, irritability and frustration expression.\n\n"
                            . "• Very frequent or intense irritability, quick to anger, or prolonged outbursts\n"
                            . "• Anger expressed inappropriately or disproportionately (yelling, aggression, destruction)\n"
                            . "• Clear negative impact on school functioning, peer relationships, safety and family well-being\n\n"
                            . "⚠ Prompt professional evaluation is strongly recommended (pediatrician + child psychologist, behavioral specialist or anger management program).\n"
                            . "May indicate challenges with emotional regulation, oppositional patterns, ADHD-related impulsivity/frustration, or other underlying concerns.\n"
                            . "Early, structured intervention (parent training, CBT-based anger management, school supports) usually leads to substantial improvement.\n"
                            . "This is a screening result — not a diagnosis.",
                    ],
                ],
            ],
            'children-rule-following-boys' => [
                'label'           => 'Rule-Following & Authority Interaction Screening (Boys, 6–12 Years)',
                'clinical_domain' => 'Rule-Following & Authority Interaction',
                'max_score'       => 15,
                'severity'        => [
                    [
                        'min'     => 0,
                        'max'     => 3,
                        'label'   => 'Within Expected Range',
                        'color'   => '#22C55E',
                        'message' => "Rule-following and interactions with authority figures appear age-appropriate for a boy of this age.\n\n"
                            . "• Generally follows rules and instructions from adults\n"
                            . "• Accepts consequences and shows typical respect for authority\n"
                            . "• Minimal oppositional or defiant behavior in structured settings\n\n"
                            . "✔ Continue with clear, consistent rules, positive reinforcement for compliance, and calm, predictable responses to minor pushback.",
                    ],
                    [
                        'min'     => 4,
                        'max'     => 7,
                        'label'   => 'Mild Concern',
                        'color'   => '#EAB308',
                        'message' => "Mild difficulties with consistent rule-following or authority interactions.\n\n"
                            . "• Occasionally challenges rules, questions instructions, or shows mild opposition\n"
                            . "• Some difficulty accepting consequences or understanding why rules exist\n"
                            . "• Minor impact on classroom behavior, family routines or adult relationships\n\n"
                            . "Monitor closely over the next 4–8 weeks.\n"
                            . "Use clear expectations, immediate consistent consequences, visual rule reminders, and frequent praise for following directions.",
                    ],
                    [
                        'min'     => 8,
                        'max'     => 11,
                        'label'   => 'Moderate Concern',
                        'color'   => '#F97316',
                        'message' => "Noticeable and frequent challenges with rule compliance or authority relationships.\n\n"
                            . "• Regularly disobeys instructions, argues with adults, or refuses to follow rules\n"
                            . "• Frequent oppositional behavior in school or home settings\n"
                            . "• Affects classroom functioning, peer interactions, homework completion or family harmony\n\n"
                            . "Discuss with teacher/school counselor and pediatrician soon.\n"
                            . "Implement structured behavior support plan: daily behavior tracking, clear rules + immediate consequences, reward system for compliance, consistent adult responses across settings.",
                    ],
                    [
                        'min'     => 12,
                        'max'     => 15,
                        'label'   => 'High Concern',
                        'color'   => '#EF4444',
                        'message' => "Significant and persistent difficulties with rule-following and authority interactions.\n\n"
                            . "• Very frequent defiance, arguing, or refusal to follow adult instructions\n"
                            . "• Strong oppositional or defiant pattern across multiple settings (home, school)\n"
                            . "• Major negative impact on academic progress, peer relationships, safety and family functioning\n\n"
                            . "⚠ Prompt professional evaluation is strongly recommended (pediatrician + child psychologist, behavioral specialist or developmental pediatrician).\n"
                            . "May indicate Oppositional Defiant Disorder traits, ADHD-related behavioral challenges, emotional regulation difficulties or other underlying concerns.\n"
                            . "Early, structured intervention (parent training programs, school-based behavior plans, CBT-based approaches) usually leads to substantial improvement.\n"
                            . "This is a screening result — not a diagnosis.",
                    ],
                ],
            ],
            'children-physical-play-boys' => [
                'label'           => 'Physical Play, Risk-Taking & Peer Conflicts Screening (Boys, 6–12 Years)',
                'clinical_domain' => 'Physical Play & Peer Interactions',
                'max_score'       => 15,
                'severity'        => [
                    [
                        'min'     => 0,
                        'max'     => 3,
                        'label'   => 'Within Expected Range',
                        'color'   => '#22C55E',
                        'message' => "Physical play, risk-taking and peer interaction patterns appear age-appropriate for a boy of this age.\n\n"
                            . "• Engages in active/rough play safely and with good control\n"
                            . "• Takes typical age-appropriate risks without frequent injury\n"
                            . "• Manages turn-taking, sharing and peer conflicts reasonably well\n\n"
                            . "✔ Continue encouraging active play, teaching safe boundaries, and reinforcing cooperative play behaviors.",
                    ],
                    [
                        'min'     => 4,
                        'max'     => 7,
                        'label'   => 'Mild Concern',
                        'color'   => '#EAB308',
                        'message' => "Mildly elevated or concerning patterns in physical play, risk-taking or peer interactions.\n\n"
                            . "• Occasionally engages in overly rough play or takes unnecessary risks\n"
                            . "• Some difficulty sharing, taking turns or resolving minor peer conflicts\n"
                            . "• Minimal overall impact on safety, friendships or adult supervision needs\n\n"
                            . "Monitor over the next 4–8 weeks.\n"
                            . "Set clear physical play rules, use role-playing for turn-taking/conflict resolution, supervise high-energy play closely, and praise safe/cooperative behavior.",
                    ],
                    [
                        'min'     => 8,
                        'max'     => 11,
                        'label'   => 'Moderate Concern',
                        'color'   => '#F97316',
                        'message' => "Noticeable and frequent concerns in physical play style, risk-taking or peer conflict management.\n\n"
                            . "• Regularly engages in rough/unsafe play or takes significant physical risks\n"
                            . "• Frequent pushing, physical challenges with peers, or difficulty with sharing/turns\n"
                            . "• Leads to more frequent injuries, peer conflicts, adult intervention or social exclusion\n\n"
                            . "Discuss with teacher/coach and pediatrician.\n"
                            . "Implement structured play rules: clear safety limits, immediate consequences for unsafe behavior, teach alternative conflict resolution, use behavior charts for safe play, consider social skills group.",
                    ],
                    [
                        'min'     => 12,
                        'max'     => 15,
                        'label'   => 'High Concern',
                        'color'   => '#EF4444',
                        'message' => "Significant and persistent difficulties in physical play, risk-taking and peer interaction patterns.\n\n"
                            . "• Very frequent rough/unsafe physical play or highly risky behavior\n"
                            . "• Consistent physical aggression toward peers, extreme difficulty sharing/turn-taking\n"
                            . "• High rate of injuries, peer rejection, frequent adult intervention, or safety concerns\n\n"
                            . "⚠ Prompt professional evaluation is strongly recommended (pediatrician + child psychologist, behavioral specialist or developmental pediatrician).\n"
                            . "May indicate challenges with impulse control, emotional regulation, oppositional behavior, ADHD-related hyperactivity/impulsivity, or social skill deficits.\n"
                            . "Early, structured intervention (behavioral therapy, parent training, school-based supports, possible social skills groups) can significantly reduce risks and improve peer relationships.\n"
                            . "This is a screening result — not a diagnosis.",
                    ],
                ],
            ],
            'children-emotional-sensitivity-girls' => [
                'label'           => 'Emotional Sensitivity & Mood Fluctuations Screening (Girls, 6–12 Years)',
                'clinical_domain' => 'Emotional Sensitivity & Mood',
                'max_score'       => 15,  // 5 questions × 0–3
                'severity'        => [
                    [
                        'min'     => 0,
                        'max'     => 3,
                        'label'   => 'Within Expected Range',
                        'color'   => '#22C55E',
                        'message' => "Emotional sensitivity and mood patterns appear age-appropriate for a girl of this age.\n\n"
                            . "• Occasional mood changes are normal and resolve quickly\n"
                            . "• Handles peer feedback and small disappointments reasonably well\n"
                            . "• Emotions are expressed in proportion to situations\n\n"
                            . "✔ Continue open conversations about feelings, modeling calm responses, and validating emotions without over-focusing.",
                    ],
                    [
                        'min'     => 4,
                        'max'     => 7,
                        'label'   => 'Mild Concern',
                        'color'   => '#EAB308',
                        'message' => "Mildly elevated emotional sensitivity or mood fluctuations.\n\n"
                            . "• Gets upset or worried more easily than most peers\n"
                            . "• Occasional mood swings or over-sensitivity to criticism/feedback\n"
                            . "• Minor impact on social interactions or daily functioning\n\n"
                            . "Monitor over the next 4–8 weeks.\n"
                            . "Use emotion naming, simple calming techniques (deep breathing, journaling feelings), validate emotions, and teach perspective-taking for peer comments.",
                    ],
                    [
                        'min'     => 8,
                        'max'     => 11,
                        'label'   => 'Moderate Concern',
                        'color'   => '#F97316',
                        'message' => "Noticeable and frequent emotional sensitivity or mood instability.\n\n"
                            . "• Frequent mood swings, quick to tears/frustration, or strong reactions to minor events\n"
                            . "• High sensitivity to peer feedback often leads to withdrawal or distress\n"
                            . "• Affects friendships, school participation, self-confidence or family interactions\n\n"
                            . "Discuss with school counselor and pediatrician.\n"
                            . "Create structured emotional support: daily check-ins, emotion thermometer, coping card strategies, social stories for handling criticism, gradual exposure to feedback.",
                    ],
                    [
                        'min'     => 12,
                        'max'     => 15,
                        'label'   => 'High Concern',
                        'color'   => '#EF4444',
                        'message' => "Significant and persistent emotional sensitivity and mood fluctuations.\n\n"
                            . "• Very frequent/intense mood swings, rapid shifts from happy to upset\n"
                            . "• Extreme reactivity to perceived rejection, criticism or small disappointments\n"
                            . "• Marked impact on peer relationships, school engagement, self-esteem and family well-being\n\n"
                            . "⚠ Prompt professional evaluation is strongly recommended (pediatrician + child psychologist or mental health specialist).\n"
                            . "May indicate heightened anxiety sensitivity, emerging mood regulation challenges, social anxiety traits, or other emotional/behavioral concerns.\n"
                            . "Early, supportive intervention (CBT-based emotion regulation skills, social-emotional learning programs, family-based approaches) can significantly improve resilience and confidence.\n"
                            . "This is a screening result — not a diagnosis.",
                    ],
                ],
            ],
            'children-anxiety-responses-girls' => [
                'label'           => 'Worries, Fears & Anxiety Responses Screening (Girls, 6–12 Years)',
                'clinical_domain' => 'Worries, Fears & Anxiety',
                'max_score'       => 15,  // 5 questions × 0–3
                'severity'        => [
                    [
                        'min'     => 0,
                        'max'     => 3,
                        'label'   => 'Within Expected Range',
                        'color'   => '#22C55E',
                        'message' => "Anxiety responses, worries and fears appear age-appropriate for a girl of this age.\n\n"
                            . "• Occasional worry about events or new situations is normal and manageable\n"
                            . "• Minimal physical symptoms or need for reassurance\n"
                            . "• Fears do not interfere with daily activities, school or friendships\n\n"
                            . "✔ Continue validating feelings, teaching simple coping statements, and encouraging gradual exposure to new experiences.",
                    ],
                    [
                        'min'     => 4,
                        'max'     => 7,
                        'label'   => 'Mild Concern',
                        'color'   => '#EAB308',
                        'message' => "Mildly elevated worry, fear or anxiety responses.\n\n"
                            . "• Worries more often or intensely than most peers about upcoming events/performance\n"
                            . "• Occasional physical complaints (stomach ache, headache) linked to anxiety\n"
                            . "• Seeks some extra reassurance; minor avoidance or distress in new situations\n\n"
                            . "Monitor for 4–8 weeks.\n"
                            . "Use worry time scheduling, basic relaxation (deep breathing, progressive muscle relaxation), positive self-talk, and gradual facing of feared situations with adult support.",
                    ],
                    [
                        'min'     => 8,
                        'max'     => 11,
                        'label'   => 'Moderate Concern',
                        'color'   => '#F97316',
                        'message' => "Noticeable and frequent anxiety, worry or fear responses.\n\n"
                            . "• Frequent excessive worry about school, performance, friends or future events\n"
                            . "• Regular somatic symptoms (stomach/head pain) tied to anxiety\n"
                            . "• Seeks frequent reassurance; noticeable avoidance or distress in new/uncertain situations\n"
                            . "• Impacts concentration, participation, friendships or family routines\n\n"
                            . "Discuss with school counselor and pediatrician soon.\n"
                            . "Implement structured anxiety tools: worry box/journal, daily coping check-ins, exposure ladder for feared situations, relaxation practice, reduce reassurance-seeking gradually.",
                    ],
                    [
                        'min'     => 12,
                        'max'     => 15,
                        'label'   => 'High Concern',
                        'color'   => '#EF4444',
                        'message' => "Significant and persistent anxiety, worry and fear responses.\n\n"
                            . "• Very frequent/intense worry that dominates thinking or causes major distress\n"
                            . "• Frequent or severe physical symptoms linked to anxiety\n"
                            . "• Strong avoidance of everyday situations, excessive reassurance-seeking\n"
                            . "• Clear negative impact on school attendance/performance, friendships, sleep, appetite or family functioning\n\n"
                            . "⚠ Prompt professional evaluation is strongly recommended (pediatrician + child psychologist or child psychiatrist).\n"
                            . "May indicate generalized anxiety, social anxiety, separation anxiety or other anxiety-related concerns.\n"
                            . "Evidence-based treatments (CBT with exposure, family-based CBT, possible medication evaluation) are highly effective when started early.\n"
                            . "This is a screening result — not a diagnosis.",
                    ],
                ],
            ],
            'children-social-acceptance-girls' => [
                'label'           => 'Social Acceptance & Peer Approval Concerns Screening (Girls, 6–12 Years)',
                'clinical_domain' => 'Peer Relationships & Social Approval',
                'max_score'       => 15,
                'severity'        => [
                    [
                        'min'     => 0,
                        'max'     => 3,
                        'label'   => 'Within Expected Range',
                        'color'   => '#22C55E',
                        'message' => "Concerns about social acceptance and peer approval appear age-appropriate for a girl of this age.\n\n"
                            . "• Occasional worry about fitting in or being liked is normal\n"
                            . "• Handles peer feedback, exclusion or comparison with reasonable resilience\n"
                            . "• Makes decisions and engages socially without excessive need for approval\n\n"
                            . "✔ Continue open conversations about friendships, teach self-worth independent of peer opinion, and encourage authentic self-expression.",
                    ],
                    [
                        'min'     => 4,
                        'max'     => 7,
                        'label'   => 'Mild Concern',
                        'color'   => '#EAB308',
                        'message' => "Mildly elevated concerns about social acceptance or peer approval.\n\n"
                            . "• Sometimes feels pressure to be liked or worries about rejection\n"
                            . "• Occasional social comparison or distress when ignored/criticized by peers\n"
                            . "• Minor impact on confidence or willingness to join groups\n\n"
                            . "Monitor over the next 4–8 weeks.\n"
                            . "Help build social confidence: role-play responses to criticism, discuss real vs. perceived rejection, praise unique qualities, encourage activities where she feels competent.",
                    ],
                    [
                        'min'     => 8,
                        'max'     => 11,
                        'label'   => 'Moderate Concern',
                        'color'   => '#F97316',
                        'message' => "Noticeable and frequent concerns about social acceptance and peer approval.\n\n"
                            . "• Often feels strong pressure to be liked or included\n"
                            . "• Frequent worry about exclusion, rejection or peer judgment\n"
                            . "• Seeks approval before decisions; social comparison affects mood/self-view\n"
                            . "• Impacts friendships, participation in groups, or emotional well-being\n\n"
                            . "Discuss with school counselor and pediatrician.\n"
                            . "Implement targeted support: social skills practice, cognitive reframing of peer feedback, friendship-building activities, self-esteem exercises, reduce reliance on external validation.",
                    ],
                    [
                        'min'     => 12,
                        'max'     => 15,
                        'label'   => 'High Concern',
                        'color'   => '#EF4444',
                        'message' => "Significant and persistent concerns about social acceptance and peer approval.\n\n"
                            . "• Intense, frequent worry about being liked, included or accepted\n"
                            . "• Strong distress from perceived rejection, criticism or exclusion\n"
                            . "• Excessive social comparison, approval-seeking, or avoidance of social risks\n"
                            . "• Clear negative impact on friendships, school engagement, self-esteem and emotional health\n\n"
                            . "⚠ Prompt professional evaluation is strongly recommended (pediatrician + child psychologist or mental health specialist).\n"
                            . "May indicate social anxiety, generalized anxiety, low self-esteem, emerging perfectionism or other emotional/social concerns.\n"
                            . "Early intervention (CBT-based social anxiety treatment, self-esteem building programs, social skills groups) is highly effective and can prevent longer-term difficulties.\n"
                            . "This is a screening result — not a diagnosis.",
                    ],
                ],
            ],
            'children-self-confidence-girls' => [
                'label'           => 'Self-Confidence & Emotional Expression Screening (Girls, 6–12 Years)',
                'clinical_domain' => 'Self-Confidence & Emotional Expression',
                'max_score'       => 15,  // 5 questions × 0–3
                'severity'        => [
                    [
                        'min'     => 0,
                        'max'     => 3,
                        'label'   => 'Within Expected Range',
                        'color'   => '#22C55E',
                        'message' => "Self-confidence and emotional expression appear age-appropriate for a girl of this age.\n\n"
                            . "• Comfortably expresses feelings and opinions in familiar settings\n"
                            . "• Shows belief in her abilities and bounces back from setbacks\n"
                            . "• Asserts needs appropriately without excessive hesitation\n\n"
                            . "✔ Continue encouraging her voice, celebrating effort and small wins, and creating safe spaces for sharing feelings and ideas.",
                    ],
                    [
                        'min'     => 4,
                        'max'     => 7,
                        'label'   => 'Mild Concern',
                        'color'   => '#EAB308',
                        'message' => "Mildly reduced self-confidence or hesitancy in emotional expression.\n\n"
                            . "• Sometimes hesitant to share feelings, opinions or needs\n"
                            . "• Occasional self-doubt or slower recovery from setbacks\n"
                            . "• Minor impact on participation in groups or trying new things\n\n"
                            . "Monitor over the next 4–8 weeks.\n"
                            . "Build confidence through small, achievable challenges, positive specific praise ('You kept trying even when it was hard'), role-play assertive communication, and create low-pressure opportunities to express herself.",
                    ],
                    [
                        'min'     => 8,
                        'max'     => 11,
                        'label'   => 'Moderate Concern',
                        'color'   => '#F97316',
                        'message' => "Noticeable and frequent difficulties with self-confidence or emotional expression.\n\n"
                            . "• Often avoids expressing feelings, opinions or needs\n"
                            . "• Frequent self-doubt, excessive worry about mistakes, or prolonged recovery from setbacks\n"
                            . "• Limits participation in class, activities or peer interactions\n\n"
                            . "Discuss with school counselor/teacher and pediatrician.\n"
                            . "Implement structured confidence-building: daily success journaling, assertiveness practice scripts, gradual exposure to speaking up, growth mindset language ('not yet' instead of 'can't'), and adult modeling of healthy emotional expression.",
                    ],
                    [
                        'min'     => 12,
                        'max'     => 15,
                        'label'   => 'High Concern',
                        'color'   => '#EF4444',
                        'message' => "Significant and persistent challenges with self-confidence and emotional expression.\n\n"
                            . "• Very rarely expresses feelings, needs or opinions authentically\n"
                            . "• Strong self-doubt, fear of failure or judgment, or avoidance of most social/academic risks\n"
                            . "• Marked impact on friendships, school engagement, willingness to try new things, and emotional well-being\n\n"
                            . "⚠ Prompt professional evaluation is strongly recommended (pediatrician + child psychologist or mental health specialist).\n"
                            . "May indicate low self-esteem, social anxiety, perfectionism, or other emotional/behavioral concerns.\n"
                            . "Early, supportive intervention (CBT-based self-esteem work, social-emotional learning programs, confidence-building groups) can significantly improve self-view, resilience and willingness to engage.\n"
                            . "This is a screening result — not a diagnosis.",
                    ],
                ],
            ],
            'children-academic-pressure-girls' => [
                'label'           => 'Academic Pressure & Performance Stress Screening (Girls, 6–12 Years)',
                'clinical_domain' => 'Academic Pressure & Stress',
                'max_score'       => 15,
                'severity'        => [
                    [
                        'min'     => 0,
                        'max'     => 3,
                        'label'   => 'Within Expected Range',
                        'color'   => '#22C55E',
                        'message' => "Academic pressure and performance-related stress appear age-appropriate for a girl of this age.\n\n"
                            . "• Occasional worry about schoolwork or tests is normal and manageable\n"
                            . "• Handles homework, exams and expectations with reasonable effort and calm\n"
                            . "• No excessive anxiety or avoidance related to academic tasks\n\n"
                            . "✔ Continue supporting healthy study habits, effort-based praise, balanced schedules, and open conversations about school feelings.",
                    ],
                    [
                        'min'     => 4,
                        'max'     => 7,
                        'label'   => 'Mild Concern',
                        'color'   => '#EAB308',
                        'message' => "Mildly elevated academic pressure or performance-related stress.\n\n"
                            . "• Sometimes feels stressed about grades, tests or meeting expectations\n"
                            . "• Occasional anxiety before exams or excessive time spent on homework due to worry\n"
                            . "• Minor impact on enjoyment of learning or free time\n\n"
                            . "Monitor over the next 4–8 weeks.\n"
                            . "Help reduce pressure: focus praise on effort/process not just results, break tasks into smaller steps, teach basic relaxation before tests, maintain reasonable homework routines, and discuss realistic expectations with teachers/parents.",
                    ],
                    [
                        'min'     => 8,
                        'max'     => 11,
                        'label'   => 'Moderate Concern',
                        'color'   => '#F97316',
                        'message' => "Noticeable and frequent academic pressure or performance stress.\n\n"
                            . "• Often feels stressed/worried about school performance, grades or failing\n"
                            . "• Regular anxiety before tests, excessive homework time driven by fear, or pressure from adults\n"
                            . "• Affects concentration, enjoyment of school, sleep, mood or free time\n\n"
                            . "Discuss with teacher/school counselor and pediatrician soon.\n"
                            . "Implement targeted support: realistic goal-setting, test anxiety coping strategies (breathing, positive visualization), time management tools, balanced schedule with breaks, reduce perfectionism language, and consider school accommodations if needed.",
                    ],
                    [
                        'min'     => 12,
                        'max'     => 15,
                        'label'   => 'High Concern',
                        'color'   => '#EF4444',
                        'message' => "Significant and persistent academic pressure and performance-related stress.\n\n"
                            . "• Very frequent/intense worry about grades, failure or disappointing others\n"
                            . "• Strong test anxiety, excessive study time due to fear, or physical symptoms (headaches, stomach issues) linked to school stress\n"
                            . "• Clear negative impact on sleep, appetite, mood, friendships, self-esteem and overall well-being\n\n"
                            . "⚠ Prompt professional evaluation is strongly recommended (pediatrician + child psychologist or mental health specialist).\n"
                            . "May indicate performance anxiety, generalized anxiety, perfectionism, or early signs of academic burnout.\n"
                            . "Early intervention (CBT for anxiety/perfectionism, family-based approaches, school supports) is very effective and can prevent escalation during adolescence.\n"
                            . "This is a screening result — not a diagnosis.",
                    ],
                ],
            ],
            'adolescents-risk-taking-general' => [
                'label'           => 'Risk-Taking & Substance-Related Behaviors Screening (Adolescents, 13–17 Years)',
                'clinical_domain' => 'Risk-Taking & Substance-Related Behaviors',
                'max_score'       => 45,  // 15 questions × 0–3
                'severity'        => [
                    [
                        'min'     => 0,
                        'max'     => 11,
                        'label'   => 'Low Risk',
                        'color'   => '#22C55E',
                        'message' => "Risk-taking and substance-related behaviors appear within typical limits for this age.\n\n"
                            . "• Occasional experimentation or thrill-seeking is common in adolescence\n"
                            . "• No regular substance use or highly dangerous behaviors reported\n"
                            . "• Able to consider consequences and maintain safety most of the time\n\n"
                            . "✔ Continue open, non-judgmental conversations about choices, peer pressure, safety boundaries, and healthy coping strategies. Reinforce positive decision-making.",
                    ],
                    [
                        'min'     => 12,
                        'max'     => 22,
                        'label'   => 'Elevated Risk',
                        'color'   => '#EAB308',
                        'message' => "Elevated risk-taking or emerging concerning patterns.\n\n"
                            . "• More frequent impulsivity, rule-breaking, or unsafe choices than most peers\n"
                            . "• Occasional substance use experimentation or risky online/offline behaviors\n"
                            . "• Some impact on school, family relationships, safety or emotional well-being\n\n"
                            . "Monitor closely over the next 1–3 months.\n"
                            . "Increase supervision, set clear expectations + natural consequences, teach risk-evaluation skills (pros/cons, pause-and-think), strengthen protective factors (hobbies, trusted adults, healthy peer group), and maintain honest dialogue about substances and safety.",
                    ],
                    [
                        'min'     => 23,
                        'max'     => 33,
                        'label'   => 'Moderate–High Risk',
                        'color'   => '#F97316',
                        'message' => "Moderate to high risk-taking and/or substance-related behaviors.\n\n"
                            . "• Frequent impulsivity, dangerous risk-taking, rule/law-breaking or substance use\n"
                            . "• Noticeable negative consequences (school difficulties, family conflict, safety incidents, peer problems)\n"
                            . "• Behaviors may be used to cope with stress, boredom or emotional pain\n\n"
                            . "Urgent discussion with adolescent, parents and school counselor recommended.\n"
                            . "Consider referral to adolescent mental health / substance use specialist, structured risk-reduction program, family therapy, increased monitoring, and removal of access to substances/high-risk situations. Focus on building alternative coping skills and positive outlets.",
                    ],
                    [
                        'min'     => 34,
                        'max'     => 45,
                        'label'   => 'Very High Risk',
                        'color'   => '#EF4444',
                        'message' => "Very high risk of serious harm from risk-taking and/or substance-related behaviors.\n\n"
                            . "• Frequent, severe, or escalating dangerous behaviors (substance use, reckless driving, unsafe sexual activity, criminal acts, self-harm challenges)\n"
                            . "• Significant negative impact on physical safety, mental health, school functioning, legal status and/or family relationships\n"
                            . "• Strong signs behaviors are compulsive, coping-driven or out of control\n\n"
                            . "⚠ Immediate professional intervention strongly recommended — contact pediatrician/adolescent psychiatrist, addiction specialist or crisis service today.\n"
                            . "May require urgent safety planning, inpatient/residential evaluation, intensive outpatient treatment, family-based intervention and/or legal involvement.\n"
                            . "Early, decisive, multi-system support dramatically improves long-term outcomes.\n"
                            . "This is a screening result — not a diagnosis.",
                    ],
                ],
            ],
            'children-peer-relationships' => [
                'label'           => 'Peer Relationships & Social Belonging Screening (Adolescents, 13–17 Years)',
                'clinical_domain' => 'Peer Relationships & Social Belonging',
                'max_score'       => 24,  // assuming 8 questions × 0–3 (common pattern)
                'severity'        => [
                    [
                        'min'     => 0,
                        'max'     => 7,
                        'label'   => 'Healthy Range',
                        'color'   => '#22C55E',
                        'message' => "Peer relationships and sense of social belonging appear healthy for this age.\n\n"
                            . "• Maintains meaningful friendships and feels accepted by peers\n"
                            . "• Comfortable in group settings and handles peer conflict constructively\n"
                            . "• Balanced social media/online interactions without excessive comparison\n\n"
                            . "✔ Continue nurturing authentic connections, open communication about peer experiences, and healthy boundaries with social media.",
                    ],
                    [
                        'min'     => 8,
                        'max'     => 14,
                        'label'   => 'Mild Concern',
                        'color'   => '#EAB308',
                        'message' => "Mild difficulties in peer relationships or feelings of belonging.\n\n"
                            . "• Occasional feelings of exclusion, loneliness or peer pressure\n"
                            . "• Some conflict or comparison in friendships/social media\n"
                            . "• Minor impact on mood, self-esteem or school engagement\n\n"
                            . "Monitor over the next 1–2 months.\n"
                            . "Encourage quality over quantity in friendships, teach assertiveness and boundary-setting, limit social media comparison triggers, and support involvement in interest-based groups.",
                    ],
                    [
                        'min'     => 15,
                        'max'     => 21,
                        'label'   => 'Moderate Concern',
                        'color'   => '#F97316',
                        'message' => "Noticeable challenges in peer relationships or social belonging.\n\n"
                            . "• Frequent feelings of rejection, isolation or not fitting in\n"
                            . "• Ongoing peer conflicts, bullying experiences or heavy social media comparison\n"
                            . "• Affects mood, self-worth, school attendance or willingness to socialize\n\n"
                            . "Discuss with school counselor and pediatrician/adolescent mental health provider.\n"
                            . "Consider social skills or peer support groups, cognitive reframing of social experiences, safety planning if bullying is present, and structured activities to build positive peer connections.",
                    ],
                    [
                        'min'     => 22,
                        'max'     => 24,
                        'label'   => 'High Concern',
                        'color'   => '#EF4444',
                        'message' => "Significant and persistent difficulties in peer relationships and social belonging.\n\n"
                            . "• Strong, ongoing feelings of rejection, loneliness or social alienation\n"
                            . "• Severe peer conflict, victimization, or extreme social withdrawal\n"
                            . "• Major negative impact on mental health, school functioning, self-esteem and safety\n\n"
                            . "⚠ Prompt professional evaluation is strongly recommended (adolescent psychiatrist, psychologist or mental health clinic).\n"
                            . "May indicate social anxiety, depression, bullying/trauma effects, or emerging identity-related distress.\n"
                            . "Early, intensive support (therapy, school-based interventions, peer mentoring, possible medication) can significantly improve belonging and emotional health.\n"
                            . "This is a screening result — not a diagnosis.",
                    ],
                ],
            ],
            'male-adolescents-mood' => [
                'label'           => 'Mood, Sadness & Irritability Screening (Male Adolescents, 13–17 Years)',
                'clinical_domain' => 'Mood, Sadness & Irritability',
                'max_score'       => 30,  // 10 questions × 0–3
                'severity'        => [
                    [
                        'min'     => 0,
                        'max'     => 7,
                        'label'   => 'Within Expected Range',
                        'color'   => '#22C55E',
                        'message' => "Mood, sadness and irritability levels appear typical for a male adolescent of this age.\n\n"
                            . "• Occasional low mood, frustration or irritability is normal during adolescence\n"
                            . "• Maintains interest in activities, energy, and social connections most of the time\n"
                            . "• Handles setbacks and expresses emotions in proportion to situations\n\n"
                            . "✔ Continue open, non-judgmental conversations about feelings, encourage healthy outlets (exercise, hobbies, trusted friends/adults), and maintain consistent routines/sleep.",
                    ],
                    [
                        'min'     => 8,
                        'max'     => 15,
                        'label'   => 'Mild Concern',
                        'color'   => '#EAB308',
                        'message' => "Mildly elevated sadness, irritability or mood difficulties.\n\n"
                            . "• More frequent low mood, irritability or loss of interest than most peers\n"
                            . "• Occasional withdrawal, fatigue, concentration issues or guilt feelings\n"
                            . "• Minor impact on school performance, friendships, family interactions or self-esteem\n\n"
                            . "Monitor closely over the next 4–8 weeks.\n"
                            . "Increase supportive conversations, encourage physical activity and social connection, teach basic mood regulation strategies (exercise, journaling, talking to someone), and track sleep/nutrition patterns.",
                    ],
                    [
                        'min'     => 16,
                        'max'     => 23,
                        'label'   => 'Moderate Concern',
                        'color'   => '#F97316',
                        'message' => "Noticeable and frequent mood, sadness or irritability concerns.\n\n"
                            . "• Often feels sad/down, highly irritable, or loses interest in previously enjoyed activities\n"
                            . "• Regular fatigue, concentration problems, hopelessness, guilt or social withdrawal\n"
                            . "• Clear impact on schoolwork, peer relationships, family functioning and/or self-worth\n\n"
                            . "Urgent discussion with adolescent and parents recommended — contact school counselor and pediatrician/adolescent mental health provider soon.\n"
                            . "Consider structured support: mood tracking, daily routine stabilization, cognitive reframing of negative thoughts, increased physical/social activity, and possible referral to adolescent psychologist or psychiatrist.",
                    ],
                    [
                        'min'     => 24,
                        'max'     => 30,
                        'label'   => 'High Concern',
                        'color'   => '#EF4444',
                        'message' => "Significant and persistent mood difficulties, sadness and/or irritability.\n\n"
                            . "• Frequent/intense sadness, hopelessness, worthlessness or very high irritability\n"
                            . "• Marked loss of interest/pleasure, severe fatigue, concentration impairment\n"
                            . "• Substantial withdrawal, aggression, self-harm thoughts or major disruption to school, relationships and daily functioning\n\n"
                            . "⚠ Immediate professional evaluation strongly recommended — contact pediatrician/adolescent psychiatrist or mental health crisis service today.\n"
                            . "May indicate major depressive episode, persistent depressive disorder, bipolar traits, trauma-related issues or other serious mental health concerns.\n"
                            . "Urgent safety assessment, evidence-based treatment (CBT, interpersonal therapy, family involvement, possible medication) and close monitoring are critical.\n"
                            . "This is a screening result — not a diagnosis.",
                    ],
                ],
            ],
            'female-adolescents-mood' => [
                'label'           => 'Mood, Sadness & Irritability Screening (Female Adolescents, 13–17 Years)',
                'clinical_domain' => 'Mood, Sadness & Irritability',
                'max_score'       => 30,  // 10 questions × 0–3
                'severity'        => [
                    [
                        'min'     => 0,
                        'max'     => 7,
                        'label'   => 'Within Expected Range',
                        'color'   => '#22C55E',
                        'message' => "Mood, sadness and irritability levels appear typical for a female adolescent of this age.\n\n"
                            . "• Occasional low mood, tearfulness or irritability is normal during adolescence\n"
                            . "• Maintains interest in activities, energy and social connections most of the time\n"
                            . "• Handles emotional ups and downs in proportion to situations\n\n"
                            . "✔ Continue open, supportive conversations about feelings, encourage healthy outlets (exercise, creative expression, trusted friends/adults), and maintain consistent sleep and routines.",
                    ],
                    [
                        'min'     => 8,
                        'max'     => 15,
                        'label'   => 'Mild Concern',
                        'color'   => '#EAB308',
                        'message' => "Mildly elevated sadness, irritability or mood difficulties.\n\n"
                            . "• More frequent low mood, tearfulness, irritability or loss of interest than most peers\n"
                            . "• Occasional withdrawal, fatigue, concentration issues, guilt or hopelessness\n"
                            . "• Minor impact on school performance, friendships, family interactions or self-esteem\n\n"
                            . "Monitor closely over the next 4–8 weeks.\n"
                            . "Increase supportive listening, encourage physical activity and social connection, teach basic mood regulation strategies (journaling, mindfulness, talking to someone), and track sleep, nutrition and screen time patterns.",
                    ],
                    [
                        'min'     => 16,
                        'max'     => 23,
                        'label'   => 'Moderate Concern',
                        'color'   => '#F97316',
                        'message' => "Noticeable and frequent mood, sadness or irritability concerns.\n\n"
                            . "• Often feels sad/down, highly irritable, tearful or loses interest in previously enjoyed activities\n"
                            . "• Regular fatigue, concentration problems, hopelessness, guilt, worthlessness or social withdrawal\n"
                            . "• Clear impact on schoolwork, peer relationships, family functioning and/or self-worth\n\n"
                            . "Urgent discussion with adolescent and parents recommended — contact school counselor and pediatrician/adolescent mental health provider soon.\n"
                            . "Consider structured support: mood tracking app/journal, daily routine stabilization, cognitive reframing of negative thoughts, increased physical/social activity, and possible referral to adolescent psychologist or psychiatrist.",
                    ],
                    [
                        'min'     => 24,
                        'max'     => 30,
                        'label'   => 'High Concern',
                        'color'   => '#EF4444',
                        'message' => "Significant and persistent mood difficulties, sadness and/or irritability.\n\n"
                            . "• Frequent/intense sadness, hopelessness, worthlessness or very high irritability/tearfulness\n"
                            . "• Marked loss of interest/pleasure, severe fatigue, concentration impairment\n"
                            . "• Substantial withdrawal, self-isolation, aggression, self-harm thoughts or major disruption to school, relationships and daily functioning\n\n"
                            . "⚠ Immediate professional evaluation strongly recommended — contact pediatrician/adolescent psychiatrist or mental health crisis service today.\n"
                            . "May indicate major depressive episode, persistent depressive disorder, bipolar traits, trauma-related issues, anxiety comorbidity or other serious mental health concerns.\n"
                            . "Urgent safety assessment, evidence-based treatment (CBT, interpersonal therapy, family involvement, possible medication) and close monitoring are critical.\n"
                            . "This is a screening result — not a diagnosis.",
                    ],
                ],
            ],
            'male-adolescents-anxiety' => [
                'label'           => 'Anxiety, Nervousness & Worry Screening (Male Adolescents, 13–17 Years)',
                'clinical_domain' => 'Anxiety, Nervousness & Worry',
                'max_score'       => 30,  // 10 questions × 0–3
                'severity'        => [
                    [
                        'min'     => 0,
                        'max'     => 7,
                        'label'   => 'Within Expected Range',
                        'color'   => '#22C55E',
                        'message' => "Anxiety, nervousness and worry levels appear typical for a male adolescent of this age.\n\n"
                            . "• Occasional worry or nervousness about school, future or social situations is normal\n"
                            . "• Able to relax, concentrate and sleep without significant interference from anxiety\n"
                            . "• Physical symptoms of anxiety are rare or mild\n\n"
                            . "✔ Continue open conversations about stress, encourage healthy coping (exercise, hobbies, talking to trusted people), and maintain good sleep and routine.",
                    ],
                    [
                        'min'     => 8,
                        'max'     => 15,
                        'label'   => 'Mild Concern',
                        'color'   => '#EAB308',
                        'message' => "Mildly elevated anxiety, nervousness or worry.\n\n"
                            . "• More frequent worry about future events, performance or social situations than most peers\n"
                            . "• Occasional restlessness, trouble relaxing, concentration issues or mild physical symptoms\n"
                            . "• Minor impact on school focus, sleep quality or willingness to engage socially\n\n"
                            . "Monitor over the next 4–8 weeks.\n"
                            . "Teach simple anxiety management tools (deep breathing, grounding techniques, scheduled worry time), encourage regular physical activity, limit caffeine, and support gradual facing of anxiety-provoking situations.",
                    ],
                    [
                        'min'     => 16,
                        'max'     => 23,
                        'label'   => 'Moderate Concern',
                        'color'   => '#F97316',
                        'message' => "Noticeable and frequent anxiety, nervousness or worry.\n\n"
                            . "• Often feels anxious/nervous, worries excessively about school, future or social judgment\n"
                            . "• Regular restlessness, difficulty relaxing/sleeping, concentration problems or physical symptoms\n"
                            . "• Avoidance of some situations, clear impact on school performance, friendships or daily functioning\n\n"
                            . "Urgent discussion with adolescent and parents recommended — contact school counselor and pediatrician/adolescent mental health provider soon.\n"
                            . "Implement structured support: anxiety tracking, daily relaxation practice, cognitive reframing of worry thoughts, gradual exposure to avoided situations, and possible referral to adolescent psychologist or psychiatrist.",
                    ],
                    [
                        'min'     => 24,
                        'max'     => 30,
                        'label'   => 'High Concern',
                        'color'   => '#EF4444',
                        'message' => "Significant and persistent anxiety, nervousness and worry.\n\n"
                            . "• Frequent/intense anxiety, excessive worry dominating thoughts, or strong physical symptoms\n"
                            . "• Marked restlessness, sleep disturbance, concentration impairment or avoidance of many situations\n"
                            . "• Substantial negative impact on school attendance/performance, peer relationships, family life and overall well-being\n\n"
                            . "⚠ Immediate professional evaluation strongly recommended — contact pediatrician/adolescent psychiatrist or mental health crisis service today.\n"
                            . "May indicate generalized anxiety disorder, social anxiety, panic symptoms, performance anxiety or other anxiety-related concerns.\n"
                            . "Urgent safety assessment, evidence-based treatment (CBT with exposure, possible medication evaluation) and close monitoring are critical.\n"
                            . "This is a screening result — not a diagnosis.",
                    ],
                ],
            ],
            'female-adolescents-anxiety' => [
                'label'           => 'Anxiety, Nervousness & Worry Screening (Female Adolescents, 13–17 Years)',
                'clinical_domain' => 'Anxiety, Nervousness & Worry',
                'max_score'       => 30,  // 10 questions × 0–3
                'severity'        => [
                    [
                        'min'     => 0,
                        'max'     => 7,
                        'label'   => 'Within Expected Range',
                        'color'   => '#22C55E',
                        'message' => "Anxiety, nervousness and worry levels appear typical for a female adolescent of this age.\n\n"
                            . "• Occasional worry or nervousness about school, relationships, future or social situations is normal\n"
                            . "• Able to relax, concentrate and sleep without significant interference from anxiety\n"
                            . "• Physical symptoms of anxiety are rare or mild\n\n"
                            . "✔ Continue open, supportive conversations about stress and feelings, encourage healthy coping (exercise, creative outlets, trusted friends/adults), and maintain consistent sleep and routines.",
                    ],
                    [
                        'min'     => 8,
                        'max'     => 15,
                        'label'   => 'Mild Concern',
                        'color'   => '#EAB308',
                        'message' => "Mildly elevated anxiety, nervousness or worry.\n\n"
                            . "• More frequent worry about future events, performance, relationships or social judgment than most peers\n"
                            . "• Occasional restlessness, trouble relaxing/sleeping, concentration issues or mild physical symptoms\n"
                            . "• Minor impact on school focus, social engagement, sleep quality or enjoyment of activities\n\n"
                            . "Monitor over the next 4–8 weeks.\n"
                            . "Teach simple anxiety management tools (deep breathing, grounding 5-4-3-2-1, scheduled worry time), encourage regular physical activity, limit caffeine/screens before bed, and support gradual facing of anxiety-provoking situations with adult guidance.",
                    ],
                    [
                        'min'     => 16,
                        'max'     => 23,
                        'label'   => 'Moderate Concern',
                        'color'   => '#F97316',
                        'message' => "Noticeable and frequent anxiety, nervousness or worry.\n\n"
                            . "• Often feels anxious/nervous, worries excessively about school, future, relationships or social evaluation\n"
                            . "• Regular restlessness, difficulty relaxing/sleeping, concentration problems or physical symptoms (racing heart, tension)\n"
                            . "• Avoidance of some situations, clear impact on school performance, friendships, sleep or daily functioning\n\n"
                            . "Urgent discussion with adolescent and parents recommended — contact school counselor and pediatrician/adolescent mental health provider soon.\n"
                            . "Implement structured support: anxiety tracking/journal, daily relaxation/mindfulness practice, cognitive reframing of worry thoughts, gradual exposure to avoided situations, and possible referral to adolescent psychologist or psychiatrist.",
                    ],
                    [
                        'min'     => 24,
                        'max'     => 30,
                        'label'   => 'High Concern',
                        'color'   => '#EF4444',
                        'message' => "Significant and persistent anxiety, nervousness and worry.\n\n"
                            . "• Frequent/intense anxiety, excessive worry dominating thoughts, or strong physical symptoms\n"
                            . "• Marked restlessness, sleep disturbance, concentration impairment or avoidance of many everyday situations\n"
                            . "• Substantial negative impact on school attendance/performance, peer relationships, family life, sleep, appetite and overall well-being\n\n"
                            . "⚠ Immediate professional evaluation strongly recommended — contact pediatrician/adolescent psychiatrist or mental health crisis service today.\n"
                            . "May indicate generalized anxiety disorder, social anxiety, panic symptoms, performance anxiety or other anxiety-related concerns.\n"
                            . "Urgent safety assessment, evidence-based treatment (CBT with exposure, possible medication evaluation) and close monitoring are critical.\n"
                            . "This is a screening result — not a diagnosis.",
                    ],
                ],
            ],
        ];
    }


    public function getScoreAttribute(): int
    {
        return collect($this->answers ?? [])->sum(fn($a) => (int) ($a['score'] ?? 0));
    }

    public function getOverallScoreAttribute(): int
    {
        return $this->score;
    }

    protected function getAssessmentConfig(): array
    {
        $slug = $this->form?->slug ?? 'unknown';

        return $this->assessmentConfig()[$slug] ?? [];
    }

    public function getAssessmentTypeLabelAttribute(): string
    {
        return $this->getAssessmentConfig()['label'] ?? 'Mental Health Screening';
    }

    public function getClinicalDomainAttribute(): string
    {
        return $this->getAssessmentConfig()['clinical_domain'] ?? 'General Mental Health';
    }

    public function getMaxPossibleScoreAttribute(): int
    {
        return $this->getAssessmentConfig()['max_score'] ?? 0;
    }

    protected function resolveSeverity(): array
    {
        $config = $this->getAssessmentConfig();
        if (empty($config)) {
            return $this->defaultUnclassifiedSeverity();
        }

        $score = min(max($this->overall_score, 0), $config['max_score'] ?? PHP_INT_MAX);

        foreach ($config['severity'] ?? [] as $range) {
            if ($score >= $range['min'] && $score <= $range['max']) {
                return $range;
            }
        }

        return $this->defaultUnclassifiedSeverity();
    }

    private function defaultUnclassifiedSeverity(): array
    {
        return [
            'label'   => 'Unclassified',
            'color'   => '#6B7280',
            'message' => 'Severity could not be determined – possible configuration mismatch.',
        ];
    }

    public function getSeverityLevelAttribute(): string
    {
        return $this->resolveSeverity()['label'] ?? 'Unknown';
    }

    public function getSeverityColorAttribute(): string
    {
        return $this->resolveSeverity()['color'] ?? '#6B7280';
    }

    public function getMessageAttribute(): string
    {
        $severity = $this->resolveSeverity();
        $config   = $this->getAssessmentConfig();

        $header = collect([
            $this->assessment_type_label,
            "Domain: " . $this->clinical_domain,
            "Score: {$this->overall_score} / {$this->max_possible_score}",
            "Level: " . $severity['label'],
        ])->filter()->implode("\n") . "\n\n";

        return $header . ($severity['message'] ?? '');
    }

    public function getHasCriticalFlagAttribute(): bool
    {
        return str_contains(strtolower($this->severity_level), 'high');
        // or: return $this->severity_level === 'High Concern';
    }

    // Optional: if you really need slug parameter (rare)
    public function getSeverityForSlug(string $slug): ?array
    {
        $oldSlug = $this->form?->slug;
        $this->setRelation('form', (object)['slug' => $slug]); // fake it temporarily
        $severity = $this->resolveSeverity();
        $this->setRelation('form', $oldSlug ? (object)['slug' => $oldSlug] : null);

        return $severity;
    }
}
