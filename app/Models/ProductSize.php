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

    protected $appends = ['stock'];

    public function getStockAttribute()
    {
        $product = $this->product;
        if ($product && !$product->is_auto_stock) {
            return $product->stock;
        }

        $recipe = $this->recipes()->first();
        if (!$recipe || $recipe->ingredients->isEmpty()) {
            return 0;
        }

        $maxProducts = -1;

        foreach ($recipe->ingredients as $recipeIngredient) {
            $ingredient = $recipeIngredient->ingredient;
            if (!$ingredient || $recipeIngredient->quantity <= 0) {
                continue;
            }

            $possible = floor($ingredient->current_stock / $recipeIngredient->quantity);
            if ($maxProducts === -1 || $possible < $maxProducts) {
                $maxProducts = $possible;
            }
        }

        return $maxProducts === -1 ? 0 : $maxProducts;
    }

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
