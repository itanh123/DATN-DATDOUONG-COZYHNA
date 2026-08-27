<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ProductSize extends Model
{
    protected $table = 'product_sizes';

    protected $fillable = [
        'product_id',
        'size_id',
        'selling_price',
        'cost_price',
        'calories',
        'is_default',
        'status',
    ];

    public function product() {
        return $this->belongsTo(Product::class, 'product_id');
    }

    public function size() {
        return $this->belongsTo(Size::class, 'size_id');
    }

    public function recipes() {
        return $this->hasMany(Recipe::class, 'product_size_id');
    }
}
