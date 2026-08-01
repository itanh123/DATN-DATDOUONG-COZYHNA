<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class OrderItemTopping extends Model
{
    protected $table = 'order_item_toppings';

    public $timestamps = false;

    protected $fillable = array (
  0 => 'order_item_id',
  1 => 'topping_id',
  2 => 'quantity',
  3 => 'unit_price',
  4 => 'total_price',
);

    public function orderItem() {
        return $this->belongsTo(OrderItem::class);
    }

    public function topping() {
        return $this->belongsTo(Topping::class);
    }
}