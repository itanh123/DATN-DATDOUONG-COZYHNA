<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Category extends Model
{
    protected $table = 'categories';


    protected $fillable = array (
  0 => 'name',
  1 => 'description',
  2 => 'image',
  3 => 'display_order',
  4 => 'status',
);


}