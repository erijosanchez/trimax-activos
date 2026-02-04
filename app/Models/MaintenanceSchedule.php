<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Carbon\Carbon;

class MaintenanceSchedule extends Model
{
    use HasFactory;

    protected $fillable = [
        'asset_id',
        'frequency',
        'last_maintenance_date',
        'next_maintenance_date',
        'is_active',
        'notes',
    ];

    protected $casts = [
        'last_maintenance_date' => 'date',
        'next_maintenance_date' => 'date',
        'is_active' => 'boolean',
    ];

    public function asset()
    {
        return $this->belongsTo(Asset::class);
    }

    public function maintenances()
    {
        return $this->hasMany(Maintenance::class);
    }

    /**
     * Calcular la próxima fecha de mantenimiento según la frecuencia
     */
    public function calculateNextMaintenanceDate(?Carbon $from = null): Carbon
    {
        $baseDate = $from ?? ($this->last_maintenance_date ?? now());

        return match($this->frequency) {
            'mensual' => $baseDate->copy()->addMonth(),
            'trimestral' => $baseDate->copy()->addMonths(3),
            'semestral' => $baseDate->copy()->addMonths(6),
            'anual' => $baseDate->copy()->addYear(),
            default => $baseDate->copy()->addMonths(3),
        };
    }

    /**
     * Actualizar la fecha del próximo mantenimiento
     */
    public function updateNextMaintenanceDate(): void
    {
        $this->next_maintenance_date = $this->calculateNextMaintenanceDate();
        $this->save();
    }

    /**
     * Verificar si el mantenimiento está próximo (dentro de 7 días)
     */
    public function isUpcoming(): bool
    {
        return $this->is_active && 
               $this->next_maintenance_date->isFuture() && 
               $this->next_maintenance_date->diffInDays(now()) <= 7;
    }

    /**
     * Verificar si el mantenimiento está vencido
     */
    public function isOverdue(): bool
    {
        return $this->is_active && $this->next_maintenance_date->isPast();
    }

    /**
     * Obtener el nombre de la frecuencia
     */
    public function getFrequencyNameAttribute(): string
    {
        return match($this->frequency) {
            'mensual' => 'Mensual',
            'trimestral' => 'Trimestral',
            'semestral' => 'Semestral',
            'anual' => 'Anual',
            default => 'Desconocido',
        };
    }

    /**
     * Obtener clase de color según estado
     */
    public function getStatusColorAttribute(): string
    {
        if (!$this->is_active) {
            return 'secondary';
        }
        
        if ($this->isOverdue()) {
            return 'danger';
        }
        
        if ($this->isUpcoming()) {
            return 'warning';
        }
        
        return 'success';
    }

    /**
     * Obtener días hasta el próximo mantenimiento
     */
    public function getDaysUntilMaintenanceAttribute(): int
    {
        return now()->diffInDays($this->next_maintenance_date, false);
    }
}
