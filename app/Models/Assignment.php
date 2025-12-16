<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Assignment extends Model
{
    use HasFactory;

    protected $fillable = [
        'asset_id',
        'employee_id',
        'assigned_date',
        'returned_date',
        'assignment_observations',
        'return_observations',
        'condition_on_assignment',
        'condition_on_return',
        'is_active'
    ];

    protected $casts = [
        'assigned_date' => 'date',
        'returned_date' => 'date',
        'is_active' => 'boolean',
    ];

    public function asset()
    {
        return $this->belongsTo(Asset::class);
    }

    public function employee()
    {
        return $this->belongsTo(Employee::class);
    }

    public function responsibilityDocument()
    {
        return $this->hasOne(ResponsibilityDocument::class);
    }

    // Documento de entrega
    public function deliveryDocument()
    {
        return $this->hasOne(ResponsibilityDocument::class)->where('document_type', 'delivery');
    }

    // Documento de devolución
    public function returnDocument()
    {
        return $this->hasOne(ResponsibilityDocument::class)->where('document_type', 'return');
    }

    // Todos los documentos
    public function documents()
    {
        return $this->hasMany(ResponsibilityDocument::class);
    }

    // Calcular días de uso
    public function getUsageDaysAttribute()
    {
        $endDate = $this->returned_date ?? now();
        return $this->assigned_date->diffInDays($endDate);
    }

    // Verificar si está actualmente asignado
    public function isCurrentlyAssigned()
    {
        return $this->is_active && is_null($this->returned_date);
    }
}
