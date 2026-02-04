<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class MaintenanceChecklist extends Model
{
    use HasFactory;

    protected $fillable = [
        'maintenance_id',
        'item',
        'is_checked',
        'notes',
    ];

    protected $casts = [
        'is_checked' => 'boolean',
    ];

    public function maintenance()
    {
        return $this->belongsTo(Maintenance::class);
    }
}
