<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DiningTable extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'user_id',
        'qr_token',
        'status',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
