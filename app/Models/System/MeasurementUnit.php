<?php

namespace App\Models\System;

use Illuminate\Database\Eloquent\Model;

class MeasurementUnit extends Model
{
    protected $table = 'measurement_units';


    protected $fillable = array (
  0 => 'name',
  1 => 'symbol',
  2 => 'description',
);


}
