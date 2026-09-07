<?php

namespace App\Models;
use Illuminate\Database\Eloquent\Model;

class Client extends Model
{
    protected $table = 'clients';

    protected $fillable = ['name', 'address', 'email', 'phone', 'nid', 'note'];

    public function cases()
    {
        return $this->belongsToMany(LegalCase::class, 'client_case', 'client_id', 'case_id');
    }

    public function user()
    {
        return $this->hasOne(User::class, 'client_id');
    }

    public function payments()
    {
        return $this->hasMany(ClientPayment::class, 'client_id');
    }

    public function documentRequests()
    {
        return $this->hasMany(DocumentRequest::class, 'client_id');
    }
}