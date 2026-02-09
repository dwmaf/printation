<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Printfile extends Model
{
    public function transactions()
    {
        return $this->hasMany(\App\Models\Transaction::class, 'printfile_id')->latest();
    }


    protected $guarded = [];
    // atribut virtual, artinya ada kolom type untuk tabel ini, tapi nda di database
    protected $appends = ['type'];

    // Accessor untuk mendapatkan tipe file (PDF, DOCX, dll)
    public function getTypeAttribute()
    {
        $extension = pathinfo($this->original_name, PATHINFO_EXTENSION);
        return strtoupper($extension);
    }
    public function station()
    {
        return $this->belongsTo(User::class, 'station_id');
    }
}
