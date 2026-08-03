<?php

namespace App\Models\Orders;

use Illuminate\Database\Eloquent\Model;

class Payment extends Model
{
    protected $table = 'payments';


    protected $fillable = array (
  0 => 'order_id',
  1 => 'transaction_code',
  2 => 'gateway',
  3 => 'payment_method',
  4 => 'payment_status',
  5 => 'amount',
  6 => 'gateway_response',
  7 => 'refund_amount',
  8 => 'refunded_at',
  9 => 'paid_at',
  10 => 'note',
);

    public function order() {
        return $this->belongsTo(Order::class);
    }
}
