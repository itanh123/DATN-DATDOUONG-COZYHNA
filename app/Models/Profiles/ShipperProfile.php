<?php

namespace App\Models\Profiles;

use App\Models\Auth\User;

use Illuminate\Database\Eloquent\Model;

class ShipperProfile extends Model
{
    protected $table = 'shipper_profiles';


    protected $fillable = array (
  0 => 'user_id',
  1 => 'vehicle_type',
  2 => 'license_plate',
  3 => 'current_lat',
  4 => 'current_lng',
  5 => 'rating',
  6 => 'total_deliveries',
  7 => 'status',
);

    public function user() {
        return $this->belongsTo(User::class);
    }
}
