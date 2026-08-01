<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ProductImage extends Model
{
    protected $table = 'product_images';


    protected $fillable = array (
  0 => 'product_id',
  1 => 'image',
  2 => 'sort_order',
  3 => 'is_primary',
);

    public function product() {
        return $this->belongsTo(Product::class);
    }
}