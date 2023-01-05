<?php

namespace App\Entities;

use App\Entities\Role;
use App\Entities\User;

class Form extends BaseModel
{
    const VIEW = true;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        "parent_id", "slug", "name", "desc", "assessment_group", "type", "images", "is_active", "created_by", "role_code"
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        "role_code" => "object",
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

    public function applyFilters($model, $isPluck)
    {
        $model = parent::applyFilters($model, $isPluck);
        $request = app('request');

        $status_key = $request->get('searchkey');

        if (!empty($request->get('provider_id'))) {
            $form_user_id = User::where('id', $request->get('provider_id'))->value('role_id');
            $form_role_code = Role::where('id', $form_user_id)->value('code');

            $model->whereJsonContains('role_code', $form_role_code);
        } else if ($request->user()->role_id) {
            $logged_in_role_code = Role::where('id', $request->user()->role_id)->value('code');
            $model->whereJsonContains('role_code', $logged_in_role_code);
        }

        if (strtolower($request->get('searchkey')) == "inactive" || strtolower($request->get('searchkey')) == "active") {
            $status_key = (strtolower($request->get('searchkey')) == "inactive") ? "0" : "1";
            $model->where('forms.is_active', $status_key);
        }

        if ($request->get('searchkey')) {
            $model->where('forms.name', 'like', "%" . $request->get('searchkey') . "%");
        }

        if ($request->get('slug')) {
            $model->where('forms.assessment_group', $request->get('slug'));
        }

        if ($request->get('form_slug')) {
            $model->where('forms.slug', $request->get('form_slug'));
        }

        return $model;
    }
}
