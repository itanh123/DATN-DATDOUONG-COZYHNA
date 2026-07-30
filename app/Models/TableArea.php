<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class TableArea extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = ['floor_id', 'code', 'name', 'description', 'display_order', 'status'];

    public function floor()
    {
        return $this->belongsTo(Floor::class);
    }

    public function tables()
    {
        return $this->hasMany(RestaurantTable::class, 'area_id')->orderBy('location_y')->orderBy('location_x');
    }
}
