<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class OrderStatusHistory extends Model
{
    protected $table = 'order_status_histories';


    protected $fillable = array (
  0 => 'order_id',
  1 => 'old_status',
  2 => 'new_status',
  3 => 'changed_by',
  4 => 'note',
);

    public function order() {
        return $this->belongsTo(Order::class);
    }

    public function getStatusLabelAttribute()
    {
        return match ($this->new_status) {
            'PENDING'    => 'Chờ xác nhận',
            'PREPARING'  => 'Đang chuẩn bị',
            'DELIVERING' => 'Đang giao hàng',
            'COMPLETED'  => 'Hoàn thành',
            'CANCELLED'  => 'Đã hủy',
            default      => 'Không rõ',
        };
    }

    public function getStatusColorAttribute()
    {
        return match ($this->new_status) {
            'PENDING'    => 'bg-yellow-100 text-yellow-700',
            'PREPARING'  => 'bg-blue-100 text-blue-700',
            'DELIVERING' => 'bg-indigo-100 text-indigo-700',
            'COMPLETED'  => 'bg-green-100 text-green-700',
            'CANCELLED'  => 'bg-red-100 text-red-700',
            default      => 'bg-gray-100 text-gray-700',
        };
    }

    public function changedBy()
    {
        return $this->belongsTo(User::class, 'changed_by');
    }
}