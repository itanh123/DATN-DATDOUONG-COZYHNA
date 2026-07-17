<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Ingredient extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'supplier_id',
        'unit_id',
        'code',
        'name',
        'category',
        'current_stock',
        'minimum_stock',
        'expiration_date',
        'is_fresh',
        'cost_price',
        'description',
        'status',
    ];

    protected $casts = [
        'expiration_date' => 'datetime',
        'is_fresh' => 'boolean',
        'status' => 'boolean',
    ];

    public function unit()
    {
        return $this->belongsTo(MeasurementUnit::class, 'unit_id');
    }
}
