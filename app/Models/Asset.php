<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Asset extends Model
{
    use HasFactory;

    protected $fillable = [
        'category_id', 'code', 'brand', 'model', 'serial_number',
        'specifications', 'purchase_date', 'purchase_price', 
        'status', 'observations'
    ];

    protected $casts = [
        'purchase_date' => 'date',
        'purchase_price' => 'decimal:2',
    ];

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
