<?php

namespace App\Exports;

use App\Models\Employee;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;

class EmployeesExport implements FromCollection, WithHeadings, WithMapping
{
    public function collection()
    {
        return Employee::withCount('activeAssignments')->get();
    }

    public function headings(): array
    {
        return [
            'ID',
            'DNI',
            'Nombre',
            'Apellido',
            'Email',
            'Teléfono',
            'Departamento',
            'Cargo',
            'Activos Asignados',
            'Estado',
        ];
    }

    public function map($employee): array
    {
        return [
            $employee->id,
            $employee->dni,
            $employee->first_name,
            $employee->last_name,
            $employee->email,
            $employee->phone,
            $employee->department,
            $employee->position,
            $employee->active_assignments_count,
            $employee->active ? 'Activo' : 'Inactivo',
        ];
    }
}