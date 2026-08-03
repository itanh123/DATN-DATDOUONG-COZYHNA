<?php

namespace App\Models\Inventory;

use Illuminate\Database\Eloquent\Model;

class PurchaseOrder extends Model
{
    protected $table = 'purchase_orders';


    protected $fillable = array (
  0 => 'supplier_id',
  1 => 'code',
  2 => 'total_amount',
  3 => 'order_date',
  4 => 'received_date',
  5 => 'status',
  6 => 'created_by',
  7 => 'note',
);

    public function supplier() {
        return $this->belongsTo(Supplier::class);
    }
}
