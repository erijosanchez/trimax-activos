<?php

namespace App\Exports;

use App\Models\Asset;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;    
use Maatwebsite\Excel\Concerns\WithMapping;

class AssignmentHistoryExport implements FromCollection, WithHeadings, WithMapping
{
    protected $assetId;

    public function __construct($assetId)
    {
        $this->assetId = $assetId;
    }

    public function collection()
    {
        return Asset::find($this->assetId)
            ->assignments()
            ->with('employee')
            ->orderBy('assigned_date', 'desc')
            ->get();
    }

    public function headings(): array
    {
        return [
            'ID Asignación',
            'Empleado',
            'DNI',
            'Fecha Entrega',
            'Fecha Devolución',
            'Días de Uso',
            'Estado al Entregar',
            'Estado al Devolver',
            'Observaciones Entrega',
            'Observaciones Devolución',
        ];
    }

    public function map($assignment): array
    {
        return [
            $assignment->id,
            $assignment->employee->full_name,
            $assignment->employee->dni,
            $assignment->assigned_date->format('d/m/Y'),
            $assignment->returned_date?->format('d/m/Y') ?? 'En uso',
            $assignment->usage_days,
            $assignment->condition_on_assignment,
            $assignment->condition_on_return ?? 'N/A',
            $assignment->assignment_observations,
            $assignment->return_observations,
        ];
    }
}