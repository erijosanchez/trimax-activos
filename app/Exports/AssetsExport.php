<?php

namespace App\Exports;

use App\Models\Asset;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;

class AssetsExport implements FromCollection, WithHeadings, WithMapping
{
    public function collection()
    {
        return Asset::with(['category', 'currentAssignment.employee'])->get();
    }

    public function headings(): array
    {
        return [
            'ID',
            'Código',
            'Categoría',
            'Marca',
            'Modelo',
            'Serie',
            'Especificaciones',
            'Fecha Compra',
            'Precio Compra',
            'Estado',
            'Asignado A',
            'Observaciones',
        ];
    }

    public function map($asset): array
    {
        return [
            $asset->id,
            $asset->code,
            $asset->category->name,
            $asset->brand,
            $asset->model,
            $asset->serial_number,
            $asset->specifications,
            $asset->purchase_date?->format('d/m/Y'),
            $asset->purchase_price,
            $asset->status,
            $asset->currentAssignment?->employee->full_name ?? 'No asignado',
            $asset->observations,
        ];
    }
}