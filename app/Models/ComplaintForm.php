<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Auth;

class ComplaintForm extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'complaint_forms';

    protected $fillable = [
        'nama_lengkap',
        'email',
        'nomor_hp',
        'tipe_kamar',
        'subjek',
        'kategori',
        'deskripsi',
        'status_komplain',
        'token_used',
        'admin_response',
        'responded_at',
        'responded_by',
    ];

    protected $casts = [
        'responded_at' => 'datetime',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
        'deleted_at' => 'datetime',
    ];

    protected $dates = [
        'responded_at',
        'created_at',
        'updated_at',
        'deleted_at',
    ];

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($model) {
            if (Auth::check()) {
                $model->created_by = Auth::id();
            }
        });

        static::updating(function ($model) {
            if (Auth::check()) {
                $model->updated_by = Auth::id();
            }
        });

        static::deleting(function ($model) {
            if (Auth::check()) {
                $model->deleted_by = Auth::id();
                $model->save();
            }
        });
    }

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

    public function respondedBy()
    {
        return $this->belongsTo(\App\Models\User::class, 'responded_by');
    }

    // Scopes
    public function scopeByStatus($query, $status)
    {
        return $query->where('status_komplain', $status);
    }

    public function scopeByKategori($query, $kategori)
    {
        return $query->where('kategori', $kategori);
    }

    public function scopeRecent($query)
    {
        return $query->orderBy('created_at', 'desc');
    }

    // Accessors
    public function getStatusBadgeClassAttribute()
    {
        return match($this->status_komplain) {
            'Open' => 'bg-yellow-100 text-yellow-800 dark:bg-yellow-900 dark:text-yellow-200',
            'In Progress' => 'bg-blue-100 text-blue-800 dark:bg-blue-900 dark:text-blue-200',
            'Resolved' => 'bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-200',
            'Closed' => 'bg-gray-100 text-gray-800 dark:bg-gray-900 dark:text-gray-200',
            default => 'bg-gray-100 text-gray-800 dark:bg-gray-900 dark:text-gray-200',
        };
    }

    public function getFormattedCreatedAtAttribute()
    {
        return $this->created_at->format('d M Y, H:i');
    }

    // Static methods for options
    public static function getStatusOptions()
    {
        return [
            'Open' => 'Open',
            'In Progress' => 'In Progress',
            'Resolved' => 'Resolved',
            'Closed' => 'Closed',
        ];
    }

    public static function getKategoriOptions()
    {
        return [
            'kamar' => 'Masalah Kamar',
            'fasilitas' => 'Fasilitas Hotel',
            'pelayanan' => 'Pelayanan Staff',
            'kebersihan' => 'Kebersihan',
            'makanan' => 'Makanan & Minuman',
            'wifi' => 'Internet/WiFi',
            'ac' => 'AC/Suhu Ruangan',
            'kebisingan' => 'Kebisingan',
            'lainnya' => 'Lainnya',
        ];
    }

    public static function getTipeKamarOptions()
    {
        return [
            'deluxe' => 'Deluxe Room',
            'Premium' => 'Premium Room'
        ];
    }
}
