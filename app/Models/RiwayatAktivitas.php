<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class RiwayatAktivitas extends Model
{
    use SoftDeletes;

    protected $table = 'riwayat_aktivitas';
    protected $primaryKey = 'idAktivitas';

    protected $fillable = [
        'tanggalAktivitas',
        'deskripsi',
        'created_by',
        'updated_by',
        'deleted_by'
    ];
}
