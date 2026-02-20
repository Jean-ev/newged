<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Storage;

class Document extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'name', 'original_name', 'path', 'mime_type',
        'size', 'type', 'status', 'user_id', 'folder_id', 'description', 'is_favorite',
    ];

    // Relations
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function folder()
    {
        return $this->belongsTo(Folder::class);
    }

    public function sharedWith()
    {
        return $this->belongsToMany(User::class, 'document_shares', 'document_id', 'shared_with_user_id')
                    ->withPivot('permission', 'shared_by_user_id')
                    ->withTimestamps();
    }

    // Accessors
    public function getFormattedSizeAttribute(): string
    {
        $bytes = $this->size;
        if ($bytes >= 1073741824) return number_format($bytes / 1073741824, 2) . ' GB';
        if ($bytes >= 1048576)    return number_format($bytes / 1048576, 2) . ' MB';
        if ($bytes >= 1024)       return number_format($bytes / 1024, 2) . ' KB';
        return $bytes . ' B';
    }

    public function getIconAttribute(): string
    {
        return match($this->type) {
            'pdf'   => 'pdf',
            'image' => 'image',
            'excel' => 'excel',
            'word'  => 'word',
            'video' => 'video',
            default => 'autre',
        };
    }

    // Scopes
    public function scopeShared($query)
    {
        return $query->where('status', 'partage');
    }

    public function scopePrivate($query)
    {
        return $query->where('status', 'prive');
    }

    // Helper
    public static function detectType(string $mimeType): string
    {
        if (str_contains($mimeType, 'pdf'))                                          return 'pdf';
        if (str_contains($mimeType, 'image'))                                        return 'image';
        if (str_contains($mimeType, 'spreadsheet') || str_contains($mimeType, 'excel')) return 'excel';
        if (str_contains($mimeType, 'word') || str_contains($mimeType, 'document'))  return 'word';
        if (str_contains($mimeType, 'video'))                                        return 'video';
        return 'autre';
    }
}