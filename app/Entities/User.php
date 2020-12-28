<?php

namespace App\Entities;

use DB;

class User extends BaseModel
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
        "role_id", "first_name", "last_name", "email", "isd_code", "mobile", "username", "secret", "password", "profile_image", "gender", "dob", "blood_group", "timezone_id", "address", "country_iso", "emergency_contact_info", "is_2fa", "is_active", "created_by", "updated_by",
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'address' => 'object'
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
        "first_name", "last_name", "email", "isd_code", "mobile", "username", "secret", "password", "profile_image", "gender", "dob", "blood_group", "timezone_id", "address", "country_iso", "emergency_contact_info", "is_2fa", "is_active"
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

    public static function boot()
    {
        parent::boot();

        parent::observe(new \App\Observers\UserObserver());
    }

    public function role()
    {
        return $this->belongsTo(Role::class);
    }

    public function staff()
    {
        return $this->hasOne(Staff::class, 'user_id');
    }

    public function timezone()
    {
        return $this->belongsTo(Timezone::class);
    }

    public function genderMaster()
    {
        return $this->belongsTo(Master::class, 'gender', 'slug')->where('master_type_slug', 'gender');
    }

    /*public function provider()
    {
        return $this->hasOne(Provider::class);
    }*/

    public function generalLoginAttempt($attributes, $field = "username"):  ? User
    {
        $model = self::where($field, $attributes[$field])
            ->whereHas('role', function ($query) use ($attributes) {
                $query->where('code', $attributes['role']);
            })
            ->first();

        return isset($model->id) && $model->isValidPassword($attributes) ? $model : null;
    }

    public function isValidPassword($attributes) : bool
    {
        return app('hash')->check($attributes['password'], $this->password);
    }

    public function isValidUser($attributes): bool
    {
        return $this->is_active == self::ACTIVE && $this->isValidPassword($attributes);
    }

    public function setPasswordAttribute($value): void
    {
        $this->attributes['password'] = app('hash')->make($value);
    }

    public function setEmailAttribute($value): void
    {
        $this->attributes['email'] = strtolower(strip_tags($value));
    }

    public function setUsernameAttribute($value): void
    {
        $this->attributes['username'] = strtolower(strip_tags($value));
    }

    public function getGenderTextAttribute()
    {
        return optional($this->genderMaster)->name ?? '';
    }

    public function getFullName(): string
    {
        return trim(sprintf('%s %s', $this->first_name, $this->last_name));
    }

    public function getMobileNumberAttribute()
    {
        return ($this->isd_code . $this->mobile);
    }

    public function scopeActive($query)
    {
        return $query->where('is_active', self::ACTIVE);
    }

    public function canAccess($parent, $access): bool
    {
        return true;
    }

    public function additonal_info()
    {

        switch ($this->role->code) {

            case 'staff':
                return $this->staff;
            break;

            default:
                return '';
                break;
        }

    }

    public function getBasicInfo()
    {
        return [
            "id" => $this->id,
            "name" => $this->getFullName(),
            "username" => $this->username,
            "email" => $this->email,
            "mobile" => $this->mobile_number,
            "profileImage" => $this->profile_image ?? false,
            "is2FA" => (boolean) $this->is_2fa,
            "role" => $this->role->code,
            "gender" => $this->gender_text,
            "timezone" => $this->timezone(),
        ];
    }

    protected function createModel($request)
    {
        $data = $this->getModelAttributes($request);

        DB::beginTransaction();
        try {

            // Take role_id

            $data['role_id'] = Role::where("code", $data['role'])->pluck('id')->first();

            $user = User::create($data);

           
            DB::commit();
            return $user;
        } catch (Exception $e) {
            exceptionLogger("Users Create Rollback", $e);
            DB::rollback();
        }

        return null;
    }

    public function applyFilters($model, $isPluck)
    {
        $model = parent::applyFilters($model, $isPluck);
        $request = app('request');
        

            $model->where('users.id','!=', app('request')->user()->id);

        if ($request->get('role_id')) {
            $model->where('users.role_id', $request->get('role_id'));
        }

        if ($request->get('searchkey')) {
            $model->where(function ($query) use ($request) {
            $query->orWhere('users.first_name', 'LIKE',"%".$request->get('searchkey')."%")
                ->orWhere('users.last_name', 'LIKE',"%".$request->get('searchkey')."%")
                ->orWhere('users.email', 'LIKE',"%".$request->get('searchkey')."%")
                ->orWhere('users.address', 'LIKE',"%".$request->get('searchkey')."%")
                ->orWhere('users.gender', 'LIKE',"%".$request->get('searchkey')."%")
                ->orWhere('users.mobile', 'LIKE',"%".$request->get('searchkey')."%");
            });
           
        }

        return $model;
    }

}
