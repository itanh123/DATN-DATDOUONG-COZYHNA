<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class RestaurantTable extends Model
{
    protected $table = 'restaurant_tables';


    protected $fillable = array (
  0 => 'area_id',
  1 => 'code',
  2 => 'table_name',
  3 => 'qr_code',
  4 => 'capacity',
  5 => 'minimum_capacity',
  6 => 'shape',
  7 => 'status',
  8 => 'current_session_id',
  9 => 'location_x',
  10 => 'location_y',
  11 => 'note',
);


}