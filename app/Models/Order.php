<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    protected $table = 'orders';


    protected $fillable = array(
        0 => 'customer_id',
        1 => 'table_session_id',
        2 => 'reservation_id',
        3 => 'shipper_id',
        4 => 'voucher_id',
        5 => 'order_code',
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
        17 => 'distance_km',
        18 => 'tax_amount',
        19 => 'total_amount',
        20 => 'address_id',
        21 => 'estimated_completed_at',
        22 => 'completed_at',
        23 => 'cancelled_at',
        24 => 'cancel_reason',
        25 => 'created_by',
        26 => 'shipper_rating',
    );

    public function customer()
    {
        return $this->belongsTo(CustomerProfile::class, 'customer_id');
    }

    public function address()
    {
        return $this->belongsTo(CustomerAddress::class, 'address_id');
    }

    public function tableSession()
    {
        return $this->belongsTo(TableSession::class);
    }

    public function reservation()
    {
        return $this->belongsTo(Reservation::class);
    }

    public function shipper()
    {
        return $this->belongsTo(ShipperProfile::class, 'shipper_id');
    }

    public function voucher()
    {
        return $this->belongsTo(Voucher::class);
    }

    public function items()
    {
        return $this->hasMany(OrderItem::class, 'order_id');
    }

    public function payment()
    {
        return $this->hasOne(Payment::class, 'order_id');
    }

    public function deductInventory()
    {
        $orderItems = \Illuminate\Support\Facades\DB::table('order_items')->where('order_id', $this->id)->get();
        foreach ($orderItems as $item) {
            if ($item->product_size_id) {
                $recipe = \App\Models\Recipe::where('product_size_id', $item->product_size_id)->first();
                if ($recipe) {
                    $recipeIngredients = \App\Models\RecipeIngredient::where('recipe_id', $recipe->id)->get();
                    foreach ($recipeIngredients as $ri) {
                        \App\Models\Ingredient::where('id', $ri->ingredient_id)
                            ->decrement('current_stock', $ri->quantity * $item->quantity);
                    }
                }
            }
        }
    }

    public function restoreInventory()
    {
        $orderItems = \Illuminate\Support\Facades\DB::table('order_items')->where('order_id', $this->id)->get();
        foreach ($orderItems as $item) {
            if ($item->product_size_id) {
                $recipe = \App\Models\Recipe::where('product_size_id', $item->product_size_id)->first();
                if ($recipe) {
                    $recipeIngredients = \App\Models\RecipeIngredient::where('recipe_id', $recipe->id)->get();
                    foreach ($recipeIngredients as $ri) {
                        \App\Models\Ingredient::where('id', $ri->ingredient_id)
                            ->increment('current_stock', $ri->quantity * $item->quantity);
                    }
                }
            }
        }
    }

    public function getStatusLabelAttribute()
    {
        return match ($this->order_status) {
            'PENDING'            => 'Chờ xác nhận',
            'CONFIRMED'          => 'Đã thanh toán',
            'PREPARING'          => 'Đang chuẩn bị',
            'READY_FOR_DELIVERY' => 'Chờ giao hàng',
            'DELIVERING'         => 'Đang giao hàng',
            'COMPLETED'          => 'Hoàn thành',
            'CANCELLED'          => 'Đã hủy',
            default              => $this->order_status ?? 'Không rõ',
        };
    }

    public function getStatusColorAttribute()
    {
        return match ($this->order_status) {
            'PENDING'            => 'bg-yellow-100 text-yellow-700',
            'CONFIRMED'          => 'bg-teal-100 text-teal-700',
            'PREPARING'          => 'bg-blue-100 text-blue-700',
            'READY_FOR_DELIVERY' => 'bg-purple-100 text-purple-700',
            'DELIVERING'         => 'bg-indigo-100 text-indigo-700',
            'COMPLETED'          => 'bg-green-100 text-green-700',
            'CANCELLED'          => 'bg-red-100 text-red-700',
            default              => 'bg-gray-100 text-gray-700',
        };
    }
}
