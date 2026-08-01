<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    protected $table = 'products';


    protected $fillable = array (
  0 => 'category_id',
  1 => 'code',
  2 => 'name',
  3 => 'slug',
  4 => 'short_description',
  5 => 'description',
  6 => 'sold_count',
  7 => 'favorite_count',
  8 => 'is_featured',
  9 => 'status',
);

    public function category() {
        return $this->belongsTo(Category::class);
    }

    public function productSizes() {
        return $this->hasMany(ProductSize::class);
    }
}