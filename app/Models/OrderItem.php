<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class OrderItem extends Model
{
    protected $table = 'order_items';

    protected $fillable = [
        'order_id',
        'product_size_id',
        'product_name',
        'size_name',
        'quantity',
        'unit_price',
        'discount',
        'final_price',
        'note',
    ];

    public function order() {
        return $this->belongsTo(Order::class);
    }

    public function productSize() {
        return $this->belongsTo(ProductSize::class, 'product_size_id');
    }

    public function toppings() {
        return $this->hasMany(OrderItemTopping::class, 'order_item_id');
    }

    public function getTotalPriceAttribute($value) {
        if ($value && $value > 0) {
            return $value;
        }
        
        $basePrice = $this->final_price * $this->quantity;
        $toppingsPrice = $this->toppings->sum('total_price');
        
        return $basePrice + $toppingsPrice;
    }
}
