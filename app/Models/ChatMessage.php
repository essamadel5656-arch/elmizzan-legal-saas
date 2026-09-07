<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ChatMessage extends Model
{
    use HasFactory;

    protected $table = 'chat_messages';

    protected $fillable = ['chat_session_id', 'role', 'message', 'file_path', 'file_name'];

    public function session()
    {
        return $this->belongsTo(ChatSession::class, 'chat_session_id');
    }
}