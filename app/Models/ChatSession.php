<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ChatSession extends Model
{
    use HasFactory;

    protected $table = 'chat_sessions';

    protected $fillable = ['user_id', 'title'];

    public function messages()
    {
        return $this->hasMany(ChatMessage::class)->orderBy('created_at', 'asc');
    }
}