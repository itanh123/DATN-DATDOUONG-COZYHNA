<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class OrderComplaint extends Model
{
    use HasFactory;

    protected $fillable = [
        'order_id',
        'customer_id',
        'incident_time',
        'target_person',
        'description',
        'images',
        'status',
        'is_viewed_by_admin',
        'admin_reply'
    ];

    protected $casts = [
        'images' => 'array',
        'incident_time' => 'datetime',
        'is_viewed_by_admin' => 'boolean',
    ];

    public function order()
    {
        return $this->belongsTo(Order::class);
    }

    public function customer()
    {
        return $this->belongsTo(CustomerProfile::class, 'customer_id');
    }
}
