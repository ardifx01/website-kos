<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class ExportLaporan extends Model
{
    use SoftDeletes;

    protected $table = 'export_laporan';
    protected $primaryKey = 'id';

    protected $fillable = [
        'idLaporan',
        'format',
        'lokasiFile',
        'created_by',
        'updated_by',
        'deleted_by'
    ];

    public function laporanKeuangan()
    {
        return $this->belongsTo(LaporanKeuangan::class, 'idLaporan', 'idLaporan');
    }
}
