<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class MergedTable extends Model
{
    protected $table = 'merged_tables';


    protected $fillable = array (
  0 => 'code',
  1 => 'name',
  2 => 'capacity',
  3 => 'status',
);


}