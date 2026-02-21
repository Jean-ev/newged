<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use HasFactory, Notifiable;

    protected $fillable = [
        'name', 'email', 'password', 'role', 'status', 'avatar', 'storage_quota', 'preferences',
    ];

    protected $hidden = [
        'password', 'remember_token',
    ];

    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
            'preferences' => 'array',
        ];
    }

    // Relations
    public function documents()
    {
        return $this->hasMany(Document::class);
    }

    public function folders()
    {
        return $this->hasMany(Folder::class);
    }

    public function sharedDocuments()
    {
        return $this->belongsToMany(Document::class, 'document_shares', 'shared_with_user_id', 'document_id')
                    ->withPivot('permission', 'shared_by_user_id')
                    ->withTimestamps();
    }

    public function activityLogs()
    {
        return $this->hasMany(ActivityLog::class);
    }

    // Helpers rôles
    public function isAdmin(): bool
    {
        return $this->role === 'admin';
    }

    public function isManager(): bool
    {
        return in_array($this->role, ['admin', 'manager']);
    }

    public function isActive(): bool
    {
        return $this->status === 'actif';
    }

    public function isBlocked(): bool
    {
        return $this->status === 'bloque';
    }

    public function getStorageUsedAttribute(): int
{
    return $this->documents()->sum('size') ?? 0;
}

public function getStoragePercentAttribute(): float
{
    if ($this->storage_quota <= 0) return 0;
    return min(round(($this->storage_used / $this->storage_quota) * 100, 1), 100);
}

public function getStorageQuotaFormattedAttribute(): string
{
    $bytes = $this->storage_quota;
    if ($bytes >= 1073741824) return number_format($bytes / 1073741824, 1) . ' GB';
    if ($bytes >= 1048576)    return number_format($bytes / 1048576, 1) . ' MB';
    return number_format($bytes / 1024, 1) . ' KB';
}

public function getStorageUsedFormattedAttribute(): string
{
    $bytes = $this->storage_used;
    if ($bytes >= 1073741824) return number_format($bytes / 1073741824, 2) . ' GB';
    if ($bytes >= 1048576)    return number_format($bytes / 1048576, 2) . ' MB';
    if ($bytes >= 1024)       return number_format($bytes / 1024, 2) . ' KB';
    return $bytes . ' B';
}

public function hasStorageSpace(int $fileSize): bool
{
    return ($this->storage_used + $fileSize) <= $this->storage_quota;
}
}