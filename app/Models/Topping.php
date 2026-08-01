<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Topping extends Model
{
    protected $table = 'toppings';


    protected $fillable = array (
  0 => 'code',
  1 => 'name',
  2 => 'image',
  3 => 'description',
  4 => 'price',
  5 => 'max_quantity',
  6 => 'status',
);


}