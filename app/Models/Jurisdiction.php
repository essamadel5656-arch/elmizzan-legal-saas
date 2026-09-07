<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Jurisdiction extends Model
{
    protected $table = 'jurisdictions';

    protected $fillable = ['name'];

    public function courts()
    {
        return $this->hasMany(Court::class, 'jurisdiction_id');
    }
}