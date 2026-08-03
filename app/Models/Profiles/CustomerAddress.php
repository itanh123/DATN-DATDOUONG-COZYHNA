<?php

namespace App\Models\Profiles;

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
);

    public function customer() {
        return $this->belongsTo(CustomerProfile::class, 'customer_id');
    }
}
