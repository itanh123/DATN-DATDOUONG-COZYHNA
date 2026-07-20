<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CartItem extends Model
{
    use HasFactory;

    protected $fillable = [
        'cart_id',
        'product_id',       // always filled (even for sized products)
        'product_size_id',  // nullable – null when product has no sizes
        'quantity',
        'unit_price',       // price snapshot at time of adding
    ];

    public function cart()
    {
        return $this->belongsTo(Cart::class, 'cart_id');
    }

    /** The size variant (null for no-size products) */
    public function productSize()
    {
        return $this->belongsTo(ProductSize::class, 'product_size_id');
    }

    /** Always present */
    public function product()
    {
        return $this->belongsTo(Product::class, 'product_id');
    }

    /** Human-readable size label */
    public function getSizeLabelAttribute(): string
    {
        return $this->productSize?->size?->name ?? 'Mặc định';
    }

    /** Line total */
    public function getLineTotalAttribute(): float
    {
        return $this->unit_price * $this->quantity;
    }
}
