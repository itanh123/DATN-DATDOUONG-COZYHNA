<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class MergedTableItem extends Model
{
    protected $table = 'merged_table_items';

    public $timestamps = false;

    protected $fillable = array (
  0 => 'merged_table_id',
  1 => 'table_id',
);

    public function mergedTable() {
        return $this->belongsTo(MergedTable::class);
    }
}