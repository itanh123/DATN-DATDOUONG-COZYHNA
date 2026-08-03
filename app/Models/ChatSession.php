<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ChatSession extends Model
{
    protected $table = 'chat_sessions';


    protected $fillable = array (
  0 => 'user_id',
  1 => 'title',
);

    public function user() {
        return $this->belongsTo(User::class);
    }
}