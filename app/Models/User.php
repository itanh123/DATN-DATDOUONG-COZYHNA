<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory;

    protected $table = 'users';

    protected $fillable = [
        'name',
        'username',
        'full_name',
        'email',
        'phone',
        'avatar',
        'password',
        'role_id',
        'status',
        'google_id',
        'is_restricted',
        'gender',
        'birthday',
        'remember_token',
        'login_provider',
        'last_login_at',
        'last_login_ip',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    public function reviews()
    {
        return $this->hasMany(ProductReview::class);
    }

    public function favoriteProducts()
    {
        return $this->belongsToMany(Product::class, 'favorite_products')->withTimestamps();
    }

    public function role()
    {
        return $this->belongsTo(Role::class, 'role_id');
    }

    public function customerProfile()
    {
        return $this->hasOne(CustomerProfile::class, 'user_id');
    }

    public function employeeProfile()
    {
        return $this->hasOne(EmployeeProfile::class, 'user_id');
    }

    public function shipperProfile()
    {
        return $this->hasOne(ShipperProfile::class, 'user_id');
    }
}