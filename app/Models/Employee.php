<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Employee extends Model
{
    use HasFactory;

    protected $fillable = [
        'dni', 'first_name', 'last_name', 'email', 
        'phone', 'department', 'position', 'active'
    ];

    protected $casts = [
        'active' => 'boolean',
    ];

    public function assignments()
    {
        return $this->hasMany(Assignment::class);
    }

    public function activeAssignments()
    {
        return $this->hasMany(Assignment::class)->where('is_active', true);
    }

    public function getFullNameAttribute()
    {
        return "{$this->first_name} {$this->last_name}";
    }
}
