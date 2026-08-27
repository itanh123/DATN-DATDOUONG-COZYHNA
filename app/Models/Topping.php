<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Topping extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'toppings';

    protected $fillable = ['name', 'price', 'status', 'ingredient_id', 'ingredient_quantity'];

    public function ingredient()
    {
        return $this->belongsTo(Ingredient::class, 'ingredient_id');
    }

    public function products()
    {
        return $this->belongsToMany(Product::class, 'product_toppings');
    }
}
