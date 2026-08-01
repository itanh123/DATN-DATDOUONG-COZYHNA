<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class OrderItemTopping extends Model
{
    use HasFactory;

    public $timestamps = false; // Based on migration, there are no timestamps

    protected $fillable = [
        'order_item_id',
        'topping_id',
        'quantity',
        'unit_price',
        'total_price',
    ];

    protected $casts = [
        'quantity' => 'integer',
        'unit_price' => 'decimal:2',
        'total_price' => 'decimal:2',
    ];

    public function orderItem()
    {
        return $this->belongsTo(OrderItem::class);
    }

    public function topping()
    {
        return $this->belongsTo(Topping::class);
    }
}
