<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Portfolio extends Model
{
    use HasUuids;

    protected $fillable = [
        'user_id',
        'title',
        'description',
        'images',
        'client',
        'project_url',
        'project_date',
        'technologies',
        'status',
        'sort_order',
    ];

    protected $casts = [
        'images' => 'array',
        'technologies' => 'array',
        'project_date' => 'date',
        'sort_order' => 'integer',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function getImageUrlAttribute()
    {
        if (!$this->images || empty($this->images)) {
            return null;
        }

        // Return the first image as the main image
        $firstImage = $this->images[0];
        
        if (filter_var($firstImage, FILTER_VALIDATE_URL)) {
            return $firstImage;
        }

        return asset('storage/' . $firstImage);
    }

    public function getImageUrlsAttribute()
    {
        if (!$this->images || empty($this->images)) {
            return [];
        }

        return array_map(function($image) {
            if (filter_var($image, FILTER_VALIDATE_URL)) {
                return $image;
            }
            return asset('storage/' . $image);
        }, $this->images);
    }

    public function getStatusLabelAttribute()
    {
        return match($this->status) {
            'draft' => 'Draft',
            'published' => 'Published',
            default => 'Unknown'
        };
    }

    public function getTechnologiesListAttribute()
    {
        return $this->technologies ?? [];
    }

    public function scopePublished($query)
    {
        return $query->where('status', 'published');
    }

    public function scopeOrdered($query)
    {
        return $query->orderBy('sort_order', 'asc')->orderBy('created_at', 'desc');
    }

    public function getImagesAttribute($value)
    {
        return json_decode($value, true) ?: [];
    }

    public function getTechnologiesAttribute($value)
    {
        return json_decode($value, true) ?: [];
    }
}
