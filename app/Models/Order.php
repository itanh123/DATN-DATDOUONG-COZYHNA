<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Order extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'order_code',
        'customer_id',
        'address_id',
        'shipper_id',
        'voucher_id',
        'subtotal',
        'discount_amount',
        'shipping_fee',
        'total_amount',
        'payment_method',
        'status',
        'note',
        'ordered_at',
    ];

    public function customer()
    {
        return $this->belongsTo(CustomerProfile::class, 'customer_id');
    }

    public function address()
    {
        return $this->belongsTo(CustomerAddress::class, 'address_id');
    }

    public function items()
    {
        return $this->hasMany(OrderItem::class, 'order_id');
    }

    public function getStatusLabelAttribute(): string
    {
        return match($this->status) {
            'pending'    => 'Chờ xác nhận',
            'confirmed'  => 'Đã xác nhận',
            'preparing'  => 'Đang pha chế',
            'shipping'   => 'Đang giao',
            'completed'  => 'Hoàn thành',
            'cancelled'  => 'Đã hủy',
            default      => ucfirst($this->status),
        };
    }

    public function getStatusColorAttribute(): string
    {
        return match($this->status) {
            'pending'    => 'text-yellow-600 bg-yellow-100',
            'confirmed'  => 'text-blue-600 bg-blue-100',
            'preparing'  => 'text-orange-600 bg-orange-100',
            'shipping'   => 'text-purple-600 bg-purple-100',
            'completed'  => 'text-green-600 bg-green-100',
            'cancelled'  => 'text-red-600 bg-red-100',
            default      => 'text-gray-600 bg-gray-100',
        };
    }
}
