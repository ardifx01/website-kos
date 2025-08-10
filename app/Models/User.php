<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Notifications\Notifiable;
use App\Traits\HasActivityLog;

class User extends Authenticatable
{
    use HasFactory, Notifiable, SoftDeletes, HasActivityLog;

    protected $fillable = [
        'role_id',
        'name',
        'email',
        'phone',
        'address',
        'photo',
        'password',
        'last_login_at',
        'last_login_ip',
        'created_by',
        'updated_by',
        'deleted_by',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected $casts = [
        'email_verified_at' => 'datetime',
        'last_login_at'     => 'datetime',
    ];

    /**
     * Relasi ke tabel roles
     */
    public function role()
    {
        return $this->belongsTo(Role::class, 'role_id');
    }

    public function hasRole($roleName)
    {
        return $this->roles?->pluck('name')->contains($roleName) ?? false;
    }

    /**
     * Relasi ke activity logs
     */
    public function activityLogs()
    {
        return $this->hasMany(ActivityLog::class, 'user_id');
    }

    /**
     * Audit trail relationships
     */
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

    public function initials(): string
    {
        if (!$this->name) {
            return '';
        }

        return collect(explode(' ', $this->name))
            ->map(fn ($word) => strtoupper(substr($word, 0, 1)))
            ->implode('');
    }

}
