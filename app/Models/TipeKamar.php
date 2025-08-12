<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class TipeKamar extends Model
{
    use SoftDeletes;

    protected $table = 'tipe_kamar';

    protected $primaryKey = 'id';

    protected $fillable = [
        'nama',        // nama tipe kamar, misal 'Deluxe', 'Premium', dll
        'deskripsi',   // deskripsi fasilitas / info tipe kamar
        'hargaSewa',
        'created_by',
        'updated_by',
        'deleted_by',
    ];

    // Relasi ke kamar: satu tipe kamar punya banyak kamar
    public function kamars()
    {
        return $this->hasMany(Kamar::class, 'tipe_kamar_id', 'id');
    }

    // Relasi ke user pembuat
    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    // Relasi ke user updater
    public function updater()
    {
        return $this->belongsTo(User::class, 'updated_by');
    }

    // Relasi ke user deleter
    public function deleter()
    {
        return $this->belongsTo(User::class, 'deleted_by');
    }
}
