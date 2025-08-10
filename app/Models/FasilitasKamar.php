<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class FasilitasKamar extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'fasilitas_kamar';
    protected $primaryKey = 'idFasilitas';
    public $incrementing = true;
    protected $keyType = 'int';

    protected $fillable = [
        'idKamar',
        'namaFasilitas',
        'kondisi',
        'created_by',
        'updated_by',
        'deleted_by',
    ];

    // Relasi ke Kamar
    public function kamar()
    {
        return $this->belongsTo(Kamar::class, 'idKamar', 'idKamar');
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
