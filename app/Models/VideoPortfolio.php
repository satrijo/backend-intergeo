<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class VideoPortfolio extends Model
{
    use HasUuids;

    protected $fillable = [
        'user_id',
        'title',
        'description',
        'youtube_url',
        'youtube_id',
        'thumbnail_url',
        'duration',
        'published_at',
        'status',
        'sort_order',
    ];

    protected $hidden = [
        'user_id',
    ];

    protected $appends = [
        'embed_url',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array
     */
    protected $casts = [
        'published_at' => 'date',
        'sort_order' => 'integer',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get the YouTube embed URL
     */
    public function getEmbedUrlAttribute(): string
    {
        return "https://www.youtube.com/embed/{$this->youtube_id}";
    }

    /**
     * Get the YouTube thumbnail URL
     */
    public function getThumbnailUrlAttribute($value): ?string
    {
        // If thumbnail_url is already set in database, return it
        if ($value) {
            return $value;
        }

        // If no thumbnail_url but we have youtube_id, generate it
        $youtubeId = $this->getAttributes()['youtube_id'] ?? null;
        if ($youtubeId) {
            return "https://img.youtube.com/vi/{$youtubeId}/maxresdefault.jpg";
        }

        return null;
    }

    /**
     * Get status label
     */
    public function getStatusLabelAttribute(): string
    {
        return match($this->status) {
            'draft' => 'Draft',
            'published' => 'Published',
            default => 'Unknown'
        };
    }

    /**
     * Scope for published videos
     */
    public function scopePublished($query)
    {
        return $query->where('status', 'published');
    }

    /**
     * Scope for ordered videos
     */
    public function scopeOrdered($query)
    {
        return $query->orderBy('sort_order', 'asc')->orderBy('created_at', 'desc');
    }
}
