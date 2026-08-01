<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Ingredient extends Model
{
    protected $table = 'ingredients';


    protected $fillable = array (
  0 => 'supplier_id',
  1 => 'unit_id',
  2 => 'code',
  3 => 'name',
  4 => 'category',
  5 => 'image',
  6 => 'current_stock',
  7 => 'minimum_stock',
  8 => 'maximum_stock',
  9 => 'reorder_level',
  10 => 'expiry_warning_days',
  11 => 'cost_price',
  12 => 'description',
  13 => 'status',
);

    public function supplier() {
        return $this->belongsTo(Supplier::class);
    }
}