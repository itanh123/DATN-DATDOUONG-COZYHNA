<?php

namespace App\Models\Orders;

use App\Models\Profiles\CustomerProfile;
use App\Models\Profiles\ShipperProfile;
use App\Models\Tables\TableSession;
use App\Models\System\Voucher;

use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    protected $table = 'orders';


    protected $fillable = array (
  0 => 'customer_id',
  1 => 'table_session_id',
  2 => 'reservation_id',
  3 => 'shipper_id',
  4 => 'voucher_id',
  5 => 'code',
  6 => 'order_source',
  7 => 'order_type',
  8 => 'order_status',
  9 => 'receiver_name',
  10 => 'receiver_phone',
  11 => 'delivery_address',
  12 => 'kitchen_note',
  13 => 'customer_note',
  14 => 'subtotal',
  15 => 'discount_amount',
  16 => 'shipping_fee',
  17 => 'tax_amount',
  18 => 'total_amount',
  19 => 'estimated_completed_at',
  20 => 'completed_at',
  21 => 'cancelled_at',
  22 => 'cancel_reason',
  23 => 'created_by',
);

    public function customer() {
        return $this->belongsTo(CustomerProfile::class, 'customer_id');
    }

    public function tableSession() {
        return $this->belongsTo(TableSession::class);
    }

    public function reservation() {
        return $this->belongsTo(Reservation::class);
    }

    public function shipper() {
        return $this->belongsTo(ShipperProfile::class, 'shipper_id');
    }

    public function voucher() {
        return $this->belongsTo(Voucher::class);
    }

    public function items() {
        return $this->hasMany(OrderItem::class, 'order_id');
    }

    public function getStatusLabelAttribute()
    {
        return match ($this->order_status) {
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
        return match ($this->order_status) {
            'PENDING'    => 'bg-yellow-100 text-yellow-700',
            'PREPARING'  => 'bg-blue-100 text-blue-700',
            'DELIVERING' => 'bg-indigo-100 text-indigo-700',
            'COMPLETED'  => 'bg-green-100 text-green-700',
            'CANCELLED'  => 'bg-red-100 text-red-700',
            default      => 'bg-gray-100 text-gray-700',
        };
    }
}
