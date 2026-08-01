<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Voucher extends Model
{
    protected $table = 'vouchers';


    protected $fillable = array (
  0 => 'code',
  1 => 'name',
  2 => 'description',
  3 => 'discount_type',
  4 => 'discount_value',
  5 => 'minimum_order',
  6 => 'maximum_discount',
  7 => 'usage_limit',
  8 => 'used_count',
  9 => 'start_date',
  10 => 'end_date',
  11 => 'status',
  12 => 'created_by',
);


}