<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TableSession extends Model
{
    protected $fillable = [
        'table_id', 'merged_table_id', 'customer_id',
        'opened_by', 'closed_by', 'guest_count',
        'session_status', 'opened_at', 'closed_at', 'note',
    ];

    protected $casts = [
        'opened_at' => 'datetime',
        'closed_at' => 'datetime',
    ];

    public function table()
    {
        return $this->belongsTo(RestaurantTable::class, 'table_id');
    }

    public function mergedTable()
    {
        return $this->belongsTo(MergedTable::class, 'merged_table_id');
    }

    public function openedBy()
    {
        return $this->belongsTo(User::class, 'opened_by');
    }

    public function closedBy()
    {
        return $this->belongsTo(User::class, 'closed_by');
    }
}
