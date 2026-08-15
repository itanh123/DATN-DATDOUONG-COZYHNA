<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Floor extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'floors';

    protected $fillable = ['code', 'name', 'description', 'display_order', 'status'];

    public function areas()
    {
        return $this->hasMany(TableArea::class, 'floor_id')->orderBy('display_order');
    }

    public function tables()
    {
        return $this->hasManyThrough(RestaurantTable::class, TableArea::class, 'floor_id', 'area_id');
    }
}
