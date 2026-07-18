<?php

namespace App\Models;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use App\Models\Category;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;

class Product extends Model
{


    use HasFactory, SoftDeletes;

    protected $table = 'products';

    protected $fillable = [
        'category_id',
        'code',
        'name',
        'description',
        'image',
        'status',
    ];

    protected $appends = ['average_rating', 'review_count'];

    public function getAverageRatingAttribute()
    {
        $approvedReviews = $this->reviews->where('status', 'approved');
        return $approvedReviews->avg('rating') ?? 0;
    }

    public function getReviewCountAttribute()
    {
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

    public function productSizes()
    {
        return $this->hasMany(ProductSize::class);
    }

    public function favoritedBy()
    {
        return $this->belongsToMany(User::class, 'favorite_products')->withTimestamps();
    }

    public function reviews()
    {
        return $this->hasMany(ProductReview::class);
    }

}
