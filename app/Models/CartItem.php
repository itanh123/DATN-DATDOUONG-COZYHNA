<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CartItem extends Model
{
    use HasFactory;

    protected $fillable = [
        'cart_id',
        'product_id',
        'product_size_id',
        'quantity',
        'unit_price',
    ];

    public function cart()
    {
        return $this->belongsTo(Cart::class, 'cart_id');
    }

    public function product()
    {
        return $this->belongsTo(Product::class, 'product_id');
    }

    public function productSize()
    {
        return $this->belongsTo(ProductSize::class, 'product_size_id');
    }

    public function toppings()
    {
        return $this->hasMany(CartItemTopping::class, 'cart_item_id');
    }
}
