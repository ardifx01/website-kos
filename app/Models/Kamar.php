<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Kamar extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'kamar';
    protected $primaryKey = 'idKamar';
    public $incrementing = true;
    protected $keyType = 'int';

    protected $fillable = [
        'nomorKamar',
        'tipeKamar',
        'hargaSewa',
        'status',
        'created_by',
        'updated_by',
        'deleted_by',
    ];

    // Relasi ke fasilitas kamar
    public function fasilitas()
    {
        return $this->hasMany(FasilitasKamar::class, 'idKamar', 'idKamar');
    }

    // Relasi ke histori harga renovasi
    public function historiHarga()
    {
        return $this->hasMany(HistoriHargaRenovasi::class, 'idKamar', 'idKamar');
    }

    // Audit trail
    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function updater()
    {
        return $this->belongsTo(User::class, 'updated_by');
    }

    public function deleter()
    {
        return $this->belongsTo(User::class, 'deleted_by');
    }
}
