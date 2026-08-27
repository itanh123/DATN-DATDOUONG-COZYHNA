<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TableSession extends Model
{
    use HasFactory;

    protected $table = 'table_sessions';

    protected $fillable = ['table_id', 'opened_at', 'closed_at', 'status', 'guest_count', 'note'];

    public function table()
    {
        return $this->belongsTo(RestaurantTable::class, 'table_id');
    }

    public function orders()
    {
        return $this->hasMany(Order::class, 'session_id');
    }
}
