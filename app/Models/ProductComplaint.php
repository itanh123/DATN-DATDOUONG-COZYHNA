<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ProductComplaint extends Model
{
    protected $table = 'product_complaints';


    protected $fillable = array (
  0 => 'order_item_id',
  1 => 'customer_id',
  2 => 'complaint_type',
  3 => 'reason',
  4 => 'description',
  5 => 'image',
  6 => 'status',
  7 => 'resolved_by',
  8 => 'resolved_at',
  9 => 'resolution_note',
);

    public function orderItem() {
        return $this->belongsTo(OrderItem::class);
    }

    public function customer() {
        return $this->belongsTo(CustomerProfile::class, 'customer_id');
    }
}