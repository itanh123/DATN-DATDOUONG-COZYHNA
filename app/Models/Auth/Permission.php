<?php

namespace App\Models\Auth;

use Illuminate\Database\Eloquent\Model;

class Permission extends Model
{
    protected $table = 'permissions';


    protected $fillable = array (
  0 => 'code',
  1 => 'name',
  2 => 'module',
  3 => 'description',
);


}
