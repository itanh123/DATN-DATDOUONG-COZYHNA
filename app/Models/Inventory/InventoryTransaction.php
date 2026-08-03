<?php

namespace App\Models\Inventory;

use App\Models\Products\Ingredient;

use Illuminate\Database\Eloquent\Model;

class InventoryTransaction extends Model
{
    protected $table = 'inventory_transactions';


    protected $fillable = array (
  0 => 'ingredient_id',
  1 => 'transaction_type',
  2 => 'quantity',
  3 => 'unit_id',
  4 => 'before_quantity',
  5 => 'after_quantity',
  6 => 'reference_type',
  7 => 'reference_id',
  8 => 'created_by',
  9 => 'note',
);

    public function ingredient() {
        return $this->belongsTo(Ingredient::class);
    }
}

