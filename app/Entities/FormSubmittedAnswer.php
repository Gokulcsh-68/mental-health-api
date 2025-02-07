<?php

namespace App\Entities;
use DB;
use Illuminate\Support\Facades\Log;

class FormSubmittedAnswer extends BaseModel
{
    const VIEW = true;

    const CREATE = true;

    const UPDATE = true;

    const ACTION = true;
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        "form_id", "patient_id", "answers", "score", "created_by"
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        
        'answers' => 'object',
    ];

    /**
     * The attributes excluded from the model's JSON form.
     *
     * @var array
     */
    protected $hidden = [
        
    ];

    /**
     * The attributes that should be updated on patch method.
     *
     * @var array
    */
    protected $partialFillable = [
        
    ];

    /**
     * The attributes that should be mutated to dates.
     *
     * @var array
    */
    protected $dates = [
        
    ];

    /**
     * The event map for the model.
     *
     * @var array
    */
    protected $dispatchesEvents = [
        
    ];


    public function form()
    {
        return $this->belongsTo(Form::class);
    }


    protected function createModel($request)
    {
        $data = $this->getModelAttributes($request);

        DB::beginTransaction();
        try {

            $this->Where('patient_id',$data['patient_id'])
                ->Where('form_id',$data['form_id'])
                ->delete();

            $data['score'] = 0;

            $answers = [];

            // foreach ($data['answers'] as $key => $value) {
            //   if(isset($value['score'])){
            //    $data['score'] =  $data['score'] + $value['score'];
            //   }

            //   if(isset($value['answer'])){
            //    $value['answer']['score']  = $value['score']; 
            //    $answers[$key]   = $value['answer'];
            //   } else {
            //     $answers[$key]  =  $value;
            //   }
            // }

            foreach ($data['answers'] as $key => &$value) { // Use reference to modify directly
              if (is_array($value) && isset($value['score'])) {
                  $data['score'] += $value['score'];
              } elseif ($data['form_id'] == '10') {
                  $score = 0; // Default score
          
                  if (!is_array($value)) {
                      $value = ['name' => $value]; // Convert scalar to array
                  }
          
                  if ($key == 127) { // Age
                      if ($value['name'] <= 59) {
                          $score = 0;
                      } elseif ($value['name'] >= 60 && $value['name'] <= 79) {
                          $score = 1;
                      } elseif ($value['name'] >= 80) {
                          $score = 2;
                      }
                  } elseif ($key == 128) {
                      if ($value['name'] <= 10) {
                          $score = 0;
                      } elseif ($value['name'] >= 11 && $value['name'] <= 20) {
                          $score = 2;
                      } elseif ($value['name'] >= 21) {
                          $score = 4;
                      }
                  }
          
                  $value['score'] = $score; // Assign score to value
                  $data['score'] += $score; // Add to total score
              }
          
              // Ensure value is treated as an array before assigning 'score'
              $value['score'] = $value['score'] ?? 0;
          
              if (isset($value['answer'])) {
                  $value['answer']['score'] = $value['score'];
                  $answers[$key] = $value['answer'];
              } else {
                  $answers[$key] = $value;
              }
            }

            $data['answers']    = $answers;
            $data['created_by'] = $request->user()->id;
            $model = $this->create($data);

            DB::commit();
            return $model;
        } catch (Exception $e) {
            exceptionLogger("Assessment Create Rollback", $e);
            DB::rollback();
        }

        return null;
    }

    public static function Anger_score_fields()
  {

    $data_fields = array(
                      5 =>array('T-Score' => 32.9, 'SE' => 5.3),
                      6 =>array('T-Score' => 38.1, 'SE' => 4),
                      7 =>array('T-Score' => 41.3, 'SE' => 3.7),
                      8 =>array('T-Score' => 44, 'SE' => 3.5),
                      9 =>array('T-Score' => 46.3, 'SE' => 3.4),
                      10 =>array('T-Score' => 48.4, 'SE' => 3.3),
                      11 =>array('T-Score' => 50.5, 'SE' => 3.2),
                      12 =>array('T-Score' => 52.6, 'SE' => 3.2),
                      13 =>array('T-Score' => 54.7, 'SE' => 3.2),
                      14 =>array('T-Score' => 56.7, 'SE' => 3.2),
                      15 =>array('T-Score' => 58.8, 'SE' => 3.2),
                      16 =>array('T-Score' => 60.8, 'SE' => 3.2),
                      17 =>array('T-Score' => 62.9, 'SE' => 3.2),
                      18 =>array('T-Score' => 65, 'SE' => 3.2),
                      19 =>array('T-Score' => 67.2, 'SE' => 3.2),
                      20 =>array('T-Score' => 69.4, 'SE' => 3.3),
                      21 =>array('T-Score' => 71.7, 'SE' => 3.3),
                      22 =>array('T-Score' => 74.1, 'SE' => 3.3),
                      23 =>array('T-Score' => 76.8, 'SE' => 3.4),
                      24 =>array('T-Score' => 79.7, 'SE' => 3.5),
                      25 =>array('T-Score' => 83.3, 'SE' => 3.9),
                    );
    return $data_fields;
  }


  public static function Psychosis_Symptom_Severity_score_fields()
  {

   $data_fields = array(0 => 'none', 1 => 'equivocal', 2 => 'present, but mild',
                      3 => 'present and moderate', 4 => 'present and severe');
    return $data_fields;
  }

  public static function conduct_disorder_one_score_fields()
  {

   $data_fields = array(0 => 'None', 1 => 'Mild', 2 => 'Moderate',3 => 'Severe');
    return $data_fields;
  }

  public static function conduct_disorder_two_score_fields()
  {

   $data_fields = array(0 => 'Level 0', 1 => 'Level 1', 2 => 'Level 2',3 => 'Level 3');
    return $data_fields;
  }

  public static function nonsuicidal_self_injury_one_score_fields()
  {

   $data_fields = array(0 => 'None', 1 => 'Subthreshold', 2 => 'Mild',3 => 'Moderate',4 => 'Severe');
    return $data_fields;
  }

  public static function nonsuicidal_self_injury_two_score_fields()
  {

   $data_fields = array(0 => 'Level 0', 1 => 'Level 1', 2 => 'Level 2',3 => 'Level 3', 4 => 'Level 4');
    return $data_fields;
  }

  public static function oppositional_defiant_disorder_one_score_fields()
  {

   $data_fields = array(0 => 'None', 1 => 'Mild', 2 => 'Moderate',3 => 'Severe');
    return $data_fields;
  }

  public static function oppositional_defiant_disorder_two_score_fields()
  {

   $data_fields = array(0 => 'Level 0', 1 => 'Level 1', 2 => 'Level 2',3 => 'Level 3');
    return $data_fields;
  }

  public static function autism_social_disorder_one_score_fields()
  {

   $data_fields = array(0 => 'None', 1 => 'Mild/Requiring support',
    2 => 'Moderate/Requiring SUBSTANTIAL support',3 => 'Severe/Requiring VERY SUBSTANTIAL support');
    return $data_fields;
  }

  public static function autism_social_disorder_two_score_fields()
  {

   $data_fields = array(0 => 'Level 0', 1 => 'Level 1', 2 => 'Level 2',3 => 'Level 3');
    return $data_fields;
  }

  public static function Anxiety_score_fields()
  {

    $data_fields = array(
                      7 =>array('T-Score' => 36.3, 'SE' => 5.4),
                      8 =>array('T-Score' => 42.1, 'SE' => 3.4),
                      9 =>array('T-Score' => 44.7, 'SE' => 2.9),
                      10 =>array('T-Score' => 46.7, 'SE' => 2.6),
                      11 =>array('T-Score' => 48.4, 'SE' => 2.4),
                      12 =>array('T-Score' => 49.9, 'SE' => 2.3),
                      13 =>array('T-Score' => 51.3, 'SE' => 2.3),
                      14 =>array('T-Score' => 52.6, 'SE' => 2.2),
                      15 =>array('T-Score' => 53.8, 'SE' => 2.2),
                      16 =>array('T-Score' => 55.1, 'SE' => 2.2),
                      17 =>array('T-Score' => 56.3, 'SE' => 2.2),
                      18 =>array('T-Score' => 57.6, 'SE' => 2.2),
                      19 =>array('T-Score' => 58.8, 'SE' => 2.2),
                      20 =>array('T-Score' => 60.0, 'SE' => 2.2),
                      21 =>array('T-Score' => 61.3, 'SE' => 2.2),
                      22 =>array('T-Score' => 62.6, 'SE' => 2.2),
                      23 =>array('T-Score' => 63.8, 'SE' => 2.2),
                      24 =>array('T-Score' => 65.1, 'SE' => 2.2),
                      25 =>array('T-Score' => 66.4, 'SE' => 2.2),
                      26 =>array('T-Score' => 67.7, 'SE' => 2.2),
                      27 =>array('T-Score' => 68.9, 'SE' => 2.2),
                      28 =>array('T-Score' => 70.2, 'SE' => 2.2),
                      29 =>array('T-Score' => 71.5, 'SE' => 2.2),
                      30 =>array('T-Score' => 72.9, 'SE' => 2.2),
                      31 =>array('T-Score' => 74.3, 'SE' => 2.2),
                      32 =>array('T-Score' => 75.8, 'SE' => 2.3),
                      33 =>array('T-Score' => 77.4, 'SE' => 2.4),
                      34 =>array('T-Score' => 79.5, 'SE' => 2.7),
                      35 =>array('T-Score' => 82.7, 'SE' => 3.5),
                    );
    return $data_fields;
  }


  public static function Depression_score_fields()
  {

    $data_fields = array(
                      8 =>array('T-Score' => 37.1, 'SE' => 5.5),
                      9 =>array('T-Score' => 43.3, 'SE' => 3.4),
                      10 =>array('T-Score' => 46.2, 'SE' => 2.8),
                      11 =>array('T-Score' => 48.2, 'SE' => 2.4),
                      12 =>array('T-Score' => 49.8, 'SE' => 2.2),
                      13 =>array('T-Score' => 51.2, 'SE' => 2.0),
                      14 =>array('T-Score' => 52.3, 'SE' => 1.9),
                      15 =>array('T-Score' => 53.4, 'SE' => 1.8),
                      16 =>array('T-Score' => 54.3, 'SE' => 1.8),
                      17 =>array('T-Score' => 55.3, 'SE' => 1.7),
                      18 =>array('T-Score' => 56.2, 'SE' => 1.7),
                      19 =>array('T-Score' => 57.1, 'SE' => 1.7),
                      20 =>array('T-Score' => 57.9, 'SE' => 1.7),
                      21 =>array('T-Score' => 58.8, 'SE' => 1.7),
                      22 =>array('T-Score' => 59.7, 'SE' => 1.8),
                      23 =>array('T-Score' => 60.7, 'SE' => 1.8),
                      24 =>array('T-Score' => 61.6, 'SE' => 1.8),
                      25 =>array('T-Score' => 62.5, 'SE' => 1.8),
                      26 =>array('T-Score' => 63.5, 'SE' => 1.8),
                      27 =>array('T-Score' => 64.4, 'SE' => 1.8),
                      28 =>array('T-Score' => 65.4, 'SE' => 1.8),
                      29 =>array('T-Score' => 66.4, 'SE' => 1.8),
                      30 =>array('T-Score' => 67.4, 'SE' => 1.8),
                      31 =>array('T-Score' => 68.3, 'SE' => 1.8),
                      32 =>array('T-Score' => 69.3, 'SE' => 1.8),
                      33 =>array('T-Score' => 70.4, 'SE' => 1.8),
                      34 =>array('T-Score' => 71.4, 'SE' => 1.8),
                      35 =>array('T-Score' => 72.5, 'SE' => 1.8),
                      36 =>array('T-Score' => 73.6, 'SE' => 1.8),
                      37 =>array('T-Score' => 74.8, 'SE' => 1.9),
                      38 =>array('T-Score' => 76.2, 'SE' => 2.0),
                      39 =>array('T-Score' => 77.9, 'SE' => 2.4),
                      40 =>array('T-Score' => 81.1, 'SE' => 3.4),
                    );
    return $data_fields;
  }


  public static function calculate_score($data){
    // dd($data['name']);
    if(!empty($data['name']) && !empty($data['score'])){
      switch ($data['name']) {
        case 'Physical Symptoms':
          $score = (int)$data['score'];
          $score_message  = "@span PHQ-15 - Levels of Somatic Symptom Severity is @c";
          $txt_score      = '@f'.$score.'@c score';

          if($score == 0 || $score <= 4){
            $score_message = $score_message.'  !success!'.$txt_score.'  @br @f Minimal @c !';
          } else if($score == 5 || $score <= 9){
            $score_message = $score_message.'  !primary!'.$txt_score.'  @br @f Low @c !';
          } else if($score == 10 || $score <= 14){
            $score_message = $score_message.' !warning!'.$txt_score.'  @br @f Medium @c !';
          } else if($score == 15 || $score <= 30){
            $score_message = $score_message.' !danger!'.$txt_score.'  @br @f High @c !';
          }else{
            $score_message = $score_message.' !secondary!'.$txt_score.'  @br @f Unknown @c !';
          }

          return $score_message;
        break;

        case 'Anger':
          $pre_score  = self::Anger_score_fields();

          $score      = (int)$data['score'];

          if(!empty($pre_score[$score])){
            $t_score    = $pre_score[$score]['T-Score'];
            $se_score   = $pre_score[$score]['SE'];

            $score_message  = "@span Raw Score is $score, SE is $se_score, T-Score is @c";
            $txt_score      = '@f'.$t_score.'@c score';

            if($t_score == 0 || $t_score < 55){
              $score_message = $score_message.' !success!'.$txt_score.'@br @f None @c !';
            } else if($t_score == 55 || $t_score <= 59.9){
              $score_message = $score_message.' !primary!'.$txt_score.'@br @f Mild @c !';
            } else if($t_score == 60 || $t_score <= 69.9){
              $score_message = $score_message.' !warning!'.$txt_score.'@br @f Moderate @c !';
            } else if($t_score >= 70){
              $score_message = $score_message.' !danger!'.$txt_score.'@br @f Severe @c !';
            }else{
              $score_message = $score_message.' !secondary!'.$txt_score.'@br @f Unknown @c !';
            }

            return $score_message;
          }else{
            return "Please Retake the Assement";
          }
        break;

        case 'Anxiety':
          $pre_score  = self::Anxiety_score_fields();
          $score      = (int)$data['score'];

          if(!empty($pre_score[$score])){
            $t_score    = $pre_score[$score]['T-Score'];
            $se_score   = $pre_score[$score]['SE'];

            $score_message  = "@span Raw Score is $score, SE is $se_score, T-Score is @c";
            $txt_score      = '@f'.$t_score.'@c score';

            if($t_score == 0 || $t_score < 55){
              $score_message = $score_message.' !success!'.$txt_score.'@br @f None @c !';
            } else if($t_score == 55 || $t_score <= 59.9){
              $score_message = $score_message.' !primary!'.$txt_score.'@br @f Mild @c !';
            } else if($t_score == 60 || $t_score <= 69.9){
              $score_message = $score_message.' !warning!'.$txt_score.'@br @f Moderate @c !';
            } else if($t_score >= 70){
              $score_message = $score_message.' !danger!'.$txt_score.'@br @f Severe @c !';
            }else{
              $score_message = $score_message.' !secondary!'.$txt_score.'@br @f Unknown @c !';
            }

            return $score_message;
          }else{
            return "Please Retake the Assement";
          }
        break;

         case 'Depression':
          $pre_score  = self::Depression_score_fields();
          $score      = (int)$data['score'];

          if(!empty($pre_score[$score])){
            $t_score    = $pre_score[$score]['T-Score'];
            $se_score   = $pre_score[$score]['SE'];

            $score_message  = "@span Raw Score is $score, SE is $se_score, T-Score is @c";
            $txt_score      = '@f'.$t_score.'@c score';  

            if($t_score == 0 || $t_score < 55){
              $score_message = $score_message.' !success!'.$txt_score.'@br @f None @c !';
            } else if($t_score == 55 || $t_score <= 59.9){
              $score_message = $score_message.' !primary!'.$txt_score.'@br @f Mild @c !';
            } else if($t_score == 60 || $t_score <= 69.9){
              $score_message = $score_message.' !warning!'.$txt_score.'@br @f Moderate @c !';
            } else if($t_score >= 70){
              $score_message = $score_message.' !danger!'.$txt_score.'@br @f Severe @c !';
            }else{
              $score_message = $score_message.' !secondary!'.$txt_score.'@br @f Unknown @c !';
            }

            return $score_message;
          }else{
            return "@span Please Retake the Assement @c";
          }
        break;

        case 'Mania':
          $score = (int)$data['score'];

          $score_message = '';

          if(!empty($data['answers'])){
            $written_answers = ($data['answers']);
            unset($written_answers->feedback);
            $total_answered_questions = count((array)$written_answers);

            $prorated_score = ($score * 5)/$total_answered_questions;
            $prorated_score = number_format($prorated_score,2);

            $score_message = "@span Raw Score is $score, Total Answered Questions is $total_answered_questions, Pro Rated is !secondary! $prorated_score @br score ! @c ";
          }

          return $score_message;
        break;

        case 'Phobia':
          $score = (int)$data['score'];

          $score_message = '';

          if(!empty($data['answers'])){
            $written_answers = ($data['answers']);
            unset($written_answers->feedback);
            $total_answered_questions = count((array)$written_answers);

            if($total_answered_questions <= 7){
              return "@span Please Retake the Assement @c";
            }else if($total_answered_questions <= 9){
              $prorated_score = ($score * 10)/$total_answered_questions;
              $prorated_score = number_format($prorated_score,2);

              $score_message = "@span Raw Score is $score, Total Answered Questions is $total_answered_questions, Pro Rated is !secondary! $prorated_score @br score ! @c ";

            }else{
              $score_message = "@span Raw Score is $score, Total Answered Questions is $total_answered_questions @c ";
            }

            
          }

          return $score_message;
        break;

        case 'Assessment Scale - Parent Informant':
            $score_message = '';
            if(!empty($data['answers'])){
              $index = 1;
              $one_to_nine_answers = $ten_to_eighteen = 0;
              $nineteen_to_twentysix = $twentyseven_to_forty = $fortyone_to_fortyseven = 0;
              $forthyeight_to_fiftyfive = 0;

              $msg_occured = false;
              $inattention = $hyperactivity_impulsivity = false;

              $score_message = "Parent Assessment Scale :";
              foreach ($data['answers'] as $key => $value) {
                
                if($index <= 9){
                  if($value->score > 2){
                    $one_to_nine_answers = $one_to_nine_answers+1;
                  }
                }

                if($index > 9 && $index <= 18){
                  if($value->score > 2){
                    $ten_to_eighteen = $ten_to_eighteen+1;
                  }
                }

                if($index > 18 && $index <= 26){
                  if($value->score > 2){
                    $nineteen_to_twentysix = $nineteen_to_twentysix+1;
                  }
                }

                if($index > 26 && $index <= 40){
                  if($value->score > 2){
                    $twentyseven_to_forty = $twentyseven_to_forty+1;
                  }
                }

                if($index > 40 && $index <= 47){
                  if($value->score > 2){
                    $fortyone_to_fortyseven = $fortyone_to_fortyseven+1;
                  }
                }

                if($index > 47 && $index <= 55){
                  if($value->score > 3){
                    $forthyeight_to_fiftyfive = $forthyeight_to_fiftyfive+1;
                  }
                }

                $index++;
              }

              if($one_to_nine_answers >=6 && $forthyeight_to_fiftyfive >= 1){
                $inattention = true;
                $score_message = $score_message." Predominantly Inattentive subtype";
                $msg_occured = true;
              }

              if($ten_to_eighteen >=6 && $forthyeight_to_fiftyfive >= 1){
                $hyperactivity_impulsivity = true;
                $score_message = $score_message." Predominantly Hyperactive/Impulsive subtype";
                $msg_occured = true;
              }

              if(!empty($inattention) && !empty($hyperactivity_impulsivity)){
                $score_message = $score_message." ADHD Combined Inattention/Hyperactivity";
              }

              if($nineteen_to_twentysix >=6 && $forthyeight_to_fiftyfive >= 1){
                $score_message = $score_message." Oppositional-Defiant Disorder Screen";
                $msg_occured = true;
              }

              if($twentyseven_to_forty >=6 && $forthyeight_to_fiftyfive >= 1){
                $score_message = $score_message." Conduct Disorder Screen";
                $msg_occured = true;
              }

              if($fortyone_to_fortyseven >=6 && $forthyeight_to_fiftyfive >= 1){
                $score_message = $score_message." Anxiety/Depression Screen";
                $msg_occured = true;
              }
            }
            
           if(!empty($msg_occured)){
              return $score_message;
            }else{
              return '';
            }
        break;

        case 'Assessment Follow-up - Parent Informant':
            $score_message = '';
            if(!empty($data['answers'])){
              $index = 1;
              $total_symptoms_score = $performance_score = $average_performance_score = 0;

              $msg_occured = false;
              $inattention = $hyperactivity_impulsivity = false;

              $score_message = "Parent Assessment Follow-up :";
              foreach ($data['answers'] as $key => $value) {
                
                if($index <= 18){
                  $total_symptoms_score = $total_symptoms_score+(!empty($value->score) ? $value->score : 0);
                }
                
                if($index > 18 && $index <= 26){
                  $performance_score = $performance_score+(!empty($value->score) ? $value->score : 0);
                }

                $index++;
              }

              $score_message = 'Total Symptom Score for questions 1–18 :'.$total_symptoms_score;
              $score_message = $score_message.' Average Performance : '.round($performance_score/8,2);
            }
            return $score_message;
        break;

        case 'Assessment Scale - Teacher Informant':
            $score_message = '';
            if(!empty($data['answers'])){
              $index = 1;
              $one_to_nine_answers = $ten_to_eighteen = 0;
              $nineteen_to_twentyeight = $twentynine_to_thirtyFive = $fortyone_to_fortyseven = 0;
              $thirtyfive_to_fortythree = 0;

              $msg_occured = false;
              $inattention = $hyperactivity_impulsivity = false;

              $score_message = "Teacher Assessment Scale :";
              foreach ($data['answers'] as $key => $value) {
                
                if($index <= 9){
                  if($value->score > 2){
                    $one_to_nine_answers = $one_to_nine_answers+1;
                  }
                }

                if($index > 9 && $index <= 18){
                  if($value->score > 2){
                    $ten_to_eighteen = $ten_to_eighteen+1;
                  }
                }

                if($index > 18 && $index <= 28){
                  if($value->score > 2){
                    $nineteen_to_twentyeight = $nineteen_to_twentyeight+1;
                  }
                }

                if($index > 28 && $index <= 35){
                  if($value->score > 2){
                    $twentynine_to_thirtyFive = $twentynine_to_thirtyFive+1;
                  }
                }

                if($index > 35 && $index <= 43){
                  if($value->score > 3){
                    $thirtyfive_to_fortythree = $thirtyfive_to_fortythree+1;
                  }
                }

                $index++;
              }

              if($one_to_nine_answers >=6 && $thirtyfive_to_fortythree >= 1){
                $inattention = true;
                $score_message = $score_message." Predominantly Inattentive subtype";
                $msg_occured = true;
              }

              if($ten_to_eighteen >=6 && $thirtyfive_to_fortythree >= 1){
                $hyperactivity_impulsivity = true;
                $score_message = $score_message." Predominantly Hyperactive/Impulsive subtype";
                $msg_occured = true;
              }

              if(!empty($inattention) && !empty($hyperactivity_impulsivity)){
                $score_message = $score_message." ADHD Combined Inattention/Hyperactivity";
              }

              if($nineteen_to_twentyeight >=6 && $thirtyfive_to_fortythree >= 1){
                $score_message = $score_message." Oppositional-Defiant/Conduct Disorder Screen";
                $msg_occured = true;
              }

              if($twentynine_to_thirtyFive >=6 && $thirtyfive_to_fortythree >= 1){
                $score_message = $score_message." Anxiety/Depression Screen";
                $msg_occured = true;
              }
            }

            if(!empty($msg_occured)){
              return $score_message;
            }else{
              return '';
            }
            
        break;

        case 'Assessment Follow-up - Teacher Informant':
            $score_message = '';
            if(!empty($data['answers'])){
              $index = 1;
              $total_symptoms_score = $performance_score = $average_performance_score = 0;

              $msg_occured = false;
              $inattention = $hyperactivity_impulsivity = false;

              $score_message = "Parent Assessment Follow-up :";
              foreach ($data['answers'] as $key => $value) {
                
                if($index <= 18){
                  $total_symptoms_score = $total_symptoms_score+(!empty($value->score) ? $value->score : 0);
                }
                
                if($index > 18 && $index <= 26){
                  $performance_score = $performance_score+(!empty($value->score) ? $value->score : 0);
                }

                $index++;
              }

              $score_message = 'Total Symptom Score for questions 1–18 : '.$total_symptoms_score;
              $score_message = $score_message.' Average Performance : '.round($performance_score/8,2);
            }
            return $score_message;
        break;

        case 'Apgar Scoring System':
          $score = (int)$data['score'];
          $score_message  = "@span Apgar Score of @c";
          $txt_score      = '@f'.$score.'@c are';



          if($score == 0 || $score <= 3){
            $score_message = $score_message.'  !danger!'.$txt_score.'  @br @f Critical Low @c !';
          } else if($score == 4 || $score <= 6){
            $score_message = $score_message.'  !warning!'.$txt_score.'  @br @f Below normal @c !';
          } else{
            $score_message = $score_message.' !primary!'.$txt_score.'  @br @f Considered Normal @c !';
          }

          return $score_message;
        break;

        case 'Covid self assessment':

          // @span @c  !danger! @f'.$score.'@c are' @br @f Critical Low @c !

          $score_message  = "@span ";

          $covid_questions = Question::where('name' , 'What is your present Temperature value in fahrenheit?')
                                ->orWhere('name', 'What is your present SpO2 value?')
                                ->orWhere('name', 'What is your Respiration Rate?')->get();
          // log::info($covid_questions);

          if(!empty($covid_questions)){
            foreach ($covid_questions as $covid_question) {
             // log::info($covid_question['id']);
             $find_questions =  (array) $data['answers'];

             if(array_key_exists($covid_question['id'], $find_questions)){
                if($find_questions[$covid_question['id']]){

                  switch ($covid_question['name']) {
                    case 'What is your present Temperature value in fahrenheit?':
                      # Temperature 98.6 < please take a teleconsult 100 > please visit near by clinic
                      $score_message = $score_message.' Temperature @c'.($find_questions[$covid_question['id']] >= 100 || $find_questions[$covid_question['id']] < 95 ? ' !danger! '.$find_questions[$covid_question['id']].' @f is danger @c @br please visit near by clinic @c !' : ($find_questions[$covid_question['id']] > 98.6 ? ' !warning! '.$find_questions[$covid_question['id']].' @f is warning @c @br @teleconsult @c !' : ' !primary! '.$find_questions[$covid_question['id']].' @f @c @br Normal !'));
                    break;

                    case 'What is your present SpO2 value?':
                      # Spo2 < 95 please take a teleconsult < 90 please visit near by clinic
                      $score_message = $score_message.'@br @span Spo2 @c'.($find_questions[$covid_question['id']] <= 90 ? ' !danger! '.$find_questions[$covid_question['id']].' @f is danger @c @br @teleconsult @c !' : ($find_questions[$covid_question['id']] < 95 ? ' !warning! '.$find_questions[$covid_question['id']].' @f is warning @c @br please visit near by clinic @c !' : ' !primary! '.$find_questions[$covid_question['id']].' @f @c @br Normal !'));
                    break;

                    case 'What is your Respiration Rate?':
                      # Spo2 < 95 please take a teleconsult < 90 please visit near by clinic
                      $score_message = $score_message.'@br @span Respiration Rate @c'.($find_questions[$covid_question['id']] > 18 ? ' !danger! '.$find_questions[$covid_question['id']].' @f is High @c @br @teleconsult @c !' : ($find_questions[$covid_question['id']] < 12 ? ' !warning! '.$find_questions[$covid_question['id']].' @f is Low @c @br please visit near by clinic @c !' : ' !primary! '.$find_questions[$covid_question['id']].' @f @c @br Normal !'));
                    break;
                    
                    default:
                      # code...
                    break;
                  }
                }
             }
             
            }
          }

          return $score_message;
        break;

        case 'Dimensions of Psychosis Symptom Severity':

          $score_message  = "@span ";

          $all_questions = Question::whereIn('name' , ['Hallucinations','Delusions', 'Disorganized speech',
            'Abnormal psychomotor behavior', 'Negative symptoms (restricted emotional expression or avolition)',
            'Impaired cognition', 'Depression','Mania'])->get();

          $pre_defined_scores  = self::Psychosis_Symptom_Severity_score_fields();

          if(!empty($all_questions)){
            foreach ($all_questions as $all_question) {
             // log::info($all_question['id']);
             $find_questions =  (array) $data['answers'];

             if(array_key_exists($all_question['id'], $find_questions)){
                if($find_questions[$all_question['id']]){

                  $answeredscore = (int) (!empty($find_questions[$all_question['id']]->score) ? $find_questions[$all_question['id']]->score : 0);

                  $score_explanation = (!empty($pre_defined_scores[$answeredscore]) ? $pre_defined_scores[$answeredscore] : 'Nothing');

                    switch ($answeredscore) {
                      case 0:
                        $answer_color = '!success!';
                      break;

                      case 1:
                        $answer_color = '!primary!';
                      break;

                      case 2:
                        $answer_color = '!secondary!';
                      break;

                      case 3:
                        $answer_color = '!warning!';
                      break;

                      case 4:
                        $answer_color = '!danger!';
                      break;
                      
                      default:
                        $answer_color = '!danger!';
                      break;
                    }
                    $score_message = $score_message.'@br @span '.$all_question['name'].' @c  '.$answer_color.' @f is '.$score_explanation.' @c @br @c ! @f Score is '.$answeredscore.' @c';

                }
              }
             
            }
          }

          return $score_message;
        break;

        case 'Triage (SOP) in Covid 19':
          $score_message  = "@span ";

          $pre_asymptomatic = Question::whereIn('name' , ['Test +ve(positive)', 'No Symptoms that are consistant with Covid19'])->get();

          $mild_illness = Question::whereIn('name' , ['Symptoms of Fever, Cough, Soar Throat, Fatigue, Head ache, Body ache, loss of smell & or Taste etc', 'No shortness of breath', 'No difficulty in breathing', 
            'No abnormal chest imaging', 'No Current Mental Conditions', 'Correlate with No co-morbidity if any'])->get();

          $moderate_illness = Question::whereIn('name' , ['Evidence of lower respiratory infections',
           'Positive Chest Imaging', 'On Clinical Evaluation or Symptomatic Assessment',
           'SpO2 at <=94%', 'Cough with Expectoration', 'Sputum (may be colored or blood stained)',
           'Increased in respiration rate > 20 BPM', 'Shortness or Difficulty in Breathing',
           'Pain or burning in the Chest'])->get();

          $severe_illness = Question::whereIn('name' , ['SpO2 < 94%','Respiratory Rate > 30 BPM',
            'Imaging Lung infilltrates > 50%','Po2 / F1o2 (Less than 300mm Hg)'])->get();

          $critical_illness = Question::whereIn('name' , ['Respiratory Failure','Septic shock',
            'Multiple Organ Dysfunction'])->get();

          $critical_illness_error = $severe_illness_error = $moderate_illness_error = $mild_illness_error = $pre_asymptomatic_error = false;

          if(!empty($critical_illness)){
            foreach ($critical_illness as $covid_question) {
             $find_questions =  (array) $data['answers'];

             if(array_key_exists($covid_question['id'], $find_questions)){
                if($find_questions[$covid_question['id']]){
                  if($find_questions[$covid_question['id']]->name == 'Yes'){
                    $critical_illness_error = true;
                  }
                }
              }
           }
         }

         if(!empty($severe_illness)){
            // log::info($severe_illness);
            foreach ($severe_illness as $covid_question) {
             $find_questions =  (array) $data['answers'];

             if(array_key_exists($covid_question['id'], $find_questions)){
                if($find_questions[$covid_question['id']]){
                  if($find_questions[$covid_question['id']]->name == 'Yes'){
                    $severe_illness_error = true;
                  }
                }
              }
           }
         }

         if(!empty($moderate_illness)){
            foreach ($moderate_illness as $covid_question) {
             $find_questions =  (array) $data['answers'];

             if(array_key_exists($covid_question['id'], $find_questions)){
                if($find_questions[$covid_question['id']]){
                  if($find_questions[$covid_question['id']]->name == 'Yes'){
                    $moderate_illness_error = true;
                  }
                }
              }
           }
         }

         if(!empty($mild_illness)){
            foreach ($mild_illness as $covid_question) {
             $find_questions =  (array) $data['answers'];

             if(array_key_exists($covid_question['id'], $find_questions)){
                if($find_questions[$covid_question['id']]){
                  if($find_questions[$covid_question['id']]->name == 'Yes'){
                    $mild_illness_error = true;
                  }
                }
              }
           }
         }

         if(!empty($pre_asymptomatic)){
            foreach ($pre_asymptomatic as $covid_question) {
             $find_questions =  (array) $data['answers'];

             if(array_key_exists($covid_question['id'], $find_questions)){
                if($find_questions[$covid_question['id']]){
                  if($find_questions[$covid_question['id']]->name == 'Yes'){
                    $pre_asymptomatic_error = true;
                  }
                }
              }
           }
         }


         if(!empty($critical_illness_error)){
          $score_message = $score_message.'@br @span CRITICAL ILLNESS @c !danger! Active Assisted ICU support @f @c @br @c!';
         }else if(!empty($severe_illness_error)){
          $score_message = $score_message.'@br @span SEVERE ILLNESS @c !warning! Immediate Hospitalization @f @c @br @c!';
         }else if(!empty($moderate_illness_error)){
          $score_message = $score_message.'@br @span MODERATE ILLNESS @c !secondary! Immediate face to face consultation with Doctor @f @c @br @c!';
         }else if(!empty($mild_illness_error)){
          $score_message = $score_message.'@br @span MILD ILLNESS @c !primary! Home Quarantine, Self Isolation, Masking, Good Hyderation, Paracetamol SOS Fever, Monitor Vital Signs and enter in the App @f @c @c @br !success! RED FLAG SIGNS DURING HOME MONITORING @c @br 1. Fever (High Grade) rising even ater 7th day  @br 2.Biphasic Fever - Second face may signal the start of Cytokines storm @br 3. Normal Activity provoked (6 Minutes walking) - increase in respiratory rate / unmasking breathlessness / Decreasing in SpO2 by 3% from baseline @br 4. Severe vomitting & diarrhea
          @br !success! RETURN FROM QUARANTINE @c @br 1. Negative RTPCR test result @br 2. CT value >34 @br 3. 3 days of syptom free period with more than 10 days since onset of symptoms 
          @br !success! PEDIATRIC SPECIFIC Red Flag @c @br 1. Fall in Urinary output @br 2. Skin Rash - Mosaic @br 3. Breast Feeding children - Stop Feeding !';
         }else if(!empty($pre_asymptomatic_error)){
          $score_message = $score_message.'@br @span PRE / ASYMPTOMATIC @c !success! Home Quarantine, Self Isolation, Masking, Good Hyderation, Paracetamol SOS Fever, Monitor Vital Signs and enter in the App @f @c @c @br !success! RED FLAG SIGNS DURING HOME MONITORING @c @br 1. Fever (High Grade) rising even ater 7th day  @br 2.Biphasic Fever - Second face may signal the start of Cytokines storm @br 3. Normal Activity provoked (6 Minutes walking) - increase in respiratory rate / unmasking breathlessness / Decreasing in SpO2 by 3% from baseline @br 4. Severe vomitting & diarrhea
          @br !success! RETURN FROM QUARANTINE @c @br 1. Negative RTPCR test result @br 2. CT value >34 @br 3. 3 days of syptom free period with more than 10 days since onset of symptoms 
          @br !success! PEDIATRIC SPECIFIC Red Flag @c @br 1. Fall in Urinary output @br 2. Skin Rash - Mosaic @br 3. Breast Feeding children - Stop Feeding !';
         }else{
          $score_message = "";
         }

         // dd($score_message);

         return $score_message;
        break;

        case 'CT Imaging for COVID19':

          $score_message  = "@span ";

          $covid_questions = Question::whereIn('name' , ['CT Scoring', 'CO-RADS - Level of Suspension COVID19 infection'])->get();

          if(!empty($covid_questions)){
            foreach ($covid_questions as $covid_question) {
             $find_questions =  (array) $data['answers'];

             if(array_key_exists($covid_question['id'], $find_questions)){
                if($find_questions[$covid_question['id']]){

                  switch ($covid_question['name']) {
                    case 'CT Scoring':
                      switch($find_questions[$covid_question['id']]->name){
                        case '0':
                          $score_message = $score_message.'@br CT Scoring @span @c !success! Normal / Negative @f @c @br @c!';
                        break;

                        case '1 to 14':
                          $score_message = $score_message.'@br CT Scoring @span @c !primary! Mild Variety @f @c @br @c!';
                        break;

                        case '15 to 25':
                          $score_message = $score_message.'@br CT Scoring @span @c !warning! Moderate Variant @f @c @br @c!';
                        break;

                        case '26 to 40':
                          $score_message = $score_message.'@br CT Scoring @span @c !danger! Severe Variant @f @c @br @c!';
                        break;

                        default:
                          # code...
                        break;
                      }
                   
                    break;

                    case 'CO-RADS - Level of Suspension COVID19 infection':
                      switch($find_questions[$covid_question['id']]->name){
                        case 'Not Interpretable':
                          $score_message = $score_message.'@br CO-RADS - Level of Suspension COVID19 infection @span @c !success! Scan Technically insufficient for assigning a score @f @c @br @c!';
                        break;

                        case 'Very Low':
                          $score_message = $score_message.'@br CO-RADS - Level of Suspension COVID19 infection @span @c !success! Normal or Noninfectious @f @c @br @c!';
                        break;

                        case 'Low':
                          $score_message = $score_message.'@br CO-RADS - Level of Suspension COVID19 infection @span @c !warning! Typical for other infection but not COVID19 @f @c @br @c!';
                        break;

                        case 'Equivocal / Unsure':
                          $score_message = $score_message.'@br CO-RADS - Level of Suspension COVID19 infection @span @c !warning! Features compatible with COVID19 but also other diseases @f @c @br @c!';
                        break;

                        case 'High':
                          $score_message = $score_message.'@br CO-RADS - Level of Suspension COVID19 infection @span @c !warning! Suspicious of COVID19 @f @c @br @c!';
                        break;

                        case 'Very High':
                          $score_message = $score_message.'@br CO-RADS - Level of Suspension COVID19 infection @span @c !warning! Typical for COVID19 @f @c @br @c!';
                        break;

                        case 'Proven':
                          $score_message = $score_message.'@br CO-RADS - Level of Suspension COVID19 infection @span @c !danger! RT-PCR Positive for SARS - CoV-2 @f @c @br @c!';
                        break;

                        default:
                          # code...
                        break;
                      }
                    break;
                    
                    default:
                      # code...
                    break;
                  }
                }
             }
             
            }
          }

          return $score_message;
        break;

        case 'Investigation for Biochemical Monitoring of Covid 19 Patients':

          $score_message  = "@span ";

          $covid_questions = Question::whereIn('name' , ['WBC (Complete Blood Count)','Neutrophil (Complete Blood Count)',
            'Lympocyte (Complete Blood Count)', 'Platlet Count (Complete Blood Count)',
            'Blood Gases', 'Albumin', 'Lactate dehydrogenase (LDH)?', 'ALT (S.G.P.T)', 
            'AST (S.G.O.T)', 'Total Bilirubin', 'Creatinine', 'Urea', 'Cardiac Troponin',
            'D-Dimer', 'Prothrombin Time', 'Procalcitonin', 'C-reactive Protein',
            'Ferritin', 'Cytokines'])->get();

          # log::info($covid_questions);

          if(!empty($covid_questions)){
            foreach ($covid_questions as $covid_question) {
             // log::info($covid_question['id']);
             $find_questions =  (array) $data['answers'];

             if(array_key_exists($covid_question['id'], $find_questions)){
                if($find_questions[$covid_question['id']]){

                  switch ($covid_question['name']) {
                    case 'WBC (Complete Blood Count)':
                      $score_message = $score_message.'@br @span WBC @c'.($find_questions[$covid_question['id']] >= 4000 && $find_questions[$covid_question['id']] <= 11000 ? ' !primary! '.$find_questions[$covid_question['id']].' @f is Normal @c @br @c !' : ($find_questions[$covid_question['id']] > 11000 ? ' !danger! '.$find_questions[$covid_question['id']].' @f is Abnormal @c @br Bacterial (Super) Infection @c !' : ' !warning! '.$find_questions[$covid_question['id']].' @f @c @br Low !'));
                    break;

                    case 'Neutrophil (Complete Blood Count)':
                      $score_message = $score_message.'@br @span Neutrophil @c'.($find_questions[$covid_question['id']] >= 40 && $find_questions[$covid_question['id']] <= 75 ? ' !primary! '.$find_questions[$covid_question['id']].' @f is Normal @c @br @c !' : ($find_questions[$covid_question['id']] > 75 ? ' !danger! '.$find_questions[$covid_question['id']].' @f is Abnormal @c @br Bacterial (Super) Infection @c !' : ' !warning! '.$find_questions[$covid_question['id']].' @f @c @br Low !'));
                    break;

                    case 'Lympocyte (Complete Blood Count)':
                      $score_message = $score_message.'@br @span Lympocyte @c'.($find_questions[$covid_question['id']] >= 20 && $find_questions[$covid_question['id']] <= 45 ? ' !primary! '.$find_questions[$covid_question['id']].' @f is Normal @c @br @c !' : ($find_questions[$covid_question['id']] < 20 ? ' !danger! '.$find_questions[$covid_question['id']].' @f is Abnormal @c @br Decreased Immunological Response to Virus @c !' : ' !warning! '.$find_questions[$covid_question['id']].' @f @c @br High !'));
                    break;

                    case 'Platlet Count (Complete Blood Count)':
                      $score_message = $score_message.'@br @span Platlet Count @c'.($find_questions[$covid_question['id']] >= 1.5 && $find_questions[$covid_question['id']] <= 4.1 ? ' !primary! '.$find_questions[$covid_question['id']].' @f is Normal @c @br @c !' : ($find_questions[$covid_question['id']] < 1.5 ? ' !danger! '.$find_questions[$covid_question['id']].' @f is Abnormal @c @br Consumption Coagulopathy (DIC) @c !' : ' !warning! '.$find_questions[$covid_question['id']].' @f @c @br High !'));
                    break;

                    case 'Blood Gases':
                     $score_message = $score_message.'@br @span Blood Gases @c'.($find_questions[$covid_question['id']] >= 94 && $find_questions[$covid_question['id']] <= 100 ? ' !primary! '.$find_questions[$covid_question['id']].' @f is Normal @c @br @c !' : ($find_questions[$covid_question['id']] < 94 ? ' !danger! '.$find_questions[$covid_question['id']].' @f is Abnormal @c @br Requires Hopitalization and Critical Care Management @c !' : ' !warning! '.$find_questions[$covid_question['id']].' @f @c @br High !'));
                    break;

                    case 'Albumin':
                      $score_message = $score_message.'@br @span Albumin @c'.($find_questions[$covid_question['id']] >= 3.5 && $find_questions[$covid_question['id']] <= 5.2 ? ' !primary! '.$find_questions[$covid_question['id']].' @f is Normal @c @br @c !' : ($find_questions[$covid_question['id']] < 3.5 ? ' !danger! '.$find_questions[$covid_question['id']].' @f is Abnormal @c @br Impaired Liver Function @c !' : ' !warning! '.$find_questions[$covid_question['id']].' @f @c @br High !'));
                    break;

                    case 'Lactate dehydrogenase (LDH)?':
                      $score_message = $score_message.'@br @span Lactate dehydrogenase @c'.($find_questions[$covid_question['id']] >= 140 && $find_questions[$covid_question['id']] <= 280 ? ' !primary! '.$find_questions[$covid_question['id']].' @f is Normal @c @br @c !' : ($find_questions[$covid_question['id']] > 280 ? ' !danger! '.$find_questions[$covid_question['id']].' @f is Abnormal @c @br Pulmonary Injury and / or widespread organ damage @c !' : ' !warning! '.$find_questions[$covid_question['id']].' @f @c @br Low !'));
                    break;

                    case 'ALT (S.G.P.T)':
                      $score_message = $score_message.'@br @span ALT (S.G.P.T) @c'.($find_questions[$covid_question['id']] <= 41 ? ' !primary! '.$find_questions[$covid_question['id']].' @f is Normal @c @br @c !' : ($find_questions[$covid_question['id']] > 41 ? ' !danger! '.$find_questions[$covid_question['id']].' @f is Abnormal @c @br Liver Injury or organ damage @c !' : ' !warning! '.$find_questions[$covid_question['id']].' @f @c @br Other !'));
                    break;

                    case 'AST (S.G.O.T)':
                      $score_message = $score_message.'@br @span AST (S.G.O.T) @c'.($find_questions[$covid_question['id']] <= 40 ? ' !primary! '.$find_questions[$covid_question['id']].' @f is Normal @c @br @teleconsult @c !' : ($find_questions[$covid_question['id']] > 40 ? ' !danger! '.$find_questions[$covid_question['id']].' @f is Abnormal @c @br Liver Injury or organ damage @c !' : ' !warning! '.$find_questions[$covid_question['id']].' @f @c @br Other !'));
                    break;

                    case 'Total Bilirubin':
                      $score_message = $score_message.'@br @span Total Bilirubin @c'.($find_questions[$covid_question['id']] >= 0.3 && $find_questions[$covid_question['id']] <= 1.2 ? ' !primary! '.$find_questions[$covid_question['id']].' @f is Normal @c @br @teleconsult @c !' : ($find_questions[$covid_question['id']] > 1.2 ? ' !danger! '.$find_questions[$covid_question['id']].' @f is Abnormal @c @br Liver Injury @c !' : ' !warning! '.$find_questions[$covid_question['id']].' @f @c @br Low !'));
                    
                    break;

                    case 'Creatinine':
                      $score_message = $score_message.'@br @span Creatinine @c'.($find_questions[$covid_question['id']] >= 0.7 && $find_questions[$covid_question['id']] <= 1.2 ? ' !primary! '.$find_questions[$covid_question['id']].' @f is Normal @c @br @teleconsult @c !' : ($find_questions[$covid_question['id']] > 1.2 ? ' !danger! '.$find_questions[$covid_question['id']].' @f is Abnormal @c @br Kidney Injury @c !' : ' !warning! '.$find_questions[$covid_question['id']].' @f @c @br Low !'));
                    break;

                    case 'Urea':
                      $score_message = $score_message.'@br @span Urea @c'.($find_questions[$covid_question['id']] >= 13 && $find_questions[$covid_question['id']] <= 49 ? ' !primary! '.$find_questions[$covid_question['id']].' @f is Normal @c @br @teleconsult @c !' : ($find_questions[$covid_question['id']] > 49 ? ' !danger! '.$find_questions[$covid_question['id']].' @f is Abnormal @c @br Kidney Injury @c !' : ' !warning! '.$find_questions[$covid_question['id']].' @f @c @br Low !'));
                    break;

                    case 'Cardiac Troponin':
                      $score_message = $score_message.'@br @span Cardiac Troponin @c'.($find_questions[$covid_question['id']] <= 0.4 ? ' !primary! '.$find_questions[$covid_question['id']].' @f is Normal @c @br @teleconsult @c !' : ($find_questions[$covid_question['id']] > 0.4 ? ' !danger! '.$find_questions[$covid_question['id']].' @f is Abnormal @c @br Cardiac Injury / Myocarditis @c !' : ' !warning! '.$find_questions[$covid_question['id']].' @f @c @br Low !'));
                    break;

                    case 'D-Dimer':
                      $score_message = $score_message.'@br @span D-Dimer @c'.($find_questions[$covid_question['id']] <= 0.5 ? ' !primary! '.$find_questions[$covid_question['id']].' @f is Normal @c @br @teleconsult @c !' : ($find_questions[$covid_question['id']] > 0.5 ? ' !danger! '.$find_questions[$covid_question['id']].' @f is Abnormal @c @br Activation of Blood Coagulation (DIC) @c !' : ' !warning! '.$find_questions[$covid_question['id']].' @f @c @br Low !'));
                    break;

                    case 'Prothrombin Time':
                      $score_message = $score_message.'@br @span Prothrombin Time @c'.($find_questions[$covid_question['id']] >= 11 && $find_questions[$covid_question['id']] <= 13.5 ? ' !primary! '.$find_questions[$covid_question['id']].' @f is Normal @c @br @c !' : ($find_questions[$covid_question['id']] > 13.5 ? ' !danger! '.$find_questions[$covid_question['id']].' @f is Abnormal @c @br Activation of Blood Coagulation (DIC) @c !' : ' !warning! '.$find_questions[$covid_question['id']].' @f @c @br Low !'));
                    break;

                    case 'Procalcitonin':
                      $score_message = $score_message.'@br @span Procalcitonin @c'.($find_questions[$covid_question['id']] <= 0.15 ? ' !primary! '.$find_questions[$covid_question['id']].' @f is Normal @c @br @c !' : ($find_questions[$covid_question['id']] > 0.15 ? ' !danger! '.$find_questions[$covid_question['id']].' @f is Abnormal @c @br Bacterial (Super) Infection (DIC) @c !' : ' !warning! '.$find_questions[$covid_question['id']].' @f @c @br Low !'));
                    break;

                    case 'C-reactive Protein':
                      $score_message = $score_message.'@br @span C-reactive Protein @c'.($find_questions[$covid_question['id']] <= 3 ? ' !primary! '.$find_questions[$covid_question['id']].' @f is Normal @c @br @c !' : ($find_questions[$covid_question['id']] > 3 ? ' !danger! '.$find_questions[$covid_question['id']].' @f is Abnormal @c @br Severe Viral Infection / Viremia / Viral Sepsis / Cardiac Causes @c !' : ' !warning! '.$find_questions[$covid_question['id']].' @f @c @br Low !'));
                    break;

                    case 'Ferritin':
                      $score_message = $score_message.'@br @span Ferritin @c'.($find_questions[$covid_question['id']] <= 300 ? ' !primary! '.$find_questions[$covid_question['id']].' @f is Normal @c @br @c !' : ($find_questions[$covid_question['id']] > 300 ? ' !danger! '.$find_questions[$covid_question['id']].' @f is Abnormal @c @br Severe Inflammation @c !' : ' !warning! '.$find_questions[$covid_question['id']].' @f @c @br Low !'));
                    break;

                    case 'Cytokines':
                      $score_message = $score_message.'@br @span Cytokines @c'.($find_questions[$covid_question['id']] >= 0.16 && $find_questions[$covid_question['id']] <= 37.7 ? ' !primary! '.$find_questions[$covid_question['id']].' @f is Normal @c @br @c !' : ($find_questions[$covid_question['id']] > 37.7 ? ' !danger! '.$find_questions[$covid_question['id']].' @f is Abnormal @c @br Citokines Storm Syndrome @c !' : ' !warning! '.$find_questions[$covid_question['id']].' @f @c @br High !'));
                    break;
                    
                    default:
                      # code...
                    break;
                  }
                }
             }
             
            }
          }

          return $score_message;
        break;

        case 'CONDUCT DISORDER':
          $score_message  = "@span ";
          $score      = (int)$data['score'];
          $score      = (int) (!empty($score) ? $score : 0);

          $pre_score    = self::conduct_disorder_one_score_fields();
          $post_score   = self::conduct_disorder_two_score_fields();

          $score_explanation_one = (!empty($pre_score[$score]) ? $pre_score[$score] : 'Nothing');
          $score_explanation_two = (!empty($post_score[$score]) ? $post_score[$score] : 'Nothing');

          switch ($score) {
            case 0:
              $answer_explaination = '!success!';
            break;

            case 1:
              $answer_explaination = '!primary!';
            break;

            case 2:
              $answer_explaination = '!warning!';
            break;

            case 3:
              $answer_explaination = '!danger!';
            break;
                      
            default:
              $answer_explaination = '!danger!';
            break;
          }
          $score_message = $score_message.'@br @span Raw Score is '.$score.' @c  '.$answer_explaination.' @f '.$score_explanation_two.' = '.$score_explanation_one.' @c';

          return $score_message;
          
        break;

        case 'NONSUICIDAL SELF-INJURY':
          $score_message  = "@span ";
          $score      = (int)$data['score'];
          $score      = (int) (!empty($score) ? $score : 0);

          $pre_score    = self::nonsuicidal_self_injury_one_score_fields();
          $post_score   = self::nonsuicidal_self_injury_two_score_fields();

          $score_explanation_one = (!empty($pre_score[$score]) ? $pre_score[$score] : 'Nothing');
          $score_explanation_two = (!empty($post_score[$score]) ? $post_score[$score] : 'Nothing');

          switch ($score) {
            case 0:
              $answer_explaination = '!success!';
            break;

            case 1:
              $answer_explaination = '!primary!';
            break;

            case 2:
              $answer_explaination = '!secondary!';
            break;

            case 3:
              $answer_explaination = '!warning!';
            break;

            case 4:
              $answer_explaination = '!danger!';
            break;
                      
            default:
              $answer_explaination = '!danger!';
            break;
          }
          $score_message = $score_message.'@br @span Raw Score is '.$score.' @c  '.$answer_explaination.' @f '.$score_explanation_two.' = '.$score_explanation_one.' @c';

          return $score_message;
          
        break;

        case 'OPPOSITIONAL DEFIANT DISORDER':
          $score_message  = "@span ";
          $score      = (int)$data['score'];
          $score      = (int) (!empty($score) ? $score : 0);

          $pre_score    = self::oppositional_defiant_disorder_one_score_fields();
          $post_score   = self::oppositional_defiant_disorder_two_score_fields();

          $score_explanation_one = (!empty($pre_score[$score]) ? $pre_score[$score] : 'Nothing');
          $score_explanation_two = (!empty($post_score[$score]) ? $post_score[$score] : 'Nothing');

          switch ($score) {
            case 0:
              $answer_explaination = '!success!';
            break;

            case 1:
              $answer_explaination = '!primary!';
            break;

            case 2:
              $answer_explaination = '!warning!';
            break;

            case 3:
              $answer_explaination = '!danger!';
            break;
                      
            default:
              $answer_explaination = '!danger!';
            break;
          }
          $score_message = $score_message.'@br @span Raw Score is '.$score.' @c  '.$answer_explaination.' @f '.$score_explanation_two.' = '.$score_explanation_one.' @c';

          return $score_message;
          
        break;

        case 'SOMATIC SYMPTOM DISORDER':

          $score_message  = "@span ";
          if(!empty($data['answers'])){
              $index = 1;
              $total_symptoms_score = $performance_score = $average_performance_score = 0;

              $msg_occured = false;
              $inattention = $hyperactivity_impulsivity = false;

              foreach ($data['answers'] as $key => $value) {
                $total_symptoms_score = $total_symptoms_score+(!empty($value->score) ? $value->score : 0);

                $index++;
              }

              $score_message = 'Total Raw Score :'.$total_symptoms_score;
              $score_message = $score_message.', Average Score : '.round($total_symptoms_score/3,2);
          }

          return $score_message;
        break;

        case 'Autism Spectrum Disorder':
          $score_message  = "@span ";
          $score      = (int)$data['score'];
          $score      = (int) (!empty($score) ? $score : 0);

          $pre_score    = self::autism_social_disorder_one_score_fields();
          $post_score   = self::autism_social_disorder_two_score_fields();

          $score_explanation_one = (!empty($pre_score[$score]) ? $pre_score[$score] : 'Nothing');
          $score_explanation_two = (!empty($post_score[$score]) ? $post_score[$score] : 'Nothing');

          switch ($score) {
            case 0:
              $answer_explaination = '!success!';
            break;

            case 1:
              $answer_explaination = '!primary!';
            break;

            case 2:
              $answer_explaination = '!warning!';
            break;

            case 3:
              $answer_explaination = '!danger!';
            break;
                      
            default:
              $answer_explaination = '!danger!';
            break;
          }
          $score_message = $score_message.'@br @span Raw Score is '.$score.' @c  '.$answer_explaination.' @f '.$score_explanation_two.' = '.$score_explanation_one.' @c';

          return $score_message;
          
        break;

        case 'Social Communication Disorder':
          $score_message  = "@span ";
          $score      = (int)$data['score'];
          $score      = (int) (!empty($score) ? $score : 0);

          $pre_score    = self::autism_social_disorder_one_score_fields();
          $post_score   = self::autism_social_disorder_two_score_fields();

          $score_explanation_one = (!empty($pre_score[$score]) ? $pre_score[$score] : 'Nothing');
          $score_explanation_two = (!empty($post_score[$score]) ? $post_score[$score] : 'Nothing');

          switch ($score) {
            case 0:
              $answer_explaination = '!success!';
            break;

            case 1:
              $answer_explaination = '!primary!';
            break;

            case 2:
              $answer_explaination = '!warning!';
            break;

            case 3:
              $answer_explaination = '!danger!';
            break;
                      
            default:
              $answer_explaination = '!danger!';
            break;
          }
          $score_message = $score_message.'@br @span Raw Score is '.$score.' @c  '.$answer_explaination.' @f '.$score_explanation_two.' = '.$score_explanation_one.' @c';

          return $score_message;
          
        break;
        
        default:
          $score = (int)$data['score'];
          if(!empty($score)){
            $score_message = "Score is : ".$score;
            return $score_message;
          }
        break;
      }

      
    }
  }
}
