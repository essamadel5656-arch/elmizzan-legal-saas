<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Court extends Model
{
    protected $table = 'courts';

    protected $fillable = [
        'name',
        'jurisdiction_id',
    ];

    public function divisions()
    {
        return $this->hasMany(CourtDivision::class, 'court_id');
    }

    public function jurisdiction()
    {
        return $this->belongsTo(Jurisdiction::class, 'jurisdiction_id');
    }

    public function cases()
    {
        return $this->hasMany(LegalCase::class, 'court_id');
    }
}