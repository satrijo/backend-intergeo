<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Support\Facades\Storage;

class MediaFile extends Model
{
    use HasUuids;

    protected $fillable = [
        'user_id',
        'filename',
        'original_name',
        'mime_type',
        'extension',
        'size',
        'path',
        'url',
        'disk',
        'metadata',
    ];

    protected $casts = [
        'metadata' => 'array',
        'size' => 'integer',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function getFullUrlAttribute()
    {
        return Storage::disk($this->disk)->url($this->path);
    }

    public function getHumanSizeAttribute()
    {
        $bytes = $this->size;
        $units = ['B', 'KB', 'MB', 'GB', 'TB'];

        for ($i = 0; $bytes > 1024 && $i < count($units) - 1; $i++) {
            $bytes /= 1024;
        }

        return round($bytes, 2) . ' ' . $units[$i];
    }

    public function isImage()
    {
        return str_starts_with($this->mime_type, 'image/');
    }

    public function isDocument()
    {
        $documentTypes = [
            'application/pdf',
            'application/msword',
            'application/vnd.openxmlformats-officedocument.wordprocessingml.document',
            'application/vnd.ms-excel',
            'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet',
            'text/plain',
            'text/csv',
        ];

        return in_array($this->mime_type, $documentTypes);
    }

    public function delete()
    {
        // Delete the actual file from storage
        if (Storage::disk($this->disk)->exists($this->path)) {
            Storage::disk($this->disk)->delete($this->path);
        }

        return parent::delete();
    }
} 