<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class MergedTableItem extends Model
{
    protected $table = 'merged_table_items';

    protected $fillable = ['merged_table_id', 'table_id', 'is_primary'];

    public function mergedTable()
    {
        return $this->belongsTo(MergedTable::class, 'merged_table_id');
    }

    public function table()
    {
        return $this->belongsTo(RestaurantTable::class, 'table_id');
    }
}
