<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class RestaurantTable extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'area_id', 'code', 'table_name', 'qr_token',
        'capacity', 'minimum_capacity', 'shape',
        'status', 'location_x', 'location_y', 'note',
    ];

    public function area()
    {
        return $this->belongsTo(TableArea::class, 'area_id');
    }

    public function floor()
    {
        return $this->hasOneThrough(Floor::class, TableArea::class, 'id', 'id', 'area_id', 'floor_id');
    }

    public function mergedTableItems()
    {
        return $this->hasMany(MergedTableItem::class, 'table_id');
    }

    public function currentMerge()
    {
        return $this->hasOne(MergedTableItem::class, 'table_id');
    }

    public function sessions()
    {
        return $this->hasMany(TableSession::class, 'table_id');
    }

    public function activeSession()
    {
        return $this->hasOne(TableSession::class, 'table_id')->where('session_status', 'open');
    }

    public function getStatusLabelAttribute(): string
    {
        return match($this->status) {
            'available' => 'Trống',
            'occupied'  => 'Có khách',
            'reserved'  => 'Đặt trước',
            'disabled'  => 'Không dùng',
            'merged'    => 'Đã ghép',
            default     => ucfirst($this->status),
        };
    }

    public function getStatusColorAttribute(): string
    {
        return match($this->status) {
            'available' => 'green',
            'occupied'  => 'red',
            'reserved'  => 'amber',
            'disabled'  => 'gray',
            'merged'    => 'slate',
            default     => 'gray',
        };
    }
}
