<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Maintenance extends Model
{
    use HasFactory;

    protected $fillable = [
        'asset_id',
        'maintenance_schedule_id',
        'technician_id',
        'user_id',
        'type',
        'status',
        'scheduled_date',
        'completed_date',
        'description',
        'activities_performed',
        'observations',
        'recommendations',
        'parts_replaced',
        'cost',
        'duration_minutes',
        'document_path',
        'technician_signed_at',
        'user_signed_at',
    ];

    protected $casts = [
        'scheduled_date' => 'date',
        'completed_date' => 'date',
        'parts_replaced' => 'array',
        'cost' => 'decimal:2',
        'technician_signed_at' => 'datetime',
        'user_signed_at' => 'datetime',
    ];

    public function asset()
    {
        return $this->belongsTo(Asset::class);
    }

    public function maintenanceSchedule()
    {
        return $this->belongsTo(MaintenanceSchedule::class);
    }

    public function technician()
    {
        return $this->belongsTo(User::class, 'technician_id');
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function checklists()
    {
        return $this->hasMany(MaintenanceChecklist::class);
    }

    /**
     * Verificar si está completamente firmado
     */
    public function isFullySigned(): bool
    {
        return !is_null($this->technician_signed_at) && !is_null($this->user_signed_at);
    }

    /**
     * Verificar si está vencido
     */
    public function isOverdue(): bool
    {
        return $this->status === 'programado' && $this->scheduled_date->isPast();
    }

    /**
     * Obtener el estado en español
     */
    public function getStatusNameAttribute(): string
    {
        return match($this->status) {
            'programado' => 'Programado',
            'en_proceso' => 'En Proceso',
            'completado' => 'Completado',
            'cancelado' => 'Cancelado',
            default => 'Desconocido',
        };
    }

    /**
     * Obtener el tipo en español
     */
    public function getTypeNameAttribute(): string
    {
        return match($this->type) {
            'preventivo' => 'Preventivo',
            'correctivo' => 'Correctivo',
            'predictivo' => 'Predictivo',
            default => 'Desconocido',
        };
    }

    /**
     * Obtener clase de color según estado
     */
    public function getStatusColorAttribute(): string
    {
        return match($this->status) {
            'programado' => 'warning',
            'en_proceso' => 'info',
            'completado' => 'success',
            'cancelado' => 'danger',
            default => 'secondary',
        };
    }
}
