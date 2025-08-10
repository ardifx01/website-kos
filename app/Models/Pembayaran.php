<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Pembayaran extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'pembayaran';
    protected $primaryKey = 'idPembayaran';
    public $incrementing = true;
    protected $keyType = 'int';

    protected $fillable = [
        'idBooking',
        'jumlah',
        'tanggalBayar',
        'metodePembayaran',
        'status',
        'buktiTransfer',
        'created_by',
        'updated_by',
        'deleted_by',
    ];

    protected $casts = [
        'tanggalBayar' => 'date',
    ];

    // Relasi ke Booking Kamar
    public function booking()
    {
        return $this->belongsTo(BookingKamar::class, 'idBooking', 'idBooking');
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
