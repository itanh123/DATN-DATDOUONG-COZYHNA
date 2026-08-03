<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ProductTopping extends Model
{
    protected $table = 'product_toppings';


    protected $fillable = array (
  0 => 'product_id',
  1 => 'topping_id',
  2 => 'extra_price',
  3 => 'is_default',
);

    public function product() {
        return $this->belongsTo(Product::class);
    }

    public function topping() {
        return $this->belongsTo(Topping::class);
    }
}