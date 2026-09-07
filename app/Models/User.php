<?php

namespace App\Models;

use Database\Factories\UserFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    /** @use HasFactory<UserFactory> */
    use HasFactory, Notifiable;

    protected $table = 'users';

    public function sentWorkspaceMessages()
    {
        return $this->hasMany(\App\Models\WorkspaceMessage::class, 'sender_id');
    }

    public function receivedWorkspaceMessages()
    {
        return $this->hasMany(\App\Models\WorkspaceMessage::class, 'receiver_id');
    }

    public function isOnline(): bool
    {
        return $this->last_seen_at && $this->last_seen_at->gt(now()->subMinutes(5));
    }

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'role',
        'lawyer_id',
        'client_id',
        'activation_token',
        'activation_token_expires_at',
        'last_seen_at',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
        'activation_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at'            => 'datetime',
            'password'                     => 'hashed',
            'activation_token_expires_at'  => 'datetime',
            'last_seen_at'                 => 'datetime',
        ];
    }

    public function lawyer()
    {
        return $this->belongsTo(Lawyer::class, 'lawyer_id');
    }

    public function client()
    {
        return $this->belongsTo(Client::class, 'client_id');
    }

    public function isAdmin(): bool    { return $this->role === 'admin'; }
    public function isLawyer(): bool   { return $this->role === 'lawyer'; }
    public function isClient(): bool   { return $this->role === 'client'; }
    public function isAssistant(): bool{ return $this->role === 'assistant'; }
}
