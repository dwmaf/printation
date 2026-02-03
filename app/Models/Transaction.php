<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Transaction extends Model
{
    protected $guarded = [];

    // Casting JSON ke Array otomatis
    protected $casts = [
        'print_config' => 'array',
    ];

    // Relasi ke File (Printfile)
    public function file()
    {
        return $this->belongsTo(Printfile::class, 'file_id');
    }
}