<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Kamar extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'kamar';
    protected $primaryKey = 'id';  // Sesuai migrasi: primary key idKamar
    public $incrementing = true;
    protected $keyType = 'int';

    protected $fillable = [
        'nomorKamar',
        'tipe_kamar_id',  // foreign key ke tipe kamar
        'status',
        'created_by',
        'updated_by',
        'deleted_by',
    ];

    // Relasi ke tipe kamar (banyak kamar milik satu tipe kamar)
    public function tipeKamar()
    {
        return $this->belongsTo(TipeKamar::class, 'tipe_kamar_id', 'id');
    }

    // Relasi ke histori harga renovasi (jika masih digunakan)
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
