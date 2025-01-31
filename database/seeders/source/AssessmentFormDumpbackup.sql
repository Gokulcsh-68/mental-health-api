#forms
insert into `forms`( `name`, `desc`,  `assessment_group`, `type`)
 values('Healthy Heart','Thank you for participating in the Urban Indian Heart Health Program. The purpose of this
survey is to learn about the heart health of patients at this clinic. The following pages ask
questions about heart attack and stroke, and about your blood pressure, cholesterol, physical
activity, diet, and tobacco use.
The survey should take about 20-30 minutes to complete. Answering these questions is
voluntary and your answers will be kept private. This is not a test. Just mark the answers that
best reflect what you think. If you have questions, please feel free to ask a project staff member
for help. Thank you for completing this survey!', 'healthy-heart', 'normal');

insert into `forms`( `name`, `desc`, `assessment_group`, `type`)
 values('Physical Symptoms','On the DSM-5 Level 1 cross-cutting questionnaire that you just completed, you indicated that during the
past 2 weeks you (the individual receiving care) have been bothered by “unexplained aches and pains”, and/or “feeling
that your illnesses are not being taken seriously enough” at a mild or greater level of severity. The questions below ask
about these feelings in more detail and especially how often you (the individual receiving care) have been bothered by
a list of symptoms during the past 7 days. Please respond to each item by marking ( or x) one box per row.',
'psychiatric-exam', 'score');

insert into `forms`( `name`, `desc`, `assessment_group`, `type`)
 values('Anger','On the DSM-5 Level 1 cross-cutting questionnaire that you just completed,
  you indicated that during the past 2 weeks you (individual receiving care) have been bothered by
   “feeling nervous, anxious, frightened, worried, or on edge”, “feeling panic or being frightened”,
    and/or “avoiding situations that make you anxious” at a mild or greater level of severity.
     The questions below ask about these feelings in more detail and especially how often you 
     (individual receiving care) have been bothered by a list of symptoms during the past 7 days.
      Please respond to each item by marking (&#10004; or &#10006;) one box per row. Clinician',
'psychiatric-exam', 'score');

insert into `forms`( `name`, `desc`, `assessment_group`, `type`)
 values('Anxiety','On the DSM-5 Level 1 cross-cutting questionnaire that you just completed,
  you indicated that during the past 2 weeks you (individual receiving care) have been bothered by “feeling nervous,
   anxious, frightened, worried, or on edge”, “feeling panic or being frightened”,
    and/or “avoiding situations that make you anxious” at a mild or greater level of severity.
     The questions below ask about these feelings in more detail and especially how often you 
     (individual receiving care) have been bothered by a list of symptoms during the past 7 days.
      Please respond to each item by marking (&#10004; or &#10006;) one box per row.',
'psychiatric-exam', 'score');

insert into `forms`( `name`, `desc`, `assessment_group`, `type`)
 values('Depression','On the DSM-5 Level 1 cross-cutting questionnaire that you just completed, 
 	you indicated that during the past 2 weeks you (the individual receiving care) have been bothered by 
 	“no interest or pleasure in doing things” and/or “feeling down, depressed, or hopeless” at a mild or greater level of severity.
 	The questions below ask about these feelings in more detail and especially how often you (the individual receiving care) 
 	have been bothered by a list of symptoms during the past 7 days. 
 	Please respond to each item by marking (&#10004; or &#10006;) one box per row.',
'psychiatric-exam', 'score');

insert into `forms`( `name`, `desc`, `assessment_group`, `type`)
 values('Mania','On the DSM-5 Level 1 cross-cutting questionnaire you just completed, you indicated that during the past 2 weeks you (the
individual receiving care) have been bothered by “sleeping less than usual, but still having a lot of energy” and/or “starting lots more
projects than usual or doing more risky things than usual” at a mild or greater level of severity. The five statement groups or questions
below ask about these feelings in more detail.
1. Please read each group of statements/question carefully.
2. Choose the one statement in each group that best describes the way you (the individual receiving care) have been feeling for
the past week.
3. Check the box ( or x) next to the number/statement selected.
4. Please note: The word “occasionally” when used here means once or twice; “often” means several times or more and
“frequently” means most of the time.',
'psychiatric-exam', 'score');

 insert into `forms`( `name`, `desc`, `assessment_group`, `type`)
 values('Symptoms & Signs',"Symptoms & Signs",'stroke-scale', 'normal');
 
 insert into `forms`( `name`, `desc`, `assessment_group`, `type`)
 values('NIH Stroke Scale/Score (NIHSS)',"The NIH Stroke Scale has many caveats buried within it. 
 	If your patient has prior known neurologic deficits e.g. prior weakness,
 	hemi- or quadriplegia, blindness, etc. or is intubated, has a language barrier, etc.,
 	it becomes especially complicated. In those cases, consult the NIH Stroke Scale website.
 	MDCalc's version is an attempt to clarify many of these confusing caveats,
 	but cannot and should not be substituted for the official protocol.",
'stroke-scale', 'score');

insert into `forms`( `name`, `desc`, `assessment_group`, `type`)
 values('TPA Contraindications for Ischemic Stroke','Institutions may have slightly 
 	different absolute and relative contraindications to Tissue Plasminogen Activator (tPA);
 	 this list is meant to be a quick reference, but practice should be guided by institutional protocol 
 	 and consultation with neurology. Reflects recommendations from Demaerschalk et al, Stroke 2015.',
'stroke-scale', 'score');

insert into `forms`( `name`, `desc`, `assessment_group`, `type`)
 values('THRIVE Score for Stroke Outcome',
 	'The THRIVE score can help physicians predict several key outcomes 
 	in patients suffering an ischemic stroke.',
'stroke-scale', 'score');


# health heart Questions

insert into `questions` (`name`, `type`, `is_active`)
 values('When was the last time you had your blood pressure checked?','radio', 1);

insert into `questions` (`name`, `type`, `is_active`)
 values ('The LAST time you had your blood pressure checked, was it normal or high?','radio', 1);

insert into `questions` (`name`, `type`, `is_active`)
 values ('Have you EVER been told by a doctor, nurse, or other health professional that you have
high blood pressure?','radio', 1);

insert into `questions` (`name`, `type`, `is_active`)
 values ('If yes, and if you are female, was this only when you were pregnant?','radio', 1);

insert into `questions` (`name`, `type`, `is_active`)
 values('Are you currently taking medicine for your high blood pressure?','radio', 1);

insert into `questions` (`name`, `type`, `is_active`)
 values('Are you changing your eating habits to help lower or control your blood pressure?','radio', 1);

insert into `questions` (`name`, `type`, `is_active`)
 values('Are you cutting down on salt to help lower or control your blood pressure?','radio', 1);

insert into `questions` (`name`, `type`, `is_active`)
 values('Are you reducing alcohol use to help lower or control your blood pressure?','radio', 1);

insert into `questions` (`name`, `type`, `is_active`)
 values('Are you exercising to help lower or control your blood pressure?','radio', 1);

insert into `questions` (`name`, `type`, `is_active`)
 values('Blood cholesterol is a fatty substance found in the blood. Have you ever had your blood
cholesterol checked?','radio', 1);

insert into `questions` (`name`, `type`, `is_active`)
 values('About how long has it been since you last had your blood cholesterol checked?','radio', 1);

insert into `questions` (`name`, `type`, `is_active`)
 values('The last time you had your blood cholesterol checked, was it normal or high?','radio', 1);

 insert into `questions` (`name`, `type`, `is_active`)
 values( 'Have you EVER been told by a doctor, nurse or other health professional that your blood
cholesterol is high?','radio', 1);

 insert into `questions` (`name`, `type`, `is_active`)
 values( 'If so, when were you told that your blood cholesterol was high?','radio', 1);

 insert into `questions` (`name`, `type`, `is_active`)
 values( 'How many days per week do you do moderate physical activities for at least 30 minutes?','input', 1);

 insert into `questions` (`name`, `type`, `is_active`)
 values( 'How many days per week do you do vigorous physical activities for at least 20 minutes?','input', 1);

 insert into `questions` (`name`, `type`, `is_active`)
 values( 'Thinking back on the past 30 days, please check yes or no for each statement. You may
    choose “yes” for more than one statement.','sub_question', 1);

 insert into `questions` (`name`, `type`, `is_active`)
 values( 'Over the past 30 days in general, how many hours per day did you usually spend watching
television, sitting at a computer, playing video games, doing beadwork, or other activities
that don’t require much physical activity?','radio', 1);

 insert into `questions` (`name`, `type`, `is_active`)
 values( 'Do you plan to increase the amount of physical activity you get every week?','radio', 1);

 insert into `questions` (`name`, `type`, `is_active`)
 values( 'Please think about what you usually ate or drank during the past 30 days. Read each item
carefully and indicate one response for each. How often did you...','sub_question', 1);

 insert into `questions` (`name`, `type`, `is_active`)
 values( 'What kind of milk did you usually use? (Pick the one that you used most often in the past 30
days.)','radio', 1);

 insert into `questions` (`name`, `type`, `is_active`)
 values( 'What kinds of fat or oil did you usually use in cooking in the past 30 days (if more than one,
choose the one used most often)?','radio', 1);

 insert into `questions` (`name`, `type`, `is_active`)
 values( 'How often do you do the following things? Mark your answer with an X.','sub_question',1);

 insert into `questions` (`name`, `type`, `is_active`)
 values( 'Are you able to buy or grow low-cost vegetables?','radio', 1);

 insert into `questions` (`name`, `type`, `is_active`)
 values( 'In the future, do you intend to reduce the amount of fat you eat so it is lower than it is now?','radio', 1);

 insert into `questions` (`name`, `type`, `is_active`)
 values( 'Do you smoke cigarettes now? (For these questions, we are not interested in the tobacco you may smoke for ceremonial use.)','radio', 1);

 insert into `questions` (`name`, `type`, `is_active`)
 values( 'Thinking over the past 30 days, including today, how many days during this time did you
smoke?','input', 1);

 insert into `questions` (`name`, `type`, `is_active`)
 values( 'About how many cigarettes a day do you now smoke?','input', 1);

 insert into `questions` (`name`, `type`, `is_active`)
 values( 'About how many years have you been smoking?','input', 1);

 insert into `questions` (`name`, `type`, `is_active`)
 values( 'In the past year, how many times have you quit smoking for at least 24 hours?','input', 1);

 insert into `questions` (`name`, `type`, `is_active`)
 values( 'Are you seriously thinking of quitting smoking?','radio', 1);

 insert into `questions` (`name`, `type`, `is_active`)
 values( 'Do you think pain or discomfort in the jaw, neck, or back are symptoms of a heart attack?','radio', 1);

 insert into `questions` (`name`, `type`, `is_active`)
 values( 'Do you think feeling weak, lightheaded, or faint are symptoms of a heart attack?','radio', 1);

 insert into `questions` (`name`, `type`, `is_active`)
 values( 'Do you think swelling of the feet and legs is a symptom of a heart attack?','radio', 1);

 insert into `questions` (`name`, `type`, `is_active`)
 values( 'Do you think chest pain or discomfort are symptoms of a heart attack?','radio', 1);

 insert into `questions` (`name`, `type`, `is_active`)
 values( 'Do you think sudden trouble seeing in one or both eyes is a symptom of a heart attack?','radio', 1);

 insert into `questions` (`name`, `type`, `is_active`)
 values( 'Do you think tingling in the fingers and toes are symptoms of a heart attack?','radio', 1);

 insert into `questions` (`name`, `type`, `is_active`)
 values( 'Do you think pain or discomfort in the arms or shoulder are symptoms of a heart attack?','radio', 1);

 insert into `questions` (`name`, `type`, `is_active`)
 values( 'Do you think shortness of breath is a symptom of a heart attack?','radio', 1);

 insert into `questions` (`name`, `type`, `is_active`)
 values( 'Do you think sudden confusion or trouble speaking are symptoms of a stroke?','radio', 1);

 insert into `questions` (`name`, `type`, `is_active`)
 values( 'Do you think sudden numbness or weakness of face, arm, or leg, especially on one side, are symptoms of a stroke?','radio', 1);

 insert into `questions` (`name`, `type`, `is_active`)
 values( 'Do you think feeling sick to your stomach is a symptom of a stroke?','radio', 1);

 insert into `questions` (`name`, `type`, `is_active`)
 values( 'Do you think sharp pain in the jaw or mouth is a symptom of a stroke?','radio', 1);

 insert into `questions` (`name`, `type`, `is_active`)
 values( 'Do you think sudden trouble seeing in one or both eyes is a symptom of a stroke?','radio', 1);

 insert into `questions` (`name`, `type`, `is_active`)
 values( 'Do you think sudden chest pain or discomfort are symptoms of a stroke?','radio', 1);

 insert into `questions` (`name`, `type`, `is_active`)
 values( 'Do you think sudden trouble walking, dizziness, or loss of balance are symptoms of a stroke?','radio', 1);

 insert into `questions` (`name`, `type`, `is_active`)
 values( 'Do you think severe headache with no known cause is a symptom of a stroke?','radio', 1);

 insert into `questions` (`name`, `type`, `is_active`)
 values( 'If you thought someone was having a heart attack or a stroke, what is the first thing you would do?','radio', 1);

insert into `questions` (`name`, `type`, `is_active`)
 values('Can a large waist (>35 inches for women or >40 inches for men) increase your risk of heart disease?','radio', 1);

insert into `questions` (`name`, `type`, `is_active`)
 values('Can the Body Mass Index (BMI) Chart tell you if you are overweight?','radio', 1);

insert into `questions` (`name`, `type`, `is_active`)
 values('Does your liver make all the cholesterol your body needs to keep you healthy?','radio', 1);

 insert into `questions` (`name`, `type`, `is_active`)
 values( 'Can eating foods that are high in sodium increase your risk of high blood pressure?','radio', 1);

 insert into `questions` (`name`, `type`, `is_active`)
 values( 'Does lard have a low amount of saturated fat?','radio', 1);

 insert into `questions` (`name`, `type`, `is_active`)
 values( 'Can eating too much saturated fat and trans fat raise your cholesterol level?','radio', 1);

 insert into `questions` (`name`, `type`, `is_active`)
 values( 'Is a blood pressure of 140/90 mmHg considered high?','radio', 1);

 insert into `questions` (`name`, `type`, `is_active`)
 values( 'Can being overweight or obese put you at risk for developing high blood cholesterol?','radio', 1);

 insert into `questions` (`name`, `type`, `is_active`)
 values( 'Is being physically active a way to reduce your risk for heart disease?','radio', 1);

 insert into `questions` (`name`, `type`, `is_active`)
 values( 'Is it true that only people with high blood cholesterol should follow a heart healthy diet?','radio', 1);

 insert into `questions` (`name`, `type`, `is_active`)
 values( 'Can nonsmokers die from secondhand smoke?','radio', 1);

 insert into `questions` (`name`, `type`, `is_active`)
 values( 'How often do you have a hard time understanding written information about your health that you get from your clinic? (This might include information from a doctor or nurse.)','radio', 1);

 insert into `questions` (`name`, `type`, `is_active`)
 values( 'How confident are you in filling out medical forms by yourself?','radio', 1);

 insert into `questions` (`name`, `type`, `is_active`)
 values( 'How often do you prefer that someone (like a family member or someone else) help you read medical materials?','radio', 1);

 insert into `questions` (`name`, `type`, `is_active`)
 values( 'Which of the following numbers represents the lowest risk? For example, which would you most like to hear from a doctor about your risk for a medical condition?','radio', 1);

 insert into `questions` (`name`, `type`, `is_active`)
 values( 'If the chance of getting a health condition is 20 out of 100 people, this would be the same as having a what percent (%) chance of getting the condition?','radio', 1);

insert into `questions` (`name`, `type`, `is_active`)
 values('A prescription says “Take one tablet by mouth every 6 hours.” If you take your first tablet at 7 a.m., when should you take your second tablet?','radio', 1);

insert into `questions` (`name`, `type`, `is_active`)
 values('Normal fasting blood sugar is 70-100. If your blood sugar today is 140, is your blood sugar normal?','radio', 1);

insert into `questions` (`name`, `type`, `is_active`)
 values('Do you have a TV?','radio', 1);

 insert into `questions` (`name`, `type`, `is_active`)
 values( 'Do you have a gaming system you hook up to your TV? By this we mean something like the Nintendo Wii, Xbox, or Sony Playstation?','radio', 1);

 insert into `questions` (`name`, `type`, `is_active`)
 values( 'Which system do you have?','radio', 1);

 insert into `questions` (`name`, `type`, `is_active`)
 values( 'Do you have a personal computer in your home?','radio', 1);

 insert into `questions` (`name`, `type`, `is_active`)
 values( 'Is it a Windows or Apple system?','radio', 1);

 insert into `questions` (`name`, `type`, `is_active`)
 values( 'How confident are you in using your computer?','radio', 1);

 insert into `questions` (`name`, `type`, `is_active`)
 values( 'Do you have Internet access?','radio', 1);

 insert into `questions` (`name`, `type`, `is_active`)
 values( 'Do you have an e-mail account that you check regularly?','radio', 1);

 insert into `questions` (`name`, `type`, `is_active`)
 values( 'Do you have a cell phone?','radio', 1);

 insert into `questions` (`name`, `type`, `is_active`)
 values( 'Are you able to send and received text messages using your cell phone?','radio', 1);

 insert into `questions` (`name`, `type`, `is_active`)
 values( 'Would you be willing to receive text messages about heart disease and heart-healthy living on your cell phone?','radio', 1);

 insert into `questions` (`name`, `type`, `is_active`)
 values( 'What are some of the reasons you would not be interested in getting text messages about heart health?','radio', 1);

 insert into `questions` (`name`, `type`, `is_active`)
 values( 'How much do you currently weigh without shoes?','input', 1);

 insert into `questions` (`name`, `type`, `is_active`)
 values( 'How tall are you without shoes?','input', 1);

 insert into `questions` (`name`, `type`, `is_active`)
 values( 'Are you male or female?','radio', 1);

 insert into `questions` (`name`, `type`, `is_active`)
 values( 'How old are you today?','input', 1);

 insert into `questions` (`name`, `type`, `is_active`)
 values( 'What is your ethnicity?','radio', 1);

 insert into `questions` (`name`, `type`, `is_active`)
 values( 'What is your race?','radio', 1);

 insert into `questions` (`name`, `type`, `is_active`)
 values( 'If you marked “American Indian or Alaska Native” in the previous question, what tribe do you most closely identify with?','input', 1);

 insert into `questions` (`name`, `type`, `is_active`)
 values( 'What is the highest grade in school you completed?','radio', 1);

 insert into `questions` (`name`, `type`, `is_active`)
 values( 'Please fill in the category below that best fits the total combined income before taxes of all people who lived in your household last year. This should include not only wages, salaries, and tips but also income from social security, pension, unemployment, or disability compensation, alimony, child support, welfare, or any other money income received by all household members – by you or anyone else living with you.','radio', 1);



 # SubQuestions

 insert into `questions`( `parent_id`, `name`, `type`, `is_active`)
 values((SELECT current_question.id FROM questions as current_question WHERE current_question.name="Thinking back on the past 30 days, please check yes or no for each statement. You may
choose “yes” for more than one statement." LIMIT 1),
 	'I rarely or never do any physical activities','radio',1),
 ((SELECT current_question.id FROM questions as current_question WHERE current_question.name="Thinking back on the past 30 days, please check yes or no for each statement. You may
choose “yes” for more than one statement." LIMIT 1),
 	'I do some light or moderate physical activities, but not every week','radio',1),
 ((SELECT current_question.id FROM questions as current_question WHERE current_question.name="Thinking back on the past 30 days, please check yes or no for each statement. You may
choose “yes” for more than one statement." LIMIT 1),
 	'I do some light physical activity every week','radio',1),
 ((SELECT current_question.id FROM questions as current_question WHERE current_question.name="Thinking back on the past 30 days, please check yes or no for each statement. You may
choose “yes” for more than one statement." LIMIT 1),
 	'I do moderate physical activities every week, but less than 30 minutes a day,
5 days a week','radio',1),
 ((SELECT current_question.id FROM questions as current_question WHERE current_question.name="Thinking back on the past 30 days, please check yes or no for each statement. You may
choose “yes” for more than one statement." LIMIT 1),
 	'I do vigorous physical activities every week, but less than 20 minutes a day, 3
days a week','radio',1),
 ((SELECT current_question.id FROM questions as current_question WHERE current_question.name="Thinking back on the past 30 days, please check yes or no for each statement. You may
choose “yes” for more than one statement." LIMIT 1),
 	'I do 30 minutes or more per day of moderate physical activities, 5 or more
days a week','radio',1),
 ((SELECT current_question.id FROM questions as current_question WHERE current_question.name="Thinking back on the past 30 days, please check yes or no for each statement. You may
choose “yes” for more than one statement." LIMIT 1),
 	'I do 20 minutes or more per day of vigorous physical activities, 3 or more
days a week','radio',1),
 ((SELECT current_question.id FROM questions as current_question WHERE current_question.name="Thinking back on the past 30 days, please check yes or no for each statement. You may
choose “yes” for more than one statement." LIMIT 1),
 	'I do activities to increase muscle strength, such as lifting weights, once a
week or more','radio',1),
 ((SELECT current_question.id FROM questions as current_question WHERE current_question.name="Thinking back on the past 30 days, please check yes or no for each statement. You may
choose “yes” for more than one statement." LIMIT 1),
 	'I do activities to improve flexibility, such as stretching or yoga, once a week
or more','radio',1);


 insert into `questions`(`parent_id`, `name`, `type`, `is_active`)
 values((SELECT current_question.id FROM questions as current_question WHERE current_question.name="Please think about what you usually ate or drank during the past 30 days. Read each item
carefully and indicate one response for each. How often did you..." LIMIT 1),
 	'eat bacon or sausage? (Do not include low-fat,
light, or turkey varieties.)','radio',1),
 ((SELECT current_question.id FROM questions as current_question WHERE current_question.name="Please think about what you usually ate or drank during the past 30 days. Read each item
carefully and indicate one response for each. How often did you..." LIMIT 1),
 	'eat processed meat (for example, lunch meat, hot dogs made of beef or pork, spam, corned beef)?','radio',1),
 ((SELECT current_question.id FROM questions as current_question WHERE current_question.name="Please think about what you usually ate or drank during the past 30 days. Read each item
carefully and indicate one response for each. How often did you..." LIMIT 1),
 	'eat whole grain bread (for example, whole
wheat, rye, oatmeal, or pumpernickel sandwich
bread or rolls, corn tortillas)?','radio',1),
 ((SELECT current_question.id FROM questions as current_question WHERE current_question.name="Please think about what you usually ate or drank during the past 30 days. Read each item
carefully and indicate one response for each. How often did you..." LIMIT 1),
 	'eat bread from processed flour (for example,
white sandwich bread or rolls, round pueblo
bread, flour tortillas)?','radio',1),
 ((SELECT current_question.id FROM questions as current_question WHERE current_question.name="Please think about what you usually ate or drank during the past 30 days. Read each item
carefully and indicate one response for each. How often did you..." LIMIT 1),
 	'eat Frybread or other fried pastries?','radio',1),
 ((SELECT current_question.id FROM questions as current_question WHERE current_question.name="Please think about what you usually ate or drank during the past 30 days. Read each item
carefully and indicate one response for each. How often did you..." LIMIT 1),
 	'eat other baked goods (for example, doughnuts,
Danish, coffee cake, cookies, pies, or cakes)?','radio',1),
 ((SELECT current_question.id FROM questions as current_question WHERE current_question.name="Please think about what you usually ate or drank during the past 30 days. Read each item
carefully and indicate one response for each. How often did you..." LIMIT 1),
 	'drink regular soft drinks/pop/soda (for
example, Slushees, Coke, bottled drinks like
Snapple)? (Do not include diet drinks.)','radio',1),
 ((SELECT current_question.id FROM questions as current_question WHERE current_question.name="Please think about what you usually ate or drank during the past 30 days. Read each item
carefully and indicate one response for each. How often did you..." LIMIT 1),
 	'drink 100% fruit juice (for example, orange,
grapefruit, apple, and grape juices). (Do not
count fruit drinks, such as Kool-Aid, lemonade,
Cranberry Juice Cocktail, Hi-C, and Tang.)','radio',1),
 ((SELECT current_question.id FROM questions as current_question WHERE current_question.name="Please think about what you usually ate or drank during the past 30 days. Read each item
carefully and indicate one response for each. How often did you..." LIMIT 1),
 	'add sugar (or honey) and/or creamer to your
coffee or tea?','radio',1),
 ((SELECT current_question.id FROM questions as current_question WHERE current_question.name="Please think about what you usually ate or drank during the past 30 days. Read each item
carefully and indicate one response for each. How often did you..." LIMIT 1),
 	'eat fruit? Count fresh, frozen, dried, or canned
fruit. Do not count juices.','radio',1),
 ((SELECT current_question.id FROM questions as current_question WHERE current_question.name="Please think about what you usually ate or drank during the past 30 days. Read each item
carefully and indicate one response for each. How often did you..." LIMIT 1),
 	'use regular fat salad dressing or mayonnaise,
including on salad and sandwiches? Do not
include low-fat, light, or diet dressings.','radio',1),
 ((SELECT current_question.id FROM questions as current_question WHERE current_question.name="Please think about what you usually ate or drank during the past 30 days. Read each item
carefully and indicate one response for each. How often did you..." LIMIT 1),
 	'eat lettuce or green leafy salad (for example,
cabbage and spinach, with or without other
vegetables)?','radio',1),
 ((SELECT current_question.id FROM questions as current_question WHERE current_question.name="Please think about what you usually ate or drank during the past 30 days. Read each item
carefully and indicate one response for each. How often did you..." LIMIT 1),
 	'eat French fries, fried potatoes, tater tots or
hash brown potatoes?','radio',1),
 ((SELECT current_question.id FROM questions as current_question WHERE current_question.name="Please think about what you usually ate or drank during the past 30 days. Read each item
carefully and indicate one response for each. How often did you..." LIMIT 1),
 	'eat cooked dried beans (for example, refried
beans, baked beans, bean soup, pork and beans)?','radio',1),
 ((SELECT current_question.id FROM questions as current_question WHERE current_question.name="Please think about what you usually ate or drank during the past 30 days. Read each item
carefully and indicate one response for each. How often did you..." LIMIT 1),
 	'eat “red” meat (for example, beef, pork or salt
pork, veal, lamb, liver, kidneys)?','radio',1),
 ((SELECT current_question.id FROM questions as current_question WHERE current_question.name="Please think about what you usually ate or drank during the past 30 days. Read each item
carefully and indicate one response for each. How often did you..." LIMIT 1),
 	'eat fish, chicken, game?','radio',1),
 ((SELECT current_question.id FROM questions as current_question WHERE current_question.name="Please think about what you usually ate or drank during the past 30 days. Read each item
carefully and indicate one response for each. How often did you..." LIMIT 1),
 	'eat vegetables (for example, squash, okra, corn,
zucchini, seaweed, kelp)? Count any form of
vegetable – raw, cooked, canned, or frozen. Do
not count lettuce salads, white potatoes, cooked
dried beans, or rice.','radio',1),
 ((SELECT current_question.id FROM questions as current_question WHERE current_question.name="Please think about what you usually ate or drank during the past 30 days. Read each item
carefully and indicate one response for each. How often did you..." LIMIT 1),
 	'eat fast food from a restaurant or store (for
example, hamburgers, pizza, fried chicken,
chimichangas/tacos)?','radio',1);


insert into `questions`( `parent_id`, `name`, `type`, `is_active`)
 values((SELECT current_question.id FROM questions as current_question WHERE current_question.name="How often do you do the following things? Mark your answer with an X." LIMIT 1),
 	'Do you use fresh vegetables instead of canned vegetables?','radio',1),
 ((SELECT current_question.id FROM questions as current_question WHERE current_question.name="How often do you do the following things? Mark your answer with an X." LIMIT 1),
 	'Do you use bouillon cubes when you cook?','radio',1),
 ((SELECT current_question.id FROM questions as current_question WHERE current_question.name="How often do you do the following things? Mark your answer with an X." LIMIT 1),
 	'Do you read food labels to choose foods with a low-sodium content?','radio',1),
 ((SELECT current_question.id FROM questions as current_question WHERE current_question.name="How often do you do the following things? Mark your answer with an X." LIMIT 1),
 	'Do you add salt to fruit?','radio',1),
 ((SELECT current_question.id FROM questions as current_question WHERE current_question.name="How often do you do the following things? Mark your answer with an X." LIMIT 1),
 	'Do you add salt to the water when you cook beans, rice, pasta, or vegetables?','radio',1),
 ((SELECT current_question.id FROM questions as current_question WHERE current_question.name="How often do you do the following things? Mark your answer with an X." LIMIT 1),
 	'Do you use a saltshaker at the table?','radio',1),
 ((SELECT current_question.id FROM questions as current_question WHERE current_question.name="How often do you do the following things? Mark your answer with an X." LIMIT 1),
 	'Do you fill your saltshaker with a mixture of herbs and spices instead of salt?','radio',1),
 ((SELECT current_question.id FROM questions as current_question WHERE current_question.name="How often do you do the following things? Mark your answer with an X." LIMIT 1),
 	'Do you choose fruits and vegetables instead of
potato chips, french fries, or pork rinds?','radio',1),
 ((SELECT current_question.id FROM questions as current_question WHERE current_question.name="How often do you do the following things? Mark your answer with an X." LIMIT 1),
 	'Do you eat low-fat cheese instead of regular
cheese?','radio',1),
 ((SELECT current_question.id FROM questions as current_question WHERE current_question.name="How often do you do the following things? Mark your answer with an X." LIMIT 1),
 	'Do you read food labels to help you choose foods
lower in saturated fat, trans fat, and cholesterol?','radio',1),
 ((SELECT current_question.id FROM questions as current_question WHERE current_question.name="How often do you do the following things? Mark your answer with an X." LIMIT 1),
 	'Do you use fresh vegetables instead of canned
vegetables?','radio',1),
 ((SELECT current_question.id FROM questions as current_question WHERE current_question.name="How often do you do the following things? Mark your answer with an X." LIMIT 1),
 	'Do you remove the skin before cooking chicken?','radio',1),
 ((SELECT current_question.id FROM questions as current_question WHERE current_question.name="How often do you do the following things? Mark your answer with an X." LIMIT 1),
 	'Do you drain the fat and throw it away when you
cook ground meat?','radio',1),
 ((SELECT current_question.id FROM questions as current_question WHERE current_question.name="How often do you do the following things? Mark your answer with an X." LIMIT 1),
 	'Do you choose fat-free or low-fat salad dressing
or mayonnaise instead of regular?','radio',1),
 ((SELECT current_question.id FROM questions as current_question WHERE current_question.name="How often do you do the following things? Mark your answer with an X." LIMIT 1),
 	'Do you read labels to choose foods lower in
calories?','radio',1),
 ((SELECT current_question.id FROM questions as current_question WHERE current_question.name="How often do you do the following things? Mark your answer with an X." LIMIT 1),
 	'Do you bake or grill foods instead of frying them?','radio',1),
 ((SELECT current_question.id FROM questions as current_question WHERE current_question.name="How often do you do the following things? Mark your answer with an X." LIMIT 1),
 	'Do you serve more vegetables on your plate than
meat?','radio',1),
 ((SELECT current_question.id FROM questions as current_question WHERE current_question.name="How often do you do the following things? Mark your answer with an X." LIMIT 1),
 	'Do you serve yourself large portions of food?','radio',1),
 ((SELECT current_question.id FROM questions as current_question WHERE current_question.name="How often do you do the following things? Mark your answer with an X." LIMIT 1),
 	'Do you eat fruits instead of desserts or snacks that
contain large amounts of sugar?','radio',1);

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
 ('More than $100,000',1);

# Answers Mapping
#1
 insert into `form_question_answers` (`form_question_id`,`answer_id`,`jump_to_question_id`)
 values((SELECT current_question.id FROM questions as current_question WHERE current_question.name="When was the last time you had your blood pressure checked?" LIMIT 1), 
 	(SELECT id FROM answers WHERE name="Within the past year (anytime less than 12 months ago)" LIMIT 1), null),
 ((SELECT current_question.id FROM questions as current_question WHERE current_question.name="When was the last time you had your blood pressure checked?" LIMIT 1), 
 	(SELECT id FROM answers WHERE name="Within the past 2 years (more than 1 year ago but less than 2 years ago)" LIMIT 1), 
 	null),
 ((SELECT current_question.id FROM questions as current_question WHERE current_question.name="When was the last time you had your blood pressure checked?" LIMIT 1),
 	(SELECT id FROM answers WHERE name="Within the past 5 years (more than 2 years ago but less than 5 years ago)" LIMIT 1),
 	null),
 
 ((SELECT current_question.id FROM questions as current_question WHERE current_question.name="When was the last time you had your blood pressure checked?" LIMIT 1), 
 	(SELECT id FROM answers WHERE name="Five or more years ago" LIMIT 1), null),

 ((SELECT current_question.id FROM questions as current_question WHERE current_question.name="When was the last time you had your blood pressure checked?" LIMIT 1), 
 	(SELECT id FROM answers WHERE name="Don’t Know" LIMIT 1), null),

 ((SELECT current_question.id FROM questions as current_question WHERE current_question.name="When was the last time you had your blood pressure checked?" LIMIT 1), 
 	(SELECT id FROM answers WHERE name="Never had it checked" LIMIT 1), 
 	(SELECT current_question.id FROM questions as current_question WHERE current_question.name="Blood cholesterol is a fatty substance found in the blood. Have you ever had your blood
cholesterol checked?" LIMIT 1));

#2
 insert into `form_question_answers` (`form_question_id`,`answer_id`,`jump_to_question_id`)
 values((SELECT current_question.id FROM questions as current_question WHERE current_question.name="The LAST time you had your blood pressure checked, was it normal or high?" LIMIT 1), 
 	(SELECT id FROM answers WHERE name="Normal" LIMIT 1), null),
 ((SELECT current_question.id FROM questions as current_question WHERE current_question.name="The LAST time you had your blood pressure checked, was it normal or high?" LIMIT 1), 
 	(SELECT id FROM answers WHERE name="High" LIMIT 1), null),
 ((SELECT current_question.id FROM questions as current_question WHERE current_question.name="The LAST time you had your blood pressure checked, was it normal or high?" LIMIT 1), 
 	(SELECT id FROM answers WHERE name="Don’t Know/Not Sure" LIMIT 1), null);

#3
 insert into `form_question_answers` (`form_question_id`,`answer_id`,`jump_to_question_id`)
 values((SELECT current_question.id FROM questions as current_question WHERE current_question.name="Have you EVER been told by a doctor, nurse, or other health professional that you have
high blood pressure?" LIMIT 1), 
 	(SELECT id FROM answers WHERE name="Yes" LIMIT 1), null),
 ((SELECT current_question.id FROM questions as current_question WHERE current_question.name="Have you EVER been told by a doctor, nurse, or other health professional that you have
high blood pressure?" LIMIT 1), 
 	(SELECT id FROM answers WHERE name="No" LIMIT 1), (SELECT current_question.id FROM questions as current_question WHERE current_question.name="Blood cholesterol is a fatty substance found in the blood. Have you ever had your blood
cholesterol checked?" LIMIT 1)),
 ((SELECT current_question.id FROM questions as current_question WHERE current_question.name="Have you EVER been told by a doctor, nurse, or other health professional that you have
high blood pressure?" LIMIT 1), 
 	(SELECT id FROM answers WHERE name="Don’t Know/Not Sure" LIMIT 1), (SELECT current_question.id FROM questions as current_question WHERE current_question.name="Blood cholesterol is a fatty substance found in the blood. Have you ever had your blood
cholesterol checked?" LIMIT 1));

#4
 insert into `form_question_answers` (`form_question_id`,`answer_id`,`jump_to_question_id`)
 values((SELECT current_question.id FROM questions as current_question WHERE current_question.name="If yes, and if you are female, was this only when you were pregnant?" LIMIT 1), 
 	(SELECT id FROM answers WHERE name="Yes" LIMIT 1), (SELECT current_question.id FROM questions as current_question WHERE current_question.name="Blood cholesterol is a fatty substance found in the blood. Have you ever had your blood
cholesterol checked?" LIMIT 1)),
 ((SELECT current_question.id FROM questions as current_question WHERE current_question.name="If yes, and if you are female, was this only when you were pregnant?" LIMIT 1), 
 	(SELECT id FROM answers WHERE name="No" LIMIT 1), null),
 ((SELECT current_question.id FROM questions as current_question WHERE current_question.name="If yes, and if you are female, was this only when you were pregnant?" LIMIT 1), 
 	(SELECT id FROM answers WHERE name="Don’t Know/Not Sure" LIMIT 1), (SELECT current_question.id FROM questions as current_question WHERE current_question.name="Blood cholesterol is a fatty substance found in the blood. Have you ever had your blood
cholesterol checked?" LIMIT 1));

 #5
 insert into `form_question_answers` (`form_question_id`,`answer_id`,`jump_to_question_id`)
 values((SELECT current_question.id FROM questions as current_question WHERE current_question.name="Are you currently taking medicine for your high blood pressure?" LIMIT 1), 
 	(SELECT id FROM answers WHERE name="Yes" LIMIT 1), null),

 ((SELECT current_question.id FROM questions as current_question WHERE current_question.name="Are you currently taking medicine for your high blood pressure?" LIMIT 1), 
 	(SELECT id FROM answers WHERE name="No" LIMIT 1), null),

 ((SELECT current_question.id FROM questions as current_question WHERE current_question.name="Are you currently taking medicine for your high blood pressure?" LIMIT 1), 
 	(SELECT id FROM answers WHERE name="Don’t Know/Not Sure" LIMIT 1), null);

#6
 insert into `form_question_answers` (`form_question_id`,`answer_id`,`jump_to_question_id`)
 values((SELECT current_question.id FROM questions as current_question WHERE current_question.name="Are you changing your eating habits to help lower or control your blood pressure?" LIMIT 1), 
 	(SELECT id FROM answers WHERE name="Yes" LIMIT 1), null),
 ((SELECT current_question.id FROM questions as current_question WHERE current_question.name="Are you changing your eating habits to help lower or control your blood pressure?" LIMIT 1), 
 	(SELECT id FROM answers WHERE name="No" LIMIT 1), null),
 ((SELECT current_question.id FROM questions as current_question WHERE current_question.name="Are you changing your eating habits to help lower or control your blood pressure?" LIMIT 1), 
 	(SELECT id FROM answers WHERE name="Don’t Know/Not Sure" LIMIT 1), null);

#7
 insert into `form_question_answers` (`form_question_id`,`answer_id`,`jump_to_question_id`)
 values((SELECT current_question.id FROM questions as current_question WHERE current_question.name="Are you cutting down on salt to help lower or control your blood pressure?" LIMIT 1), 
 	(SELECT id FROM answers WHERE name="Yes" LIMIT 1), null),
 ((SELECT current_question.id FROM questions as current_question WHERE current_question.name="Are you cutting down on salt to help lower or control your blood pressure?" LIMIT 1), 
 	(SELECT id FROM answers WHERE name="No" LIMIT 1), null),
 ((SELECT current_question.id FROM questions as current_question WHERE current_question.name="Are you cutting down on salt to help lower or control your blood pressure?" LIMIT 1), 
 	(SELECT id FROM answers WHERE name="Do Not Use Salt" LIMIT 1), null),
 ((SELECT current_question.id FROM questions as current_question WHERE current_question.name="Are you cutting down on salt to help lower or control your blood pressure?" LIMIT 1), 
 	(SELECT id FROM answers WHERE name="Don’t Know/Not Sure" LIMIT 1), null);

#8
 insert into `form_question_answers` (`form_question_id`,`answer_id`,`jump_to_question_id`)
 values((SELECT current_question.id FROM questions as current_question WHERE current_question.name="Are you reducing alcohol use to help lower or control your blood pressure?" LIMIT 1), 
 	(SELECT id FROM answers WHERE name="Yes" LIMIT 1), null),
 ((SELECT current_question.id FROM questions as current_question WHERE current_question.name="Are you reducing alcohol use to help lower or control your blood pressure?" LIMIT 1), 
 	(SELECT id FROM answers WHERE name="No" LIMIT 1), null),
 ((SELECT current_question.id FROM questions as current_question WHERE current_question.name="Are you reducing alcohol use to help lower or control your blood pressure?" LIMIT 1), 
 	(SELECT id FROM answers WHERE name="Do Not Drink" LIMIT 1), null),
 ((SELECT current_question.id FROM questions as current_question WHERE current_question.name="Are you reducing alcohol use to help lower or control your blood pressure?" LIMIT 1), 
 	(SELECT id FROM answers WHERE name="Don’t Know/Not Sure" LIMIT 1), null);

#9
 insert into `form_question_answers` (`form_question_id`,`answer_id`,`jump_to_question_id`)
 values((SELECT current_question.id FROM questions as current_question WHERE current_question.name="Are you exercising to help lower or control your blood pressure?" LIMIT 1), 
 	(SELECT id FROM answers WHERE name="Yes" LIMIT 1), null),
 ((SELECT current_question.id FROM questions as current_question WHERE current_question.name="Are you exercising to help lower or control your blood pressure?" LIMIT 1), 
 	(SELECT id FROM answers WHERE name="No" LIMIT 1), null),
 ((SELECT current_question.id FROM questions as current_question WHERE current_question.name="Are you exercising to help lower or control your blood pressure?" LIMIT 1), 
 	(SELECT id FROM answers WHERE name="Don’t Know/Not Sure" LIMIT 1), null);

#10
 insert into `form_question_answers` (`form_question_id`,`answer_id`,`jump_to_question_id`)
 values((SELECT current_question.id FROM questions as current_question WHERE current_question.name="Blood cholesterol is a fatty substance found in the blood. Have you ever had your blood
cholesterol checked?" LIMIT 1), 
 	(SELECT id FROM answers WHERE name="Yes" LIMIT 1), null),
 ((SELECT current_question.id FROM questions as current_question WHERE current_question.name="Blood cholesterol is a fatty substance found in the blood. Have you ever had your blood
cholesterol checked?" LIMIT 1), 
 	(SELECT id FROM answers WHERE name="No" LIMIT 1), 
 	(SELECT current_question.id FROM questions as current_question WHERE current_question.name="If so, when were you told that your blood cholesterol was high?" LIMIT 1)),
 ((SELECT current_question.id FROM questions as current_question WHERE current_question.name="Blood cholesterol is a fatty substance found in the blood. Have you ever had your blood
cholesterol checked?" LIMIT 1), 
 	(SELECT id FROM answers WHERE name="Don’t Know/Not Sure" LIMIT 1),
 	 (SELECT current_question.id FROM questions as current_question WHERE current_question.name="If so, when were you told that your blood cholesterol was high?" LIMIT 1)
);

#11
insert into `form_question_answers` (`form_question_id`,`answer_id`,`jump_to_question_id`)
 values((SELECT current_question.id FROM questions as current_question WHERE current_question.name="About how long has it been since you last had your blood cholesterol checked?" LIMIT 1), 
 	(SELECT id FROM answers WHERE name="Within the past year (anytime less than 12 months ago)" LIMIT 1), null),
 ((SELECT current_question.id FROM questions as current_question WHERE current_question.name="About how long has it been since you last had your blood cholesterol checked?" LIMIT 1), 
 	(SELECT id FROM answers WHERE name="Within the past 2 years (more than 1 year ago but less than 2 years ago)" LIMIT 1), null),
 ((SELECT current_question.id FROM questions as current_question WHERE current_question.name="About how long has it been since you last had your blood cholesterol checked?" LIMIT 1), 
 	(SELECT id FROM answers WHERE name="Within the past 5 years (more than 2 years ago but less than 5 years ago)" LIMIT 1), null),
 ((SELECT current_question.id FROM questions as current_question WHERE current_question.name="About how long has it been since you last had your blood cholesterol checked?" LIMIT 1), 
 	(SELECT id FROM answers WHERE name="Five or more years ago" LIMIT 1), null),
 ((SELECT current_question.id FROM questions as current_question WHERE current_question.name="About how long has it been since you last had your blood cholesterol checked?" LIMIT 1), 
 	(SELECT id FROM answers WHERE name="Don’t Know" LIMIT 1), null);

#12
 insert into `form_question_answers` (`form_question_id`,`answer_id`,`jump_to_question_id`)
 values((SELECT current_question.id FROM questions as current_question WHERE current_question.name="The last time you had your blood cholesterol checked, was it normal or high?" LIMIT 1), 
 	(SELECT id FROM answers WHERE name="Normal" LIMIT 1), null),
 ((SELECT current_question.id FROM questions as current_question WHERE current_question.name="The last time you had your blood cholesterol checked, was it normal or high?" LIMIT 1), 
 	(SELECT id FROM answers WHERE name="High" LIMIT 1), null),
 ((SELECT current_question.id FROM questions as current_question WHERE current_question.name="The last time you had your blood cholesterol checked, was it normal or high?" LIMIT 1), 
 	(SELECT id FROM answers WHERE name="Don’t Know/Not Sure" LIMIT 1), null);

#13
insert into `form_question_answers` (`form_question_id`,`answer_id`,`jump_to_question_id`)
 values((SELECT current_question.id FROM questions as current_question WHERE current_question.name="Have you EVER been told by a doctor, nurse or other health professional that your blood
cholesterol is high?" LIMIT 1), 
  (SELECT id FROM answers WHERE name="Yes" LIMIT 1), null),
 ((SELECT current_question.id FROM questions as current_question WHERE current_question.name="Have you EVER been told by a doctor, nurse or other health professional that your blood
cholesterol is high?" LIMIT 1), 
  (SELECT id FROM answers WHERE name="No" LIMIT 1),
  (SELECT current_question.id FROM questions as current_question WHERE current_question.name="If so, when were you told that your blood cholesterol was high?" LIMIT 1)),
 ((SELECT current_question.id FROM questions as current_question WHERE current_question.name="Have you EVER been told by a doctor, nurse or other health professional that your blood
cholesterol is high?" LIMIT 1), 
  (SELECT id FROM answers WHERE name="Don’t Know/Not Sure" LIMIT 1),
  (SELECT current_question.id FROM questions as current_question WHERE current_question.name="If so, when were you told that your blood cholesterol was high?" LIMIT 1)
 );

#14
 insert into `form_question_answers` (`form_question_id`,`answer_id`,`jump_to_question_id`)
 values((SELECT current_question.id FROM questions as current_question WHERE current_question.name="If so, when were you told that your blood cholesterol was high?" LIMIT 1), 
 	(SELECT id FROM answers WHERE name="Within the past year (anytime less than 12 months ago)" LIMIT 1), null),
 ((SELECT current_question.id FROM questions as current_question WHERE current_question.name="If so, when were you told that your blood cholesterol was high?" LIMIT 1), 
 	(SELECT id FROM answers WHERE name="Within the past 2 years (more than 1 year ago but less than 2 years ago)" LIMIT 1), null),
 ((SELECT current_question.id FROM questions as current_question WHERE current_question.name="If so, when were you told that your blood cholesterol was high?" LIMIT 1), 
 	(SELECT id FROM answers WHERE name="Within the past 5 years (more than 2 years ago but less than 5 years ago)" LIMIT 1), null),
 ((SELECT current_question.id FROM questions as current_question WHERE current_question.name="If so, when were you told that your blood cholesterol was high?" LIMIT 1), 
 	(SELECT id FROM answers WHERE name="Five or more years ago" LIMIT 1), null),
 ((SELECT current_question.id FROM questions as current_question WHERE current_question.name="If so, when were you told that your blood cholesterol was high?" LIMIT 1), 
 	(SELECT id FROM answers WHERE name="Don’t Know/Not Sure" LIMIT 1), null);


 #15
 insert into `form_question_answers` (`form_question_id`,`answer_id`,`jump_to_question_id`)
 values((SELECT current_question.id FROM questions as current_question WHERE current_question.name="How many days per week do you do moderate physical activities for at least 30 minutes?" LIMIT 1), 
 	(SELECT id FROM answers WHERE name="Days per week (Please write “0” if the answer is “none.”)" LIMIT 1), null);

#16
 insert into `form_question_answers` (`form_question_id`,`answer_id`,`jump_to_question_id`)
 values((SELECT current_question.id FROM questions as current_question WHERE current_question.name="How many days per week do you do vigorous physical activities for at least 20 minutes?" LIMIT 1), 
 	(SELECT id FROM answers WHERE name="Days per week (Please write “0” if the answer is “none.”)" LIMIT 1), null);

#17
 insert into `form_question_answers` (`form_question_id`,`answer_id`,`jump_to_question_id`)
 values((SELECT current_question.id FROM questions as current_question WHERE current_question.name="Thinking back on the past 30 days, please check yes or no for each statement. You may
choose “yes” for more than one statement." LIMIT 1), 
 	(SELECT id FROM answers WHERE name="Yes" LIMIT 1), null),
 ((SELECT current_question.id FROM questions as current_question WHERE current_question.name="Thinking back on the past 30 days, please check yes or no for each statement. You may
choose “yes” for more than one statement." LIMIT 1), 
 	(SELECT id FROM answers WHERE name="No" LIMIT 1), null);

#18
insert into `form_question_answers` (`form_question_id`,`answer_id`,`jump_to_question_id`)
 values((SELECT current_question.id FROM questions as current_question WHERE current_question.name="Over the past 30 days in general, how many hours per day did you usually spend watching
television, sitting at a computer, playing video games, doing beadwork, or other activities
that don’t require much physical activity?" LIMIT 1), 
  (SELECT id FROM answers WHERE name="1 hour or less" LIMIT 1), null),
 ((SELECT current_question.id FROM questions as current_question WHERE current_question.name="Over the past 30 days in general, how many hours per day did you usually spend watching
television, sitting at a computer, playing video games, doing beadwork, or other activities
that don’t require much physical activity?" LIMIT 1), 
  (SELECT id FROM answers WHERE name="2 hours" LIMIT 1), null),
 ((SELECT current_question.id FROM questions as current_question WHERE current_question.name="Over the past 30 days in general, how many hours per day did you usually spend watching
television, sitting at a computer, playing video games, doing beadwork, or other activities
that don’t require much physical activity?" LIMIT 1), 
  (SELECT id FROM answers WHERE name="3 hours" LIMIT 1), null),
 ((SELECT current_question.id FROM questions as current_question WHERE current_question.name="Over the past 30 days in general, how many hours per day did you usually spend watching
television, sitting at a computer, playing video games, doing beadwork, or other activities
that don’t require much physical activity?" LIMIT 1), 
  (SELECT id FROM answers WHERE name="4 hours" LIMIT 1), null),
 ((SELECT current_question.id FROM questions as current_question WHERE current_question.name="Over the past 30 days in general, how many hours per day did you usually spend watching
television, sitting at a computer, playing video games, doing beadwork, or other activities
that don’t require much physical activity?" LIMIT 1), 
  (SELECT id FROM answers WHERE name="5 hours" LIMIT 1), null),
 ((SELECT current_question.id FROM questions as current_question WHERE current_question.name="Over the past 30 days in general, how many hours per day did you usually spend watching
television, sitting at a computer, playing video games, doing beadwork, or other activities
that don’t require much physical activity?" LIMIT 1), 
  (SELECT id FROM answers WHERE name="6 hours" LIMIT 1), null),
 ((SELECT current_question.id FROM questions as current_question WHERE current_question.name="Over the past 30 days in general, how many hours per day did you usually spend watching
television, sitting at a computer, playing video games, doing beadwork, or other activities
that don’t require much physical activity?" LIMIT 1), 
  (SELECT id FROM answers WHERE name="7 hours" LIMIT 1), null),
 ((SELECT current_question.id FROM questions as current_question WHERE current_question.name="Over the past 30 days in general, how many hours per day did you usually spend watching
television, sitting at a computer, playing video games, doing beadwork, or other activities
that don’t require much physical activity?" LIMIT 1), 
  (SELECT id FROM answers WHERE name="8 hours" LIMIT 1), null),
 ((SELECT current_question.id FROM questions as current_question WHERE current_question.name="Over the past 30 days in general, how many hours per day did you usually spend watching
television, sitting at a computer, playing video games, doing beadwork, or other activities
that don’t require much physical activity?" LIMIT 1), 
  (SELECT id FROM answers WHERE name="9 hours" LIMIT 1), null),
 ((SELECT current_question.id FROM questions as current_question WHERE current_question.name="Over the past 30 days in general, how many hours per day did you usually spend watching
television, sitting at a computer, playing video games, doing beadwork, or other activities
that don’t require much physical activity?" LIMIT 1), 
  (SELECT id FROM answers WHERE name="10 hours or more" LIMIT 1), null);


#19
 insert into `form_question_answers` (`form_question_id`,`answer_id`,`jump_to_question_id`)
 values((SELECT current_question.id FROM questions as current_question WHERE current_question.name="Do you plan to increase the amount of physical activity you get every week?" LIMIT 1), 
 	(SELECT id FROM answers WHERE name="Yes, I intend to in the next 30 days" LIMIT 1), null),
 ((SELECT current_question.id FROM questions as current_question WHERE current_question.name="Do you plan to increase the amount of physical activity you get every week?" LIMIT 1), 
 	(SELECT id FROM answers WHERE name="Yes, I intend to in the next 6 months" LIMIT 1), null),
 ((SELECT current_question.id FROM questions as current_question WHERE current_question.name="Do you plan to increase the amount of physical activity you get every week?" LIMIT 1), 
 	(SELECT id FROM answers WHERE name="No, and I do not intend to in the next 6 months" LIMIT 1), null);

#20
 insert into `form_question_answers` (`form_question_id`,`answer_id`,`jump_to_question_id`)
 values((SELECT current_question.id FROM questions as current_question WHERE current_question.name="Please think about what you usually ate or drank during the past 30 days. Read each item
carefully and indicate one response for each. How often did you..." LIMIT 1), 
 	(SELECT id FROM answers WHERE name="More than once a day" LIMIT 1), null),
 ((SELECT current_question.id FROM questions as current_question WHERE current_question.name="Please think about what you usually ate or drank during the past 30 days. Read each item
carefully and indicate one response for each. How often did you..." LIMIT 1), 
 	(SELECT id FROM answers WHERE name="About once a day" LIMIT 1), null),
 ((SELECT current_question.id FROM questions as current_question WHERE current_question.name="Please think about what you usually ate or drank during the past 30 days. Read each item
carefully and indicate one response for each. How often did you..." LIMIT 1), 
 	(SELECT id FROM answers WHERE name="2-3 times a week" LIMIT 1), null),
 ((SELECT current_question.id FROM questions as current_question WHERE current_question.name="Please think about what you usually ate or drank during the past 30 days. Read each item
carefully and indicate one response for each. How often did you..." LIMIT 1), 
 	(SELECT id FROM answers WHERE name="About once a week" LIMIT 1), null),
 ((SELECT current_question.id FROM questions as current_question WHERE current_question.name="Please think about what you usually ate or drank during the past 30 days. Read each item
carefully and indicate one response for each. How often did you..." LIMIT 1), 
 	(SELECT id FROM answers WHERE name="1-3 times a month" LIMIT 1), null),
 ((SELECT current_question.id FROM questions as current_question WHERE current_question.name="Please think about what you usually ate or drank during the past 30 days. Read each item
carefully and indicate one response for each. How often did you..." LIMIT 1), 
 	(SELECT id FROM answers WHERE name="Less than once a month" LIMIT 1), null);

#21
 insert into `form_question_answers` (`form_question_id`,`answer_id`,`jump_to_question_id`)
 values((SELECT current_question.id FROM questions as current_question WHERE current_question.name="What kind of milk did you usually use? (Pick the one that you used most often in the past 30
days.)" LIMIT 1), 
 	(SELECT id FROM answers WHERE name="Whole milk" LIMIT 1), null),
 ((SELECT current_question.id FROM questions as current_question WHERE current_question.name="What kind of milk did you usually use? (Pick the one that you used most often in the past 30
days.)" LIMIT 1), 
 	(SELECT id FROM answers WHERE name="2% fat" LIMIT 1), null),
 ((SELECT current_question.id FROM questions as current_question WHERE current_question.name="What kind of milk did you usually use? (Pick the one that you used most often in the past 30
days.)" LIMIT 1), 
 	(SELECT id FROM answers WHERE name="1% fat" LIMIT 1), null),
 ((SELECT current_question.id FROM questions as current_question WHERE current_question.name="What kind of milk did you usually use? (Pick the one that you used most often in the past 30
days.)" LIMIT 1), 
 	(SELECT id FROM answers WHERE name="1⁄2% fat" LIMIT 1), null),
 ((SELECT current_question.id FROM questions as current_question WHERE current_question.name="What kind of milk did you usually use? (Pick the one that you used most often in the past 30
days.)" LIMIT 1), 
 	(SELECT id FROM answers WHERE name="Non-fat or skim" LIMIT 1), null),
 ((SELECT current_question.id FROM questions as current_question WHERE current_question.name="What kind of milk did you usually use? (Pick the one that you used most often in the past 30
days.)" LIMIT 1), 
 	(SELECT id FROM answers WHERE name="Soy/lactose free" LIMIT 1), null),
 ((SELECT current_question.id FROM questions as current_question WHERE current_question.name="What kind of milk did you usually use? (Pick the one that you used most often in the past 30
days.)" LIMIT 1), 
 	(SELECT id FROM answers WHERE name="Canned milk" LIMIT 1), null),
 ((SELECT current_question.id FROM questions as current_question WHERE current_question.name="What kind of milk did you usually use? (Pick the one that you used most often in the past 30
days.)" LIMIT 1), 
 	(SELECT id FROM answers WHERE name="Powdered milk" LIMIT 1), null),
 ((SELECT current_question.id FROM questions as current_question WHERE current_question.name="What kind of milk did you usually use? (Pick the one that you used most often in the past 30
days.)" LIMIT 1), 
 	(SELECT id FROM answers WHERE name="Did not use milk in past 30 days" LIMIT 1), null);

#22
 insert into `form_question_answers` (`form_question_id`,`answer_id`,`jump_to_question_id`)
 values((SELECT current_question.id FROM questions as current_question WHERE current_question.name="What kinds of fat or oil did you usually use in cooking in the past 30 days (if more than one,
choose the one used most often)?" LIMIT 1), 
 	(SELECT id FROM answers WHERE name="Pam/cooking spray" LIMIT 1), null),
 ((SELECT current_question.id FROM questions as current_question WHERE current_question.name="What kinds of fat or oil did you usually use in cooking in the past 30 days (if more than one,
choose the one used most often)?" LIMIT 1), 
 	(SELECT id FROM answers WHERE name="Stick margarine/butter/margarine blend/soft-tub" LIMIT 1), null),
 ((SELECT current_question.id FROM questions as current_question WHERE current_question.name="What kinds of fat or oil did you usually use in cooking in the past 30 days (if more than one,
choose the one used most often)?" LIMIT 1), 
 	(SELECT id FROM answers WHERE name="Lard, fatback, bacon fat" LIMIT 1), null),
 ((SELECT current_question.id FROM questions as current_question WHERE current_question.name="What kinds of fat or oil did you usually use in cooking in the past 30 days (if more than one,
choose the one used most often)?" LIMIT 1), 
 	(SELECT id FROM answers WHERE name="Crisco" LIMIT 1), null),
 ((SELECT current_question.id FROM questions as current_question WHERE current_question.name="What kinds of fat or oil did you usually use in cooking in the past 30 days (if more than one,
choose the one used most often)?" LIMIT 1), 
 	(SELECT id FROM answers WHERE name="Vegetable oil/olive oil/corn oil" LIMIT 1), null);


#23
 insert into `form_question_answers` (`form_question_id`,`answer_id`,`jump_to_question_id`)
 values((SELECT current_question.id FROM questions as current_question WHERE current_question.name="How often do you do the following things? Mark your answer with an X." LIMIT 1), 
 	(SELECT id FROM answers WHERE name="Never" LIMIT 1), null),
 ((SELECT current_question.id FROM questions as current_question WHERE current_question.name="How often do you do the following things? Mark your answer with an X." LIMIT 1), 
 	(SELECT id FROM answers WHERE name="Sometimes" LIMIT 1), null),
 ((SELECT current_question.id FROM questions as current_question WHERE current_question.name="How often do you do the following things? Mark your answer with an X." LIMIT 1), 
 	(SELECT id FROM answers WHERE name="Most of the Time" LIMIT 1), null),
 ((SELECT current_question.id FROM questions as current_question WHERE current_question.name="How often do you do the following things? Mark your answer with an X." LIMIT 1), 
 	(SELECT id FROM answers WHERE name="All of the Time" LIMIT 1), null),
 ((SELECT current_question.id FROM questions as current_question WHERE current_question.name="How often do you do the following things? Mark your answer with an X." LIMIT 1), 
 	(SELECT id FROM answers WHERE name="Does Not Apply" LIMIT 1), null);

#24
 insert into `form_question_answers` (`form_question_id`,`answer_id`,`jump_to_question_id`)
 values((SELECT current_question.id FROM questions as current_question WHERE current_question.name="Are you able to buy or grow low-cost vegetables?" LIMIT 1), 
 	(SELECT id FROM answers WHERE name="Yes" LIMIT 1), null),
 ((SELECT current_question.id FROM questions as current_question WHERE current_question.name="Are you able to buy or grow low-cost vegetables?" LIMIT 1), 
 	(SELECT id FROM answers WHERE name="No" LIMIT 1), null);

#25
 insert into `form_question_answers` (`form_question_id`,`answer_id`,`jump_to_question_id`)
 values((SELECT current_question.id FROM questions as current_question WHERE current_question.name="In the future, do you intend to reduce the amount of fat you eat so it is lower than it is now?" LIMIT 1), 
 	(SELECT id FROM answers WHERE name="Yes, and I intend to in the next 30 days" LIMIT 1), null),
 ((SELECT current_question.id FROM questions as current_question WHERE current_question.name="In the future, do you intend to reduce the amount of fat you eat so it is lower than it is now?" LIMIT 1), 
 	(SELECT id FROM answers WHERE name="Yes, and I intend to in the next 6 months" LIMIT 1), null),
 ((SELECT current_question.id FROM questions as current_question WHERE current_question.name="In the future, do you intend to reduce the amount of fat you eat so it is lower than it is now?" LIMIT 1), 
 	(SELECT id FROM answers WHERE name="No, and I do not intend to in the next 6 months" LIMIT 1), null);


 #26
 insert into `form_question_answers` (`form_question_id`,`answer_id`,`jump_to_question_id`)
 values((SELECT current_question.id FROM questions as current_question WHERE current_question.name="Do you smoke cigarettes now? (For these questions, we are not interested in the tobacco you may smoke for ceremonial use.)" LIMIT 1), 
 	(SELECT id FROM answers WHERE name="Yes" LIMIT 1), null),
 ((SELECT current_question.id FROM questions as current_question WHERE current_question.name="Do you smoke cigarettes now? (For these questions, we are not interested in the tobacco you may smoke for ceremonial use.)" LIMIT 1), 
 	(SELECT id FROM answers WHERE name="No" LIMIT 1), (SELECT current_question.id FROM questions as current_question WHERE current_question.name="Do you think pain or discomfort in the jaw, neck, or back are symptoms of a heart attack?" LIMIT 1));

 #27
 insert into `form_question_answers` (`form_question_id`,`answer_id`,`jump_to_question_id`)
 values((SELECT current_question.id FROM questions as current_question WHERE current_question.name="Thinking over the past 30 days, including today, how many days during this time did you
smoke?" LIMIT 1), 
 	(SELECT id FROM answers WHERE name="days" LIMIT 1), null);

 #28
 insert into `form_question_answers` (`form_question_id`,`answer_id`,`jump_to_question_id`)
 values((SELECT current_question.id FROM questions as current_question WHERE current_question.name="About how many cigarettes a day do you now smoke?" LIMIT 1), 
 	(SELECT id FROM answers WHERE name="cigarettes a day" LIMIT 1), null);

#29
 insert into `form_question_answers` (`form_question_id`,`answer_id`,`jump_to_question_id`)
 values((SELECT current_question.id FROM questions as current_question WHERE current_question.name="About how many years have you been smoking?" LIMIT 1), 
 	(SELECT id FROM answers WHERE name="years" LIMIT 1), null);

 #30
 insert into `form_question_answers` (`form_question_id`,`answer_id`,`jump_to_question_id`)
 values((SELECT current_question.id FROM questions as current_question WHERE current_question.name="In the past year, how many times have you quit smoking for at least 24 hours?" LIMIT 1), 
 	(SELECT id FROM answers WHERE name="times" LIMIT 1), null);

 #31
 insert into `form_question_answers` (`form_question_id`,`answer_id`,`jump_to_question_id`)
 values((SELECT current_question.id FROM questions as current_question WHERE current_question.name="Are you seriously thinking of quitting smoking?" LIMIT 1), 
 	(SELECT id FROM answers WHERE name="Yes, within the next 30 days" LIMIT 1), null),
 ((SELECT current_question.id FROM questions as current_question WHERE current_question.name="Are you seriously thinking of quitting smoking?" LIMIT 1), 
 	(SELECT id FROM answers WHERE name="Yes, within the next 6 months" LIMIT 1), null),
 ((SELECT current_question.id FROM questions as current_question WHERE current_question.name="Are you seriously thinking of quitting smoking?" LIMIT 1), 
 	(SELECT id FROM answers WHERE name="No, not thinking of quitting" LIMIT 1), null);

  #32
 insert into `form_question_answers` (`form_question_id`,`answer_id`,`jump_to_question_id`)
 values((SELECT current_question.id FROM questions as current_question WHERE current_question.name="Do you think pain or discomfort in the jaw, neck, or back are symptoms of a heart attack?" LIMIT 1), 
 	(SELECT id FROM answers WHERE name="Yes" LIMIT 1), null),
 ((SELECT current_question.id FROM questions as current_question WHERE current_question.name="Do you think pain or discomfort in the jaw, neck, or back are symptoms of a heart attack?" LIMIT 1), 
 	(SELECT id FROM answers WHERE name="No" LIMIT 1), null),
 ((SELECT current_question.id FROM questions as current_question WHERE current_question.name="Do you think pain or discomfort in the jaw, neck, or back are symptoms of a heart attack?" LIMIT 1), 
 	(SELECT id FROM answers WHERE name="Don’t Know/Not Sure" LIMIT 1), null);

  #33
 insert into `form_question_answers` (`form_question_id`,`answer_id`,`jump_to_question_id`)
 values((SELECT current_question.id FROM questions as current_question WHERE current_question.name="Do you think feeling weak, lightheaded, or faint are symptoms of a heart attack?" LIMIT 1), 
 	(SELECT id FROM answers WHERE name="Yes" LIMIT 1), null),
 ((SELECT current_question.id FROM questions as current_question WHERE current_question.name="Do you think feeling weak, lightheaded, or faint are symptoms of a heart attack?" LIMIT 1), 
 	(SELECT id FROM answers WHERE name="No" LIMIT 1), null),
 ((SELECT current_question.id FROM questions as current_question WHERE current_question.name="Do you think feeling weak, lightheaded, or faint are symptoms of a heart attack?" LIMIT 1), 
 	(SELECT id FROM answers WHERE name="Don’t Know/Not Sure" LIMIT 1), null);

  #34
 insert into `form_question_answers` (`form_question_id`,`answer_id`,`jump_to_question_id`)
 values((SELECT current_question.id FROM questions as current_question WHERE current_question.name="Do you think swelling of the feet and legs is a symptom of a heart attack?" LIMIT 1), 
 	(SELECT id FROM answers WHERE name="Yes" LIMIT 1), null),
 ((SELECT current_question.id FROM questions as current_question WHERE current_question.name="Do you think swelling of the feet and legs is a symptom of a heart attack?" LIMIT 1), 
 	(SELECT id FROM answers WHERE name="No" LIMIT 1), null),
 ((SELECT current_question.id FROM questions as current_question WHERE current_question.name="Do you think swelling of the feet and legs is a symptom of a heart attack?" LIMIT 1), 
 	(SELECT id FROM answers WHERE name="Don’t Know/Not Sure" LIMIT 1), null);


 #35
 insert into `form_question_answers` (`form_question_id`,`answer_id`,`jump_to_question_id`)
 values((SELECT current_question.id FROM questions as current_question WHERE current_question.name="Do you think chest pain or discomfort are symptoms of a heart attack?" LIMIT 1), 
 	(SELECT id FROM answers WHERE name="Yes" LIMIT 1), null),
 ((SELECT current_question.id FROM questions as current_question WHERE current_question.name="Do you think chest pain or discomfort are symptoms of a heart attack?" LIMIT 1), 
 	(SELECT id FROM answers WHERE name="No" LIMIT 1), null),
 ((SELECT current_question.id FROM questions as current_question WHERE current_question.name="Do you think chest pain or discomfort are symptoms of a heart attack?" LIMIT 1), 
 	(SELECT id FROM answers WHERE name="Don’t Know/Not Sure" LIMIT 1), null);

 #36
 insert into `form_question_answers` (`form_question_id`,`answer_id`,`jump_to_question_id`)
 values((SELECT current_question.id FROM questions as current_question WHERE current_question.name="Do you think sudden trouble seeing in one or both eyes is a symptom of a heart attack?" LIMIT 1), 
 	(SELECT id FROM answers WHERE name="Yes" LIMIT 1), null),
 ((SELECT current_question.id FROM questions as current_question WHERE current_question.name="Do you think sudden trouble seeing in one or both eyes is a symptom of a heart attack?" LIMIT 1), 
 	(SELECT id FROM answers WHERE name="No" LIMIT 1), null),
 ((SELECT current_question.id FROM questions as current_question WHERE current_question.name="Do you think sudden trouble seeing in one or both eyes is a symptom of a heart attack?" LIMIT 1), 
 	(SELECT id FROM answers WHERE name="Don’t Know/Not Sure" LIMIT 1), null);

 #37
 insert into `form_question_answers` (`form_question_id`,`answer_id`,`jump_to_question_id`)
 values((SELECT current_question.id FROM questions as current_question WHERE current_question.name="Do you think tingling in the fingers and toes are symptoms of a heart attack?" LIMIT 1), 
 	(SELECT id FROM answers WHERE name="Yes" LIMIT 1), null),
 ((SELECT current_question.id FROM questions as current_question WHERE current_question.name="Do you think tingling in the fingers and toes are symptoms of a heart attack?" LIMIT 1), 
 	(SELECT id FROM answers WHERE name="No" LIMIT 1), null),
 ((SELECT current_question.id FROM questions as current_question WHERE current_question.name="Do you think tingling in the fingers and toes are symptoms of a heart attack?" LIMIT 1), 
 	(SELECT id FROM answers WHERE name="Don’t Know/Not Sure" LIMIT 1), null);

 #38
 insert into `form_question_answers` (`form_question_id`,`answer_id`,`jump_to_question_id`)
 values((SELECT current_question.id FROM questions as current_question WHERE current_question.name="Do you think pain or discomfort in the arms or shoulder are symptoms of a heart attack?" LIMIT 1), 
 	(SELECT id FROM answers WHERE name="Yes" LIMIT 1), null),
 ((SELECT current_question.id FROM questions as current_question WHERE current_question.name="Do you think pain or discomfort in the arms or shoulder are symptoms of a heart attack?" LIMIT 1), 
 	(SELECT id FROM answers WHERE name="No" LIMIT 1), null),
 ((SELECT current_question.id FROM questions as current_question WHERE current_question.name="Do you think pain or discomfort in the arms or shoulder are symptoms of a heart attack?" LIMIT 1), 
 	(SELECT id FROM answers WHERE name="Don’t Know/Not Sure" LIMIT 1), null);

 #39
 insert into `form_question_answers` (`form_question_id`,`answer_id`,`jump_to_question_id`)
 values((SELECT current_question.id FROM questions as current_question WHERE current_question.name="Do you think shortness of breath is a symptom of a heart attack?" LIMIT 1), 
 	(SELECT id FROM answers WHERE name="Yes" LIMIT 1), null),
 ((SELECT current_question.id FROM questions as current_question WHERE current_question.name="Do you think shortness of breath is a symptom of a heart attack?" LIMIT 1), 
 	(SELECT id FROM answers WHERE name="No" LIMIT 1), null),
 ((SELECT current_question.id FROM questions as current_question WHERE current_question.name="Do you think shortness of breath is a symptom of a heart attack?" LIMIT 1), 
 	(SELECT id FROM answers WHERE name="Don’t Know/Not Sure" LIMIT 1), null);

 #40
 insert into `form_question_answers` (`form_question_id`,`answer_id`,`jump_to_question_id`)
 values((SELECT current_question.id FROM questions as current_question WHERE current_question.name="Do you think sudden confusion or trouble speaking are symptoms of a stroke?" LIMIT 1), 
 	(SELECT id FROM answers WHERE name="Yes" LIMIT 1), null),
 ((SELECT current_question.id FROM questions as current_question WHERE current_question.name="Do you think sudden confusion or trouble speaking are symptoms of a stroke?" LIMIT 1), 
 	(SELECT id FROM answers WHERE name="No" LIMIT 1), null),
 ((SELECT current_question.id FROM questions as current_question WHERE current_question.name="Do you think sudden confusion or trouble speaking are symptoms of a stroke?" LIMIT 1), 
 	(SELECT id FROM answers WHERE name="Don’t Know/Not Sure" LIMIT 1), null);

 #41
 insert into `form_question_answers` (`form_question_id`,`answer_id`,`jump_to_question_id`)
 values((SELECT current_question.id FROM questions as current_question WHERE current_question.name="Do you think sudden numbness or weakness of face, arm, or leg, especially on one side, are symptoms of a stroke?" LIMIT 1), 
 	(SELECT id FROM answers WHERE name="Yes" LIMIT 1), null),
 ((SELECT current_question.id FROM questions as current_question WHERE current_question.name="Do you think sudden numbness or weakness of face, arm, or leg, especially on one side, are symptoms of a stroke?" LIMIT 1), 
 	(SELECT id FROM answers WHERE name="No" LIMIT 1), null),
 ((SELECT current_question.id FROM questions as current_question WHERE current_question.name="Do you think sudden numbness or weakness of face, arm, or leg, especially on one side, are symptoms of a stroke?" LIMIT 1), 
 	(SELECT id FROM answers WHERE name="Don’t Know/Not Sure" LIMIT 1), null);

 #42
 insert into `form_question_answers` (`form_question_id`,`answer_id`,`jump_to_question_id`)
 values((SELECT current_question.id FROM questions as current_question WHERE current_question.name="Do you think feeling sick to your stomach is a symptom of a stroke?" LIMIT 1), 
 	(SELECT id FROM answers WHERE name="Yes" LIMIT 1), null),
 ((SELECT current_question.id FROM questions as current_question WHERE current_question.name="Do you think feeling sick to your stomach is a symptom of a stroke?" LIMIT 1), 
 	(SELECT id FROM answers WHERE name="No" LIMIT 1), null),
 ((SELECT current_question.id FROM questions as current_question WHERE current_question.name="Do you think feeling sick to your stomach is a symptom of a stroke?" LIMIT 1), 
 	(SELECT id FROM answers WHERE name="Don’t Know/Not Sure" LIMIT 1), null);

 #43
 insert into `form_question_answers` (`form_question_id`,`answer_id`,`jump_to_question_id`)
 values((SELECT current_question.id FROM questions as current_question WHERE current_question.name="Do you think sharp pain in the jaw or mouth is a symptom of a stroke?" LIMIT 1), 
 	(SELECT id FROM answers WHERE name="Yes" LIMIT 1), null),
 ((SELECT current_question.id FROM questions as current_question WHERE current_question.name="Do you think sharp pain in the jaw or mouth is a symptom of a stroke?" LIMIT 1), 
 	(SELECT id FROM answers WHERE name="No" LIMIT 1), null),
 ((SELECT current_question.id FROM questions as current_question WHERE current_question.name="Do you think sharp pain in the jaw or mouth is a symptom of a stroke?" LIMIT 1), 
 	(SELECT id FROM answers WHERE name="Don’t Know/Not Sure" LIMIT 1), null);

 #44
 insert into `form_question_answers` (`form_question_id`,`answer_id`,`jump_to_question_id`)
 values((SELECT current_question.id FROM questions as current_question WHERE current_question.name="Do you think sudden trouble seeing in one or both eyes is a symptom of a stroke?" LIMIT 1), 
 	(SELECT id FROM answers WHERE name="Yes" LIMIT 1), null),
 ((SELECT current_question.id FROM questions as current_question WHERE current_question.name="Do you think sudden trouble seeing in one or both eyes is a symptom of a stroke?" LIMIT 1), 
 	(SELECT id FROM answers WHERE name="No" LIMIT 1), null),
 ((SELECT current_question.id FROM questions as current_question WHERE current_question.name="Do you think sudden trouble seeing in one or both eyes is a symptom of a stroke?" LIMIT 1), 
 	(SELECT id FROM answers WHERE name="Don’t Know/Not Sure" LIMIT 1), null);

 #45
 insert into `form_question_answers` (`form_question_id`,`answer_id`,`jump_to_question_id`)
 values((SELECT current_question.id FROM questions as current_question WHERE current_question.name="Do you think sudden chest pain or discomfort are symptoms of a stroke?" LIMIT 1), 
 	(SELECT id FROM answers WHERE name="Yes" LIMIT 1), null),
 ((SELECT current_question.id FROM questions as current_question WHERE current_question.name="Do you think sudden chest pain or discomfort are symptoms of a stroke?" LIMIT 1), 
 	(SELECT id FROM answers WHERE name="No" LIMIT 1), null),
 ((SELECT current_question.id FROM questions as current_question WHERE current_question.name="Do you think sudden chest pain or discomfort are symptoms of a stroke?" LIMIT 1), 
 	(SELECT id FROM answers WHERE name="Don’t Know/Not Sure" LIMIT 1), null);

 #46
 insert into `form_question_answers` (`form_question_id`,`answer_id`,`jump_to_question_id`)
 values((SELECT current_question.id FROM questions as current_question WHERE current_question.name="Do you think sudden trouble walking, dizziness, or loss of balance are symptoms of a stroke?" LIMIT 1), 
 	(SELECT id FROM answers WHERE name="Yes" LIMIT 1), null),
 ((SELECT current_question.id FROM questions as current_question WHERE current_question.name="Do you think sudden trouble walking, dizziness, or loss of balance are symptoms of a stroke?" LIMIT 1), 
 	(SELECT id FROM answers WHERE name="No" LIMIT 1), null),
 ((SELECT current_question.id FROM questions as current_question WHERE current_question.name="Do you think sudden trouble walking, dizziness, or loss of balance are symptoms of a stroke?" LIMIT 1), 
 	(SELECT id FROM answers WHERE name="Don’t Know/Not Sure" LIMIT 1), null);

 #47
 insert into `form_question_answers` (`form_question_id`,`answer_id`,`jump_to_question_id`)
 values((SELECT current_question.id FROM questions as current_question WHERE current_question.name="Do you think severe headache with no known cause is a symptom of a stroke?" LIMIT 1), 
 	(SELECT id FROM answers WHERE name="Yes" LIMIT 1), null),
 ((SELECT current_question.id FROM questions as current_question WHERE current_question.name="Do you think severe headache with no known cause is a symptom of a stroke?" LIMIT 1), 
 	(SELECT id FROM answers WHERE name="No" LIMIT 1), null),
 ((SELECT current_question.id FROM questions as current_question WHERE current_question.name="Do you think severe headache with no known cause is a symptom of a stroke?" LIMIT 1), 
 	(SELECT id FROM answers WHERE name="Don’t Know/Not Sure" LIMIT 1), null);

 #48
 insert into `form_question_answers` (`form_question_id`,`answer_id`,`jump_to_question_id`)
 values((SELECT current_question.id FROM questions as current_question WHERE current_question.name="If you thought someone was having a heart attack or a stroke, what is the first thing you would do?" LIMIT 1), 
 	(SELECT id FROM answers WHERE name="Take them to the hospital" LIMIT 1), null),
 ((SELECT current_question.id FROM questions as current_question WHERE current_question.name="If you thought someone was having a heart attack or a stroke, what is the first thing you would do?" LIMIT 1), 
 	(SELECT id FROM answers WHERE name="Tell them to call their doctor" LIMIT 1), null),
 ((SELECT current_question.id FROM questions as current_question WHERE current_question.name="If you thought someone was having a heart attack or a stroke, what is the first thing you would do?" LIMIT 1), 
 	(SELECT id FROM answers WHERE name="Call 911" LIMIT 1), null),
 ((SELECT current_question.id FROM questions as current_question WHERE current_question.name="If you thought someone was having a heart attack or a stroke, what is the first thing you would do?" LIMIT 1), 
 	(SELECT id FROM answers WHERE name="Call their spouse or a family member" LIMIT 1), null),
 ((SELECT current_question.id FROM questions as current_question WHERE current_question.name="If you thought someone was having a heart attack or a stroke, what is the first thing you would do?" LIMIT 1), 
 	(SELECT id FROM answers WHERE name="Do something else" LIMIT 1), null),
 ((SELECT current_question.id FROM questions as current_question WHERE current_question.name="If you thought someone was having a heart attack or a stroke, what is the first thing you would do?" LIMIT 1), 
 	(SELECT id FROM answers WHERE name="Don’t Know/Not Sure" LIMIT 1), null);


 #49
 insert into `form_question_answers` (`form_question_id`,`answer_id`,`jump_to_question_id`)
 values((SELECT current_question.id FROM questions as current_question WHERE current_question.name="Can a large waist (>35 inches for women or >40 inches for men) increase your risk of heart disease?" LIMIT 1), 
 	(SELECT id FROM answers WHERE name="Yes" LIMIT 1), null),
 ((SELECT current_question.id FROM questions as current_question WHERE current_question.name="Can a large waist (>35 inches for women or >40 inches for men) increase your risk of heart disease?" LIMIT 1), 
 	(SELECT id FROM answers WHERE name="No" LIMIT 1), null),
 ((SELECT current_question.id FROM questions as current_question WHERE current_question.name="Can a large waist (>35 inches for women or >40 inches for men) increase your risk of heart disease?" LIMIT 1), 
 	(SELECT id FROM answers WHERE name="Don’t Know/Not Sure" LIMIT 1), null);

 #50
 insert into `form_question_answers` (`form_question_id`,`answer_id`,`jump_to_question_id`)
 values((SELECT current_question.id FROM questions as current_question WHERE current_question.name="Can the Body Mass Index (BMI) Chart tell you if you are overweight?" LIMIT 1), 
 	(SELECT id FROM answers WHERE name="Yes" LIMIT 1), null),
 ((SELECT current_question.id FROM questions as current_question WHERE current_question.name="Can the Body Mass Index (BMI) Chart tell you if you are overweight?" LIMIT 1), 
 	(SELECT id FROM answers WHERE name="No" LIMIT 1), null),
 ((SELECT current_question.id FROM questions as current_question WHERE current_question.name="Can the Body Mass Index (BMI) Chart tell you if you are overweight?" LIMIT 1), 
 	(SELECT id FROM answers WHERE name="Don’t Know/Not Sure" LIMIT 1), null);

 #51
 insert into `form_question_answers` (`form_question_id`,`answer_id`,`jump_to_question_id`)
 values((SELECT current_question.id FROM questions as current_question WHERE current_question.name="Does your liver make all the cholesterol your body needs to keep you healthy?" LIMIT 1), 
 	(SELECT id FROM answers WHERE name="Yes" LIMIT 1), null),
 ((SELECT current_question.id FROM questions as current_question WHERE current_question.name="Does your liver make all the cholesterol your body needs to keep you healthy?" LIMIT 1), 
 	(SELECT id FROM answers WHERE name="No" LIMIT 1), null),
 ((SELECT current_question.id FROM questions as current_question WHERE current_question.name="Does your liver make all the cholesterol your body needs to keep you healthy?" LIMIT 1), 
 	(SELECT id FROM answers WHERE name="Don’t Know/Not Sure" LIMIT 1), null);

 #52
 insert into `form_question_answers` (`form_question_id`,`answer_id`,`jump_to_question_id`)
 values((SELECT current_question.id FROM questions as current_question WHERE current_question.name="Can eating foods that are high in sodium increase your risk of high blood pressure?" LIMIT 1), 
 	(SELECT id FROM answers WHERE name="Yes" LIMIT 1), null),
 ((SELECT current_question.id FROM questions as current_question WHERE current_question.name="Can eating foods that are high in sodium increase your risk of high blood pressure?" LIMIT 1), 
 	(SELECT id FROM answers WHERE name="No" LIMIT 1), null),
 ((SELECT current_question.id FROM questions as current_question WHERE current_question.name="Can eating foods that are high in sodium increase your risk of high blood pressure?" LIMIT 1), 
 	(SELECT id FROM answers WHERE name="Don’t Know/Not Sure" LIMIT 1), null);

 #53
 insert into `form_question_answers` (`form_question_id`,`answer_id`,`jump_to_question_id`)
 values((SELECT current_question.id FROM questions as current_question WHERE current_question.name="Does lard have a low amount of saturated fat?" LIMIT 1), 
 	(SELECT id FROM answers WHERE name="Yes" LIMIT 1), null),
 ((SELECT current_question.id FROM questions as current_question WHERE current_question.name="Does lard have a low amount of saturated fat?" LIMIT 1), 
 	(SELECT id FROM answers WHERE name="No" LIMIT 1), null),
 ((SELECT current_question.id FROM questions as current_question WHERE current_question.name="Does lard have a low amount of saturated fat?" LIMIT 1), 
 	(SELECT id FROM answers WHERE name="Don’t Know/Not Sure" LIMIT 1), null);

 #54
 insert into `form_question_answers` (`form_question_id`,`answer_id`,`jump_to_question_id`)
 values((SELECT current_question.id FROM questions as current_question WHERE current_question.name="Can eating too much saturated fat and trans fat raise your cholesterol level?" LIMIT 1), 
 	(SELECT id FROM answers WHERE name="Yes" LIMIT 1), null),
 ((SELECT current_question.id FROM questions as current_question WHERE current_question.name="Can eating too much saturated fat and trans fat raise your cholesterol level?" LIMIT 1), 
 	(SELECT id FROM answers WHERE name="No" LIMIT 1), null),
 ((SELECT current_question.id FROM questions as current_question WHERE current_question.name="Can eating too much saturated fat and trans fat raise your cholesterol level?" LIMIT 1), 
 	(SELECT id FROM answers WHERE name="Don’t Know/Not Sure" LIMIT 1), null);

 #55
 insert into `form_question_answers` (`form_question_id`,`answer_id`,`jump_to_question_id`)
 values((SELECT current_question.id FROM questions as current_question WHERE current_question.name="Is a blood pressure of 140/90 mmHg considered high?" LIMIT 1), 
 	(SELECT id FROM answers WHERE name="Yes" LIMIT 1), null),
 ((SELECT current_question.id FROM questions as current_question WHERE current_question.name="Is a blood pressure of 140/90 mmHg considered high?" LIMIT 1), 
 	(SELECT id FROM answers WHERE name="No" LIMIT 1), null),
 ((SELECT current_question.id FROM questions as current_question WHERE current_question.name="Is a blood pressure of 140/90 mmHg considered high?" LIMIT 1), 
 	(SELECT id FROM answers WHERE name="Don’t Know/Not Sure" LIMIT 1), null);

 #56
 insert into `form_question_answers` (`form_question_id`,`answer_id`,`jump_to_question_id`)
 values((SELECT current_question.id FROM questions as current_question WHERE current_question.name="Can being overweight or obese put you at risk for developing high blood cholesterol?" LIMIT 1), 
 	(SELECT id FROM answers WHERE name="Yes" LIMIT 1), null),
 ((SELECT current_question.id FROM questions as current_question WHERE current_question.name="Can being overweight or obese put you at risk for developing high blood cholesterol?" LIMIT 1), 
 	(SELECT id FROM answers WHERE name="No" LIMIT 1), null),
 ((SELECT current_question.id FROM questions as current_question WHERE current_question.name="Can being overweight or obese put you at risk for developing high blood cholesterol?" LIMIT 1), 
 	(SELECT id FROM answers WHERE name="Don’t Know/Not Sure" LIMIT 1), null);

 #57
 insert into `form_question_answers` (`form_question_id`,`answer_id`,`jump_to_question_id`)
 values((SELECT current_question.id FROM questions as current_question WHERE current_question.name="Is being physically active a way to reduce your risk for heart disease?" LIMIT 1), 
 	(SELECT id FROM answers WHERE name="Yes" LIMIT 1), null),
 ((SELECT current_question.id FROM questions as current_question WHERE current_question.name="Is being physically active a way to reduce your risk for heart disease?" LIMIT 1), 
 	(SELECT id FROM answers WHERE name="No" LIMIT 1), null),
 ((SELECT current_question.id FROM questions as current_question WHERE current_question.name="Is being physically active a way to reduce your risk for heart disease?" LIMIT 1), 
 	(SELECT id FROM answers WHERE name="Don’t Know/Not Sure" LIMIT 1), null);

 #58
 insert into `form_question_answers` (`form_question_id`,`answer_id`,`jump_to_question_id`)
 values((SELECT current_question.id FROM questions as current_question WHERE current_question.name="Is it true that only people with high blood cholesterol should follow a heart healthy diet?" LIMIT 1), 
 	(SELECT id FROM answers WHERE name="Yes" LIMIT 1), null),
 ((SELECT current_question.id FROM questions as current_question WHERE current_question.name="Is it true that only people with high blood cholesterol should follow a heart healthy diet?" LIMIT 1), 
 	(SELECT id FROM answers WHERE name="No" LIMIT 1), null),
 ((SELECT current_question.id FROM questions as current_question WHERE current_question.name="Is it true that only people with high blood cholesterol should follow a heart healthy diet?" LIMIT 1), 
 	(SELECT id FROM answers WHERE name="Don’t Know/Not Sure" LIMIT 1), null);

 #59
 insert into `form_question_answers` (`form_question_id`,`answer_id`,`jump_to_question_id`)
 values((SELECT current_question.id FROM questions as current_question WHERE current_question.name="Can nonsmokers die from secondhand smoke?" LIMIT 1), 
 	(SELECT id FROM answers WHERE name="Yes" LIMIT 1), null),
 ((SELECT current_question.id FROM questions as current_question WHERE current_question.name="Can nonsmokers die from secondhand smoke?" LIMIT 1), 
 	(SELECT id FROM answers WHERE name="No" LIMIT 1), null),
 ((SELECT current_question.id FROM questions as current_question WHERE current_question.name="Can nonsmokers die from secondhand smoke?" LIMIT 1), 
 	(SELECT id FROM answers WHERE name="Don’t Know/Not Sure" LIMIT 1), null);

 #60
 insert into `form_question_answers` (`form_question_id`,`answer_id`,`jump_to_question_id`)
 values((SELECT current_question.id FROM questions as current_question WHERE current_question.name="How often do you have a hard time understanding written information about your health that you get from your clinic? (This might include information from a doctor or nurse.)" LIMIT 1), 
 	(SELECT id FROM answers WHERE name="Always" LIMIT 1), null),
 ((SELECT current_question.id FROM questions as current_question WHERE current_question.name="How often do you have a hard time understanding written information about your health that you get from your clinic? (This might include information from a doctor or nurse.)" LIMIT 1), 
 	(SELECT id FROM answers WHERE name="Often" LIMIT 1), null),
 ((SELECT current_question.id FROM questions as current_question WHERE current_question.name="How often do you have a hard time understanding written information about your health that you get from your clinic? (This might include information from a doctor or nurse.)" LIMIT 1), 
 	(SELECT id FROM answers WHERE name="Sometimes" LIMIT 1), null),
 ((SELECT current_question.id FROM questions as current_question WHERE current_question.name="How often do you have a hard time understanding written information about your health that you get from your clinic? (This might include information from a doctor or nurse.)" LIMIT 1), 
 	(SELECT id FROM answers WHERE name="Rarely" LIMIT 1), null),
 ((SELECT current_question.id FROM questions as current_question WHERE current_question.name="How often do you have a hard time understanding written information about your health that you get from your clinic? (This might include information from a doctor or nurse.)" LIMIT 1), 
 	(SELECT id FROM answers WHERE name="Never" LIMIT 1), null);


 #61
 insert into `form_question_answers` (`form_question_id`,`answer_id`,`jump_to_question_id`)
 values((SELECT current_question.id FROM questions as current_question WHERE current_question.name="How confident are you in filling out medical forms by yourself?" LIMIT 1), 
 	(SELECT id FROM answers WHERE name="Extremely" LIMIT 1), null),
 ((SELECT current_question.id FROM questions as current_question WHERE current_question.name="How confident are you in filling out medical forms by yourself?" LIMIT 1), 
 	(SELECT id FROM answers WHERE name="Quite a bit" LIMIT 1), null),
 ((SELECT current_question.id FROM questions as current_question WHERE current_question.name="How confident are you in filling out medical forms by yourself?" LIMIT 1), 
 	(SELECT id FROM answers WHERE name="Somewhat" LIMIT 1), null),
 ((SELECT current_question.id FROM questions as current_question WHERE current_question.name="How confident are you in filling out medical forms by yourself?" LIMIT 1), 
 	(SELECT id FROM answers WHERE name="A little bit" LIMIT 1), null),
 ((SELECT current_question.id FROM questions as current_question WHERE current_question.name="How confident are you in filling out medical forms by yourself?" LIMIT 1), 
 	(SELECT id FROM answers WHERE name="Not at all" LIMIT 1), null);

 #62
 insert into `form_question_answers` (`form_question_id`,`answer_id`,`jump_to_question_id`)
 values((SELECT current_question.id FROM questions as current_question WHERE current_question.name="How often do you prefer that someone (like a family member or someone else) help you read medical materials?" LIMIT 1), 
 	(SELECT id FROM answers WHERE name="Always" LIMIT 1), null),
 ((SELECT current_question.id FROM questions as current_question WHERE current_question.name="How often do you prefer that someone (like a family member or someone else) help you read medical materials?" LIMIT 1), 
 	(SELECT id FROM answers WHERE name="Often" LIMIT 1), null),
 ((SELECT current_question.id FROM questions as current_question WHERE current_question.name="How often do you prefer that someone (like a family member or someone else) help you read medical materials?" LIMIT 1), 
 	(SELECT id FROM answers WHERE name="Sometimes" LIMIT 1), null),
 ((SELECT current_question.id FROM questions as current_question WHERE current_question.name="How often do you prefer that someone (like a family member or someone else) help you read medical materials?" LIMIT 1), 
 	(SELECT id FROM answers WHERE name="Rarely" LIMIT 1), null),
 ((SELECT current_question.id FROM questions as current_question WHERE current_question.name="How often do you prefer that someone (like a family member or someone else) help you read medical materials?" LIMIT 1), 
 	(SELECT id FROM answers WHERE name="Never" LIMIT 1), null);

 #63
 insert into `form_question_answers` (`form_question_id`,`answer_id`,`jump_to_question_id`)
 values((SELECT current_question.id FROM questions as current_question WHERE current_question.name="Which of the following numbers represents the lowest risk? For example, which would you most like to hear from a doctor about your risk for a medical condition?" LIMIT 1), 
 	(SELECT id FROM answers WHERE name="1 in 10 people" LIMIT 1), null),
 ((SELECT current_question.id FROM questions as current_question WHERE current_question.name="Which of the following numbers represents the lowest risk? For example, which would you most like to hear from a doctor about your risk for a medical condition?" LIMIT 1), 
 	(SELECT id FROM answers WHERE name="1 in 100 people" LIMIT 1), null),
 ((SELECT current_question.id FROM questions as current_question WHERE current_question.name="Which of the following numbers represents the lowest risk? For example, which would you most like to hear from a doctor about your risk for a medical condition?" LIMIT 1), 
 	(SELECT id FROM answers WHERE name="1 in 1000 people" LIMIT 1), null),
 ((SELECT current_question.id FROM questions as current_question WHERE current_question.name="Which of the following numbers represents the lowest risk? For example, which would you most like to hear from a doctor about your risk for a medical condition?" LIMIT 1), 
 	(SELECT id FROM answers WHERE name="Don’t Know" LIMIT 1), null);

 #64
 insert into `form_question_answers` (`form_question_id`,`answer_id`,`jump_to_question_id`)
 values((SELECT current_question.id FROM questions as current_question WHERE current_question.name="If the chance of getting a health condition is 20 out of 100 people, this would be the same as having a what percent (%) chance of getting the condition?" LIMIT 1), 
 	(SELECT id FROM answers WHERE name="2%" LIMIT 1), null),
 ((SELECT current_question.id FROM questions as current_question WHERE current_question.name="If the chance of getting a health condition is 20 out of 100 people, this would be the same as having a what percent (%) chance of getting the condition?" LIMIT 1), 
 	(SELECT id FROM answers WHERE name="20%" LIMIT 1), null),
 ((SELECT current_question.id FROM questions as current_question WHERE current_question.name="If the chance of getting a health condition is 20 out of 100 people, this would be the same as having a what percent (%) chance of getting the condition?" LIMIT 1), 
 	(SELECT id FROM answers WHERE name="200%" LIMIT 1), null),
 ((SELECT current_question.id FROM questions as current_question WHERE current_question.name="If the chance of getting a health condition is 20 out of 100 people, this would be the same as having a what percent (%) chance of getting the condition?" LIMIT 1), 
 	(SELECT id FROM answers WHERE name="Don’t Know" LIMIT 1), null);

  #65
 insert into `form_question_answers` (`form_question_id`,`answer_id`,`jump_to_question_id`)
 values((SELECT current_question.id FROM questions as current_question WHERE current_question.name="A prescription says “Take one tablet by mouth every 6 hours.” If you take your first tablet at 7 a.m., when should you take your second tablet?" LIMIT 1), 
 	(SELECT id FROM answers WHERE name="10 a.m" LIMIT 1), null),
 ((SELECT current_question.id FROM questions as current_question WHERE current_question.name="A prescription says “Take one tablet by mouth every 6 hours.” If you take your first tablet at 7 a.m., when should you take your second tablet?" LIMIT 1), 
 	(SELECT id FROM answers WHERE name="12 p.m" LIMIT 1), null),
 ((SELECT current_question.id FROM questions as current_question WHERE current_question.name="A prescription says “Take one tablet by mouth every 6 hours.” If you take your first tablet at 7 a.m., when should you take your second tablet?" LIMIT 1), 
 	(SELECT id FROM answers WHERE name="1 p.m" LIMIT 1), null),
 ((SELECT current_question.id FROM questions as current_question WHERE current_question.name="A prescription says “Take one tablet by mouth every 6 hours.” If you take your first tablet at 7 a.m., when should you take your second tablet?" LIMIT 1), 
 	(SELECT id FROM answers WHERE name="6 p.m" LIMIT 1), null),
 ((SELECT current_question.id FROM questions as current_question WHERE current_question.name="A prescription says “Take one tablet by mouth every 6 hours.” If you take your first tablet at 7 a.m., when should you take your second tablet?" LIMIT 1), 
 	(SELECT id FROM answers WHERE name="7 p.m" LIMIT 1), null);

#66
 insert into `form_question_answers` (`form_question_id`,`answer_id`,`jump_to_question_id`)
 values((SELECT current_question.id FROM questions as current_question WHERE current_question.name="Normal fasting blood sugar is 70-100. If your blood sugar today is 140, is your blood sugar normal?" LIMIT 1), 
 	(SELECT id FROM answers WHERE name="Yes" LIMIT 1), null),
 ((SELECT current_question.id FROM questions as current_question WHERE current_question.name="Normal fasting blood sugar is 70-100. If your blood sugar today is 140, is your blood sugar normal?" LIMIT 1), 
 	(SELECT id FROM answers WHERE name="No" LIMIT 1), null),
 ((SELECT current_question.id FROM questions as current_question WHERE current_question.name="Normal fasting blood sugar is 70-100. If your blood sugar today is 140, is your blood sugar normal?" LIMIT 1), 
 	(SELECT id FROM answers WHERE name="Don’t Know" LIMIT 1), null);

 #67
 insert into `form_question_answers` (`form_question_id`,`answer_id`,`jump_to_question_id`)
 values((SELECT current_question.id FROM questions as current_question WHERE current_question.name="Do you have a TV?" LIMIT 1), 
 	(SELECT id FROM answers WHERE name="Yes" LIMIT 1), null),
 ((SELECT current_question.id FROM questions as current_question WHERE current_question.name="Do you have a TV?" LIMIT 1), 
 	(SELECT id FROM answers WHERE name="No" LIMIT 1), (SELECT current_question.id FROM questions as current_question WHERE current_question.name="Do you have a personal computer in your home?" LIMIT 1));

 #68
 insert into `form_question_answers` (`form_question_id`,`answer_id`,`jump_to_question_id`)
 values((SELECT current_question.id FROM questions as current_question WHERE current_question.name="Do you have a gaming system you hook up to your TV? By this we mean something like the Nintendo Wii, Xbox, or Sony Playstation?" LIMIT 1), 
 	(SELECT id FROM answers WHERE name="Yes" LIMIT 1), null),
 ((SELECT current_question.id FROM questions as current_question WHERE current_question.name="Do you have a gaming system you hook up to your TV? By this we mean something like the Nintendo Wii, Xbox, or Sony Playstation?" LIMIT 1), 
 	(SELECT id FROM answers WHERE name="No" LIMIT 1), (SELECT current_question.id FROM questions as current_question WHERE current_question.name="Do you have a personal computer in your home?" LIMIT 1));

 #69
 insert into `form_question_answers` (`form_question_id`,`answer_id`,`jump_to_question_id`)
 values((SELECT current_question.id FROM questions as current_question WHERE current_question.name="Which system do you have?" LIMIT 1), 
 	(SELECT id FROM answers WHERE name="Nintendo Wii" LIMIT 1), null),
 ((SELECT current_question.id FROM questions as current_question WHERE current_question.name="Which system do you have?" LIMIT 1), 
 	(SELECT id FROM answers WHERE name="Xbox" LIMIT 1), null),
 ((SELECT current_question.id FROM questions as current_question WHERE current_question.name="Which system do you have?" LIMIT 1), 
 	(SELECT id FROM answers WHERE name="Sony Playstation" LIMIT 1), null),
 ((SELECT current_question.id FROM questions as current_question WHERE current_question.name="Which system do you have?" LIMIT 1), 
 	(SELECT id FROM answers WHERE name="Other (Please specify:" LIMIT 1), null);

#70
 insert into `form_question_answers` (`form_question_id`,`answer_id`,`jump_to_question_id`)
 values((SELECT current_question.id FROM questions as current_question WHERE current_question.name="Do you have a personal computer in your home?" LIMIT 1), 
 	(SELECT id FROM answers WHERE name="Yes" LIMIT 1), null),
 ((SELECT current_question.id FROM questions as current_question WHERE current_question.name="Do you have a personal computer in your home?" LIMIT 1), 
 	(SELECT id FROM answers WHERE name="No" LIMIT 1), (SELECT current_question.id FROM questions as current_question WHERE current_question.name="Do you have a cell phone?" LIMIT 1));

 #71
 insert into `form_question_answers` (`form_question_id`,`answer_id`,`jump_to_question_id`)
 values((SELECT current_question.id FROM questions as current_question WHERE current_question.name="Is it a Windows or Apple system?" LIMIT 1), 
 	(SELECT id FROM answers WHERE name="Windows" LIMIT 1), null),
 ((SELECT current_question.id FROM questions as current_question WHERE current_question.name="Is it a Windows or Apple system?" LIMIT 1), 
 	(SELECT id FROM answers WHERE name="Apple" LIMIT 1), null);

 #72
 insert into `form_question_answers` (`form_question_id`,`answer_id`,`jump_to_question_id`)
 values((SELECT current_question.id FROM questions as current_question WHERE current_question.name="How confident are you in using your computer?" LIMIT 1), 
 	(SELECT id FROM answers WHERE name="Very confident" LIMIT 1), null),
 ((SELECT current_question.id FROM questions as current_question WHERE current_question.name="How confident are you in using your computer?" LIMIT 1), 
 	(SELECT id FROM answers WHERE name="Fairly confident" LIMIT 1), null),
 ((SELECT current_question.id FROM questions as current_question WHERE current_question.name="How confident are you in using your computer?" LIMIT 1), 
 	(SELECT id FROM answers WHERE name="Not at all confident" LIMIT 1), null);

 #73
 insert into `form_question_answers` (`form_question_id`,`answer_id`,`jump_to_question_id`)
 values((SELECT current_question.id FROM questions as current_question WHERE current_question.name="Do you have Internet access?" LIMIT 1), 
 	(SELECT id FROM answers WHERE name="Yes" LIMIT 1), null),
 ((SELECT current_question.id FROM questions as current_question WHERE current_question.name="Do you have Internet access?" LIMIT 1), 
 	(SELECT id FROM answers WHERE name="No" LIMIT 1), null);

 #74
 insert into `form_question_answers` (`form_question_id`,`answer_id`,`jump_to_question_id`)
 values((SELECT current_question.id FROM questions as current_question WHERE current_question.name="Do you have an e-mail account that you check regularly?" LIMIT 1), 
 	(SELECT id FROM answers WHERE name="Yes" LIMIT 1), null),
 ((SELECT current_question.id FROM questions as current_question WHERE current_question.name="Do you have an e-mail account that you check regularly?" LIMIT 1), 
 	(SELECT id FROM answers WHERE name="No" LIMIT 1), null);

 #75
 insert into `form_question_answers` (`form_question_id`,`answer_id`,`jump_to_question_id`)
 values((SELECT current_question.id FROM questions as current_question WHERE current_question.name="Do you have a cell phone?" LIMIT 1), 
 	(SELECT id FROM answers WHERE name="Yes" LIMIT 1), null),
 ((SELECT current_question.id FROM questions as current_question WHERE current_question.name="Do you have a cell phone?" LIMIT 1), 
 	(SELECT id FROM answers WHERE name="No" LIMIT 1), 
 	(SELECT current_question.id FROM questions as current_question WHERE current_question.name="How much do you currently weigh without shoes?" LIMIT 1));

 #76
 insert into `form_question_answers` (`form_question_id`,`answer_id`,`jump_to_question_id`)
 values((SELECT current_question.id FROM questions as current_question WHERE current_question.name="Are you able to send and received text messages using your cell phone?" LIMIT 1), 
 	(SELECT id FROM answers WHERE name="Yes" LIMIT 1), null),
 ((SELECT current_question.id FROM questions as current_question WHERE current_question.name="Are you able to send and received text messages using your cell phone?" LIMIT 1), 
 	(SELECT id FROM answers WHERE name="No" LIMIT 1), 
 	(SELECT current_question.id FROM questions as current_question WHERE current_question.name="How much do you currently weigh without shoes?" LIMIT 1));

 #77
 insert into `form_question_answers` (`form_question_id`,`answer_id`,`jump_to_question_id`)
 values((SELECT current_question.id FROM questions as current_question WHERE current_question.name="Would you be willing to receive text messages about heart disease and heart-healthy living on your cell phone?" LIMIT 1), 
 	(SELECT id FROM answers WHERE name="Yes" LIMIT 1), null),
 ((SELECT current_question.id FROM questions as current_question WHERE current_question.name="Would you be willing to receive text messages about heart disease and heart-healthy living on your cell phone?" LIMIT 1), 
 	(SELECT id FROM answers WHERE name="No" LIMIT 1),
 	 (SELECT current_question.id FROM questions as current_question WHERE current_question.name="How much do you currently weigh without shoes?" LIMIT 1));

 #78
 insert into `form_question_answers` (`form_question_id`,`answer_id`,`jump_to_question_id`)
 values((SELECT current_question.id FROM questions as current_question WHERE current_question.name="What are some of the reasons you would not be interested in getting text messages about heart health?" LIMIT 1), 
 	(SELECT id FROM answers WHERE name="Too expensive" LIMIT 1), null),
 ((SELECT current_question.id FROM questions as current_question WHERE current_question.name="What are some of the reasons you would not be interested in getting text messages about heart health?" LIMIT 1), 
 	(SELECT id FROM answers WHERE name="I’m not worried about my heart heatlh" LIMIT 1), null),
 ((SELECT current_question.id FROM questions as current_question WHERE current_question.name="What are some of the reasons you would not be interested in getting text messages about heart health?" LIMIT 1), 
 	(SELECT id FROM answers WHERE name="Other" LIMIT 1), null);

 #79
 insert into `form_question_answers` (`form_question_id`,`answer_id`,`jump_to_question_id`)
 values((SELECT current_question.id FROM questions as current_question WHERE current_question.name="How much do you currently weigh without shoes?" LIMIT 1), 
 	(SELECT id FROM answers WHERE name="pounds" LIMIT 1), null);

#80
 insert into `form_question_answers` (`form_question_id`,`answer_id`,`jump_to_question_id`)
 values((SELECT current_question.id FROM questions as current_question WHERE current_question.name="How tall are you without shoes?" LIMIT 1), 
 	(SELECT id FROM answers WHERE name="feet" LIMIT 1), null),
 ((SELECT current_question.id FROM questions as current_question WHERE current_question.name="How tall are you without shoes?" LIMIT 1), 
 	(SELECT id FROM answers WHERE name="inches" LIMIT 1), null);

 #81
 insert into `form_question_answers` (`form_question_id`,`answer_id`,`jump_to_question_id`)
 values((SELECT current_question.id FROM questions as current_question WHERE current_question.name="Are you male or female?" LIMIT 1), 
 	(SELECT id FROM answers WHERE name="Male" LIMIT 1), null),
 ((SELECT current_question.id FROM questions as current_question WHERE current_question.name="Are you male or female?" LIMIT 1), 
 	(SELECT id FROM answers WHERE name="Female" LIMIT 1), null);

 #82
 insert into `form_question_answers` (`form_question_id`,`answer_id`,`jump_to_question_id`)
 values((SELECT current_question.id FROM questions as current_question WHERE current_question.name="How old are you today?" LIMIT 1), 
 	(SELECT id FROM answers WHERE name="years old" LIMIT 1), null);

 #83
 insert into `form_question_answers` (`form_question_id`,`answer_id`,`jump_to_question_id`)
 values((SELECT current_question.id FROM questions as current_question WHERE current_question.name="What is your ethnicity?" LIMIT 1), 
 	(SELECT id FROM answers WHERE name="Hispanic or Latino of any race" LIMIT 1), null),
 ((SELECT current_question.id FROM questions as current_question WHERE current_question.name="What is your ethnicity?" LIMIT 1), 
 	(SELECT id FROM answers WHERE name="Not Hispanic or Latino" LIMIT 1), null),
 ((SELECT current_question.id FROM questions as current_question WHERE current_question.name="What is your ethnicity?" LIMIT 1), 
 	(SELECT id FROM answers WHERE name="Don’t Know" LIMIT 1), null);

 #84
 insert into `form_question_answers` (`form_question_id`,`answer_id`,`jump_to_question_id`)
 values((SELECT current_question.id FROM questions as current_question WHERE current_question.name="What is your race?" LIMIT 1), 
 	(SELECT id FROM answers WHERE name="American Indian or Alaska Native" LIMIT 1), null),
 ((SELECT current_question.id FROM questions as current_question WHERE current_question.name="What is your race?" LIMIT 1), 
 	(SELECT id FROM answers WHERE name="Asian" LIMIT 1), null),
 ((SELECT current_question.id FROM questions as current_question WHERE current_question.name="What is your race?" LIMIT 1), 
 	(SELECT id FROM answers WHERE name="Black or African American" LIMIT 1), null),
 ((SELECT current_question.id FROM questions as current_question WHERE current_question.name="What is your race?" LIMIT 1), 
 	(SELECT id FROM answers WHERE name="Native Hawaiian or Pacific Islander" LIMIT 1), null),
 ((SELECT current_question.id FROM questions as current_question WHERE current_question.name="What is your race?" LIMIT 1), 
 	(SELECT id FROM answers WHERE name="White" LIMIT 1), null),
 ((SELECT current_question.id FROM questions as current_question WHERE current_question.name="What is your race?" LIMIT 1), 
 	(SELECT id FROM answers WHERE name="Other (Please specify:" LIMIT 1), null),
 ((SELECT current_question.id FROM questions as current_question WHERE current_question.name="What is your race?" LIMIT 1), 
 	(SELECT id FROM answers WHERE name="Don’t Know" LIMIT 1), null);

#85
 insert into `form_question_answers` (`form_question_id`,`answer_id`,`jump_to_question_id`)
 values((SELECT current_question.id FROM questions as current_question WHERE current_question.name="If you marked “American Indian or Alaska Native” in the previous question, what tribe do you most closely identify with?" LIMIT 1), 
 	null, null);
 
 #86
 insert into `form_question_answers` (`form_question_id`,`answer_id`,`jump_to_question_id`)
 values((SELECT current_question.id FROM questions as current_question WHERE current_question.name="What is the highest grade in school you completed?" LIMIT 1), 
 	(SELECT id FROM answers WHERE name="None" LIMIT 1), null),
 ((SELECT current_question.id FROM questions as current_question WHERE current_question.name="What is the highest grade in school you completed?" LIMIT 1), 
 	(SELECT id FROM answers WHERE name="1st grade" LIMIT 1), null),
 ((SELECT current_question.id FROM questions as current_question WHERE current_question.name="What is the highest grade in school you completed?" LIMIT 1), 
 	(SELECT id FROM answers WHERE name="2nd grade" LIMIT 1), null),
 ((SELECT current_question.id FROM questions as current_question WHERE current_question.name="What is the highest grade in school you completed?" LIMIT 1), 
 	(SELECT id FROM answers WHERE name="3rd grade" LIMIT 1), null),
 ((SELECT current_question.id FROM questions as current_question WHERE current_question.name="What is the highest grade in school you completed?" LIMIT 1), 
 	(SELECT id FROM answers WHERE name="4th grade" LIMIT 1), null),
 ((SELECT current_question.id FROM questions as current_question WHERE current_question.name="What is the highest grade in school you completed?" LIMIT 1), 
 	(SELECT id FROM answers WHERE name="5th grade" LIMIT 1), null),
 ((SELECT current_question.id FROM questions as current_question WHERE current_question.name="What is the highest grade in school you completed?" LIMIT 1), 
 	(SELECT id FROM answers WHERE name="6th grade" LIMIT 1), null),
 ((SELECT current_question.id FROM questions as current_question WHERE current_question.name="What is the highest grade in school you completed?" LIMIT 1), 
 	(SELECT id FROM answers WHERE name="7th grade" LIMIT 1), null),
 ((SELECT current_question.id FROM questions as current_question WHERE current_question.name="What is the highest grade in school you completed?" LIMIT 1), 
 	(SELECT id FROM answers WHERE name="8th grade" LIMIT 1), null),
 ((SELECT current_question.id FROM questions as current_question WHERE current_question.name="What is the highest grade in school you completed?" LIMIT 1), 
 	(SELECT id FROM answers WHERE name="9th grade" LIMIT 1), null),
 ((SELECT current_question.id FROM questions as current_question WHERE current_question.name="What is the highest grade in school you completed?" LIMIT 1), 
 	(SELECT id FROM answers WHERE name="10th grade" LIMIT 1), null),
 ((SELECT current_question.id FROM questions as current_question WHERE current_question.name="What is the highest grade in school you completed?" LIMIT 1), 
 	(SELECT id FROM answers WHERE name="11th grade" LIMIT 1), null),
 ((SELECT current_question.id FROM questions as current_question WHERE current_question.name="What is the highest grade in school you completed?" LIMIT 1), 
 	(SELECT id FROM answers WHERE name="12th grade" LIMIT 1), null),
 ((SELECT current_question.id FROM questions as current_question WHERE current_question.name="What is the highest grade in school you completed?" LIMIT 1), 
 	(SELECT id FROM answers WHERE name="High School graduate/GED" LIMIT 1), null),
 ((SELECT current_question.id FROM questions as current_question WHERE current_question.name="What is the highest grade in school you completed?" LIMIT 1), 
 	(SELECT id FROM answers WHERE name="Vocational school" LIMIT 1), null),
 ((SELECT current_question.id FROM questions as current_question WHERE current_question.name="What is the highest grade in school you completed?" LIMIT 1), 
 	(SELECT id FROM answers WHERE name="Some college" LIMIT 1), null),
 ((SELECT current_question.id FROM questions as current_question WHERE current_question.name="What is the highest grade in school you completed?" LIMIT 1), 
 	(SELECT id FROM answers WHERE name="College graduate" LIMIT 1), null),
 ((SELECT current_question.id FROM questions as current_question WHERE current_question.name="What is the highest grade in school you completed?" LIMIT 1), 
 	(SELECT id FROM answers WHERE name="Some graduate/professional school" LIMIT 1), null),
 ((SELECT current_question.id FROM questions as current_question WHERE current_question.name="What is the highest grade in school you completed?" LIMIT 1), 
 	(SELECT id FROM answers WHERE name="Graduate/professional degree" LIMIT 1), null);

 #87
 insert into `form_question_answers` (`form_question_id`,`answer_id`,`jump_to_question_id`)
 values((SELECT current_question.id FROM questions as current_question 
  WHERE current_question.name="Please fill in the category below that best fits the total combined income before taxes of all people who lived in your household last year. This should include not only wages, salaries, and tips but also income from social security, pension, unemployment, or disability compensation, alimony, child support, welfare, or any other money income received by all household members – by you or anyone else living with you." LIMIT 1), 
 	(SELECT id FROM answers WHERE name="Nothing" LIMIT 1), null),
 ((SELECT current_question.id FROM questions as current_question WHERE current_question.name="Please fill in the category below that best fits the total combined income before taxes of all people who lived in your household last year. This should include not only wages, salaries, and tips but also income from social security, pension, unemployment, or disability compensation, alimony, child support, welfare, or any other money income received by all household members – by you or anyone else living with you." LIMIT 1), 
 	(SELECT id FROM answers WHERE name="Less than $1,000" LIMIT 1), null),
 ((SELECT current_question.id FROM questions as current_question WHERE current_question.name="Please fill in the category below that best fits the total combined income before taxes of all people who lived in your household last year. This should include not only wages, salaries, and tips but also income from social security, pension, unemployment, or disability compensation, alimony, child support, welfare, or any other money income received by all household members – by you or anyone else living with you." LIMIT 1), 
 	(SELECT id FROM answers WHERE name="$1,000 - $4,999" LIMIT 1), null),
 ((SELECT current_question.id FROM questions as current_question WHERE current_question.name="Please fill in the category below that best fits the total combined income before taxes of all people who lived in your household last year. This should include not only wages, salaries, and tips but also income from social security, pension, unemployment, or disability compensation, alimony, child support, welfare, or any other money income received by all household members – by you or anyone else living with you." LIMIT 1), 
 	(SELECT id FROM answers WHERE name="$5,000 - $9,999" LIMIT 1), null),
 ((SELECT current_question.id FROM questions as current_question WHERE current_question.name="Please fill in the category below that best fits the total combined income before taxes of all people who lived in your household last year. This should include not only wages, salaries, and tips but also income from social security, pension, unemployment, or disability compensation, alimony, child support, welfare, or any other money income received by all household members – by you or anyone else living with you." LIMIT 1), 
 	(SELECT id FROM answers WHERE name="$10,000 - $14,999" LIMIT 1), null),
 ((SELECT current_question.id FROM questions as current_question WHERE current_question.name="Please fill in the category below that best fits the total combined income before taxes of all people who lived in your household last year. This should include not only wages, salaries, and tips but also income from social security, pension, unemployment, or disability compensation, alimony, child support, welfare, or any other money income received by all household members – by you or anyone else living with you." LIMIT 1), 
 	(SELECT id FROM answers WHERE name="$15,000 - $19,999" LIMIT 1), null),
 ((SELECT current_question.id FROM questions as current_question WHERE current_question.name="Please fill in the category below that best fits the total combined income before taxes of all people who lived in your household last year. This should include not only wages, salaries, and tips but also income from social security, pension, unemployment, or disability compensation, alimony, child support, welfare, or any other money income received by all household members – by you or anyone else living with you." LIMIT 1), 
 	(SELECT id FROM answers WHERE name="$20,000 - $29,999" LIMIT 1), null),
 ((SELECT current_question.id FROM questions as current_question WHERE current_question.name="Please fill in the category below that best fits the total combined income before taxes of all people who lived in your household last year. This should include not only wages, salaries, and tips but also income from social security, pension, unemployment, or disability compensation, alimony, child support, welfare, or any other money income received by all household members – by you or anyone else living with you." LIMIT 1), 
 	(SELECT id FROM answers WHERE name="$30,000 - $39,999" LIMIT 1), null),
 ((SELECT current_question.id FROM questions as current_question WHERE current_question.name="Please fill in the category below that best fits the total combined income before taxes of all people who lived in your household last year. This should include not only wages, salaries, and tips but also income from social security, pension, unemployment, or disability compensation, alimony, child support, welfare, or any other money income received by all household members – by you or anyone else living with you." LIMIT 1), 
 	(SELECT id FROM answers WHERE name="$40,000 – $49,999" LIMIT 1), null),
 ((SELECT current_question.id FROM questions as current_question WHERE current_question.name="Please fill in the category below that best fits the total combined income before taxes of all people who lived in your household last year. This should include not only wages, salaries, and tips but also income from social security, pension, unemployment, or disability compensation, alimony, child support, welfare, or any other money income received by all household members – by you or anyone else living with you." LIMIT 1), 
 	(SELECT id FROM answers WHERE name="$50,000 - $74,999" LIMIT 1), null),
 ((SELECT current_question.id FROM questions as current_question WHERE current_question.name="Please fill in the category below that best fits the total combined income before taxes of all people who lived in your household last year. This should include not only wages, salaries, and tips but also income from social security, pension, unemployment, or disability compensation, alimony, child support, welfare, or any other money income received by all household members – by you or anyone else living with you." LIMIT 1), 
 	(SELECT id FROM answers WHERE name="$75,000 - $99,999" LIMIT 1), null),
 ((SELECT current_question.id FROM questions as current_question WHERE current_question.name="Please fill in the category below that best fits the total combined income before taxes of all people who lived in your household last year. This should include not only wages, salaries, and tips but also income from social security, pension, unemployment, or disability compensation, alimony, child support, welfare, or any other money income received by all household members – by you or anyone else living with you." LIMIT 1), 
 	(SELECT id FROM answers WHERE name="More than $100,000" LIMIT 1), null);

#1
insert into `form_questions` (`question_id`,`form_id`)
 values((SELECT current_question.id FROM questions as current_question WHERE current_question.name="When was the last time you had your blood pressure checked?" LIMIT 1), 
  (SELECT id FROM forms WHERE name="Healthy Heart" LIMIT 1));

#2
 insert into `form_questions` (`question_id`,`form_id`)
 values((SELECT current_question.id FROM questions as current_question WHERE current_question.name="The LAST time you had your blood pressure checked, was it normal or high?" LIMIT 1),
  (SELECT id FROM forms WHERE name="Healthy Heart" LIMIT 1));

#3
 insert into `form_questions` (`question_id`,`form_id`)
 values((SELECT current_question.id FROM questions as current_question WHERE current_question.name="Have you EVER been told by a doctor, nurse, or other health professional that you have
high blood pressure?" LIMIT 1), (SELECT id FROM forms WHERE name="Healthy Heart" LIMIT 1));

#4
 insert into `form_questions` (`question_id`,`form_id`)
 values((SELECT current_question.id FROM questions as current_question WHERE current_question.name="If yes, and if you are female, was this only when you were pregnant?" LIMIT 1), (SELECT id FROM forms WHERE name="Healthy Heart" LIMIT 1));

 #5
 insert into `form_questions` (`question_id`,`form_id`)
 values((SELECT current_question.id FROM questions as current_question WHERE current_question.name="Are you currently taking medicine for your high blood pressure?" LIMIT 1), (SELECT id FROM forms WHERE name="Healthy Heart" LIMIT 1));

#6
 insert into `form_questions` (`question_id`,`form_id`)
 values((SELECT current_question.id FROM questions as current_question WHERE current_question.name="Are you changing your eating habits to help lower or control your blood pressure?" LIMIT 1), (SELECT id FROM forms WHERE name="Healthy Heart" LIMIT 1));

#7
 insert into `form_questions` (`question_id`,`form_id`)
 values((SELECT current_question.id FROM questions as current_question WHERE current_question.name="Are you cutting down on salt to help lower or control your blood pressure?" LIMIT 1), (SELECT id FROM forms WHERE name="Healthy Heart" LIMIT 1));

#8
 insert into `form_questions` (`question_id`,`form_id`)
 values((SELECT current_question.id FROM questions as current_question WHERE current_question.name="Are you reducing alcohol use to help lower or control your blood pressure?" LIMIT 1), (SELECT id FROM forms WHERE name="Healthy Heart" LIMIT 1));

#9
 insert into `form_questions` (`question_id`,`form_id`)
 values((SELECT current_question.id FROM questions as current_question WHERE current_question.name="Are you exercising to help lower or control your blood pressure?" LIMIT 1), (SELECT id FROM forms WHERE name="Healthy Heart" LIMIT 1));

#10
 insert into `form_questions` (`question_id`,`form_id`)
 values((SELECT current_question.id FROM questions as current_question WHERE current_question.name="Blood cholesterol is a fatty substance found in the blood. Have you ever had your blood
cholesterol checked?" LIMIT 1), (SELECT id FROM forms WHERE name="Healthy Heart" LIMIT 1));

#11
insert into `form_questions` (`question_id`,`form_id`)
 values((SELECT current_question.id FROM questions as current_question WHERE current_question.name="About how long has it been since you last had your blood cholesterol checked?" LIMIT 1), (SELECT id FROM forms WHERE name="Healthy Heart" LIMIT 1));

#12
 insert into `form_questions` (`question_id`,`form_id`)
 values((SELECT current_question.id FROM questions as current_question WHERE current_question.name="The last time you had your blood cholesterol checked, was it normal or high?" LIMIT 1), (SELECT id FROM forms WHERE name="Healthy Heart" LIMIT 1));

#13
 insert into `form_questions` (`question_id`,`form_id`)
 values((SELECT current_question.id FROM questions as current_question WHERE current_question.name="Have you EVER been told by a doctor, nurse or other health professional that your blood
cholesterol is high?" LIMIT 1), (SELECT id FROM forms WHERE name="Healthy Heart" LIMIT 1));

#14
 insert into `form_questions` (`question_id`,`form_id`)
 values((SELECT current_question.id FROM questions as current_question WHERE current_question.name="If so, when were you told that your blood cholesterol was high?" LIMIT 1), (SELECT id FROM forms WHERE name="Healthy Heart" LIMIT 1));


 #15
 insert into `form_questions` (`question_id`,`form_id`)
 values((SELECT current_question.id FROM questions as current_question WHERE current_question.name="How many days per week do you do moderate physical activities for at least 30 minutes?" LIMIT 1), (SELECT id FROM forms WHERE name="Healthy Heart" LIMIT 1));

#16
 insert into `form_questions` (`question_id`,`form_id`)
 values((SELECT current_question.id FROM questions as current_question WHERE current_question.name="How many days per week do you do vigorous physical activities for at least 20 minutes?" LIMIT 1), (SELECT id FROM forms WHERE name="Healthy Heart" LIMIT 1));

#17
 insert into `form_questions` (`question_id`,`form_id`)
 values((SELECT current_question.id FROM questions as current_question WHERE current_question.name="Thinking back on the past 30 days, please check yes or no for each statement. You may
choose “yes” for more than one statement." LIMIT 1), (SELECT id FROM forms WHERE name="Healthy Heart" LIMIT 1));

#18
 insert into `form_questions` (`question_id`,`form_id`)
 values((SELECT current_question.id FROM questions as current_question WHERE current_question.name="Over the past 30 days in general, how many hours per day did you usually spend watching
television, sitting at a computer, playing video games, doing beadwork, or other activities
that don’t require much physical activity?" LIMIT 1), (SELECT id FROM forms WHERE name="Healthy Heart" LIMIT 1));


#19
 insert into `form_questions` (`question_id`,`form_id`)
 values((SELECT current_question.id FROM questions as current_question WHERE current_question.name="Do you plan to increase the amount of physical activity you get every week?" LIMIT 1), (SELECT id FROM forms WHERE name="Healthy Heart" LIMIT 1));

#20
 insert into `form_questions` (`question_id`,`form_id`)
 values((SELECT current_question.id FROM questions as current_question WHERE current_question.name="Please think about what you usually ate or drank during the past 30 days. Read each item
carefully and indicate one response for each. How often did you..." LIMIT 1), (SELECT id FROM forms WHERE name="Healthy Heart" LIMIT 1));

#21
 insert into `form_questions` (`question_id`,`form_id`)
 values((SELECT current_question.id FROM questions as current_question WHERE current_question.name="What kind of milk did you usually use? (Pick the one that you used most often in the past 30
days.)" LIMIT 1), (SELECT id FROM forms WHERE name="Healthy Heart" LIMIT 1));

#22
 insert into `form_questions` (`question_id`,`form_id`)
 values((SELECT current_question.id FROM questions as current_question WHERE current_question.name="What kinds of fat or oil did you usually use in cooking in the past 30 days (if more than one,
choose the one used most often)?" LIMIT 1), (SELECT id FROM forms WHERE name="Healthy Heart" LIMIT 1));


#23
 insert into `form_questions` (`question_id`,`form_id`)
 values((SELECT current_question.id FROM questions as current_question WHERE current_question.name="How often do you do the following things? Mark your answer with an X." LIMIT 1), (SELECT id FROM forms WHERE name="Healthy Heart" LIMIT 1));

#24
 insert into `form_questions` (`question_id`,`form_id`)
 values((SELECT current_question.id FROM questions as current_question WHERE current_question.name="Are you able to buy or grow low-cost vegetables?" LIMIT 1), (SELECT id FROM forms WHERE name="Healthy Heart" LIMIT 1));

#25
 insert into `form_questions` (`question_id`,`form_id`)
 values((SELECT current_question.id FROM questions as current_question WHERE current_question.name="In the future, do you intend to reduce the amount of fat you eat so it is lower than it is now?" LIMIT 1), (SELECT id FROM forms WHERE name="Healthy Heart" LIMIT 1));


 #26
 insert into `form_questions` (`question_id`,`form_id`)
 values((SELECT current_question.id FROM questions as current_question WHERE current_question.name="Do you smoke cigarettes now? (For these questions, we are not interested in the tobacco you may smoke for ceremonial use.)" LIMIT 1), (SELECT id FROM forms WHERE name="Healthy Heart" LIMIT 1));

 #27
 insert into `form_questions` (`question_id`,`form_id`)
 values((SELECT current_question.id FROM questions as current_question WHERE current_question.name="Thinking over the past 30 days, including today, how many days during this time did you
smoke?" LIMIT 1), (SELECT id FROM forms WHERE name="Healthy Heart" LIMIT 1));

 #28
 insert into `form_questions` (`question_id`,`form_id`)
 values((SELECT current_question.id FROM questions as current_question WHERE current_question.name="About how many cigarettes a day do you now smoke?" LIMIT 1), (SELECT id FROM forms WHERE name="Healthy Heart" LIMIT 1));

#29
 insert into `form_questions` (`question_id`,`form_id`)
 values((SELECT current_question.id FROM questions as current_question WHERE current_question.name="About how many years have you been smoking?" LIMIT 1), (SELECT id FROM forms WHERE name="Healthy Heart" LIMIT 1));

 #30
 insert into `form_questions` (`question_id`,`form_id`)
 values((SELECT current_question.id FROM questions as current_question WHERE current_question.name="In the past year, how many times have you quit smoking for at least 24 hours?" LIMIT 1), (SELECT id FROM forms WHERE name="Healthy Heart" LIMIT 1));

 #31
 insert into `form_questions` (`question_id`,`form_id`)
 values((SELECT current_question.id FROM questions as current_question WHERE current_question.name="Are you seriously thinking of quitting smoking?" LIMIT 1), (SELECT id FROM forms WHERE name="Healthy Heart" LIMIT 1));

  #32
 insert into `form_questions` (`question_id`,`form_id`)
 values((SELECT current_question.id FROM questions as current_question WHERE current_question.name="Do you think pain or discomfort in the jaw, neck, or back are symptoms of a heart attack?" LIMIT 1), (SELECT id FROM forms WHERE name="Healthy Heart" LIMIT 1));

  #33
 insert into `form_questions` (`question_id`,`form_id`)
 values((SELECT current_question.id FROM questions as current_question WHERE current_question.name="Do you think feeling weak, lightheaded, or faint are symptoms of a heart attack?" LIMIT 1), (SELECT id FROM forms WHERE name="Healthy Heart" LIMIT 1));

  #34
 insert into `form_questions` (`question_id`,`form_id`)
 values((SELECT current_question.id FROM questions as current_question WHERE current_question.name="Do you think swelling of the feet and legs is a symptom of a heart attack?" LIMIT 1), (SELECT id FROM forms WHERE name="Healthy Heart" LIMIT 1));


 #35
 insert into `form_questions` (`question_id`,`form_id`)
 values((SELECT current_question.id FROM questions as current_question WHERE current_question.name="Do you think chest pain or discomfort are symptoms of a heart attack?" LIMIT 1), (SELECT id FROM forms WHERE name="Healthy Heart" LIMIT 1));

 #36
 insert into `form_questions` (`question_id`,`form_id`)
 values((SELECT current_question.id FROM questions as current_question WHERE current_question.name="Do you think sudden trouble seeing in one or both eyes is a symptom of a heart attack?" LIMIT 1), (SELECT id FROM forms WHERE name="Healthy Heart" LIMIT 1));

 #37
 insert into `form_questions` (`question_id`,`form_id`)
 values((SELECT current_question.id FROM questions as current_question WHERE current_question.name="Do you think tingling in the fingers and toes are symptoms of a heart attack?" LIMIT 1), (SELECT id FROM forms WHERE name="Healthy Heart" LIMIT 1));

 #38
 insert into `form_questions` (`question_id`,`form_id`)
 values((SELECT current_question.id FROM questions as current_question WHERE current_question.name="Do you think pain or discomfort in the arms or shoulder are symptoms of a heart attack?" LIMIT 1), (SELECT id FROM forms WHERE name="Healthy Heart" LIMIT 1));

 #39
 insert into `form_questions` (`question_id`,`form_id`)
 values((SELECT current_question.id FROM questions as current_question WHERE current_question.name="Do you think shortness of breath is a symptom of a heart attack?" LIMIT 1), (SELECT id FROM forms WHERE name="Healthy Heart" LIMIT 1));

 #40
 insert into `form_questions` (`question_id`,`form_id`)
 values((SELECT current_question.id FROM questions as current_question WHERE current_question.name="Do you think sudden confusion or trouble speaking are symptoms of a stroke?" LIMIT 1), (SELECT id FROM forms WHERE name="Healthy Heart" LIMIT 1));

 #41
 insert into `form_questions` (`question_id`,`form_id`)
 values((SELECT current_question.id FROM questions as current_question WHERE current_question.name="Do you think sudden numbness or weakness of face, arm, or leg, especially on one side, are symptoms of a stroke?" LIMIT 1), (SELECT id FROM forms WHERE name="Healthy Heart" LIMIT 1));

 #42
 insert into `form_questions` (`question_id`,`form_id`)
 values((SELECT current_question.id FROM questions as current_question WHERE current_question.name="Do you think feeling sick to your stomach is a symptom of a stroke?" LIMIT 1), (SELECT id FROM forms WHERE name="Healthy Heart" LIMIT 1));

 #43
 insert into `form_questions` (`question_id`,`form_id`)
 values((SELECT current_question.id FROM questions as current_question WHERE current_question.name="Do you think sharp pain in the jaw or mouth is a symptom of a stroke?" LIMIT 1), (SELECT id FROM forms WHERE name="Healthy Heart" LIMIT 1));

 #44
 insert into `form_questions` (`question_id`,`form_id`)
 values((SELECT current_question.id FROM questions as current_question WHERE current_question.name="Do you think sudden trouble seeing in one or both eyes is a symptom of a stroke?" LIMIT 1), (SELECT id FROM forms WHERE name="Healthy Heart" LIMIT 1));

 #45
 insert into `form_questions` (`question_id`,`form_id`)
 values((SELECT current_question.id FROM questions as current_question WHERE current_question.name="Do you think sudden chest pain or discomfort are symptoms of a stroke?" LIMIT 1), (SELECT id FROM forms WHERE name="Healthy Heart" LIMIT 1));

 #46
 insert into `form_questions` (`question_id`,`form_id`)
 values((SELECT current_question.id FROM questions as current_question WHERE current_question.name="Do you think sudden trouble walking, dizziness, or loss of balance are symptoms of a stroke?" LIMIT 1), (SELECT id FROM forms WHERE name="Healthy Heart" LIMIT 1));

 #47
 insert into `form_questions` (`question_id`,`form_id`)
 values((SELECT current_question.id FROM questions as current_question WHERE current_question.name="Do you think severe headache with no known cause is a symptom of a stroke?" LIMIT 1), (SELECT id FROM forms WHERE name="Healthy Heart" LIMIT 1));

 #48
 insert into `form_questions` (`question_id`,`form_id`)
 values((SELECT current_question.id FROM questions as current_question WHERE current_question.name="If you thought someone was having a heart attack or a stroke, what is the first thing you would do?" LIMIT 1), (SELECT id FROM forms WHERE name="Healthy Heart" LIMIT 1));


 #49
 insert into `form_questions` (`question_id`,`form_id`)
 values((SELECT current_question.id FROM questions as current_question WHERE current_question.name="Can a large waist (>35 inches for women or >40 inches for men) increase your risk of heart disease?" LIMIT 1), (SELECT id FROM forms WHERE name="Healthy Heart" LIMIT 1));

 #50
 insert into `form_questions` (`question_id`,`form_id`)
 values((SELECT current_question.id FROM questions as current_question WHERE current_question.name="Can the Body Mass Index (BMI) Chart tell you if you are overweight?" LIMIT 1), (SELECT id FROM forms WHERE name="Healthy Heart" LIMIT 1));

 #51
 insert into `form_questions` (`question_id`,`form_id`)
 values((SELECT current_question.id FROM questions as current_question WHERE current_question.name="Does your liver make all the cholesterol your body needs to keep you healthy?" LIMIT 1), (SELECT id FROM forms WHERE name="Healthy Heart" LIMIT 1));

 #52
 insert into `form_questions` (`question_id`,`form_id`)
 values((SELECT current_question.id FROM questions as current_question WHERE current_question.name="Can eating foods that are high in sodium increase your risk of high blood pressure?" LIMIT 1), (SELECT id FROM forms WHERE name="Healthy Heart" LIMIT 1));

 #53
 insert into `form_questions` (`question_id`,`form_id`)
 values((SELECT current_question.id FROM questions as current_question WHERE current_question.name="Does lard have a low amount of saturated fat?" LIMIT 1), (SELECT id FROM forms WHERE name="Healthy Heart" LIMIT 1));

 #54
 insert into `form_questions` (`question_id`,`form_id`)
 values((SELECT current_question.id FROM questions as current_question WHERE current_question.name="Can eating too much saturated fat and trans fat raise your cholesterol level?" LIMIT 1), (SELECT id FROM forms WHERE name="Healthy Heart" LIMIT 1));

 #55
 insert into `form_questions` (`question_id`,`form_id`)
 values((SELECT current_question.id FROM questions as current_question WHERE current_question.name="Is a blood pressure of 140/90 mmHg considered high?" LIMIT 1), (SELECT id FROM forms WHERE name="Healthy Heart" LIMIT 1));

 #56
 insert into `form_questions` (`question_id`,`form_id`)
 values((SELECT current_question.id FROM questions as current_question WHERE current_question.name="Can being overweight or obese put you at risk for developing high blood cholesterol?" LIMIT 1), (SELECT id FROM forms WHERE name="Healthy Heart" LIMIT 1));

 #57
 insert into `form_questions` (`question_id`,`form_id`)
 values((SELECT current_question.id FROM questions as current_question WHERE current_question.name="Is being physically active a way to reduce your risk for heart disease?" LIMIT 1), (SELECT id FROM forms WHERE name="Healthy Heart" LIMIT 1));

 #58
 insert into `form_questions` (`question_id`,`form_id`)
 values((SELECT current_question.id FROM questions as current_question WHERE current_question.name="Is it true that only people with high blood cholesterol should follow a heart healthy diet?" LIMIT 1), (SELECT id FROM forms WHERE name="Healthy Heart" LIMIT 1));

 #59
 insert into `form_questions` (`question_id`,`form_id`)
 values((SELECT current_question.id FROM questions as current_question WHERE current_question.name="Can nonsmokers die from secondhand smoke?" LIMIT 1), (SELECT id FROM forms WHERE name="Healthy Heart" LIMIT 1));

 #60
 insert into `form_questions` (`question_id`,`form_id`)
 values((SELECT current_question.id FROM questions as current_question WHERE current_question.name="How often do you have a hard time understanding written information about your health that you get from your clinic? (This might include information from a doctor or nurse.)" LIMIT 1), (SELECT id FROM forms WHERE name="Healthy Heart" LIMIT 1));


 #61
 insert into `form_questions` (`question_id`,`form_id`)
 values((SELECT current_question.id FROM questions as current_question WHERE current_question.name="How confident are you in filling out medical forms by yourself?" LIMIT 1), (SELECT id FROM forms WHERE name="Healthy Heart" LIMIT 1));

 #62
 insert into `form_questions` (`question_id`,`form_id`)
 values((SELECT current_question.id FROM questions as current_question WHERE current_question.name="How often do you prefer that someone (like a family member or someone else) help you read medical materials?" LIMIT 1), (SELECT id FROM forms WHERE name="Healthy Heart" LIMIT 1));

 #63
 insert into `form_questions` (`question_id`,`form_id`)
 values((SELECT current_question.id FROM questions as current_question WHERE current_question.name="Which of the following numbers represents the lowest risk? For example, which would you most like to hear from a doctor about your risk for a medical condition?" LIMIT 1), (SELECT id FROM forms WHERE name="Healthy Heart" LIMIT 1));

 #64
 insert into `form_questions` (`question_id`,`form_id`)
 values((SELECT current_question.id FROM questions as current_question WHERE current_question.name="If the chance of getting a health condition is 20 out of 100 people, this would be the same as having a what percent (%) chance of getting the condition?" LIMIT 1), (SELECT id FROM forms WHERE name="Healthy Heart" LIMIT 1));

  #65
 insert into `form_questions` (`question_id`,`form_id`)
 values((SELECT current_question.id FROM questions as current_question WHERE current_question.name="A prescription says “Take one tablet by mouth every 6 hours.” If you take your first tablet at 7 a.m., when should you take your second tablet?" LIMIT 1), (SELECT id FROM forms WHERE name="Healthy Heart" LIMIT 1));

#66
 insert into `form_questions` (`question_id`,`form_id`)
 values((SELECT current_question.id FROM questions as current_question WHERE current_question.name="Normal fasting blood sugar is 70-100. If your blood sugar today is 140, is your blood sugar normal?" LIMIT 1), (SELECT id FROM forms WHERE name="Healthy Heart" LIMIT 1));

 #67
 insert into `form_questions` (`question_id`,`form_id`)
 values((SELECT current_question.id FROM questions as current_question WHERE current_question.name="Do you have a TV?" LIMIT 1), (SELECT id FROM forms WHERE name="Healthy Heart" LIMIT 1));

 #68
 insert into `form_questions` (`question_id`,`form_id`)
 values((SELECT current_question.id FROM questions as current_question WHERE current_question.name="Do you have a gaming system you hook up to your TV? By this we mean something like the Nintendo Wii, Xbox, or Sony Playstation?" LIMIT 1), (SELECT id FROM forms WHERE name="Healthy Heart" LIMIT 1));

 #69
 insert into `form_questions` (`question_id`,`form_id`)
 values((SELECT current_question.id FROM questions as current_question WHERE current_question.name="Which system do you have?" LIMIT 1), (SELECT id FROM forms WHERE name="Healthy Heart" LIMIT 1));

#70
 insert into `form_questions` (`question_id`,`form_id`)
 values((SELECT current_question.id FROM questions as current_question WHERE current_question.name="Do you have a personal computer in your home?" LIMIT 1), (SELECT id FROM forms WHERE name="Healthy Heart" LIMIT 1));

 #71
 insert into `form_questions` (`question_id`,`form_id`)
 values((SELECT current_question.id FROM questions as current_question WHERE current_question.name="Is it a Windows or Apple system?" LIMIT 1), (SELECT id FROM forms WHERE name="Healthy Heart" LIMIT 1));

 #72
 insert into `form_questions` (`question_id`,`form_id`)
 values((SELECT current_question.id FROM questions as current_question WHERE current_question.name="How confident are you in using your computer?" LIMIT 1), (SELECT id FROM forms WHERE name="Healthy Heart" LIMIT 1));

 #73
 insert into `form_questions` (`question_id`,`form_id`)
 values((SELECT current_question.id FROM questions as current_question WHERE current_question.name="Do you have Internet access?" LIMIT 1), (SELECT id FROM forms WHERE name="Healthy Heart" LIMIT 1));

 #74
 insert into `form_questions` (`question_id`,`form_id`)
 values((SELECT current_question.id FROM questions as current_question WHERE current_question.name="Do you have an e-mail account that you check regularly?" LIMIT 1), (SELECT id FROM forms WHERE name="Healthy Heart" LIMIT 1));

 #75
 insert into `form_questions` (`question_id`,`form_id`)
 values((SELECT current_question.id FROM questions as current_question WHERE current_question.name="Do you have a cell phone?" LIMIT 1), (SELECT id FROM forms WHERE name="Healthy Heart" LIMIT 1));

 #76
 insert into `form_questions` (`question_id`,`form_id`)
 values((SELECT current_question.id FROM questions as current_question WHERE current_question.name="Are you able to send and received text messages using your cell phone?" LIMIT 1), (SELECT id FROM forms WHERE name="Healthy Heart" LIMIT 1));

 #77
 insert into `form_questions` (`question_id`,`form_id`)
 values((SELECT current_question.id FROM questions as current_question WHERE current_question.name="Would you be willing to receive text messages about heart disease and heart-healthy living on your cell phone?" LIMIT 1), (SELECT id FROM forms WHERE name="Healthy Heart" LIMIT 1));

 #78
 insert into `form_questions` (`question_id`,`form_id`)
 values((SELECT current_question.id FROM questions as current_question WHERE current_question.name="What are some of the reasons you would not be interested in getting text messages about heart health?" LIMIT 1), (SELECT id FROM forms WHERE name="Healthy Heart" LIMIT 1));

 #79
 insert into `form_questions` (`question_id`,`form_id`)
 values((SELECT current_question.id FROM questions as current_question WHERE current_question.name="How much do you currently weigh without shoes?" LIMIT 1), (SELECT id FROM forms WHERE name="Healthy Heart" LIMIT 1));

#80
 insert into `form_questions` (`question_id`,`form_id`)
 values((SELECT current_question.id FROM questions as current_question WHERE current_question.name="How tall are you without shoes?" LIMIT 1), (SELECT id FROM forms WHERE name="Healthy Heart" LIMIT 1));

 #81
 insert into `form_questions` (`question_id`,`form_id`)
 values((SELECT current_question.id FROM questions as current_question WHERE current_question.name="Are you male or female?" LIMIT 1), (SELECT id FROM forms WHERE name="Healthy Heart" LIMIT 1));

 #82
 insert into `form_questions` (`question_id`,`form_id`)
 values((SELECT current_question.id FROM questions as current_question WHERE current_question.name="How old are you today?" LIMIT 1), (SELECT id FROM forms WHERE name="Healthy Heart" LIMIT 1));

 #83
 insert into `form_questions` (`question_id`,`form_id`)
 values((SELECT current_question.id FROM questions as current_question WHERE current_question.name="What is your ethnicity?" LIMIT 1), (SELECT id FROM forms WHERE name="Healthy Heart" LIMIT 1));

 #84
 insert into `form_questions` (`question_id`,`form_id`)
 values((SELECT current_question.id FROM questions as current_question WHERE current_question.name="What is your race?" LIMIT 1), (SELECT id FROM forms WHERE name="Healthy Heart" LIMIT 1));

#85
 insert into `form_questions` (`question_id`,`form_id`)
 values((SELECT current_question.id FROM questions as current_question WHERE current_question.name="If you marked “American Indian or Alaska Native” in the previous question, what tribe do you most closely identify with?" LIMIT 1), (SELECT id FROM forms WHERE name="Healthy Heart" LIMIT 1));
 
 #86
 insert into `form_questions` (`question_id`,`form_id`)
 values((SELECT current_question.id FROM questions as current_question WHERE current_question.name="What is the highest grade in school you completed?" LIMIT 1), (SELECT id FROM forms WHERE name="Healthy Heart" LIMIT 1));


 #87
 insert into `form_questions` (`question_id`,`form_id`)
 values((SELECT current_question.id FROM questions as current_question WHERE current_question.name="Please fill in the category below that best fits the total combined income before taxes of all people who lived in your household last year. This should include not only wages, salaries, and tips but also income from social security, pension, unemployment, or disability compensation, alimony, child support, welfare, or any other money income received by all household members – by you or anyone else living with you." LIMIT 1), (SELECT id FROM forms WHERE name="Healthy Heart" LIMIT 1));



# PsychiatricPhysicalSymptoms

insert into `questions` (`name`, `type`, `is_active`)
 values('During the past 7 days, how much have you been bothered by any of the following problems?','sub_question', 1);

insert into `questions`( `parent_id`, `name`, `type`, `is_active`)
 values((SELECT current_question.id FROM questions as current_question WHERE current_question.name="During the past 7 days, how much have you been bothered by any of the following problems?" LIMIT 1),
 	'Stomach pain','radio',1),
 ((SELECT current_question.id FROM questions as current_question WHERE current_question.name="During the past 7 days, how much have you been bothered by any of the following problems?" LIMIT 1),
 	'Back pain','radio',1),
 ((SELECT current_question.id FROM questions as current_question WHERE current_question.name="During the past 7 days, how much have you been bothered by any of the following problems?" LIMIT 1),
 	'Pain in your arms, legs, or joints (knees, hips, etc.)','radio',1),
 ((SELECT current_question.id FROM questions as current_question WHERE current_question.name="During the past 7 days, how much have you been bothered by any of the following problems?" LIMIT 1),
 	'Menstrual cramps or other problems with your periods WOMEN ONLY','radio',1),
 ((SELECT current_question.id FROM questions as current_question WHERE current_question.name="During the past 7 days, how much have you been bothered by any of the following problems?" LIMIT 1),
 	'Headaches','radio',1),
 ((SELECT current_question.id FROM questions as current_question WHERE current_question.name="During the past 7 days, how much have you been bothered by any of the following problems?" LIMIT 1),
 	'Chest pain','radio',1),
 ((SELECT current_question.id FROM questions as current_question WHERE current_question.name="During the past 7 days, how much have you been bothered by any of the following problems?" LIMIT 1),
 	'Dizziness','radio',1),
 ((SELECT current_question.id FROM questions as current_question WHERE current_question.name="During the past 7 days, how much have you been bothered by any of the following problems?" LIMIT 1),
 	'Fainting spells','radio',1),
 ((SELECT current_question.id FROM questions as current_question WHERE current_question.name="During the past 7 days, how much have you been bothered by any of the following problems?" LIMIT 1),
 	'Feeling your heart pound or race','radio',1),
 ((SELECT current_question.id FROM questions as current_question WHERE current_question.name="During the past 7 days, how much have you been bothered by any of the following problems?" LIMIT 1),
 	'Shortness of breath','radio',1),
 ((SELECT current_question.id FROM questions as current_question WHERE current_question.name="During the past 7 days, how much have you been bothered by any of the following problems?" LIMIT 1),
 	'Pain or problems during sexual intercourse','radio',1),
 ((SELECT current_question.id FROM questions as current_question WHERE current_question.name="During the past 7 days, how much have you been bothered by any of the following problems?" LIMIT 1),
 	'Constipation, loose bowels, or diarrhea','radio',1),
 ((SELECT current_question.id FROM questions as current_question WHERE current_question.name="During the past 7 days, how much have you been bothered by any of the following problems?" LIMIT 1),
 	'Nausea, gas, or indigestion','radio',1),
 ((SELECT current_question.id FROM questions as current_question WHERE current_question.name="During the past 7 days, how much have you been bothered by any of the following problems?" LIMIT 1),
 	'Feeling tired or having low energy','radio',1),
 ((SELECT current_question.id FROM questions as current_question WHERE current_question.name="During the past 7 days, how much have you been bothered by any of the following problems?" LIMIT 1),
 	'Trouble sleeping','radio',1);

 insert into `answers`(`name`,`is_active`)
 values
 ('Not bothered at all',1),
 ('Bothered a little',1),
 ('Bothered a lot',1);

insert into `form_question_answers` (`form_question_id`,`answer_id`,`jump_to_question_id`,`score`)
 values((SELECT current_question.id FROM questions as current_question WHERE current_question.name="During the past 7 days, how much have you been bothered by any of the following problems?" LIMIT 1), 
 	(SELECT id FROM answers WHERE name="Not bothered at all" LIMIT 1), null,0),
 ((SELECT current_question.id FROM questions as current_question WHERE current_question.name="During the past 7 days, how much have you been bothered by any of the following problems?" LIMIT 1), 
 	(SELECT id FROM answers WHERE name="Bothered a little" LIMIT 1), null,1),
 ((SELECT current_question.id FROM questions as current_question WHERE current_question.name="During the past 7 days, how much have you been bothered by any of the following problems?" LIMIT 1), 
 	(SELECT id FROM answers WHERE name="Bothered a lot" LIMIT 1), null,2);


 insert into `form_questions` (`question_id`,`form_id`)
 values((SELECT current_question.id FROM questions as current_question WHERE current_question.name="During the past 7 days, how much have you been bothered by any of the following problems?" LIMIT 1), 
 	(SELECT id FROM forms WHERE name="Physical Symptoms" LIMIT 1));

 # PsychiatricAnger




insert into `questions` (`name`, `type`, `is_active`)
 values('In the past SEVEN (7) DAYS....','sub_question', 1);

insert into `questions`( `parent_id`, `name`, `type`, `is_active`)
 values((SELECT current_question.id FROM questions as current_question WHERE current_question.name="In the past SEVEN (7) DAYS...." LIMIT 1),
 	'I was irritated more than people knew.','radio',1),
 ((SELECT current_question.id FROM questions as current_question WHERE current_question.name="In the past SEVEN (7) DAYS...." LIMIT 1),
 	'I felt angry.','radio',1),
 ((SELECT current_question.id FROM questions as current_question WHERE current_question.name="In the past SEVEN (7) DAYS...." LIMIT 1),
 	'I felt like I was ready to explode.','radio',1),
 ((SELECT current_question.id FROM questions as current_question WHERE current_question.name="In the past SEVEN (7) DAYS...." LIMIT 1),
 	'I was grouchy.','radio',1),
 ((SELECT current_question.id FROM questions as current_question WHERE current_question.name="In the past SEVEN (7) DAYS...." LIMIT 1),
 	'I felt annoyed.','radio',1);



insert into `form_question_answers` (`form_question_id`,`answer_id`,`jump_to_question_id`,`score`)
 values((SELECT current_question.id FROM questions as current_question WHERE current_question.name="In the past SEVEN (7) DAYS...." LIMIT 1), 
 	(SELECT id FROM answers WHERE name="Never" LIMIT 1), null, 1),
 ((SELECT current_question.id FROM questions as current_question WHERE current_question.name="In the past SEVEN (7) DAYS...." LIMIT 1), 
 	(SELECT id FROM answers WHERE name="Rarely" LIMIT 1), null,2),
 ((SELECT current_question.id FROM questions as current_question WHERE current_question.name="In the past SEVEN (7) DAYS...." LIMIT 1), 
 	(SELECT id FROM answers WHERE name="Sometimes" LIMIT 1), null,3),
 ((SELECT current_question.id FROM questions as current_question WHERE current_question.name="In the past SEVEN (7) DAYS...." LIMIT 1), 
 	(SELECT id FROM answers WHERE name="Often" LIMIT 1), null,4),
 ((SELECT current_question.id FROM questions as current_question WHERE current_question.name="In the past SEVEN (7) DAYS...." LIMIT 1), 
 	(SELECT id FROM answers WHERE name="Always" LIMIT 1), null,5);


 insert into `form_questions` (`question_id`,`form_id`)
 values((SELECT current_question.id FROM questions as current_question WHERE current_question.name="In the past SEVEN (7) DAYS...." LIMIT 1), 
 	(SELECT id FROM forms WHERE name="Anger" LIMIT 1));

# PsychiatricAnxiety

insert into `questions`( `parent_id`, `name`, `type`, `is_active`)
 values((SELECT current_question.id FROM questions as current_question WHERE current_question.name="In the past SEVEN (7) DAYS...." LIMIT 1),
  'I felt fearful.','radio',1),
 ((SELECT current_question.id FROM questions as current_question WHERE current_question.name="In the past SEVEN (7) DAYS...." LIMIT 1),
  'I felt anxious.','radio',1),
 ((SELECT current_question.id FROM questions as current_question WHERE current_question.name="In the past SEVEN (7) DAYS...." LIMIT 1),
  'I felt worried.','radio',1),
 ((SELECT current_question.id FROM questions as current_question WHERE current_question.name="In the past SEVEN (7) DAYS...." LIMIT 1),
  'I found it hard to focus on anything other than my anxiety.','radio',1),
 ((SELECT current_question.id FROM questions as current_question WHERE current_question.name="In the past SEVEN (7) DAYS...." LIMIT 1),
  'I felt nervous.','radio',1),
 ((SELECT current_question.id FROM questions as current_question WHERE current_question.name="In the past SEVEN (7) DAYS...." LIMIT 1),
  'I felt uneasy.','radio',1),
 ((SELECT current_question.id FROM questions as current_question WHERE current_question.name="In the past SEVEN (7) DAYS...." LIMIT 1),
  'I felt tense.','radio',1);



insert into `form_question_answers` (`form_question_id`,`answer_id`,`jump_to_question_id`,`score`)
 values((SELECT current_question.id FROM questions as current_question WHERE current_question.name="In the past SEVEN (7) DAYS...." LIMIT 1), 
 	(SELECT id FROM answers WHERE name="Never" LIMIT 1), null,1),
 ((SELECT current_question.id FROM questions as current_question WHERE current_question.name="In the past SEVEN (7) DAYS...." LIMIT 1), 
 	(SELECT id FROM answers WHERE name="Rarely" LIMIT 1), null,2),
 ((SELECT current_question.id FROM questions as current_question WHERE current_question.name="In the past SEVEN (7) DAYS...." LIMIT 1), 
 	(SELECT id FROM answers WHERE name="Sometimes" LIMIT 1), null,3),
 ((SELECT current_question.id FROM questions as current_question WHERE current_question.name="In the past SEVEN (7) DAYS...." LIMIT 1), 
 	(SELECT id FROM answers WHERE name="Often" LIMIT 1), null,4),
 ((SELECT current_question.id FROM questions as current_question WHERE current_question.name="In the past SEVEN (7) DAYS...." LIMIT 1), 
 	(SELECT id FROM answers WHERE name="Always" LIMIT 1), null,5);


 insert into `form_questions` (`question_id`,`form_id`)
 values((SELECT current_question.id FROM questions as current_question WHERE current_question.name="In the past SEVEN (7) DAYS...." LIMIT 1), 
 	(SELECT id FROM forms WHERE name="Anxiety" LIMIT 1));

# PsychiatricDepression

insert into `questions`( `parent_id`, `name`, `type`, `is_active`)
 values((SELECT current_question.id FROM questions as current_question WHERE current_question.name="In the past SEVEN (7) DAYS...." LIMIT 1),
 	'I felt worthless.','radio',1),
 ((SELECT current_question.id FROM questions as current_question WHERE current_question.name="In the past SEVEN (7) DAYS...." LIMIT 1),
 	'I felt that I had nothing to look forward to.','radio',1),
 ((SELECT current_question.id FROM questions as current_question WHERE current_question.name="In the past SEVEN (7) DAYS...." LIMIT 1),
 	'I felt helpless.','radio',1),
 ((SELECT current_question.id FROM questions as current_question WHERE current_question.name="In the past SEVEN (7) DAYS...." LIMIT 1),
 	'I felt sad.','radio',1),
 ((SELECT current_question.id FROM questions as current_question WHERE current_question.name="In the past SEVEN (7) DAYS...." LIMIT 1),
 	'I felt like a failure.','radio',1),
 ((SELECT current_question.id FROM questions as current_question WHERE current_question.name="In the past SEVEN (7) DAYS...." LIMIT 1),
 	'I felt depressed.','radio',1),
 ((SELECT current_question.id FROM questions as current_question WHERE current_question.name="In the past SEVEN (7) DAYS...." LIMIT 1),
 	'I felt unhappy.','radio',1),
 ((SELECT current_question.id FROM questions as current_question WHERE current_question.name="In the past SEVEN (7) DAYS...." LIMIT 1),
 	'I felt hopeless.','radio',1);

insert into `form_question_answers` (`form_question_id`,`answer_id`,`jump_to_question_id`,`score`)
 values((SELECT current_question.id FROM questions as current_question WHERE current_question.name="In the past SEVEN (7) DAYS...." LIMIT 1), 
 	(SELECT id FROM answers WHERE name="Never" LIMIT 1), null,1),
 ((SELECT current_question.id FROM questions as current_question WHERE current_question.name="In the past SEVEN (7) DAYS...." LIMIT 1), 
 	(SELECT id FROM answers WHERE name="Rarely" LIMIT 1), null,2),
 ((SELECT current_question.id FROM questions as current_question WHERE current_question.name="In the past SEVEN (7) DAYS...." LIMIT 1), 
 	(SELECT id FROM answers WHERE name="Sometimes" LIMIT 1), null,3),
 ((SELECT current_question.id FROM questions as current_question WHERE current_question.name="In the past SEVEN (7) DAYS...." LIMIT 1), 
 	(SELECT id FROM answers WHERE name="Often" LIMIT 1), null,4),
 ((SELECT current_question.id FROM questions as current_question WHERE current_question.name="In the past SEVEN (7) DAYS...." LIMIT 1), 
 	(SELECT id FROM answers WHERE name="Always" LIMIT 1), null,5);


 insert into `form_questions` (`question_id`,`form_id`)
 values((SELECT current_question.id FROM questions as current_question WHERE current_question.name="In the past SEVEN (7) DAYS...." LIMIT 1), 
 	(SELECT id FROM forms WHERE name="Depression" LIMIT 1));

# PsychiatricMania

 insert into `questions` (`name`, `type`, `is_active`)
 values('Question 1','radio', 1),
 ('Question 2','radio', 1),
 ('Question 3','radio', 1),
 ('Question 4','radio', 1),
 ('Question 5','radio', 1);

# Question 1
insert into `answers`(`name`,`is_active`)
 values('I do not feel happier or more cheerful than usual.',1),
 ('I occasionally feel happier or more cheerful than usual.',1),
 ('I often feel happier or more cheerful than usual.',1),
 ('I feel happier or more cheerful than usual most of the time.',1),
 ('I feel happier of more cheerful than usual all of the time.',1);

# Question 2
insert into `answers`(`name`,`is_active`)
 values('I do not feel more self-confident than usual.',1),
 ('I occasionally feel more self-confident than usual.',1),
 ('I often feel more self-confident than usual.',1),
 ('I frequently feel more self-confident than usual.',1),
 ('I feel extremely self-confident all of the time.',1);

# Question 3
insert into `answers`(`name`,`is_active`)
 values('I do not need less sleep than usual.',1),
 ('I occasionally need less sleep than usual.',1),
 ('I often need less sleep than usual.',1),
 ('I frequently need less sleep than usual.',1),
 ('I can go all day and all night without any sleep and still not feel tired.',1);

# Question 4
insert into `answers`(`name`,`is_active`)
 values('I do not talk more than usual.',1),
 ('I occasionally talk more than usual.',1),
 ('I often talk more than usual.',1),
 ('I frequently talk more than usual.',1),
 ('I talk constantly and cannot be interrupted.',1);

# Question 5
insert into `answers`(`name`,`is_active`)
 values('I have not been more active (either socially, sexually, at work, home, or school) than usual.',1),
 ('I have occasionally been more active than usual.',1),
 ('I have often been more active than usual.',1),
 ('I have frequently been more active than usual.',1),
 ('I am constantly more active or on the go all the time.',1);


insert into `form_question_answers` (`form_question_id`,`answer_id`,`jump_to_question_id`,`score`)
 values((SELECT current_question.id FROM questions as current_question WHERE current_question.name="Question 1" LIMIT 1), 
 	(SELECT id FROM answers WHERE name="I do not feel happier or more cheerful than usual." LIMIT 1), null,
 	1),
 ((SELECT current_question.id FROM questions as current_question WHERE current_question.name="Question 1" LIMIT 1), 
 	(SELECT id FROM answers WHERE name="I occasionally feel happier or more cheerful than usual." LIMIT 1), null,2),
 ((SELECT current_question.id FROM questions as current_question WHERE current_question.name="Question 1" LIMIT 1), 
 	(SELECT id FROM answers WHERE name="I often feel happier or more cheerful than usual." LIMIT 1), null,3),
 ((SELECT current_question.id FROM questions as current_question WHERE current_question.name="Question 1" LIMIT 1), 
 	(SELECT id FROM answers WHERE name="I feel happier or more cheerful than usual most of the time." LIMIT 1), null,4),
 ((SELECT current_question.id FROM questions as current_question WHERE current_question.name="Question 1" LIMIT 1), 
 	(SELECT id FROM answers WHERE name="I feel happier of more cheerful than usual all of the time." LIMIT 1), null,5);

 insert into `form_question_answers` (`form_question_id`,`answer_id`,`jump_to_question_id`,`score`)
 values((SELECT current_question.id FROM questions as current_question WHERE current_question.name="Question 2" LIMIT 1), 
 	(SELECT id FROM answers WHERE name="I do not feel more self-confident than usual." LIMIT 1), null,1),
 ((SELECT current_question.id FROM questions as current_question WHERE current_question.name="Question 2" LIMIT 1), 
 	(SELECT id FROM answers WHERE name="I occasionally feel more self-confident than usual." LIMIT 1), null,2),
 ((SELECT current_question.id FROM questions as current_question WHERE current_question.name="Question 2" LIMIT 1), 
 	(SELECT id FROM answers WHERE name="I often feel more self-confident than usual." LIMIT 1), null,3),
 ((SELECT current_question.id FROM questions as current_question WHERE current_question.name="Question 2" LIMIT 1), 
 	(SELECT id FROM answers WHERE name="I frequently feel more self-confident than usual." LIMIT 1), null,4),
 ((SELECT current_question.id FROM questions as current_question WHERE current_question.name="Question 2" LIMIT 1), 
 	(SELECT id FROM answers WHERE name="I feel extremely self-confident all of the time." LIMIT 1), null,5);


 insert into `form_question_answers` (`form_question_id`,`answer_id`,`jump_to_question_id`,`score`)
 values((SELECT current_question.id FROM questions as current_question WHERE current_question.name="Question 3" LIMIT 1), 
 	(SELECT id FROM answers WHERE name="I do not need less sleep than usual." LIMIT 1), null,1),
 ((SELECT current_question.id FROM questions as current_question WHERE current_question.name="Question 3" LIMIT 1), 
 	(SELECT id FROM answers WHERE name="I occasionally need less sleep than usual." LIMIT 1), null,2),
 ((SELECT current_question.id FROM questions as current_question WHERE current_question.name="Question 3" LIMIT 1), 
 	(SELECT id FROM answers WHERE name="I often need less sleep than usual." LIMIT 1), null,3),
 ((SELECT current_question.id FROM questions as current_question WHERE current_question.name="Question 3" LIMIT 1), 
 	(SELECT id FROM answers WHERE name="I frequently need less sleep than usual." LIMIT 1), null,4),
 ((SELECT current_question.id FROM questions as current_question WHERE current_question.name="Question 3" LIMIT 1), 
 	(SELECT id FROM answers WHERE name="I can go all day and all night without any sleep and still not feel tired." LIMIT 1), null,5);



 insert into `form_question_answers` (`form_question_id`,`answer_id`,`jump_to_question_id`,`score`)
 values((SELECT current_question.id FROM questions as current_question WHERE current_question.name="Question 4" LIMIT 1), 
 	(SELECT id FROM answers WHERE name="I do not talk more than usual." LIMIT 1), null,1),
 ((SELECT current_question.id FROM questions as current_question WHERE current_question.name="Question 4" LIMIT 1), 
 	(SELECT id FROM answers WHERE name="I occasionally talk more than usual." LIMIT 1), null,2),
 ((SELECT current_question.id FROM questions as current_question WHERE current_question.name="Question 4" LIMIT 1), 
 	(SELECT id FROM answers WHERE name="I often talk more than usual." LIMIT 1), null,3),
 ((SELECT current_question.id FROM questions as current_question WHERE current_question.name="Question 4" LIMIT 1), 
 	(SELECT id FROM answers WHERE name="I frequently talk more than usual." LIMIT 1), null,4),
 ((SELECT current_question.id FROM questions as current_question WHERE current_question.name="Question 4" LIMIT 1), 
 	(SELECT id FROM answers WHERE name="I talk constantly and cannot be interrupted." LIMIT 1), null,5);


 insert into `form_question_answers` (`form_question_id`,`answer_id`,`jump_to_question_id`,`score`)
 values((SELECT current_question.id FROM questions as current_question WHERE current_question.name="Question 5" LIMIT 1), 
 	(SELECT id FROM answers WHERE name="I have not been more active (either socially, sexually, at work, home, or school) than usual." LIMIT 1), null,1),
 ((SELECT current_question.id FROM questions as current_question WHERE current_question.name="Question 5" LIMIT 1), 
 	(SELECT id FROM answers WHERE name="I have occasionally been more active than usual." LIMIT 1), null,2),
 ((SELECT current_question.id FROM questions as current_question WHERE current_question.name="Question 5" LIMIT 1), 
 	(SELECT id FROM answers WHERE name="I have often been more active than usual." LIMIT 1), null,3),
 ((SELECT current_question.id FROM questions as current_question WHERE current_question.name="Question 5" LIMIT 1), 
 	(SELECT id FROM answers WHERE name="I have frequently been more active than usual." LIMIT 1), null,4),
 ((SELECT current_question.id FROM questions as current_question WHERE current_question.name="Question 5" LIMIT 1), 
 	(SELECT id FROM answers WHERE name="I am constantly more active or on the go all the time." LIMIT 1), null,5);


 insert into `form_questions` (`question_id`,`form_id`)
 values((SELECT current_question.id FROM questions as current_question WHERE current_question.name="Question 1" LIMIT 1), 
 	(SELECT id FROM forms WHERE name="Mania" LIMIT 1)),
 ((SELECT current_question.id FROM questions as current_question WHERE current_question.name="Question 2" LIMIT 1), 
 	(SELECT id FROM forms WHERE name="Mania" LIMIT 1)),
 ((SELECT current_question.id FROM questions as current_question WHERE current_question.name="Question 3" LIMIT 1), 
 	(SELECT id FROM forms WHERE name="Mania" LIMIT 1)),
 ((SELECT current_question.id FROM questions as current_question WHERE current_question.name="Question 4" LIMIT 1), 
 	(SELECT id FROM forms WHERE name="Mania" LIMIT 1)),
 ((SELECT current_question.id FROM questions as current_question WHERE current_question.name="Question 5" LIMIT 1), 
 	(SELECT id FROM forms WHERE name="Mania" LIMIT 1));

 # Strokes Scale

 # StrokeScaleGeneral

insert into `questions` (`name`, `type`, `is_active`)values('Sudden numbness','radio', 1);
insert into `questions` (`name`, `type`, `is_active`)values('Sudden Weakness','radio', 1);
insert into `questions` (`name`, `type`, `is_active`)values('Sudden Disability','radio', 1);
insert into `questions` (`name`, `type`, `is_active`)values('Vision','radio', 1);
insert into `questions` (`name`, `type`, `is_active`)values('Gait / Posture','radio', 1);
insert into `questions` (`name`, `type`, `is_active`)values('Seizure','radio', 1);
insert into `questions` (`name`, `type`, `is_active`)values('Sudden Pain / Ache','radio', 1);
insert into `questions` (`name`, `type`, `is_active`)values('F—Face: Ask the person to smile. Does one side of the face droop','radio', 1);
insert into `questions` (`name`, `type`, `is_active`)values('A—Arms: Ask the person to raise both arms. Does one arm drift downward','radio', 1);
insert into `questions` (`name`, `type`, `is_active`)values('S—Speech: Ask the person to repeat a simple phrase. Is the speech slurred or strange','radio', 1);
insert into `questions` (`name`, `type`, `is_active`)values('T—Time: If you see any of these signs, call +919840056700','radio', 1);


 insert into `answers`(`name`,`is_active`)
 values
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
 ('Other Parts of Body',1);

insert into `form_question_answers` (`form_question_id`,`answer_id`,`jump_to_question_id`)
 values((SELECT current_question.id FROM questions as current_question WHERE current_question.name="Sudden numbness" LIMIT 1), 
 	(SELECT id FROM answers WHERE name="face" LIMIT 1), null),
 ((SELECT current_question.id FROM questions as current_question WHERE current_question.name="Sudden numbness" LIMIT 1), 
 	(SELECT id FROM answers WHERE name="arm" LIMIT 1), null),
 ((SELECT current_question.id FROM questions as current_question WHERE current_question.name="Sudden numbness" LIMIT 1), 
 	(SELECT id FROM answers WHERE name="leg" LIMIT 1), null);


insert into `form_question_answers` (`form_question_id`,`answer_id`,`jump_to_question_id`)
 values((SELECT current_question.id FROM questions as current_question WHERE current_question.name="Sudden Weakness" LIMIT 1), 
 	(SELECT id FROM answers WHERE name="face" LIMIT 1), null),
 ((SELECT current_question.id FROM questions as current_question WHERE current_question.name="Sudden Weakness" LIMIT 1), 
 	(SELECT id FROM answers WHERE name="arm" LIMIT 1), null),
 ((SELECT current_question.id FROM questions as current_question WHERE current_question.name="Sudden Weakness" LIMIT 1), 
 	(SELECT id FROM answers WHERE name="leg" LIMIT 1), null);


insert into `form_question_answers` (`form_question_id`,`answer_id`,`jump_to_question_id`)
 values((SELECT current_question.id FROM questions as current_question WHERE current_question.name="Sudden Disability" LIMIT 1), 
 	(SELECT id FROM answers WHERE name="face" LIMIT 1), null),
 ((SELECT current_question.id FROM questions as current_question WHERE current_question.name="Sudden Disability" LIMIT 1), 
 	(SELECT id FROM answers WHERE name="arm" LIMIT 1), null),
 ((SELECT current_question.id FROM questions as current_question WHERE current_question.name="Sudden Disability" LIMIT 1), 
 	(SELECT id FROM answers WHERE name="leg" LIMIT 1), null);


insert into `form_question_answers` (`form_question_id`,`answer_id`,`jump_to_question_id`)
 values((SELECT current_question.id FROM questions as current_question WHERE current_question.name="Vision" LIMIT 1), 
 	(SELECT id FROM answers WHERE name="Blurring" LIMIT 1), null),
 ((SELECT current_question.id FROM questions as current_question WHERE current_question.name="Vision" LIMIT 1), 
 	(SELECT id FROM answers WHERE name="Double Vision" LIMIT 1), null),
 ((SELECT current_question.id FROM questions as current_question WHERE current_question.name="Vision" LIMIT 1), 
 	(SELECT id FROM answers WHERE name="Dropping of eye lid" LIMIT 1), null),
 ((SELECT current_question.id FROM questions as current_question WHERE current_question.name="Vision" LIMIT 1), 
 	(SELECT id FROM answers WHERE name="Loss of Vision" LIMIT 1), null);


insert into `form_question_answers` (`form_question_id`,`answer_id`,`jump_to_question_id`)
 values((SELECT current_question.id FROM questions as current_question WHERE current_question.name="Gait / Posture" LIMIT 1), 
 	(SELECT id FROM answers WHERE name="Lack of coordination" LIMIT 1), null),
 ((SELECT current_question.id FROM questions as current_question WHERE current_question.name="Gait / Posture" LIMIT 1), 
 	(SELECT id FROM answers WHERE name="Unable to move" LIMIT 1), null);

 insert into `form_question_answers` (`form_question_id`,`answer_id`,`jump_to_question_id`)
 values((SELECT current_question.id FROM questions as current_question WHERE current_question.name="Seizure" LIMIT 1), 
 	(SELECT id FROM answers WHERE name="Yes" LIMIT 1), null),
 ((SELECT current_question.id FROM questions as current_question WHERE current_question.name="Seizure" LIMIT 1), 
 	(SELECT id FROM answers WHERE name="No" LIMIT 1), null);

insert into `form_question_answers` (`form_question_id`,`answer_id`,`jump_to_question_id`)
 values((SELECT current_question.id FROM questions as current_question WHERE current_question.name="Sudden Pain / Ache" LIMIT 1), 
 	(SELECT id FROM answers WHERE name="Head" LIMIT 1), null),
 ((SELECT current_question.id FROM questions as current_question WHERE current_question.name="Sudden Pain / Ache" LIMIT 1), 
 	(SELECT id FROM answers WHERE name="Neck" LIMIT 1), null),
 ((SELECT current_question.id FROM questions as current_question WHERE current_question.name="Sudden Pain / Ache" LIMIT 1), 
 	(SELECT id FROM answers WHERE name="Other Parts of Body" LIMIT 1), null);


 insert into `form_question_answers` (`form_question_id`,`answer_id`,`jump_to_question_id`)
 values((SELECT current_question.id FROM questions as current_question WHERE current_question.name="F—Face: Ask the person to smile. Does one side of the face droop" LIMIT 1), 
 	(SELECT id FROM answers WHERE name="Yes" LIMIT 1), null),
 ((SELECT current_question.id FROM questions as current_question WHERE current_question.name="F—Face: Ask the person to smile. Does one side of the face droop" LIMIT 1), 
 	(SELECT id FROM answers WHERE name="No" LIMIT 1), null);

 insert into `form_question_answers` (`form_question_id`,`answer_id`,`jump_to_question_id`)
 values((SELECT current_question.id FROM questions as current_question WHERE current_question.name="A—Arms: Ask the person to raise both arms. Does one arm drift downward" LIMIT 1), 
 	(SELECT id FROM answers WHERE name="Yes" LIMIT 1), null),
 ((SELECT current_question.id FROM questions as current_question WHERE current_question.name="A—Arms: Ask the person to raise both arms. Does one arm drift downward" LIMIT 1), 
 	(SELECT id FROM answers WHERE name="No" LIMIT 1), null);

 insert into `form_question_answers` (`form_question_id`,`answer_id`,`jump_to_question_id`)
 values((SELECT current_question.id FROM questions as current_question WHERE current_question.name="S—Speech: Ask the person to repeat a simple phrase. Is the speech slurred or strange" LIMIT 1), 
 	(SELECT id FROM answers WHERE name="Yes" LIMIT 1), null),
 ((SELECT current_question.id FROM questions as current_question WHERE current_question.name="S—Speech: Ask the person to repeat a simple phrase. Is the speech slurred or strange" LIMIT 1), 
 	(SELECT id FROM answers WHERE name="No" LIMIT 1), null);

 insert into `form_question_answers` (`form_question_id`,`answer_id`,`jump_to_question_id`)
 values((SELECT current_question.id FROM questions as current_question WHERE current_question.name="T—Time: If you see any of these signs, call +919840056700" LIMIT 1), 
 	(SELECT id FROM answers WHERE name="Yes" LIMIT 1), null),
 ((SELECT current_question.id FROM questions as current_question WHERE current_question.name="T—Time: If you see any of these signs, call +919840056700" LIMIT 1), 
 	(SELECT id FROM answers WHERE name="No" LIMIT 1), null);

insert into `form_questions` (`question_id`,`form_id`)
 values((SELECT current_question.id FROM questions as current_question WHERE current_question.name="Sudden numbness" LIMIT 1), 
 	(SELECT id FROM forms WHERE name="Symptoms & Signs" LIMIT 1));

insert into `form_questions` (`question_id`,`form_id`)
 values((SELECT current_question.id FROM questions as current_question WHERE current_question.name="Sudden Weakness" LIMIT 1), 
 	(SELECT id FROM forms WHERE name="Symptoms & Signs" LIMIT 1));

insert into `form_questions` (`question_id`,`form_id`)
 values((SELECT current_question.id FROM questions as current_question WHERE current_question.name="Sudden Disability" LIMIT 1), 
 	(SELECT id FROM forms WHERE name="Symptoms & Signs" LIMIT 1));

insert into `form_questions` (`question_id`,`form_id`)
 values((SELECT current_question.id FROM questions as current_question WHERE current_question.name="Vision" LIMIT 1), 
 	(SELECT id FROM forms WHERE name="Symptoms & Signs" LIMIT 1));

insert into `form_questions` (`question_id`,`form_id`)
 values((SELECT current_question.id FROM questions as current_question WHERE current_question.name="Gait / Posture" LIMIT 1), 
 	(SELECT id FROM forms WHERE name="Symptoms & Signs" LIMIT 1));

insert into `form_questions` (`question_id`,`form_id`)
 values((SELECT current_question.id FROM questions as current_question WHERE current_question.name="Seizure" LIMIT 1), 
 	(SELECT id FROM forms WHERE name="Symptoms & Signs" LIMIT 1));

insert into `form_questions` (`question_id`,`form_id`)
 values((SELECT current_question.id FROM questions as current_question WHERE current_question.name="Sudden Pain / Ache" LIMIT 1), 
 	(SELECT id FROM forms WHERE name="Symptoms & Signs" LIMIT 1));

insert into `form_questions` (`question_id`,`form_id`)
 values((SELECT current_question.id FROM questions as current_question WHERE current_question.name="F—Face: Ask the person to smile. Does one side of the face droop" LIMIT 1), 
 	(SELECT id FROM forms WHERE name="Symptoms & Signs" LIMIT 1));

insert into `form_questions` (`question_id`,`form_id`)
 values((SELECT current_question.id FROM questions as current_question WHERE current_question.name="A—Arms: Ask the person to raise both arms. Does one arm drift downward" LIMIT 1), 
 	(SELECT id FROM forms WHERE name="Symptoms & Signs" LIMIT 1));

insert into `form_questions` (`question_id`,`form_id`)
 values((SELECT current_question.id FROM questions as current_question WHERE current_question.name="S—Speech: Ask the person to repeat a simple phrase. Is the speech slurred or strange" LIMIT 1), 
 	(SELECT id FROM forms WHERE name="Symptoms & Signs" LIMIT 1));

insert into `form_questions` (`question_id`,`form_id`)
 values((SELECT current_question.id FROM questions as current_question WHERE current_question.name="T—Time: If you see any of these signs, call +919840056700" LIMIT 1), 
 	(SELECT id FROM forms WHERE name="Symptoms & Signs" LIMIT 1));

# nih-stroke-scale-score-nihss




insert into `questions` (`name`, `type`, `is_active`)values('Level of consciousness','radio', 1);
insert into `questions` (`name`, `type`, `is_active`)values('Ask month and age','radio', 1);
insert into `questions` (`name`, `type`, `is_active`)values('Blink eyes & squeeze hands','radio', 1);
insert into `questions` (`name`, `type`, `is_active`)values('Horizontal extraocular movements','radio', 1);
insert into `questions` (`name`, `type`, `is_active`)values('Visual fields','radio', 1);
insert into `questions` (`name`, `type`, `is_active`)values('Facial palsy','radio', 1);
insert into `questions` (`name`, `type`, `is_active`)values('Left arm motor drift','radio', 1);
insert into `questions` (`name`, `type`, `is_active`)values('Right arm motor drift','radio', 1);
insert into `questions` (`name`, `type`, `is_active`)values('Left leg motor drift','radio', 1);
insert into `questions` (`name`, `type`, `is_active`)values('Right leg motor drift','radio', 1);
insert into `questions` (`name`, `type`, `is_active`)values('Limb Ataxia','radio', 1);
insert into `questions` (`name`, `type`, `is_active`)values('Sensation','radio', 1);
insert into `questions` (`name`, `type`, `is_active`)values('Language/aphasia','radio', 1);
insert into `questions` (`name`, `type`, `is_active`)values('Dysarthria','radio', 1);
insert into `questions` (`name`, `type`, `is_active`)values('Extinction/inattention','radio', 1);


 insert into `answers`(`name`,`is_active`)
 values
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
 ('Extinction to >1 modality',1);

insert into `form_question_answers` (`form_question_id`,`answer_id`,`jump_to_question_id`,`score`)
 values((SELECT current_question.id FROM questions as current_question WHERE current_question.name="Level of consciousness" LIMIT 1), 
 	(SELECT id FROM answers WHERE name="Alert; keenly responsive" LIMIT 1), null,0),
 ((SELECT current_question.id FROM questions as current_question WHERE current_question.name="Level of consciousness" LIMIT 1), 
 	(SELECT id FROM answers WHERE name="Arouses to minor stimulation" LIMIT 1), null,1),
 ((SELECT current_question.id FROM questions as current_question WHERE current_question.name="Level of consciousness" LIMIT 1), 
 	(SELECT id FROM answers WHERE name="Requires repeated stimulation to arouse" LIMIT 1), null,2),
 ((SELECT current_question.id FROM questions as current_question WHERE current_question.name="Level of consciousness" LIMIT 1), 
 	(SELECT id FROM answers WHERE name="Movements to pain" LIMIT 1), null,2),
 ((SELECT current_question.id FROM questions as current_question WHERE current_question.name="Level of consciousness" LIMIT 1), 
 	(SELECT id FROM answers WHERE name="Postures or unresponsive" LIMIT 1), null,3);

insert into `form_question_answers` (`form_question_id`,`answer_id`,`jump_to_question_id`,`score`)
 values((SELECT current_question.id FROM questions as current_question WHERE current_question.name="Ask month and age" LIMIT 1), 
 	(SELECT id FROM answers WHERE name="Both questions right" LIMIT 1), null,0),
 ((SELECT current_question.id FROM questions as current_question WHERE current_question.name="Ask month and age" LIMIT 1), 
 	(SELECT id FROM answers WHERE name="1 question right" LIMIT 1), null,1),
 ((SELECT current_question.id FROM questions as current_question WHERE current_question.name="Ask month and age" LIMIT 1), 
 	(SELECT id FROM answers WHERE name="0 questions right" LIMIT 1), null,2),
 ((SELECT current_question.id FROM questions as current_question WHERE current_question.name="Ask month and age" LIMIT 1), 
 	(SELECT id FROM answers WHERE name="Dysarthric/intubated/trauma/language barrier" LIMIT 1), null,1),
 ((SELECT current_question.id FROM questions as current_question WHERE current_question.name="Ask month and age" LIMIT 1), 
 	(SELECT id FROM answers WHERE name="Aphasic" LIMIT 1), null,2);


insert into `form_question_answers` (`form_question_id`,`answer_id`,`jump_to_question_id`,`score`)
 values((SELECT current_question.id FROM questions as current_question WHERE current_question.name="Blink eyes & squeeze hands" LIMIT 1), 
 	(SELECT id FROM answers WHERE name="Performs both tasks" LIMIT 1), null,0),
 ((SELECT current_question.id FROM questions as current_question WHERE current_question.name="Blink eyes & squeeze hands" LIMIT 1), 
 	(SELECT id FROM answers WHERE name="Performs 1 task" LIMIT 1), null,1),
 ((SELECT current_question.id FROM questions as current_question WHERE current_question.name="Blink eyes & squeeze hands" LIMIT 1), 
 	(SELECT id FROM answers WHERE name="Performs 0 tasks" LIMIT 1), null,2);


insert into `form_question_answers` (`form_question_id`,`answer_id`,`jump_to_question_id`,`score`)
 values((SELECT current_question.id FROM questions as current_question WHERE current_question.name="Horizontal extraocular movements" LIMIT 1), 
 	(SELECT id FROM answers WHERE name="Normal" LIMIT 1), null,0),
 ((SELECT current_question.id FROM questions as current_question WHERE current_question.name="Horizontal extraocular movements" LIMIT 1), 
 	(SELECT id FROM answers WHERE name="Partial gaze palsy: can be overcome" LIMIT 1), null,1),
 ((SELECT current_question.id FROM questions as current_question WHERE current_question.name="Horizontal extraocular movements" LIMIT 1), 
 	(SELECT id FROM answers WHERE name="Partial gaze palsy: corrects with oculocephalic reflex" LIMIT 1), null,1),
 ((SELECT current_question.id FROM questions as current_question WHERE current_question.name="Horizontal extraocular movements" LIMIT 1), 
 	(SELECT id FROM answers WHERE name="Forced gaze palsy: cannot be overcome" LIMIT 1), null,2);

 insert into `form_question_answers` (`form_question_id`,`answer_id`,`jump_to_question_id`,`score`)
 values((SELECT current_question.id FROM questions as current_question WHERE current_question.name="Visual fields" LIMIT 1), 
 	(SELECT id FROM answers WHERE name="No visual loss" LIMIT 1), null,0),
 ((SELECT current_question.id FROM questions as current_question WHERE current_question.name="Visual fields" LIMIT 1), 
 	(SELECT id FROM answers WHERE name="Partial hemianopia" LIMIT 1), null,1),
 ((SELECT current_question.id FROM questions as current_question WHERE current_question.name="Visual fields" LIMIT 1), 
 	(SELECT id FROM answers WHERE name="Complete hemianopia" LIMIT 1), null,2),
 ((SELECT current_question.id FROM questions as current_question WHERE current_question.name="Visual fields" LIMIT 1), 
 	(SELECT id FROM answers WHERE name="Patient is bilaterally blind" LIMIT 1), null,3),
 ((SELECT current_question.id FROM questions as current_question WHERE current_question.name="Visual fields" LIMIT 1), 
 	(SELECT id FROM answers WHERE name="Bilateral hemianopia" LIMIT 1), null,3);


insert into `form_question_answers` (`form_question_id`,`answer_id`,`jump_to_question_id`,`score`)
 values((SELECT current_question.id FROM questions as current_question WHERE current_question.name="Facial palsy" LIMIT 1), 
 	(SELECT id FROM answers WHERE name="Normal symmetry" LIMIT 1), null,0),
 ((SELECT current_question.id FROM questions as current_question WHERE current_question.name="Facial palsy" LIMIT 1), 
 	(SELECT id FROM answers WHERE name="Minor paralysis (flat nasolabial fold, smile asymmetry)" LIMIT 1), null,1),
 ((SELECT current_question.id FROM questions as current_question WHERE current_question.name="Facial palsy" LIMIT 1), 
 	(SELECT id FROM answers WHERE name="Partial paralysis (lower face)" LIMIT 1), null,2),
 ((SELECT current_question.id FROM questions as current_question WHERE current_question.name="Facial palsy" LIMIT 1), 
 	(SELECT id FROM answers WHERE name="Unilateral complete paralysis (upper/lower face)" LIMIT 1), null,3),
 ((SELECT current_question.id FROM questions as current_question WHERE current_question.name="Facial palsy" LIMIT 1), 
 	(SELECT id FROM answers WHERE name="Bilateral complete paralysis (upper/lower face)" LIMIT 1), null,3);


insert into `form_question_answers` (`form_question_id`,`answer_id`,`jump_to_question_id`,`score`)
 values((SELECT current_question.id FROM questions as current_question WHERE current_question.name="Left arm motor drift" LIMIT 1), 
 	(SELECT id FROM answers WHERE name="No drift for 10 seconds" LIMIT 1), null,0),
 ((SELECT current_question.id FROM questions as current_question WHERE current_question.name="Left arm motor drift" LIMIT 1), 
 	(SELECT id FROM answers WHERE name="Drift, but doesn't hit bed" LIMIT 1), null,1),
 ((SELECT current_question.id FROM questions as current_question WHERE current_question.name="Left arm motor drift" LIMIT 1), 
 	(SELECT id FROM answers WHERE name="Drift, hits bed" LIMIT 1), null,2),
 ((SELECT current_question.id FROM questions as current_question WHERE current_question.name="Left arm motor drift" LIMIT 1), 
 	(SELECT id FROM answers WHERE name="Some effort against gravity" LIMIT 1), null,2),
 ((SELECT current_question.id FROM questions as current_question WHERE current_question.name="Left arm motor drift" LIMIT 1), 
 	(SELECT id FROM answers WHERE name="No effort against gravity" LIMIT 1), null,3),
 ((SELECT current_question.id FROM questions as current_question WHERE current_question.name="Left arm motor drift" LIMIT 1), 
 	(SELECT id FROM answers WHERE name="No movement" LIMIT 1), null,4),
 ((SELECT current_question.id FROM questions as current_question WHERE current_question.name="Left arm motor drift" LIMIT 1), 
 	(SELECT id FROM answers WHERE name="Amputation/joint fusion" LIMIT 1), null,0);

insert into `form_question_answers` (`form_question_id`,`answer_id`,`jump_to_question_id`,`score`)
 values((SELECT current_question.id FROM questions as current_question WHERE current_question.name="Right arm motor drift" LIMIT 1), 
 	(SELECT id FROM answers WHERE name="No drift for 10 seconds" LIMIT 1), null,0),
 ((SELECT current_question.id FROM questions as current_question WHERE current_question.name="Right arm motor drift" LIMIT 1), 
 	(SELECT id FROM answers WHERE name="Drift, but doesn't hit bed" LIMIT 1), null,1),
 ((SELECT current_question.id FROM questions as current_question WHERE current_question.name="Right arm motor drift" LIMIT 1), 
 	(SELECT id FROM answers WHERE name="Drift, hits bed" LIMIT 1), null,2),
 ((SELECT current_question.id FROM questions as current_question WHERE current_question.name="Right arm motor drift" LIMIT 1), 
 	(SELECT id FROM answers WHERE name="Some effort against gravity" LIMIT 1), null,2),
 ((SELECT current_question.id FROM questions as current_question WHERE current_question.name="Right arm motor drift" LIMIT 1), 
 	(SELECT id FROM answers WHERE name="No effort against gravity" LIMIT 1), null,3),
 ((SELECT current_question.id FROM questions as current_question WHERE current_question.name="Right arm motor drift" LIMIT 1), 
 	(SELECT id FROM answers WHERE name="No movement" LIMIT 1), null,4),
 ((SELECT current_question.id FROM questions as current_question WHERE current_question.name="Right arm motor drift" LIMIT 1), 
 	(SELECT id FROM answers WHERE name="Amputation/joint fusion" LIMIT 1), null,0);

 insert into `form_question_answers` (`form_question_id`,`answer_id`,`jump_to_question_id`,`score`)
 values((SELECT current_question.id FROM questions as current_question WHERE current_question.name="Left leg motor drift" LIMIT 1), 
 	(SELECT id FROM answers WHERE name="No drift for 10 seconds" LIMIT 1), null,0),
 ((SELECT current_question.id FROM questions as current_question WHERE current_question.name="Left leg motor drift" LIMIT 1), 
 	(SELECT id FROM answers WHERE name="Drift, but doesn't hit bed" LIMIT 1), null,1),
 ((SELECT current_question.id FROM questions as current_question WHERE current_question.name="Left leg motor drift" LIMIT 1), 
 	(SELECT id FROM answers WHERE name="Drift, hits bed" LIMIT 1), null,2),
 ((SELECT current_question.id FROM questions as current_question WHERE current_question.name="Left leg motor drift" LIMIT 1), 
 	(SELECT id FROM answers WHERE name="Some effort against gravity" LIMIT 1), null,2),
 ((SELECT current_question.id FROM questions as current_question WHERE current_question.name="Left leg motor drift" LIMIT 1), 
 	(SELECT id FROM answers WHERE name="No effort against gravity" LIMIT 1), null,3),
 ((SELECT current_question.id FROM questions as current_question WHERE current_question.name="Left leg motor drift" LIMIT 1), 
 	(SELECT id FROM answers WHERE name="No movement" LIMIT 1), null,4),
 ((SELECT current_question.id FROM questions as current_question WHERE current_question.name="Left leg motor drift" LIMIT 1), 
 	(SELECT id FROM answers WHERE name="Amputation/joint fusion" LIMIT 1), null,0);

 insert into `form_question_answers` (`form_question_id`,`answer_id`,`jump_to_question_id`,`score`)
 values((SELECT current_question.id FROM questions as current_question WHERE current_question.name="Right leg motor drift" LIMIT 1), 
 	(SELECT id FROM answers WHERE name="No drift for 10 seconds" LIMIT 1), null,0),
 ((SELECT current_question.id FROM questions as current_question WHERE current_question.name="Right leg motor drift" LIMIT 1), 
 	(SELECT id FROM answers WHERE name="Drift, but doesn't hit bed" LIMIT 1), null,1),
 ((SELECT current_question.id FROM questions as current_question WHERE current_question.name="Right leg motor drift" LIMIT 1), 
 	(SELECT id FROM answers WHERE name="Drift, hits bed" LIMIT 1), null,2),
 ((SELECT current_question.id FROM questions as current_question WHERE current_question.name="Right leg motor drift" LIMIT 1), 
 	(SELECT id FROM answers WHERE name="Some effort against gravity" LIMIT 1), null,2),
 ((SELECT current_question.id FROM questions as current_question WHERE current_question.name="Right leg motor drift" LIMIT 1), 
 	(SELECT id FROM answers WHERE name="No effort against gravity" LIMIT 1), null,3),
 ((SELECT current_question.id FROM questions as current_question WHERE current_question.name="Right leg motor drift" LIMIT 1), 
 	(SELECT id FROM answers WHERE name="No movement" LIMIT 1), null,4),
 ((SELECT current_question.id FROM questions as current_question WHERE current_question.name="Right leg motor drift" LIMIT 1), 
 	(SELECT id FROM answers WHERE name="Amputation/joint fusion" LIMIT 1), null,0);

insert into `form_question_answers` (`form_question_id`,`answer_id`,`jump_to_question_id`,`score`)
 values((SELECT current_question.id FROM questions as current_question WHERE current_question.name="Limb Ataxia" LIMIT 1), 
 	(SELECT id FROM answers WHERE name="No ataxia" LIMIT 1), null,0),
 ((SELECT current_question.id FROM questions as current_question WHERE current_question.name="Limb Ataxia" LIMIT 1), 
 	(SELECT id FROM answers WHERE name="Ataxia in 1 Limb" LIMIT 1), null,1),
 ((SELECT current_question.id FROM questions as current_question WHERE current_question.name="Limb Ataxia" LIMIT 1), 
 	(SELECT id FROM answers WHERE name="Ataxia in 2 Limbs" LIMIT 1), null,2),
 ((SELECT current_question.id FROM questions as current_question WHERE current_question.name="Limb Ataxia" LIMIT 1), 
 	(SELECT id FROM answers WHERE name="Does not understand" LIMIT 1), null,0),
 ((SELECT current_question.id FROM questions as current_question WHERE current_question.name="Limb Ataxia" LIMIT 1), 
 	(SELECT id FROM answers WHERE name="Paralyzed" LIMIT 1), null,0),
 ((SELECT current_question.id FROM questions as current_question WHERE current_question.name="Limb Ataxia" LIMIT 1), 
 	(SELECT id FROM answers WHERE name="Amputation/joint fusion" LIMIT 1), null,0);

insert into `form_question_answers` (`form_question_id`,`answer_id`,`jump_to_question_id`,`score`)
 values((SELECT current_question.id FROM questions as current_question WHERE current_question.name="Sensation" LIMIT 1), 
 	(SELECT id FROM answers WHERE name="Normal; no sensory loss" LIMIT 1), null,0),
 ((SELECT current_question.id FROM questions as current_question WHERE current_question.name="Sensation" LIMIT 1), 
 	(SELECT id FROM answers WHERE name="Mild-moderate loss: less sharp/more dull" LIMIT 1), null,1),
 ((SELECT current_question.id FROM questions as current_question WHERE current_question.name="Sensation" LIMIT 1), 
 	(SELECT id FROM answers WHERE name="Mild-moderate loss: can sense being touched" LIMIT 1), null,1),
 ((SELECT current_question.id FROM questions as current_question WHERE current_question.name="Sensation" LIMIT 1), 
 	(SELECT id FROM answers WHERE name="Complete loss: cannot sense being touched at all" LIMIT 1), null,2),
 ((SELECT current_question.id FROM questions as current_question WHERE current_question.name="Sensation" LIMIT 1), 
 	(SELECT id FROM answers WHERE name="No response and quadriplegic" LIMIT 1), null,2),
 ((SELECT current_question.id FROM questions as current_question WHERE current_question.name="Sensation" LIMIT 1), 
 	(SELECT id FROM answers WHERE name="Coma/unresponsive" LIMIT 1), null,2);


insert into `form_question_answers` (`form_question_id`,`answer_id`,`jump_to_question_id`,`score`)
 values((SELECT current_question.id FROM questions as current_question WHERE current_question.name="Language/aphasia" LIMIT 1), 
 	(SELECT id FROM answers WHERE name="Normal; no aphasia" LIMIT 1), null,0),
 ((SELECT current_question.id FROM questions as current_question WHERE current_question.name="Language/aphasia" LIMIT 1), 
 	(SELECT id FROM answers WHERE name="Mild-moderate aphasia: some obvious changes, without significant limitation" LIMIT 1), null,1),
 ((SELECT current_question.id FROM questions as current_question WHERE current_question.name="Language/aphasia" LIMIT 1), 
 	(SELECT id FROM answers WHERE name="Severe aphasia: fragmentary expression, inference needed, cannot identify materials+" LIMIT 1), null,2),
 ((SELECT current_question.id FROM questions as current_question WHERE current_question.name="Language/aphasia" LIMIT 1), 
 	(SELECT id FROM answers WHERE name="Mute/global aphasia: no usable speech/auditory comprehension" LIMIT 1), null,3),
 ((SELECT current_question.id FROM questions as current_question WHERE current_question.name="Language/aphasia" LIMIT 1), 
 	(SELECT id FROM answers WHERE name="Coma/unresponsive" LIMIT 1), null,3);


insert into `form_question_answers` (`form_question_id`,`answer_id`,`jump_to_question_id`,`score`)
 values((SELECT current_question.id FROM questions as current_question WHERE current_question.name="Dysarthria" LIMIT 1), 
 	(SELECT id FROM answers WHERE name="Normal" LIMIT 1), null,0),
 ((SELECT current_question.id FROM questions as current_question WHERE current_question.name="Dysarthria" LIMIT 1), 
 	(SELECT id FROM answers WHERE name="Mild-moderate dysarthria: slurring but can be understood" LIMIT 1), null,1),
 ((SELECT current_question.id FROM questions as current_question WHERE current_question.name="Dysarthria" LIMIT 1), 
 	(SELECT id FROM answers WHERE name="Severe dysarthria: unintelligible slurring or out of proportion to dysphasia" LIMIT 1), null,2),
 ((SELECT current_question.id FROM questions as current_question WHERE current_question.name="Dysarthria" LIMIT 1), 
 	(SELECT id FROM answers WHERE name="Mute/anarthric" LIMIT 1), null,2),
 ((SELECT current_question.id FROM questions as current_question WHERE current_question.name="Dysarthria" LIMIT 1), 
 	(SELECT id FROM answers WHERE name="Intubated/unable to test" LIMIT 1), null,0);


insert into `form_question_answers` (`form_question_id`,`answer_id`,`jump_to_question_id`,`score`)
 values((SELECT current_question.id FROM questions as current_question WHERE current_question.name="Extinction/inattention" LIMIT 1), 
 	(SELECT id FROM answers WHERE name="No abnormality" LIMIT 1), null,0),
 ((SELECT current_question.id FROM questions as current_question WHERE current_question.name="Extinction/inattention" LIMIT 1), 
 	(SELECT id FROM answers WHERE name="Visual/tactile/auditory/spatial/personal inattention" LIMIT 1), null,1),
 ((SELECT current_question.id FROM questions as current_question WHERE current_question.name="Extinction/inattention" LIMIT 1), 
 	(SELECT id FROM answers WHERE name="Extinction to bilateral simultaneous stimulation" LIMIT 1), null,1),
 ((SELECT current_question.id FROM questions as current_question WHERE current_question.name="Extinction/inattention" LIMIT 1), 
 	(SELECT id FROM answers WHERE name="Profound hemi-inattention (ex: does not recognize own hand)" LIMIT 1), null,2),
 ((SELECT current_question.id FROM questions as current_question WHERE current_question.name="Extinction/inattention" LIMIT 1), 
 	(SELECT id FROM answers WHERE name="Extinction to >1 modality" LIMIT 1), null,2);


insert into `form_questions` (`question_id`,`form_id`)
 values((SELECT current_question.id FROM questions as current_question WHERE current_question.name="Level of consciousness" LIMIT 1), 
 	(SELECT id FROM forms WHERE name="NIH Stroke Scale/Score (NIHSS)" LIMIT 1));

insert into `form_questions` (`question_id`,`form_id`)
 values((SELECT current_question.id FROM questions as current_question WHERE current_question.name="Ask month and age" LIMIT 1), 
 	(SELECT id FROM forms WHERE name="NIH Stroke Scale/Score (NIHSS)" LIMIT 1));

insert into `form_questions` (`question_id`,`form_id`)
 values((SELECT current_question.id FROM questions as current_question WHERE current_question.name="Blink eyes & squeeze hands" LIMIT 1), 
 	(SELECT id FROM forms WHERE name="NIH Stroke Scale/Score (NIHSS)" LIMIT 1));

insert into `form_questions` (`question_id`,`form_id`)
 values((SELECT current_question.id FROM questions as current_question WHERE current_question.name="Horizontal extraocular movements" LIMIT 1), 
 	(SELECT id FROM forms WHERE name="NIH Stroke Scale/Score (NIHSS)" LIMIT 1));

insert into `form_questions` (`question_id`,`form_id`)
 values((SELECT current_question.id FROM questions as current_question WHERE current_question.name="Visual fields" LIMIT 1), 
 	(SELECT id FROM forms WHERE name="NIH Stroke Scale/Score (NIHSS)" LIMIT 1));

insert into `form_questions` (`question_id`,`form_id`)
 values((SELECT current_question.id FROM questions as current_question WHERE current_question.name="Facial palsy" LIMIT 1), 
 	(SELECT id FROM forms WHERE name="NIH Stroke Scale/Score (NIHSS)" LIMIT 1));

insert into `form_questions` (`question_id`,`form_id`)
 values((SELECT current_question.id FROM questions as current_question WHERE current_question.name="Left arm motor drift" LIMIT 1), 
 	(SELECT id FROM forms WHERE name="NIH Stroke Scale/Score (NIHSS)" LIMIT 1));

insert into `form_questions` (`question_id`,`form_id`)
 values((SELECT current_question.id FROM questions as current_question WHERE current_question.name="Right arm motor drift" LIMIT 1), 
 	(SELECT id FROM forms WHERE name="NIH Stroke Scale/Score (NIHSS)" LIMIT 1));

insert into `form_questions` (`question_id`,`form_id`)
 values((SELECT current_question.id FROM questions as current_question WHERE current_question.name="Left leg motor drift" LIMIT 1), 
 	(SELECT id FROM forms WHERE name="NIH Stroke Scale/Score (NIHSS)" LIMIT 1));

insert into `form_questions` (`question_id`,`form_id`)
 values((SELECT current_question.id FROM questions as current_question WHERE current_question.name="Right leg motor drift" LIMIT 1), 
 	(SELECT id FROM forms WHERE name="NIH Stroke Scale/Score (NIHSS)" LIMIT 1));

insert into `form_questions` (`question_id`,`form_id`)
 values((SELECT current_question.id FROM questions as current_question WHERE current_question.name="Limb Ataxia" LIMIT 1), 
 	(SELECT id FROM forms WHERE name="NIH Stroke Scale/Score (NIHSS)" LIMIT 1));

insert into `form_questions` (`question_id`,`form_id`)
 values((SELECT current_question.id FROM questions as current_question WHERE current_question.name="Sensation" LIMIT 1), 
 	(SELECT id FROM forms WHERE name="NIH Stroke Scale/Score (NIHSS)" LIMIT 1));

insert into `form_questions` (`question_id`,`form_id`)
 values((SELECT current_question.id FROM questions as current_question WHERE current_question.name="Language/aphasia" LIMIT 1), 
 	(SELECT id FROM forms WHERE name="NIH Stroke Scale/Score (NIHSS)" LIMIT 1));

insert into `form_questions` (`question_id`,`form_id`)
 values((SELECT current_question.id FROM questions as current_question WHERE current_question.name="Dysarthria" LIMIT 1), 
 	(SELECT id FROM forms WHERE name="NIH Stroke Scale/Score (NIHSS)" LIMIT 1));

insert into `form_questions` (`question_id`,`form_id`)
 values((SELECT current_question.id FROM questions as current_question WHERE current_question.name="Extinction/inattention" LIMIT 1), 
 	(SELECT id FROM forms WHERE name="NIH Stroke Scale/Score (NIHSS)" LIMIT 1));

# tpa-contraindications-ischemic-stroke.sql



 insert into `questions` (`name`, `type`, `is_active`)
 values('Eligibility for TPA','sub_question', 1),
 ('Absolute Contraindications to TPA','sub_question', 1),
 ('Relative Contraindications/Warnings to TPA','sub_question', 1),
 ('Additional Warnings to TPA >3hr Onset','sub_question', 1);

insert into `questions`( `parent_id`, `name`, `type`, `is_active`)
 values((SELECT current_question.id FROM questions as current_question WHERE current_question.name="Eligibility for TPA" LIMIT 1),
 	'Age ≥18','radio',1),
 ((SELECT current_question.id FROM questions as current_question WHERE current_question.name="Eligibility for TPA" LIMIT 1),
 	'Clinical diagnosis of ischemic stroke causing neurological deficit','radio',1),
 ((SELECT current_question.id FROM questions as current_question WHERE current_question.name="Eligibility for TPA" LIMIT 1),
 	'Time of symptom onset <4.5 hours See Additional Warnings to tPA at 3-4.5hr below','radio',1);

 insert into `questions`( `parent_id`, `name`, `type`, `is_active`)
 values((SELECT current_question.id FROM questions as current_question WHERE current_question.name="Absolute Contraindications to TPA" LIMIT 1),
 	'Intracranial hemorrhage on CT','radio',1),
 ((SELECT current_question.id FROM questions as current_question WHERE current_question.name="Absolute Contraindications to TPA" LIMIT 1),
 	'Clinical presentation suggests subarachnoid hemorrhage','radio',1),
 ((SELECT current_question.id FROM questions as current_question WHERE current_question.name="Absolute Contraindications to TPA" LIMIT 1),
 	'Neurosurgery, head trauma, or stroke in past 3 months','radio',1),
 ((SELECT current_question.id FROM questions as current_question WHERE current_question.name="Absolute Contraindications to TPA" LIMIT 1),
 	'Uncontrolled hypertension (>185 mmHg SBP or >110 mmHg DBP)','radio',1),
 ((SELECT current_question.id FROM questions as current_question WHERE current_question.name="Absolute Contraindications to TPA" LIMIT 1),
 	'History of intracranial hemorrhage','radio',1),
 ((SELECT current_question.id FROM questions as current_question WHERE current_question.name="Absolute Contraindications to TPA" LIMIT 1),
 	'Known intracranial arteriovenous malformation, neoplasm, or aneurysm','radio',1),
 ((SELECT current_question.id FROM questions as current_question WHERE current_question.name="Absolute Contraindications to TPA" LIMIT 1),
 	'Active internal bleeding','radio',1),
 ((SELECT current_question.id FROM questions as current_question WHERE current_question.name="Absolute Contraindications to TPA" LIMIT 1),
 	'Suspected/confirmed endocarditis','radio',1),
 ((SELECT current_question.id FROM questions as current_question WHERE current_question.name="Absolute Contraindications to TPA" LIMIT 1),
 	'Known bleeding diathesis
(1) Platelet count < 100,000; (2) Patient has received heparin within 48 hours and has an elevated aPTT (greater than upper limit of normal for laboratory); (3) Current use of oral anticoagulants (ex: warfarin) and INR >1.7; (4)Current use of direct thrombin inhibitors or direct factor Xa inhibitors','radio',1),
 ((SELECT current_question.id FROM questions as current_question WHERE current_question.name="Absolute Contraindications to TPA" LIMIT 1),
 	'Abnormal blood glucose (<50 mg/dL)','radio',1);

 insert into `questions`( `parent_id`, `name`, `type`, `is_active`)
 values((SELECT current_question.id FROM questions as current_question WHERE current_question.name="Relative Contraindications/Warnings to TPA" LIMIT 1),
 	'Only minor or rapidly improving stroke symptoms','radio',1),
 ((SELECT current_question.id FROM questions as current_question WHERE current_question.name="Relative Contraindications/Warnings to TPA" LIMIT 1),
 	'Major surgery or serious non-head trauma in the previous 14 days','radio',1),
 ((SELECT current_question.id FROM questions as current_question WHERE current_question.name="Relative Contraindications/Warnings to TPA" LIMIT 1),
 	'History of gastrointestinal or urinary tract hemorrhage within 21 days','radio',1),
 ((SELECT current_question.id FROM questions as current_question WHERE current_question.name="Relative Contraindications/Warnings to TPA" LIMIT 1),
 	'Seizure at stroke onset','radio',1),
 ((SELECT current_question.id FROM questions as current_question WHERE current_question.name="Relative Contraindications/Warnings to TPA" LIMIT 1),
 	'Recent arterial puncture at a noncompressible site','radio',1),
 ((SELECT current_question.id FROM questions as current_question WHERE current_question.name="Relative Contraindications/Warnings to TPA" LIMIT 1),
 	'Recent lumbar puncture','radio',1),
 ((SELECT current_question.id FROM questions as current_question WHERE current_question.name="Relative Contraindications/Warnings to TPA" LIMIT 1),
 	'Post myocardial infarction pericarditis','radio',1),
 ((SELECT current_question.id FROM questions as current_question WHERE current_question.name="Relative Contraindications/Warnings to TPA" LIMIT 1),
 	'Pregnancy','radio',1);


insert into `questions`( `parent_id`, `name`, `type`, `is_active`)
 values((SELECT current_question.id FROM questions as current_question WHERE current_question.name="Additional Warnings to TPA >3hr Onset" LIMIT 1),
 	'Age >80 years','radio',1),
 ((SELECT current_question.id FROM questions as current_question WHERE current_question.name="Additional Warnings to TPA >3hr Onset" LIMIT 1),
 	'History of prior stroke and diabetes','radio',1),
 ((SELECT current_question.id FROM questions as current_question WHERE current_question.name="Additional Warnings to TPA >3hr Onset" LIMIT 1),
 	'Any active anticoagulant use (even with INR <1.7)','radio',1),
 ((SELECT current_question.id FROM questions as current_question WHERE current_question.name="Additional Warnings to TPA >3hr Onset" LIMIT 1),
 	'NIHSS >25','radio',1),
 ((SELECT current_question.id FROM questions as current_question WHERE current_question.name="Additional Warnings to TPA >3hr Onset" LIMIT 1),
 	'CT shows multilobar infarction (hypodensity >1/3 cerebral hemisphere)','radio',1);



insert into `form_question_answers` (`form_question_id`,`answer_id`,`jump_to_question_id`)
 values((SELECT current_question.id FROM questions as current_question WHERE current_question.name="Eligibility for TPA" LIMIT 1), 
 	(SELECT id FROM answers WHERE name="Yes" LIMIT 1), null),
 ((SELECT current_question.id FROM questions as current_question WHERE current_question.name="Eligibility for TPA" LIMIT 1), 
 	(SELECT id FROM answers WHERE name="No" LIMIT 1), null),
 ((SELECT current_question.id FROM questions as current_question WHERE current_question.name="Absolute Contraindications to TPA" LIMIT 1), 
 	(SELECT id FROM answers WHERE name="Yes" LIMIT 1), null),
 ((SELECT current_question.id FROM questions as current_question WHERE current_question.name="Absolute Contraindications to TPA" LIMIT 1), 
 	(SELECT id FROM answers WHERE name="No" LIMIT 1), null),
 ((SELECT current_question.id FROM questions as current_question WHERE current_question.name="Relative Contraindications/Warnings to TPA" LIMIT 1), 
 	(SELECT id FROM answers WHERE name="Yes" LIMIT 1), null),
 ((SELECT current_question.id FROM questions as current_question WHERE current_question.name="Relative Contraindications/Warnings to TPA" LIMIT 1), 
 	(SELECT id FROM answers WHERE name="No" LIMIT 1), null),
 ((SELECT current_question.id FROM questions as current_question WHERE current_question.name="Additional Warnings to TPA >3hr Onset" LIMIT 1), 
 	(SELECT id FROM answers WHERE name="Yes" LIMIT 1), null),
 ((SELECT current_question.id FROM questions as current_question WHERE current_question.name="Additional Warnings to TPA >3hr Onset" LIMIT 1), 
 	(SELECT id FROM answers WHERE name="No" LIMIT 1), null);


 insert into `form_questions` (`question_id`,`form_id`)
 values((SELECT current_question.id FROM questions as current_question WHERE current_question.name="Eligibility for TPA" LIMIT 1), 
 	(SELECT id FROM forms WHERE name="TPA Contraindications for Ischemic Stroke" LIMIT 1)),
 ((SELECT current_question.id FROM questions as current_question WHERE current_question.name="Absolute Contraindications to TPA" LIMIT 1), 
 	(SELECT id FROM forms WHERE name="TPA Contraindications for Ischemic Stroke" LIMIT 1)),
 ((SELECT current_question.id FROM questions as current_question WHERE current_question.name="Relative Contraindications/Warnings to TPA" LIMIT 1), 
 	(SELECT id FROM forms WHERE name="TPA Contraindications for Ischemic Stroke" LIMIT 1)),
 ((SELECT current_question.id FROM questions as current_question WHERE current_question.name="Additional Warnings to TPA >3hr Onset" LIMIT 1), 
 	(SELECT id FROM forms WHERE name="TPA Contraindications for Ischemic Stroke" LIMIT 1));

# thrive-score-stroke-outcome


 insert into `questions` (`name`, `type`, `is_active`)
 values( 'Age','input', 1),
 ('NIH Stroke Scale','input', 1),
 ('History of hypertension','radio', 1),
 ('History of diabetes mellitus','radio', 1),
 ('History of atrial fibrillation','radio', 1);

 insert into `answers`(`name`,`is_active`)values('Norm: 0 - 42 points',1);

insert into `form_question_answers` (`form_question_id`,`answer_id`,`jump_to_question_id`)
 values((SELECT current_question.id FROM questions as current_question WHERE current_question.name="Age" LIMIT 1), 
 	(SELECT id FROM answers WHERE name="Years" LIMIT 1), null),
 ((SELECT current_question.id FROM questions as current_question WHERE current_question.name="NIH Stroke Scale" LIMIT 1), 
 	(SELECT id FROM answers WHERE name="Norm: 0 - 42 points" LIMIT 1), null);


insert into `form_question_answers` (`form_question_id`,`answer_id`,`jump_to_question_id`,`score`)
 values((SELECT current_question.id FROM questions as current_question WHERE current_question.name="History of hypertension" LIMIT 1), 
 	(SELECT id FROM answers WHERE name="Yes" LIMIT 1), null,1),
 ((SELECT current_question.id FROM questions as current_question WHERE current_question.name="History of hypertension" LIMIT 1), 
 	(SELECT id FROM answers WHERE name="No" LIMIT 1), null,0),
 ((SELECT current_question.id FROM questions as current_question WHERE current_question.name="History of diabetes mellitus" LIMIT 1), 
 	(SELECT id FROM answers WHERE name="Yes" LIMIT 1), null,1),
 ((SELECT current_question.id FROM questions as current_question WHERE current_question.name="History of diabetes mellitus" LIMIT 1), 
 	(SELECT id FROM answers WHERE name="No" LIMIT 1), null,0),
 ((SELECT current_question.id FROM questions as current_question WHERE current_question.name="History of atrial fibrillation" LIMIT 1), 
 	(SELECT id FROM answers WHERE name="Yes" LIMIT 1), null,1),
 ((SELECT current_question.id FROM questions as current_question WHERE current_question.name="History of atrial fibrillation" LIMIT 1), 
 	(SELECT id FROM answers WHERE name="No" LIMIT 1), null,0);


 insert into `form_questions` (`question_id`,`form_id`)
 values((SELECT current_question.id FROM questions as current_question WHERE current_question.name="Age" LIMIT 1), 
 	(SELECT id FROM forms WHERE name="THRIVE Score for Stroke Outcome" LIMIT 1)),
 ((SELECT current_question.id FROM questions as current_question WHERE current_question.name="NIH Stroke Scale" LIMIT 1), 
 	(SELECT id FROM forms WHERE name="THRIVE Score for Stroke Outcome" LIMIT 1)),
 ((SELECT current_question.id FROM questions as current_question WHERE current_question.name="History of hypertension" LIMIT 1), 
 	(SELECT id FROM forms WHERE name="THRIVE Score for Stroke Outcome" LIMIT 1)),
 ((SELECT current_question.id FROM questions as current_question WHERE current_question.name="History of diabetes mellitus" LIMIT 1), 
 	(SELECT id FROM forms WHERE name="THRIVE Score for Stroke Outcome" LIMIT 1)),
 ((SELECT current_question.id FROM questions as current_question WHERE current_question.name="History of atrial fibrillation" LIMIT 1), 
 	(SELECT id FROM forms WHERE name="THRIVE Score for Stroke Outcome" LIMIT 1));
