<?php

namespace App\Models;

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

    protected $appends = ['dynamic_rank'];

    public function user() {
        return $this->belongsTo(User::class);
    }

    public function getDynamicRankAttribute()
    {
        $profileId = $this->id;
        
        // 1. Tính tổng tiền các đơn hoàn thành trong tháng hiện tại
        $monthlySpent = \App\Models\Order::where('customer_id', $profileId)
            ->where('status', 'completed')
            ->whereMonth('created_at', now()->month)
            ->whereYear('created_at', now()->year)
            ->sum('total_amount');
            
        // 2. Xác định hạng gốc dựa trên chi tiêu tháng
        $baseRank = 'Member';
        $rankIndex = 0;
        
        if ($monthlySpent >= 10000000) {
            $baseRank = 'Diamond';
            $rankIndex = 3;
        } elseif ($monthlySpent >= 6000000) {
            $baseRank = 'Gold';
            $rankIndex = 2;
        } elseif ($monthlySpent >= 1000000) {
            $baseRank = 'Silver';
            $rankIndex = 1;
        }
        
        // 3. Kiểm tra hạ bậc (đơn hoàn thành gần nhất)
        $lastOrder = \App\Models\Order::where('customer_id', $profileId)
            ->where('status', 'completed')
            ->latest('created_at')
            ->first();
            
        $dropRank = false;
        if (!$lastOrder) {
            // Nếu chưa từng có đơn thì không rớt hạng (vì vốn dĩ là Member)
            if ($rankIndex > 0) $dropRank = true;
        } else {
            $daysSinceLastOrder = now()->diffInDays($lastOrder->created_at);
            if ($daysSinceLastOrder > 15) {
                $dropRank = true;
            }
        }
        
        if ($dropRank && $rankIndex > 0) {
            $rankIndex -= 1;
            $ranks = ['Member', 'Silver', 'Gold', 'Diamond'];
            $baseRank = $ranks[$rankIndex];
        }
        
        return [
            'level' => $baseRank,
            'monthly_spent' => (int) $monthlySpent,
            'days_since_last_order' => $lastOrder ? now()->diffInDays($lastOrder->created_at) : null
        ];
    }

    public function addresses() {
        return $this->hasMany(CustomerAddress::class, 'customer_id');
    }
}