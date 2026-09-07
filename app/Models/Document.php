<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Document extends Model
{
    protected $table = 'documents';

    protected $fillable = ['title','type','file_path','case_id'];

public function case()
    {
        return $this->belongsTo(LegalCase::class, 'case_id');
    }

    public function clients()
    {
        return $this->belongsToMany(Client::class, 'client_document', 'document_id', 'client_id');
    }
}
