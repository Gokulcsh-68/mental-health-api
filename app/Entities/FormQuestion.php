<?php

namespace App\Entities;

class FormQuestion extends BaseModel
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        "form_id", "question_id", "order"
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        
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

    public function FormQuestionAnswer()
    {
        return $this->hasMany(FormQuestionAnswer::class,'question_id', 'question_id');
    }

    public function Questions()
    {
        return $this->hasOne(Question::class, 'id', 'question_id');
    }
}
