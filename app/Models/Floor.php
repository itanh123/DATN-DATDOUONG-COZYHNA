<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Floor extends Model
{
    protected $table = 'floors';


    protected $fillable = array (
  0 => 'code',
  1 => 'name',
  2 => 'description',
  3 => 'display_order',
  4 => 'status',
);


}