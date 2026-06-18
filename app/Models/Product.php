<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Product extends Model
{
    use HasUuids;

    protected $fillable = [
        'user_id', 'product_category_id', 'name', 'slug', 'summary', 'description',
        'images', 'price', 'specifications', 'status', 'featured', 'sort_order', 'published_at',
    ];

    protected $hidden = ['user_id'];

    protected $casts = [
        'images' => 'array',
        'specifications' => 'array',
        'price' => 'decimal:2',
        'featured' => 'boolean',
        'sort_order' => 'integer',
        'published_at' => 'datetime',
    ];

    protected $appends = ['image_url', 'image_urls', 'price_label'];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function category(): BelongsTo
    {
        return $this->belongsTo(ProductCategory::class, 'product_category_id');
    }

    public function getImageUrlAttribute(): ?string
    {
        return $this->image_urls[0] ?? null;
    }

    public function getImageUrlsAttribute(): array
    {
        return collect($this->images ?? [])->filter()->map(function ($image) {
            if (filter_var($image, FILTER_VALIDATE_URL)) {
                return $image;
            }

            return asset('storage/' . $image);
        })->values()->all();
    }

    public function getPriceLabelAttribute(): ?string
    {
        if ($this->price === null) {
            return null;
        }

        return 'Rp ' . number_format((float) $this->price, 0, ',', '.');
    }

    public function scopePublished($query)
    {
        return $query->where('status', 'published');
    }

    public function scopeFeatured($query)
    {
        return $query->where('featured', true);
    }

    public function scopeOrdered($query)
    {
        return $query->orderBy('sort_order')->orderByDesc('created_at');
    }
}
