<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Asset extends Model
{
    use HasFactory;

    protected $fillable = [
        'category_id',
        'code',
        'brand',
        'model',
        'serial_number',
        'purchase_date',
        'purchase_price',
        'status',
        'observations',

        // Campos para celulares
        'imei',
        'imei_2',
        'phone',
        'operator_name',

        // Campos para PC/Laptop
        'processor',
        'ram',
        'storage',
        'storage_type',
        'graphics_card',
        'operating_system',
        'screen_size',

        // Campos para monitores
        'screen_size_monitor',
        'resolution',
        'panel_type',
        'refresh_rate',

        // Campos para periféricos
        'connection_type',
        'is_wireless',

        // Campos para audífonos
        'audio_type',
        'has_microphone'
    ];

    protected $casts = [
        'purchase_date' => 'date',
        'purchase_price' => 'decimal:2',
        'is_wireless' => 'boolean',
        'has_microphone' => 'boolean',
    ];

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($asset) {
            if (empty($asset->code)) {
                $asset->code = self::generateCode();
            }
        });
    }

    /**
     * Generar código automático formato: GMSAC202500001
     */
    public static function generateCode()
    {
        $year = date('Y');
        $prefix = 'GMSAC' . $year;

        // Obtener el último código del año actual
        $lastAsset = self::where('code', 'like', $prefix . '%')
            ->orderBy('code', 'desc')
            ->first();

        if ($lastAsset) {
            // Extraer el número secuencial y sumarle 1
            $lastNumber = intval(substr($lastAsset->code, -5));
            $newNumber = $lastNumber + 1;
        } else {
            // Primer activo del año
            $newNumber = 1;
        }

        // Formatear con 5 dígitos
        return $prefix . str_pad($newNumber, 5, '0', STR_PAD_LEFT);
    }

    /**
     * Obtener el siguiente código que se generaría
     */
    public static function getNextCode()
    {
        return self::generateCode();
    }

    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    public function assignments()
    {
        return $this->hasMany(Assignment::class);
    }

    public function currentAssignment()
    {
        return $this->hasOne(Assignment::class)->where('is_active', true);
    }

    public function assignmentHistory()
    {
        return $this->hasMany(Assignment::class)->orderBy('assigned_date', 'desc');
    }

    // Calcular tiempo total de uso
    public function getTotalUsageDaysAttribute()
    {
        return $this->assignments()
            ->whereNotNull('returned_date')
            ->get()
            ->sum(function ($assignment) {
                return $assignment->assigned_date->diffInDays($assignment->returned_date);
            });
    }
}
