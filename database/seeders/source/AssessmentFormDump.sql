#forms
insert into `forms`( `name`, `desc`,  `assessment_group`, `type`, `slug`)
 values('Healthy Heart','Thank you for participating in the Urban Indian Heart Health Program. The purpose of this
survey is to learn about the heart health of patients at this clinic. The following pages ask
questions about heart attack and stroke, and about your blood pressure, cholesterol, physical
activity, diet, and tobacco use.
The survey should take about 20-30 minutes to complete. Answering these questions is
voluntary and your answers will be kept private. This is not a test. Just mark the answers that
best reflect what you think. If you have questions, please feel free to ask a project staff member
for help. Thank you for completing this survey!', 'healthy-heart', 'normal', 'healthy-heart'),
('Physical Symptoms','On the DSM-5 Level 1 cross-cutting questionnaire that you just completed, you indicated that during the
past 2 weeks you (the individual receiving care) have been bothered by “unexplained aches and pains”, and/or “feeling
that your illnesses are not being taken seriously enough” at a mild or greater level of severity. The questions below ask
about these feelings in more detail and especially how often you (the individual receiving care) have been bothered by
a list of symptoms during the past 7 days. Please respond to each item by marking ( or x) one box per row.',
'psychiatric-exam', 'score', 'psychiatric-exam-symptoms'),
('Anger','On the DSM-5 Level 1 cross-cutting questionnaire that you just completed,
  you indicated that during the past 2 weeks you (individual receiving care) have been bothered by
   “feeling nervous, anxious, frightened, worried, or on edge”, “feeling panic or being frightened”,
    and/or “avoiding situations that make you anxious” at a mild or greater level of severity.
     The questions below ask about these feelings in more detail and especially how often you 
     (individual receiving care) have been bothered by a list of symptoms during the past 7 days.
      Please respond to each item by marking (&#10004; or &#10006;) one box per row. Clinician',
'psychiatric-exam', 'score', 'psychiatric-exam-anger'),
('Anxiety','On the DSM-5 Level 1 cross-cutting questionnaire that you just completed,
  you indicated that during the past 2 weeks you (individual receiving care) have been bothered by “feeling nervous,
   anxious, frightened, worried, or on edge”, “feeling panic or being frightened”,
    and/or “avoiding situations that make you anxious” at a mild or greater level of severity.
     The questions below ask about these feelings in more detail and especially how often you 
     (individual receiving care) have been bothered by a list of symptoms during the past 7 days.
      Please respond to each item by marking (&#10004; or &#10006;) one box per row.',
'psychiatric-exam', 'score', 'psychiatric-exam-anxiety'),
('Depression','On the DSM-5 Level 1 cross-cutting questionnaire that you just completed, 
 	you indicated that during the past 2 weeks you (the individual receiving care) have been bothered by 
 	“no interest or pleasure in doing things” and/or “feeling down, depressed, or hopeless” at a mild or greater level of severity.
 	The questions below ask about these feelings in more detail and especially how often you (the individual receiving care) 
 	have been bothered by a list of symptoms during the past 7 days. 
 	Please respond to each item by marking (&#10004; or &#10006;) one box per row.',
'psychiatric-exam', 'score', 'psychiatric-exam-depression'),
('Mania','On the DSM-5 Level 1 cross-cutting questionnaire you just completed, you indicated that during the past 2 weeks you (the
individual receiving care) have been bothered by “sleeping less than usual, but still having a lot of energy” and/or “starting lots more
projects than usual or doing more risky things than usual” at a mild or greater level of severity. The five statement groups or questions
below ask about these feelings in more detail.
1. Please read each group of statements/question carefully.
2. Choose the one statement in each group that best describes the way you (the individual receiving care) have been feeling for
the past week.
3. Check the box ( or x) next to the number/statement selected.
4. Please note: The word “occasionally” when used here means once or twice; “often” means several times or more and
“frequently” means most of the time.',
'psychiatric-exam', 'score', 'psychiatric-exam-mania'),
('Symptoms & Signs',"Symptoms & Signs",'stroke-scale', 'normal', 'stroke-scale-symptoms-signs'),
('NIH Stroke Scale/Score (NIHSS)',"The NIH Stroke Scale has many caveats buried within it. 
 	If your patient has prior known neurologic deficits e.g. prior weakness,
 	hemi- or quadriplegia, blindness, etc. or is intubated, has a language barrier, etc.,
 	it becomes especially complicated. In those cases, consult the NIH Stroke Scale website.
 	MDCalc's version is an attempt to clarify many of these confusing caveats,
 	but cannot and should not be substituted for the official protocol.",
'stroke-scale', 'score', 'stroke-scale-nih'),
('TPA Contraindications for Ischemic Stroke','Institutions may have slightly 
 	different absolute and relative contraindications to Tissue Plasminogen Activator (tPA);
 	 this list is meant to be a quick reference, but practice should be guided by institutional protocol 
 	 and consultation with neurology. Reflects recommendations from Demaerschalk et al, Stroke 2015.',
'stroke-scale', 'score', 'stroke-scale-tpa-contraindications'),
('THRIVE Score for Stroke Outcome',
 	'The THRIVE score can help physicians predict several key outcomes 
 	in patients suffering an ischemic stroke.',
'stroke-scale', 'score', 'stroke-scale-ischemic');


# Questions

insert into `questions` (`name`, `type`, `is_active`)
 values
 ('When was the last time you had your blood pressure checked?','radio', 1),

 ('The LAST time you had your blood pressure checked, was it normal or high?','radio', 1),

 ('Have you EVER been told by a doctor, nurse, or other health professional that you have high blood pressure?','radio', 1),

 ('If yes, and if you are female, was this only when you were pregnant?','radio', 1),

('Are you currently taking medicine for your high blood pressure?','radio', 1),

('Are you changing your eating habits to help lower or control your blood pressure?','radio', 1),

('Are you cutting down on salt to help lower or control your blood pressure?','radio', 1),

('Are you reducing alcohol use to help lower or control your blood pressure?','radio', 1),

('Are you exercising to help lower or control your blood pressure?','radio', 1),

('Blood cholesterol is a fatty substance found in the blood. Have you ever had your blood cholesterol checked?','radio', 1),

('About how long has it been since you last had your blood cholesterol checked?','radio', 1),

('The last time you had your blood cholesterol checked, was it normal or high?','radio', 1),

 ( 'Have you EVER been told by a doctor, nurse or other health professional that your blood cholesterol is high?','radio', 1),

 ( 'If so, when were you told that your blood cholesterol was high?','radio', 1),

 ( 'How many days per week do you do moderate physical activities for at least 30 minutes?','input', 1),

 ( 'How many days per week do you do vigorous physical activities for at least 20 minutes?','input', 1),

 ( 'Thinking back on the past 30 days, please check yes or no for each statement. You may choose “yes” for more than one statement.','sub_question', 1),

 ( 'Over the past 30 days in general, how many hours per day did you usually spend watching television, sitting at a computer, playing video games, doing beadwork, or other activities that don’t require much physical activity?','radio', 1),

 ( 'Do you plan to increase the amount of physical activity you get every week?','radio', 1),

 ( 'Please think about what you usually ate or drank during the past 30 days. Read each item carefully and indicate one response for each. How often did you...','sub_question', 1),

 ( 'What kind of milk did you usually use? (Pick the one that you used most often in the past 30 days.) What kind of milk did you usually use? (Pick the one that you used most often in the past 30 days.)','radio', 1),

 ( 'What kinds of fat or oil did you usually use in cooking in the past 30 days (if more than one, choose the one used most often)?','radio', 1),

 ( 'How often do you do the following things? Mark your answer with an X.','sub_question',1),

 ( 'Are you able to buy or grow low-cost vegetables?','radio', 1),

 ( 'In the future, do you intend to reduce the amount of fat you eat so it is lower than it is now?','radio', 1),

 ( 'Do you smoke cigarettes now? (For these questions, we are not interested in the tobacco you may smoke for ceremonial use.)','radio', 1),

 ( 'Thinking over the past 30 days, including today, how many days during this time did you smoke?','input', 1),

 ( 'About how many cigarettes a day do you now smoke?','input', 1),

 ( 'About how many years have you been smoking?','input', 1),

 ( 'In the past year, how many times have you quit smoking for at least 24 hours?','input', 1),

 ( 'Are you seriously thinking of quitting smoking?','radio', 1),

 ( 'Do you think pain or discomfort in the jaw, neck, or back are symptoms of a heart attack?','radio', 1),

 ( 'Do you think feeling weak, lightheaded, or faint are symptoms of a heart attack?','radio', 1),

 ( 'Do you think swelling of the feet and legs is a symptom of a heart attack?','radio', 1),

 ( 'Do you think chest pain or discomfort are symptoms of a heart attack?','radio', 1),

 ( 'Do you think sudden trouble seeing in one or both eyes is a symptom of a heart attack?','radio', 1),

 ( 'Do you think tingling in the fingers and toes are symptoms of a heart attack?','radio', 1),

 ( 'Do you think pain or discomfort in the arms or shoulder are symptoms of a heart attack?','radio', 1),

 ( 'Do you think shortness of breath is a symptom of a heart attack?','radio', 1),

 ( 'Do you think sudden confusion or trouble speaking are symptoms of a stroke?','radio', 1),

 ( 'Do you think sudden numbness or weakness of face, arm, or leg, especially on one side, are symptoms of a stroke?','radio', 1),

 ( 'Do you think feeling sick to your stomach is a symptom of a stroke?','radio', 1),

 ( 'Do you think sharp pain in the jaw or mouth is a symptom of a stroke?','radio', 1),

 ( 'Do you think sudden trouble seeing in one or both eyes is a symptom of a stroke?','radio', 1),

 ( 'Do you think sudden chest pain or discomfort are symptoms of a stroke?','radio', 1),

 ( 'Do you think sudden trouble walking, dizziness, or loss of balance are symptoms of a stroke?','radio', 1),

 ( 'Do you think severe headache with no known cause is a symptom of a stroke?','radio', 1),

 ( 'If you thought someone was having a heart attack or a stroke, what is the first thing you would do?','radio', 1),

('Can a large waist (>35 inches for women or >40 inches for men) increase your risk of heart disease?','radio', 1),

('Can the Body Mass Index (BMI) Chart tell you if you are overweight?','radio', 1),

('Does your liver make all the cholesterol your body needs to keep you healthy?','radio', 1),

 ( 'Can eating foods that are high in sodium increase your risk of high blood pressure?','radio', 1),

 ( 'Does lard have a low amount of saturated fat?','radio', 1),

 ( 'Can eating too much saturated fat and trans fat raise your cholesterol level?','radio', 1),

 ( 'Is a blood pressure of 140/90 mmHg considered high?','radio', 1),

 ( 'Can being overweight or obese put you at risk for developing high blood cholesterol?','radio', 1),

 ( 'Is being physically active a way to reduce your risk for heart disease?','radio', 1),

 ( 'Is it true that only people with high blood cholesterol should follow a heart healthy diet?','radio', 1),

 ( 'Can nonsmokers die from secondhand smoke?','radio', 1),

 ( 'How often do you have a hard time understanding written information about your health that you get from your clinic? (This might include information from a doctor or nurse.)','radio', 1),

 ( 'How confident are you in filling out medical forms by yourself?','radio', 1),

 ( 'How often do you prefer that someone (like a family member or someone else) help you read medical materials?','radio', 1),

 ( 'Which of the following numbers represents the lowest risk? For example, which would you most like to hear from a doctor about your risk for a medical condition?','radio', 1),

 ( 'If the chance of getting a health condition is 20 out of 100 people, this would be the same as having a what percent (%) chance of getting the condition?','radio', 1),

('A prescription says “Take one tablet by mouth every 6 hours.” If you take your first tablet at 7 a.m., when should you take your second tablet?','radio', 1),

('Normal fasting blood sugar is 70-100. If your blood sugar today is 140, is your blood sugar normal?','radio', 1),

('Do you have a TV?','radio', 1),

 ( 'Do you have a gaming system you hook up to your TV? By this we mean something like the Nintendo Wii, Xbox, or Sony Playstation?','radio', 1),

 ( 'Which system do you have?','radio', 1),

 ( 'Do you have a personal computer in your home?','radio', 1),

 ( 'Is it a Windows or Apple system?','radio', 1),

 ( 'How confident are you in using your computer?','radio', 1),

 ( 'Do you have Internet access?','radio', 1),

 ( 'Do you have an e-mail account that you check regularly?','radio', 1),

 ( 'Do you have a cell phone?','radio', 1),

 ( 'Are you able to send and received text messages using your cell phone?','radio', 1),

 ( 'Would you be willing to receive text messages about heart disease and heart-healthy living on your cell phone?','radio', 1),

 ( 'What are some of the reasons you would not be interested in getting text messages about heart health?','radio', 1),

 ( 'How much do you currently weigh without shoes?','input', 1),

 ( 'How tall are you without shoes?','input', 1),

 ( 'Are you male or female?','radio', 1),

 ( 'How old are you today?','input', 1),

 ( 'What is your ethnicity?','radio', 1),

 ( 'What is your race?','radio', 1),

 ( 'If you marked “American Indian or Alaska Native” in the previous question, what tribe do you most closely identify with?','input', 1),

 ( 'What is the highest grade in school you completed?','radio', 1),

 ( 'Please fill in the category below that best fits the total combined income before taxes of all people who lived in your household last year. This should include not only wages, salaries, and tips but also income from social security, pension, unemployment, or disability compensation, alimony, child support, welfare, or any other money income received by all household members – by you or anyone else living with you.','radio', 1),

 ('During the past 7 days, how much have you been bothered by any of the following problems?','sub_question', 1),

 ('In the past SEVEN (7) DAYS....','sub_question', 1),
 ('Question 1','radio', 1),
 ('Question 2','radio', 1),
 ('Question 3','radio', 1),
 ('Question 4','radio', 1),
 ('Question 5','radio', 1),
 ('Sudden numbness','radio', 1),
('Sudden Weakness','radio', 1),
('Sudden Disability','radio', 1),
('Vision','radio', 1),
('Gait / Posture','radio', 1),
('Seizure','radio', 1),
('Sudden Pain / Ache','radio', 1),
('F—Face: Ask the person to smile. Does one side of the face droop','radio', 1),
('A—Arms: Ask the person to raise both arms. Does one arm drift downward','radio', 1),
('S—Speech: Ask the person to repeat a simple phrase. Is the speech slurred or strange','radio', 1),
('T—Time: If you see any of these signs, call +919840056700','radio', 1),

('Level of consciousness','radio', 1),
('Ask month and age','radio', 1),
('Blink eyes & squeeze hands','radio', 1),
('Horizontal extraocular movements','radio', 1),
('Visual fields','radio', 1),
('Facial palsy','radio', 1),
('Left arm motor drift','radio', 1),
('Right arm motor drift','radio', 1),
('Left leg motor drift','radio', 1),
('Right leg motor drift','radio', 1),
('Limb Ataxia','radio', 1),
('Sensation','radio', 1),
('Language/aphasia','radio', 1),
('Dysarthria','radio', 1),
('Extinction/inattention','radio', 1),
 ('Eligibility for TPA','sub_question', 1),
 ('Absolute Contraindications to TPA','sub_question', 1),
 ('Relative Contraindications/Warnings to TPA','sub_question', 1),
 ('Additional Warnings to TPA >3hr Onset','sub_question', 1),
 ( 'Age','input', 1),
 ('NIH Stroke Scale','input', 1),
 ('History of hypertension','radio', 1),
 ('History of diabetes mellitus','radio', 1),
 ('History of atrial fibrillation','radio', 1);

 # answers
insert into `answers`(`name`,`is_active`)
 values
 ('Within the past year (anytime less than 12 months ago)',1),
 ('Within the past 2 years (more than 1 year ago but less than 2 years ago)',1),
 ('Within the past 5 years (more than 2 years ago but less than 5 years ago)',1),
 ('Five or more years ago',1),
 ('Don’t Know',1),
 ('Never had it checked',1),
 ('Normal',1),
 ('High',1),
 ('Don’t Know/Not Sure',1),
 ('Yes',1),
 ('No',1),
 ('Do Not Use Salt',1),
 ('Do Not Drink',1),
 ('Days per week (Please write “0” if the answer is “none.”)',1),
 ('Never',1),
 ('Sometimes',1),
 ('Most of the Time',1),
 ('All of the Time',1),
 ('Does Not Apply',1),
 ('More than once a day',1),
 ('About once a day',1),
 ('2-3 times a week',1),
 ('About once a week',1),
 ('1-3 times a month',1),
 ('Less than once a month',1),
 ('1 hour or less',1),
 ('2 hours',1),
 ('3 hours',1),
 ('4 hours',1),
 ('5 hours',1),
 ('6 hours',1),
 ('7 hours',1),
 ('8 hours',1),
 ('9 hours',1),
 ('10 hours or more',1),
 ('Yes, I intend to in the next 30 days',1),
 ('Yes, I intend to in the next 6 months',1),
 ('No, and I do not intend to in the next 6 months',1),
 ('Whole milk',1),
 ('2% fat',1),
 ('1% fat',1),
 ('1⁄2% fat',1),
 ('Non-fat or skim',1),
 ('Soy/lactose free',1),
 ('Canned milk',1),
 ('Powdered milk',1),
 ('Did not use milk in past 30 days',1),
 ('Pam/cooking spray',1),
 ('Stick margarine/butter/margarine blend/soft-tub',1),
 ('Lard, fatback, bacon fat',1),
 ('Crisco',1),
 ('Vegetable oil/olive oil/corn oil',1),
 ('Yes, and I intend to in the next 30 days',1),
 ('Yes, and I intend to in the next 6 months',1),
 ('days',1),
 ('cigarettes a day',1),
 ('years',1),
 ('times',1),
 ('Yes, within the next 30 days',1),
 ('Yes, within the next 6 months',1),
 ('No, not thinking of quitting',1),
 ('Take them to the hospital',1),
 ('Tell them to call their doctor',1),
 ('Call 911',1),
 ('Call their spouse or a family member',1),
 ('Do something else',1),
 ('Always',1),
 ('Often',1),
 ('Rarely',1),
 ('Extremely',1),
 ('Quite a bit',1),
 ('Somewhat',1),
 ('A little bit',1),
 ('Not at all',1),
 ('1 in 10 people',1),
 ('1 in 100 people',1),
 ('1 in 1000 people',1),
 ('2%',1),
 ('20%',1),
 ('200%',1),
 ('10 a.m',1),
 ('12 p.m',1),
 ('1 p.m',1),
 ('6 p.m',1),
 ('7 p.m',1),
 ('Nintendo Wii',1),
 ('Xbox',1),
 ('Sony Playstation',1),
 ('Other (Please specify:',1),
 ('Windows',1),
 ('Apple',1),
 ('Very confident',1),
 ('Fairly confident',1),
 ('Not at all confident',1),
 ('Too expensive',1),
 ('I’m not worried about my heart heatlh',1),
 ('Other',1),
 ('pounds',1),
 ('feet',1),
 ('inches',1),
 ('Male',1),
 ('Female',1),
 ('years old',1),
 ('Hispanic or Latino of any race',1),
 ('Not Hispanic or Latino',1),
 ('American Indian or Alaska Native',1),
 ('Asian',1),
 ('Black or African American',1),
 ('Native Hawaiian or Pacific Islander',1),
 ('White',1),
 ('None',1),
 ('1st grade',1),
 ('2nd grade',1),
 ('3rd grade',1),
 ('4th grade',1),
 ('5th grade',1),
 ('6th grade',1),
 ('7th grade',1),
 ('8th grade',1),
 ('9th grade',1),
 ('10th grade',1),
 ('11th grade',1),
 ('12th grade',1),
 ('High School graduate/GED',1),
 ('Vocational school',1),
 ('Some college',1),
 ('College graduate',1),
 ('Some graduate/professional school',1),
 ('Graduate/professional degree',1),
 ('Nothing',1),
 ('Less than $1,000',1),
 ('$1,000 - $4,999',1),
 ('$5,000 - $9,999',1),
 ('$10,000 - $14,999',1),
 ('$15,000 - $19,999',1),
 ('$20,000 - $29,999',1),
 ('$30,000 - $39,999',1),
 ('$40,000 – $49,999',1),
 ('$50,000 - $74,999',1),
 ('$75,000 - $99,999',1),
 ('More than $100,000',1),
 ('Not bothered at all',1),
 ('Bothered a little',1),
 ('Bothered a lot',1),
 ('I do not feel happier or more cheerful than usual.',1),
 ('I occasionally feel happier or more cheerful than usual.',1),
 ('I often feel happier or more cheerful than usual.',1),
 ('I feel happier or more cheerful than usual most of the time.',1),
 ('I feel happier of more cheerful than usual all of the time.',1),
 ('I do not feel more self-confident than usual.',1),
 ('I occasionally feel more self-confident than usual.',1),
 ('I often feel more self-confident than usual.',1),
 ('I frequently feel more self-confident than usual.',1),
 ('I feel extremely self-confident all of the time.',1),
 ('I do not need less sleep than usual.',1),
 ('I occasionally need less sleep than usual.',1),
 ('I often need less sleep than usual.',1),
 ('I frequently need less sleep than usual.',1),
 ('I can go all day and all night without any sleep and still not feel tired.',1),
 ('I do not talk more than usual.',1),
 ('I occasionally talk more than usual.',1),
 ('I often talk more than usual.',1),
 ('I frequently talk more than usual.',1),
 ('I talk constantly and cannot be interrupted.',1),
 ('I have not been more active (either socially, sexually, at work, home, or school) than usual.',1),
 ('I have occasionally been more active than usual.',1),
 ('I have often been more active than usual.',1),
 ('I have frequently been more active than usual.',1),
 ('I am constantly more active or on the go all the time.',1),
 ('face',1),
 ('arm',1),
 ('leg',1),
 ('Blurring',1),
 ('Double Vision',1),
 ('Dropping of eye lid',1),
 ('Loss of Vision',1),
 ('Lack of coordination',1),
 ('Unable to move',1),
 ('Head',1),
 ('Neck',1),
 ('Other Parts of Body',1),
 ('Alert; keenly responsive',1),
 ('Arouses to minor stimulation',1),
 ('Requires repeated stimulation to arouse',1),
 ('Movements to pain',1),
 ('Postures or unresponsive',1),
 ('Both questions right',1),
 ('1 question right',1),
 ('0 questions right',1),
 ('Dysarthric/intubated/trauma/language barrier',1),
 ('Aphasic',1),
 ('Performs both tasks',1),
 ('Performs 1 task',1),
 ('Performs 0 tasks',1),
 ('Partial gaze palsy: can be overcome',1),
 ('Partial gaze palsy: corrects with oculocephalic reflex',1),
 ('Forced gaze palsy: cannot be overcome',1),
 ('No visual loss',1),
 ('Partial hemianopia',1),
 ('Complete hemianopia',1),
 ('Patient is bilaterally blind',1),
 ('Bilateral hemianopia',1),
 ('Normal symmetry',1),
 ('Minor paralysis (flat nasolabial fold, smile asymmetry)',1),
 ('Partial paralysis (lower face)',1),
 ('Unilateral complete paralysis (upper/lower face)',1),
 ('Bilateral complete paralysis (upper/lower face)',1),
 ('No drift for 10 seconds',1),
 ("Drift, but doesn't hit bed",1),
 ('Drift, hits bed',1),
 ('Some effort against gravity',1),
 ('No effort against gravity',1),
 ('No movement',1),
 ('Amputation/joint fusion',1),
 ('No ataxia',1),
 ('Ataxia in 1 Limb',1),
 ('Ataxia in 2 Limbs',1),
 ('Does not understand',1),
 ('Paralyzed',1),
 ('Normal; no sensory loss',1),
 ('Mild-moderate loss: less sharp/more dull',1),
 ('Mild-moderate loss: can sense being touched',1),
 ('Complete loss: cannot sense being touched at all',1),
 ('No response and quadriplegic',1),
 ('Coma/unresponsive',1),
 ('Normal; no aphasia',1),
 ('Mild-moderate aphasia: some obvious changes, without significant limitation',1),
 ('Severe aphasia: fragmentary expression, inference needed, cannot identify materials+',1),
 ('Mute/global aphasia: no usable speech/auditory comprehension',1),
 ('Mild-moderate dysarthria: slurring but can be understood',1),
 ('Severe dysarthria: unintelligible slurring or out of proportion to dysphasia',1),
 ('Mute/anarthric',1),
 ('Intubated/unable to test',1),
 ('No abnormality',1),
 ('Visual/tactile/auditory/spatial/personal inattention',1),
 ('Extinction to bilateral simultaneous stimulation',1),
 ('Profound hemi-inattention (ex: does not recognize own hand)',1),
 ('Extinction to >1 modality',1),
 ('Norm: 0 - 42 points',1);
