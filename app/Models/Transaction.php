<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\User;
use App\Models\Outlet;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Transaction extends Model
{
    use HasFactory;

    // biar gampang mass assign (kayak kamu)
    protected $guarded = [];

    // kalau kolom status kamu string biasa, ini aman
    // kalau status kamu enum, juga aman
    protected $casts = [
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
        'paid_at'    => 'datetime',
        'print_config' => 'array', // karena kamu bilang print_config longtext JSON
    ];

    // =========================
    // RELATIONS
    // =========================

    public function printfile()
    {
        return $this->belongsTo(Printfile::class);
    }

    public function file()
    {
        // Mengarahkan ke model Printfile menggunakan kolom 'file_id' yang ada di database
        return $this->belongsTo(Printfile::class, 'file_id');
    }

    /**
     * Station yang bikin transaksi (ngikut dari printfile->station)
     * NOTE: ini butuh relasi station() di Printfile model.
     */
    public function station()
    {
        return $this->belongsTo(User::class, 'station_id');
    }

    public function outlet()
    {
        return $this->belongsTo(Outlet::class);
    }

    // =========================
    // SCOPES (biar query gampang)
    // =========================

    public function scopePending($q)
    {
        return $q->where('status', 'pending');
    }

    public function scopePaid($q)
    {
        return $q->where('status', 'paid');
    }
}
