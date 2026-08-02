<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Voucher extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'vouchers';

    protected $fillable = [
        'code',
        'name',
        'description',
        'discount_type',
        'discount_target',
        'discount_value',
        'minimum_order',
        'maximum_discount',
        'quantity',
        'used',
        'start_date',
        'end_date',
        'status',
        'is_hot',
    ];

    protected $casts = [
        'start_date' => 'datetime',
        'end_date' => 'datetime',
        'status' => 'boolean',
        'is_hot' => 'boolean',
    ];

    public function savedByUsers()
    {
        return $this->belongsToMany(User::class, 'user_vouchers')
                    ->withPivot('is_used')
                    ->withTimestamps();
    }

    public function products()
    {
        return $this->belongsToMany(Product::class, 'voucher_products')
                    ->withTimestamps();
    }
}
