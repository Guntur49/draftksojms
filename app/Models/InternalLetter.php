<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class InternalLetter extends Model
{
    use HasFactory;

    protected $fillable = [
        'document_name',
        'document_number',
        'status',
        'pdf_path',
    ];
}
