<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Penyewa extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'penyewas';

    protected $fillable = [
        'nama_lengkap',
        'email',
        'nomor_hp',
        'pekerjaan',
        'alamat_ktp',
        'alamat_domisili',
        'tipe_kamar',
        'tanggal_masuk',
        'status_sewa',
        'catatan',
        'booking_form_id',
        'created_by',
        'updated_by',
        'deleted_by',
    ];

    // Casting kolom tanggal ke Carbon
    protected $casts = [
        'tanggal_masuk' => 'date',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
        'deleted_at' => 'datetime',
    ];

    /**
     * Relasi ke booking form (jika berasal dari booking).
     */
    public function bookingForm()
    {
        return $this->belongsTo(BookingForm::class, 'booking_form_id');
    }

    /**
     * User yang membuat data ini.
     */
    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    /**
     * User yang terakhir mengubah data ini.
     */
    public function updater()
    {
        return $this->belongsTo(User::class, 'updated_by');
    }

    /**
     * User yang menghapus data ini.
     */
    public function deleter()
    {
        return $this->belongsTo(User::class, 'deleted_by');
    }
}
