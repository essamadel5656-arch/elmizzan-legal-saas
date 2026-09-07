<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class LegalCase extends Model
{
    protected $table = 'cases';

    protected $fillable = [
        'case_number',
        'status',
        'description',
        'costs',
        'court_id',
        'jurisdiction_id',
        'court_level_id',
        'circuit',
        'total_costs',
        'agreed_legal_fee',  // Admin-only: the officially agreed legal fee
        'deposit',
        'Previous_procedure',
        'case_file',
        'final_decision',
        'notes',
        'rival_name',
        'rival_number',
        'rival_address',
        'rival_nid',
        'procuration',
        'lawyer_id',
    ];

    public function court()
    {
        return $this->belongsTo(Court::class, 'court_id');
    }

    public function jurisdiction()
    {
        return $this->belongsTo(Jurisdiction::class, 'jurisdiction_id');
    }

    public function courtLevel()
    {
        return $this->belongsTo(CourtLevel::class, 'court_level_id');
    }

    public function appointments()
    {
        return $this->hasMany(Appointment::class, 'case_id');
    }

    public function clients()
    {
        return $this->belongsToMany(Client::class, 'client_case', 'case_id', 'client_id')->withTimestamps();
    }

    public function lawyers()
    {
        return $this->belongsToMany(Lawyer::class, 'lawyerscases', 'case_id', 'lawyer_id')
                    ->withPivot('role')->withTimestamps();
    }

    public function documents()
    {
        return $this->hasMany(Document::class, 'case_id');
    }

    public function expenses()
    {
        return $this->hasMany(CaseExpense::class, 'case_id');
    }

    public function approvedExpenses()
    {
        return $this->hasMany(CaseExpense::class, 'case_id')->where('status', 'approved');
    }

    public function clientPayments()
    {
        return $this->hasMany(ClientPayment::class, 'case_id');
    }

    public function documentRequests()
    {
        return $this->hasMany(DocumentRequest::class, 'case_id');
    }
}