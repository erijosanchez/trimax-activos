<?php

namespace App\Exports;

use App\Models\Assignment;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;

class AssignmentsExport implements FromCollection, WithHeadings, WithMapping
{
    public function collection()
    {
        return Assignment::with(['asset.category', 'employee'])->get();
    }

    public function headings(): array
    {
        return [
            'ID',
            'Activo',
            'Código Activo',
            'Categoría',
            'Empleado',
            'DNI',
            'Fecha Asignación',
            'Fecha Devolución',
            'Días de Uso',
            'Estado Entrega',
            'Estado Devolución',
            'Activo',
        ];
    }

    public function map($assignment): array
    {
        return [
            $assignment->id,
            $assignment->asset->brand . ' ' . $assignment->asset->model,
            $assignment->asset->code,
            $assignment->asset->category->name,
            $assignment->employee->full_name,
            $assignment->employee->dni,
            $assignment->assigned_date->format('d/m/Y'),
            $assignment->returned_date?->format('d/m/Y') ?? 'En uso',
            $assignment->usage_days,
            $assignment->condition_on_assignment,
            $assignment->condition_on_return ?? 'N/A',
            $assignment->is_active ? 'Sí' : 'No',
        ];
    }
}