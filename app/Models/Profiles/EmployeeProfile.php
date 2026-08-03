<?php

namespace App\Models\Profiles;

use App\Models\Auth\User;

use Illuminate\Database\Eloquent\Model;

class EmployeeProfile extends Model
{
    protected $table = 'employee_profiles';


    protected $fillable = array (
  0 => 'user_id',
  1 => 'employee_code',
  2 => 'address',
  3 => 'citizen_id',
  4 => 'hire_date',
  5 => 'resignation_date',
  6 => 'salary',
  7 => 'status',
  8 => 'emergency_contact',
  9 => 'emergency_phone',
);

    public function user() {
        return $this->belongsTo(User::class);
    }
}
