<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ChatMessage extends Model
{
    protected $table = 'chat_messages';

    public $timestamps = false;

    protected $fillable = array (
  0 => 'session_id',
  1 => 'role',
  2 => 'message',
  3 => 'token_usage',
  4 => 'created_at',
);


}