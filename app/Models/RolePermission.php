<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class RolePermission extends Model
{
    protected $table = 'role_permissions';

    public $timestamps = false;
    protected $primaryKey = null;
    public $incrementing = false;

    protected $fillable = array (
  0 => 'role_id',
  1 => 'permission_id',
);

    public function role() {
        return $this->belongsTo(Role::class);
    }

    public function permission() {
        return $this->belongsTo(Permission::class);
    }
}