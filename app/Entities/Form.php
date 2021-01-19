<?php

namespace App\Entities;

class Form extends BaseModel
{
    const VIEW = true;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        "parent_id", "slug", "name", "desc", "assessment_group", "type", "images", "is_active", "created_by"
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

    public function FormSubmittedAnswer()
    {
        return $this->hasMany(FormSubmittedAnswer::class);
    }

    public function formQuestions()
    {
        return $this->belongsToMany(Question::class, 'form_questions', 'form_id', 'question_id', 'id');
    }

    public function applyFilters($model, $isPluck){
        $model      = parent::applyFilters($model, $isPluck);
        $request    = app('request');

        $status_key = $request->get('searchkey');
        if(strtolower($request->get('searchkey')) == "inactive" || strtolower($request->get('searchkey')) == "active"){
            $status_key = (strtolower($request->get('searchkey')) == "inactive")?"0":"1";
            $model->where('forms.is_active', $status_key);
        }

        if ($request->get('searchkey')) {
            $model->where('forms.name', 'like', "%".$request->get('searchkey')."%");
        }

        if ($request->get('slug')) {
            $model->where('forms.assessment_group',$request->get('slug'));
        }

        return $model;
    }
}
