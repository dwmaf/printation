<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Outlet extends Model
{
    protected $guarded = [];

    public function users()
    {
        return $this->hasMany(\App\Models\User::class);
    }

    public function owner()
    {
        return $this->hasOne(\App\Models\User::class)->whereHas('roles', function ($q) {
            $q->where('name', 'outlet-owner');
        });
    }

    public function stations()
    {
        return $this->hasMany(\App\Models\User::class)->role('station');
    }
}
