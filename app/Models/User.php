<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;

class User extends Authenticatable
{
    protected $table = 'users';


    protected $fillable = array (
  0 => 'role_id',
  1 => 'username',
  2 => 'full_name',
  3 => 'email',
  4 => 'password',
  5 => 'phone',
  6 => 'avatar',
  7 => 'email_verified_at',
  8 => 'gender',
  9 => 'birthday',
  10 => 'remember_token',
  11 => 'login_provider',
  12 => 'last_login_at',
  13 => 'last_login_ip',
);

    public function role() {
        return $this->belongsTo(Role::class);
    }
}