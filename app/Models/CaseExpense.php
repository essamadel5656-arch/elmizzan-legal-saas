<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CaseExpense extends Model
{
    protected $fillable = [
        'case_id', 'user_id', 'amount', 'category',
        'notes', 'receipt_path', 'status', 'approved_by',
    ];

    protected function casts(): array
    {
        return [
            'amount' => 'decimal:2',
        ];
    }

    public function case(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(LegalCase::class, 'case_id');
    }

    public function submittedBy(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function approvedBy(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(User::class, 'approved_by');
    }

    public function isPending(): bool   { return $this->status === 'pending'; }
    public function isApproved(): bool  { return $this->status === 'approved'; }
    public function isRejected(): bool  { return $this->status === 'rejected'; }
}
