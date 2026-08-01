<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ProductSize extends Model
{
    protected $table = 'product_sizes';


    protected $fillable = array (
  0 => 'product_id',
  1 => 'size_id',
  2 => 'selling_price',
  3 => 'cost_price',
  4 => 'calories',
  5 => 'is_default',
  6 => 'status',
);

    public function product() {
        return $this->belongsTo(Product::class);
    }

    public function size() {
        return $this->belongsTo(Size::class);
    }
}