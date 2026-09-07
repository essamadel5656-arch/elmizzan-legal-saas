<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Builder;

class WorkspaceMessage extends Model
{
    protected $fillable = ['sender_id', 'receiver_id', 'body', 'read_at', 'attachment_path', 'attachment_name'];
    protected $casts    = ['read_at' => 'datetime'];

    public function sender(): BelongsTo
    {
        return $this->belongsTo(User::class, 'sender_id');
    }

    public function receiver(): BelongsTo
    {
        return $this->belongsTo(User::class, 'receiver_id');
    }

    public function scopeVisibleTo(Builder $query, int $userId): Builder
    {
        return $query->where(function ($q) use ($userId) {
            $q->where('receiver_id', $userId)->orWhereNull('receiver_id');
        })->where('sender_id', '!=', $userId);
    }

    public function scopeUnreadFor(Builder $query, int $userId): Builder
    {
        return $query->visibleTo($userId)->whereNull('read_at');
    }
}
