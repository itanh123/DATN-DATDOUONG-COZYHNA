<?php

namespace App\Models\System;

use App\Models\Profiles\CustomerProfile;
use App\Models\Products\Product;

use Illuminate\Database\Eloquent\Model;

class ProductReview extends Model
{
    protected $table = 'product_reviews';


    protected $fillable = array (
  0 => 'product_id',
  1 => 'customer_id',
  2 => 'rating',
  3 => 'comment',
  4 => 'image',
  5 => 'reply',
  6 => 'reply_by',
  7 => 'reply_at',
);

    public function product() {
        return $this->belongsTo(Product::class);
    }

    public function customer() {
        return $this->belongsTo(CustomerProfile::class, 'customer_id');
    }
}

