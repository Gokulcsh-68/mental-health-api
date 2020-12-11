<?php

namespace App\Entities;

class User extends BaseModel
{
    const VIEW = true;

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

     public static function boot()
    {
        parent::boot();

        parent::observe(new \App\Observers\UserObserver());
    }

    public function role()
    {
        return $this->belongsTo(Role::class);
    }

    public function school()
    {
        return $this->hasOne(School::class);
    }

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

    public function getBasicInfo()
    {
        return [
            "id" => $this->id,
            "name" => $this->getFullName(),
            "username" => $this->username,
            "email" => $this->email,
            "mobile" => $this->mobile_number,
            "profile_image" => $this->profile_image,
            "super_admin" => (boolean) $this->super_admin,
            "user_type" => $this->role->code,
        ];
    }

}
