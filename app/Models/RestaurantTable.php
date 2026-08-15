<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class RestaurantTable extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'restaurant_tables';

    protected $fillable = [
        'area_id',
        'code',
        'name',
        'capacity',
        'pos_x',
        'pos_y',
        'width',
        'height',
        'shape',
        'qr_code',
        'qr_token',
        'status',
    ];

    public function area()
    {
        return $this->belongsTo(TableArea::class, 'area_id');
    }

    public function sessions()
    {
        return $this->hasMany(TableSession::class, 'table_id');
    }

    public function activeSession()
    {
        return $this->hasOne(TableSession::class, 'table_id')->where('status', 'ACTIVE');
    }

    public function mergedTables()
    {
        return $this->belongsToMany(MergedTable::class, 'merged_table_items', 'table_id', 'merged_table_id')
                    ->withPivot('is_primary')
                    ->withTimestamps();
    }
}
