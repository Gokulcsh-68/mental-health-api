<?php

namespace App\Entities;

use App\Services\MasterService;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class Master extends BaseModel
{
    const VIEW = true;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        "master_type_slug", "name", "slug", "attributes", "is_active"
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'attributes' => 'object'
        
    ];

    public $timestamps = false;

    protected $appends = ['details'];

    public function immunisation()
    {
        return $this->belongsTo(Immunisation::class, 'slug', 'slug');
    }

    public function familyHistory()
    {
        return $this->belongsTo(FamilyHistory::class, 'slug', 'slug');
    }

    public function applyFilters($model, $isPluck)
    {
        $model = parent::applyFilters($model, $isPluck);
        $request = app('request');

        if ($request->get('master_type')) {
            $model->where('masters.master_type_slug', $request->get('master_type'));
        }

        if ($request->get('slug')) {
            $model->where('masters.master_type_slug', $request->get('slug'));

            // Forms Access
            if($request->get('slug') == 'assessment-group'){
                $logged_in_role_code = Role::where('id', $request->user()->role_id)->value('code');

                $form_user_id   = User::where('id',$request->get('patient_id'))->value('role_id');
                $form_role_code = Role::where('id',$form_user_id)->value('code');

                $form_slug = '';

                switch ($logged_in_role_code) {

                    case 'staff':
                       if($form_role_code == 'staff'){
                        $form_slug = ['healthy-heart', 'psychiatric-exam', 'stroke-scale'];
                       }else if($form_role_code == 'student'){
                        $form_slug = ['adhd'];
                       }
                    break;

                    case 'school':
                       if($form_role_code == 'staff'){
                        $form_slug = ['healthy-heart', 'psychiatric-exam', 'stroke-scale'];
                       }
                    break;
                    
                    default:
                       $form_slug = ['adhd', 'healthy-heart', 'psychiatric-exam', 'stroke-scale'];
                    break;
                }

                // dd($form_slug);

                if(!empty($form_slug)){
                    $model->whereIn('masters.slug', $form_slug);
                }

            }
        }
        
        if ($request->get('searchkey')) {

            $model->where(function ($subquery) use ($request) {
                        $subquery->Where('masters.name', 'LIKE',"%".$request->get('searchkey')."%")
                        ->orWhere('masters.slug', 'LIKE',"%".$request->get('searchkey')."%");
                });
        }


        return $model;
    }

    public function getResult($slug, $type, $attributes)
    {      
        $request = app('request');
        
        if($type == 'immunisation'){
            $patient_dosages = [];

            $patient_dosages = DB::table('immunisations')
                    ->Where('immunisations.patient_id', $request->get('patient_id'))
                    ->Where('immunisations.slug', $slug)
                    ->value('details');
            $patient_dosages = json_decode($patient_dosages);

            if($patient_dosages == null){ $patient_dosages = []; }

            foreach ($attributes->values as $key => $value) {
                $newValue           = $value;
                $newValue->status   = in_array($value->periods, $patient_dosages);
            }
        }else if($type == 'family_history_diseases'){
            $patient_family_history = [];

            $patient_family_history = DB::table('family_histories')
                    ->Where('family_histories.patient_id', $request->get('patient_id'))
                    ->Where('family_histories.slug', $slug)
                    ->value('details');
            $patient_family_history = json_decode($patient_family_history);
            // $attributes->results    = $patient_family_history;

            if($patient_family_history == null){ $patient_family_history = []; }

            foreach ($attributes->values as $key => $value) {
                $newValue           = $value;
                $newValue->status   = in_array($value->relationship, $patient_family_history);
            }
        }else if($type == 'ros'){

            $slug_data = DB::table('review_of_systems')
                    ->Where('review_of_systems.patient_id', $request->get('patient_id'))
                    ->Where('review_of_systems.slug', $slug)->first();

            if(empty($attributes)){
                $attributes = new \stdClass();
                $attributes->ros_available = (!empty($slug_data) ? false : true);
            }

            // foreach ($attributes->values as $key => $value) {
            //     dd($value);
            //     $newValue                   = $value;
            //     $newValue->ros_available    = (!empty($slug_data) ? false : true);
            // }
        }

        return $attributes;        
    }

}
