<?php

namespace App\Entities;
use DB;

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

            foreach ($data['answers'] as $key => $value) {
              if(isset($value['score'])){
               $data['score'] =  $data['score'] + $value['score'];

              }

              if(isset($value['answer'])){
               $answers[$key] = $value['answer'];
              }
              else{
                $answers[$key] =  $value;
              }
            }

            $data['answers'] = $answers;
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
          $score_message = "@span PHQ-15 - Levels of Somatic Symptom Severity is @c";         

          $txt_score = '@f'.$score.'@c score';

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

            $score_message = "Raw Score is $score, SE is $se_score, T-Score is ";


          $txt_score = '@f'.$t_score.'@c score';         

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

            $score_message = "Raw Score is $score, SE is $se_score, T-Score is ";      

          $txt_score = '@f'.$t_score.'@c score';    


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

            $score_message = "Raw Score is $score, SE is $se_score, T-Score is ";

          $txt_score = '@f'.$t_score.'@c score';  

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

        case 'Mania':
          $score = (int)$data['score'];

          // dd($data);

          $score_message = '';

          if(!empty($data['answers'])){
            $written_answers = ($data['answers']);
            unset($written_answers->feedback);
            $total_answered_questions = count((array)$written_answers);

            $prorated_score = ($score * 5)/$total_answered_questions;
            $prorated_score = number_format($prorated_score,2);

            $score_message = "Raw Score is $score!, Total Answered Questions is $total_answered_questions, Pro Rated Score is !!$prorated_score! ";
          }

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
