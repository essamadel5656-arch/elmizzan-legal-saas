<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ClientPayment extends Model
{
    protected $fillable = [
        'case_id', 'client_id', 'mode', 'amount',
        'payment_date', 'reference_number', 'receipt_path',
        'status', 'notes',
    ];

    protected function casts(): array
    {
        return [
            'amount'       => 'decimal:2',
            'payment_date' => 'date',
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

    public function isInPerson(): bool          { return $this->mode === 'in_person'; }
    public function isTransfer(): bool          { return $this->mode === 'transfer'; }
    public function isPending(): bool           { return $this->status === 'pending'; }
    public function isPendingVerification(): bool { return $this->status === 'pending_verification'; }
    public function isConfirmed(): bool         { return $this->status === 'confirmed'; }
}
