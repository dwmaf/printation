<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Outlet extends Model
{
    protected $guarded = [];
    public function users()
    {
        // Outlet ini "Punya Banyak" User (Owner + Stations)
        return $this->hasMany(User::class);
    }
    public function owner()
    {
        return $this->hasOne(User::class)->whereHas('roles', function ($query) {
            $query->where('name', 'outlet-owner');
        });
    }

    // Opsional: Helper khusus untuk ambil stations saja
    public function stations()
    {
        return $this->hasMany(User::class)->role('station'); // Pakai scope Spatie
    }
}
