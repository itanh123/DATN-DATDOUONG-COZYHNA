<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Banner extends Model
{
    protected $table = 'banners';


    protected $fillable = array (
  0 => 'title',
  1 => 'image',
  2 => 'link',
  3 => 'position',
  4 => 'priority',
  5 => 'start_date',
  6 => 'end_date',
  7 => 'status',
);


}