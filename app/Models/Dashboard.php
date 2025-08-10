<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Dashboard extends Model
{
    use SoftDeletes;

    protected $table = 'dashboard';
    protected $primaryKey = 'id';

    protected $fillable = [
        'totalPenyewa',
        'totalPendapatanBulanan',
        'totalKamarTersedia',
        'created_by',
        'updated_by',
        'deleted_by'
    ];
}
