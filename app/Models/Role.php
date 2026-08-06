<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Role extends Model
{
    protected $table = 'roles';


    protected $fillable = array (
  0 => 'code',
  1 => 'name',
  2 => 'description',
  3 => 'status',
);
    public function users()
    {
        return $this->hasMany(User::class, 'role_id');
    }

}