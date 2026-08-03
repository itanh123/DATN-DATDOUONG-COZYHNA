<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Product extends Model
{
    protected $table = 'products';

    protected $fillable = [
        'category_id',
        'code',
        'name',
        'slug',
        'short_description',
        'description',
        'sold_count',
        'favorite_count',
        'is_featured',
        'status',
    ];

    protected $appends = ['average_rating', 'review_count'];

    public function getAverageRatingAttribute()
    {
        if (!$this->relationLoaded('reviews')) {
            return 0;
        }
        $approvedReviews = $this->reviews->where('status', 'approved');
        return $approvedReviews->avg('rating') ?? 0;
    }

    public function getReviewCountAttribute()
    {
        if (!$this->relationLoaded('reviews')) {
            return 0;
        }
        $approvedReviews = $this->reviews->where('status', 'approved');
        return $approvedReviews->count();
    }

    public function averageRating()
    {
        return $this->getAverageRatingAttribute();
    }

    public function reviewCount()
    {
        return $this->getReviewCountAttribute();
    }

    public function category(): BelongsTo
    {
        return $this->belongsTo(Category::class, 'category_id');
    }

    public function productSizes(): HasMany
    {
        return $this->hasMany(ProductSize::class, 'product_id');
    }

    public function favoritedBy(): BelongsToMany
    {
        return $this->belongsToMany(User::class, 'favorite_products')->withTimestamps();
    }

    public function reviews(): HasMany
    {
        return $this->hasMany(ProductReview::class, 'product_id');
    }
}
