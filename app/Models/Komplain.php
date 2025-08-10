<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Komplain extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'komplain';
    protected $primaryKey = 'idKomplain';
    public $incrementing = true;
    protected $keyType = 'int';

    protected $fillable = [
        'tanggalKomplain',
        'deskripsi',
        'status',
        'created_by',
        'updated_by',
        'deleted_by',
    ];

    protected $casts = [
        'tanggalKomplain' => 'date',
    ];

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
