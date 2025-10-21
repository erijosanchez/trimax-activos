<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class ResponsibilityDocument extends Model
{
    use HasFactory;

    protected $fillable = [
        'assignment_id', 'document_number', 'document_path',
        'signed_date', 'notes'
    ];

    protected $casts = [
        'signed_date' => 'date',
    ];

    public function assignment()
    {
        return $this->belongsTo(Assignment::class);
    }
}
