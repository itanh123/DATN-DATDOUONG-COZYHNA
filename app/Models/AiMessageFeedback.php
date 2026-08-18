<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AiMessageFeedback extends Model
{
    protected $table = 'ai_message_feedback';

    protected $fillable = [
        'message_id',
        'feedback',
        'comment',
    ];

    public function message()
    {
        return $this->belongsTo(ChatMessage::class, 'message_id');
    }
}
