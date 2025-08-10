<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class LaporanKeuangan extends Model
{
    use SoftDeletes;

    protected $table = 'laporan_keuangan';
    protected $primaryKey = 'idLaporan';

    protected $fillable = [
        'periode',
        'totalPemasukan',
        'totalPengeluaran',
        'created_by',
        'updated_by',
        'deleted_by'
    ];

    public function exportLaporan()
    {
        return $this->hasMany(ExportLaporan::class, 'idLaporan', 'idLaporan');
    }
}
