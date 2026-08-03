<?php

namespace App\Models\Profiles;

use App\Models\Auth\User;
use App\Models\Profiles\CustomerAddress;

use Illuminate\Database\Eloquent\Model;

class CustomerProfile extends Model
{
    protected $table = 'customer_profiles';


    protected $fillable = array (
  0 => 'user_id',
  1 => 'loyalty_points',
  2 => 'membership_level',
  3 => 'total_orders',
  4 => 'total_spent',
  5 => 'favorite_category',
  6 => 'last_order_at',
  7 => 'status',
);

    public function user() {
        return $this->belongsTo(User::class);
    }

    public function addresses() {
        return $this->hasMany(CustomerAddress::class, 'customer_id');
    }
}
