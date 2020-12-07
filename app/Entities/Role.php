<?php

namespace App\Entities;

class Role extends BaseModel
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        "code", "name", "permissions", "is_active",
    ];

    public function users()
    {
        return $this->hasMany(User::class);
    }
}
