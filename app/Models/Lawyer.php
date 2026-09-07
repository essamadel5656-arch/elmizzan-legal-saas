<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Lawyer extends Model
{
    protected $table = 'lawyers';

    protected $fillable = [
        'name',
        'phone',
        'specialization',
        'license_number',
        'address',
        'email',
        'degree',
        'profile_image',
        'national_id_image',
        'bar_card_image',
        'bio'
    ];

    public function cases()
    {
        return $this->belongsToMany(LegalCase::class, 'lawyerscases')
                    ->withPivot('role');
    }
}
