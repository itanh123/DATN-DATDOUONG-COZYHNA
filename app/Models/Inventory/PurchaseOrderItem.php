<?php

namespace App\Models\Inventory;

use App\Models\Products\Ingredient;

use Illuminate\Database\Eloquent\Model;

class PurchaseOrderItem extends Model
{
    protected $table = 'purchase_order_items';


    protected $fillable = array (
  0 => 'purchase_order_id',
  1 => 'ingredient_id',
  2 => 'quantity',
  3 => 'unit_price',
  4 => 'total_price',
  5 => 'expiry_date',
  6 => 'batch_number',
);

    public function purchaseOrder() {
        return $this->belongsTo(PurchaseOrder::class);
    }

    public function ingredient() {
        return $this->belongsTo(Ingredient::class);
    }
}

