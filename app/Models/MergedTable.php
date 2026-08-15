<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class MergedTable extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'merged_tables';

    protected $fillable = ['code', 'name', 'capacity', 'status'];

    public function items()
    {
        return $this->hasMany(MergedTableItem::class, 'merged_table_id');
    }

    public function tables()
    {
        return $this->belongsToMany(RestaurantTable::class, 'merged_table_items', 'merged_table_id', 'table_id')
                    ->withPivot('is_primary')
                    ->withTimestamps();
    }

    public function primaryTable()
    {
        return $this->tables()->wherePivot('is_primary', true)->first();
    }
}
