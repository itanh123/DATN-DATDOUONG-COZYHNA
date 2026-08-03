<?php

namespace App\Models\Orders;

use App\Models\Profiles\CustomerProfile;
use App\Models\Tables\MergedTable;

use Illuminate\Database\Eloquent\Model;

class Reservation extends Model
{
    protected $table = 'reservations';


    protected $fillable = array (
  0 => 'customer_id',
  1 => 'table_id',
  2 => 'merged_table_id',
  3 => 'reservation_code',
  4 => 'guest_name',
  5 => 'guest_phone',
  6 => 'guest_count',
  7 => 'reservation_time',
  8 => 'expected_duration',
  9 => 'status',
  10 => 'special_request',
  11 => 'created_by',
  12 => 'cancelled_by',
  13 => 'cancelled_reason',
);

    public function customer() {
        return $this->belongsTo(CustomerProfile::class, 'customer_id');
    }

    public function mergedTable() {
        return $this->belongsTo(MergedTable::class);
    }
}
