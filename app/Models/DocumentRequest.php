<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class DocumentRequest extends Model
{
    protected $fillable = [
        'case_id', 'client_id', 'requested_by', 'title',
        'description', 'status', 'file_path', 'file_name', 'uploaded_at',
    ];

    protected function casts(): array
    {
        return [
            'uploaded_at' => 'datetime',
        ];
    }

    public function case(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(LegalCase::class, 'case_id');
    }

    public function client(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(Client::class, 'client_id');
    }

    public function requestedBy(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(User::class, 'requested_by');
    }

    public function isPending(): bool  { return $this->status === 'pending'; }
    public function isUploaded(): bool { return $this->status === 'uploaded'; }
    public function isApproved(): bool { return $this->status === 'approved'; }
}
