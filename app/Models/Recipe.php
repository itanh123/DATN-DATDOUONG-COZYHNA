<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Recipe extends Model
{
    protected $table = 'recipes';


    protected $fillable = array (
  0 => 'product_size_id',
  1 => 'code',
  2 => 'name',
  3 => 'preparation_time',
  4 => 'estimated_cost',
  5 => 'instruction',
  6 => 'waste_percentage',
  7 => 'status',
);

    public function productSize() {
        return $this->belongsTo(ProductSize::class);
    }
}