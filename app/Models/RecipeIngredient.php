<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class RecipeIngredient extends Model
{
    protected $table = 'recipe_ingredients';


    protected $fillable = array (
  0 => 'recipe_id',
  1 => 'ingredient_id',
  2 => 'unit_id',
  3 => 'quantity',
  4 => 'step_order',
  5 => 'is_optional',
  6 => 'note',
);

    public function recipe() {
        return $this->belongsTo(Recipe::class);
    }

    public function ingredient() {
        return $this->belongsTo(Ingredient::class);
    }
}