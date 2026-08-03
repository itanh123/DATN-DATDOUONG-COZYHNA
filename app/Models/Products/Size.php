<?php

namespace App\Models\Products;

use Illuminate\Database\Eloquent\Model;

class Size extends Model
{
    protected $table = 'sizes';


    protected $fillable = array (
  0 => 'name',
  1 => 'volume_ml',
  2 => 'description',
  3 => 'status',
);


}
