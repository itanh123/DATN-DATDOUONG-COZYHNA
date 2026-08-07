<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CustomerAddress extends Model
{
    protected $table = 'customer_addresses';


    protected $fillable = array (
  0 => 'customer_id',
  1 => 'province',
  2 => 'district',
  3 => 'ward',
  4 => 'address',
  5 => 'latitude',
  6 => 'longitude',
  7 => 'is_default',
  8 => 'note',
  9 => 'receiver_name',
  10 => 'receiver_phone',
  11 => 'province_code',
  12 => 'district_code',
  13 => 'ward_code',
  14 => 'is_saved',
);

    public function customer() {
        return $this->belongsTo(CustomerProfile::class, 'customer_id');
    }
}