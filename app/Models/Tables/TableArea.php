<?php

namespace App\Models\Tables;

use Illuminate\Database\Eloquent\Model;

class TableArea extends Model
{
    protected $table = 'table_areas';


    protected $fillable = array (
  0 => 'floor_id',
  1 => 'code',
  2 => 'name',
  3 => 'description',
  4 => 'display_order',
  5 => 'status',
);

    public function floor() {
        return $this->belongsTo(Floor::class);
    }
}
