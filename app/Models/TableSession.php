<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TableSession extends Model
{
    protected $table = 'table_sessions';


    protected $fillable = array (
  0 => 'table_id',
  1 => 'merged_table_id',
  2 => 'customer_id',
  3 => 'opened_by',
  4 => 'closed_by',
  5 => 'guest_count',
  6 => 'session_status',
  7 => 'opened_at',
  8 => 'closed_at',
  9 => 'note',
);

    public function mergedTable() {
        return $this->belongsTo(MergedTable::class);
    }

    public function customer() {
        return $this->belongsTo(CustomerProfile::class, 'customer_id');
    }
}