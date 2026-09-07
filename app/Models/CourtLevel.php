<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CourtLevel extends Model
{
    protected $table = 'court_levels'; 

    protected $fillable = ['name'];

    public function courts()
    {
        return $this->hasMany(Court::class, 'court_level_id');
    }
}