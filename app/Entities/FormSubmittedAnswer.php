<?php

namespace App\Entities;

use App\Traits\AssessmentScoreAccessors;
use DB;
use Illuminate\Support\Facades\Log;

class FormSubmittedAnswer extends BaseModel
{
  use AssessmentScoreAccessors;
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
    "form_id",
    "patient_id",
    "answers",
    "score",
    "created_by"
  ];

  /**
   * The attributes that should be cast to native types.
   *
   * @var array
   */
  protected $casts = [

    'answers' => 'array',
  ];

  /**
   * The attributes excluded from the model's JSON form.
   *
   * @var array
   */
  protected $hidden = [];

  /**
   * The attributes that should be updated on patch method.
   *
   * @var array
   */
  protected $partialFillable = [];

  /**
   * The attributes that should be mutated to dates.
   *
   * @var array
   */
  protected $dates = [];

  /**
   * The event map for the model.
   *
   * @var array
   */
  protected $dispatchesEvents = [];


  public function form()
  {
    return $this->belongsTo(Form::class);
  }


  protected function createModel($request)
  {
    $data = $this->getModelAttributes($request);

    DB::beginTransaction();
    try {

      $this->Where('patient_id', $data['patient_id'])
        ->Where('form_id', $data['form_id'])
        ->delete();

      $data['score'] = 0;

      $answers = [];

      foreach ($data['answers'] as $key => $value) {
        if (isset($value['score'])) {
          $data['score'] =  $data['score'] + $value['score'];
        }

        if (isset($value['answer'])) {
          $value['answer']['score']  = $value['score'];
          $answers[$key]   = $value['answer'];
        } else {
          $answers[$key]  =  $value;
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
}
