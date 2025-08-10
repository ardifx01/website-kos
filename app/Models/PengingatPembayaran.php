<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class PengingatPembayaran extends Model
{
    use SoftDeletes;

    protected $table = 'pengingat_pembayaran';
    protected $primaryKey = 'idPengingat';

    protected $fillable = [
        'tanggalJatuhTempo',
        'statusPengingat',
        'created_by',
        'updated_by',
        'deleted_by'
    ];
}
