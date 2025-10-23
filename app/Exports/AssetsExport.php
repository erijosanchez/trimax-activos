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
            // Celular
            'IMEI 1',
            'IMEI 2',
            'Teléfono',
            'Operador',
            // PC/Laptop
            'Procesador',
            'RAM',
            'Almacenamiento',
            'Tipo Almacenamiento',
            'Tarjeta Gráfica',
            'Sistema Operativo',
            'Tamaño Pantalla',
            // Monitor
            'Tamaño Monitor',
            'Resolución',
            'Tipo Panel',
            'Tasa Refresco',
            // Periféricos
            'Tipo Conexión',
            'Inalámbrico',
            'Tipo Audio',
            'Tiene Micrófono',
            // General
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
            $asset->serial_number ?? '-',
            // Celular
            $asset->imei ?? '-',
            $asset->imei_2 ?? '-',
            $asset->phone ?? '-',
            $asset->operator_name ?? '-',
            // PC/Laptop
            $asset->processor ?? '-',
            $asset->ram ?? '-',
            $asset->storage ?? '-',
            $asset->storage_type ?? '-',
            $asset->graphics_card ?? '-',
            $asset->operating_system ?? '-',
            $asset->screen_size ?? '-',
            // Monitor
            $asset->screen_size_monitor ?? '-',
            $asset->resolution ?? '-',
            $asset->panel_type ?? '-',
            $asset->refresh_rate ?? '-',
            // Periféricos
            $asset->connection_type ?? '-',
            $asset->is_wireless ? 'Sí' : 'No',
            $asset->audio_type ?? '-',
            $asset->has_microphone ? 'Sí' : 'No',
            // General
            $asset->purchase_date?->format('d/m/Y') ?? '-',
            $asset->purchase_price ?? '-',
            $asset->status,
            $asset->currentAssignment?->employee->full_name ?? 'No asignado',
            $asset->observations ?? '-',
        ];
    }
}