<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CourtDivision extends Model
{
    use HasFactory;

    // تحديد اسم الجدول في قاعدة البيانات
    protected $table = 'court_divisions';

    protected $fillable = ['name', 'court_id'];

    // الدائرة تنتمي إلى محكمة واحدة
    public function court()
    {
        return $this->belongsTo(Court::class);
    }

    // الدائرة تحتوي على العديد من القضايا
    public function cases()
    {
        return $this->hasMany(LegalCase::class, 'court_division_id');
    }
}