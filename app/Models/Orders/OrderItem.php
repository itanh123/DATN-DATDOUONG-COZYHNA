<?php

namespace App\Models\Orders;

use App\Models\Products\ProductSize;

use Illuminate\Database\Eloquent\Model;

class OrderItem extends Model
{
    protected $table = 'order_items';


    protected $fillable = array (
  0 => 'order_id',
  1 => 'product_size_id',
  2 => 'product_name',
  3 => 'size_name',
  4 => 'quantity',
  5 => 'unit_price',
  6 => 'discount',
  7 => 'final_price',
  8 => 'note',
);

    public function order() {
        return $this->belongsTo(Order::class);
    }

    public function productSize() {
        return $this->belongsTo(ProductSize::class);
    }

    public function toppings() {
        return $this->belongsToMany(\App\Models\Products\Topping::class, 'order_item_toppings', 'order_item_id', 'topping_id')
                    ->withPivot('quantity', 'unit_price', 'total_price')
                    ->withTimestamps();
    }
}

