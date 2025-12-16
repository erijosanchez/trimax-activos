<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Assignment;
use App\Models\Asset;
use App\Models\Employee;
use App\Models\ResponsibilityDocument;
use Illuminate\Support\Facades\DB;
use PhpOffice\PhpWord\TemplateProcessor;

class AssignmentController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $assignments = Assignment::with(['asset.category', 'employee'])
            ->latest()
            ->paginate(15);

        return view('assignments.index', compact('assignments'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $assets = Asset::where('status', 'available')->with('category')->get();
        $employees = Employee::where('active', true)->get();

        return view('assignments.create', compact('assets', 'employees'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'asset_id' => 'required|exists:assets,id',
            'employee_id' => 'required|exists:employees,id',
            'assigned_date' => 'required|date',
            'assignment_observations' => 'nullable|string',
            'condition_on_assignment' => 'required|in:new,good,fair,poor',
            'document_number' => 'nullable|string|unique:responsibility_documents',
            'document_file' => 'nullable|file|mimes:pdf|max:5120',
            'signed_date' => 'nullable|date',
            'document_notes' => 'nullable|string',
        ]);

        DB::beginTransaction();
        try {
            // Crear asignación
            $assignment = Assignment::create([
                'asset_id' => $validated['asset_id'],
                'employee_id' => $validated['employee_id'],
                'assigned_date' => $validated['assigned_date'],
                'assignment_observations' => $validated['assignment_observations'],
                'condition_on_assignment' => $validated['condition_on_assignment'],
                'is_active' => true,
            ]);

            // Actualizar estado del activo
            $asset = Asset::find($validated['asset_id']);
            $asset->update(['status' => 'assigned']);

            // Guardar documento de responsabilidad (si se subió)
            if ($request->hasFile('document_file') && !empty($validated['document_number'])) {
                $path = $request->file('document_file')->store('responsibility_documents', 'public');

                ResponsibilityDocument::create([
                    'assignment_id' => $assignment->id,
                    'document_type' => 'delivery', // Documento de entrega
                    'document_number' => $validated['document_number'],
                    'document_path' => $path,
                    'signed_date' => $validated['signed_date'] ?? now(),
                    'notes' => $validated['document_notes'] ?? null,
                ]);
            }

            DB::commit();

            // Mensaje según si subió documento o no
            if ($request->hasFile('document_file')) {
                return redirect()->route('assignments.show', $assignment)
                    ->with('success', 'Asignación creada exitosamente con documento adjunto.');
            } else {
                return redirect()->route('assignments.show', $assignment)
                    ->with('success', 'Asignación creada exitosamente. Descarga el acta, haz firmar y sube el documento.')
                    ->with('show_download_button', true);
            }
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Error al crear la asignación: ' . $e->getMessage());
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(Assignment $assignment)
    {
        $assignment->load(['asset.category', 'employee', 'deliveryDocument', 'returnDocument']);

        return view('assignments.show', compact('assignment'));
    }

    public function returnAsset(Request $request, Assignment $assignment)
    {
        $validated = $request->validate([
            'returned_date' => 'required|date|after_or_equal:' . $assignment->assigned_date,
            'return_observations' => 'nullable|string',
            'condition_on_return' => 'required|in:good,fair,poor,damaged',
            // Campos opcionales para documento de devolución
            'return_document_number' => 'nullable|string',
            'return_document_file' => 'nullable|file|mimes:pdf|max:5120',
            'return_signed_date' => 'nullable|date',
            'return_document_notes' => 'nullable|string',
        ]);

        DB::beginTransaction();
        try {
            $assignment->update([
                'returned_date' => $validated['returned_date'],
                'return_observations' => $validated['return_observations'],
                'condition_on_return' => $validated['condition_on_return'],
                'is_active' => false,
            ]);

            // Actualizar estado del activo
            $newStatus = $validated['condition_on_return'] === 'damaged' ? 'damaged' : 'available';
            $assignment->asset->update(['status' => $newStatus]);

            // Guardar documento de devolución si se subió
            if ($request->hasFile('return_document_file') && !empty($validated['return_document_number'])) {
                $path = $request->file('return_document_file')->store('responsibility_documents', 'public');

                ResponsibilityDocument::create([
                    'assignment_id' => $assignment->id,
                    'document_type' => 'return',
                    'document_number' => $validated['return_document_number'],
                    'document_path' => $path,
                    'signed_date' => $validated['return_signed_date'] ?? now(),
                    'notes' => $validated['return_document_notes'] ?? null,
                ]);
            }

            DB::commit();

            $message = 'Devolución registrada exitosamente';
            if ($request->hasFile('return_document_file')) {
                $message .= ' con documento adjunto.';
            } else {
                $message .= '. Puedes subir el acta de devolución cuando la tengas firmada.';
            }

            return redirect()->route('assignments.show', $assignment)
                ->with('success', $message);
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Error al registrar devolución: ' . $e->getMessage());
        }
    }

    /**
     * Generar acta de entrega en formato DOCX rellenada automáticamente
     */
    public function generateDeliveryDocument(Assignment $assignment)
    {
        // Cargar plantilla
        $templatePath = storage_path('app/templates/acta_entrega_template.docx');

        if (!file_exists($templatePath)) {
            return back()->with('error', 'Plantilla de acta no encontrada. Por favor contacte al administrador.');
        }

        $templateProcessor = new \PhpOffice\PhpWord\TemplateProcessor($templatePath);

        // Rellenar datos del empleado
        $templateProcessor->setValue('empleado_nombre', $assignment->employee->full_name);
        $templateProcessor->setValue('empleado_dni', $assignment->employee->dni);
        $templateProcessor->setValue('empleado_cargo', $assignment->employee->position ?? '');
        $templateProcessor->setValue('empleado_area', $assignment->employee->department ?? '');

        // Rellenar datos del activo
        $asset = $assignment->asset;
        $templateProcessor->setValue('equipo_tipo', $asset->category->name);
        $templateProcessor->setValue('equipo_codigo', $asset->code);
        $templateProcessor->setValue('equipo_marca', $asset->brand);
        $templateProcessor->setValue('equipo_modelo', $asset->model);
        $templateProcessor->setValue('equipo_serie', $asset->serial_number ?? 'N/A');

        // Datos específicos según tipo de equipo
        if (strtolower($asset->category->name) === 'laptop' || strtolower($asset->category->name) === 'pc') {
            $templateProcessor->setValue('equipo_ram', $asset->ram ?? 'N/A');
            $templateProcessor->setValue('equipo_procesador', $asset->processor ?? 'N/A');
        } else {
            $templateProcessor->setValue('equipo_ram', 'N/A');
            $templateProcessor->setValue('equipo_procesador', 'N/A');
        }

        // Para celulares
        if (strtolower($asset->category->name) === 'celular') {
            $templateProcessor->setValue('celular_linea', $asset->phone ?? '');
            $templateProcessor->setValue('celular_imei', $asset->imei ?? '');
        } else {
            $templateProcessor->setValue('celular_linea', '');
            $templateProcessor->setValue('celular_imei', '');
        }

        // Fechas
        $templateProcessor->setValue('fecha_entrega', $assignment->assigned_date->format('d/m/Y'));
        $templateProcessor->setValue('fecha_actual', date('d/m/Y'));

        // Condición del equipo
        $condiciones = [
            'new' => 'Nuevo',
            'good' => 'Bueno',
            'fair' => 'Regular',
            'poor' => 'Malo'
        ];
        $templateProcessor->setValue('condicion_equipo', $condiciones[$assignment->condition_on_assignment] ?? 'Bueno');

        // Observaciones
        $templateProcessor->setValue('observaciones_entrega', $assignment->assignment_observations ?? 'Ninguna');

        // Generar nombre del archivo
        $fileName = 'Acta_Entrega_' . $asset->code . '_' . date('Ymd') . '.docx';
        $tempFile = storage_path('app/temp/' . $fileName);

        // Crear directorio temp si no existe
        if (!file_exists(storage_path('app/temp'))) {
            mkdir(storage_path('app/temp'), 0755, true);
        }

        // Guardar archivo
        $templateProcessor->saveAs($tempFile);

        // Descargar
        return response()->download($tempFile, $fileName)->deleteFileAfterSend(true);
    }

    /**
     * Generar acta de DEVOLUCIÓN en formato DOCX
     */
    public function generateReturnDocument(Assignment $assignment)
    {
        // Verificar que la asignación esté devuelta
        if ($assignment->is_active || !$assignment->returned_date) {
            return back()->with('error', 'No se puede generar acta de devolución para una asignación activa.');
        }

        // Cargar plantilla
        $templatePath = storage_path('app/templates/acta_devolucion_template.docx');

        if (!file_exists($templatePath)) {
            return back()->with('error', 'Plantilla de acta de devolución no encontrada. Por favor contacte al administrador.');
        }

        $templateProcessor = new \PhpOffice\PhpWord\TemplateProcessor($templatePath);

        // Rellenar datos del empleado
        $templateProcessor->setValue('empleado_nombre', $assignment->employee->full_name);
        $templateProcessor->setValue('empleado_dni', $assignment->employee->dni);
        $templateProcessor->setValue('empleado_cargo', $assignment->employee->position ?? '');
        $templateProcessor->setValue('empleado_area', $assignment->employee->department ?? '');

        // Rellenar datos del activo
        $asset = $assignment->asset;
        $templateProcessor->setValue('equipo_tipo', $asset->category->name);
        $templateProcessor->setValue('equipo_codigo', $asset->code);
        $templateProcessor->setValue('equipo_marca', $asset->brand);
        $templateProcessor->setValue('equipo_modelo', $asset->model);
        $templateProcessor->setValue('equipo_serie', $asset->serial_number ?? 'N/A');

        // Datos específicos según tipo de equipo
        if (strtolower($asset->category->name) === 'laptop' || strtolower($asset->category->name) === 'pc') {
            $templateProcessor->setValue('equipo_ram', $asset->ram ?? 'N/A');
            $templateProcessor->setValue('equipo_procesador', $asset->processor ?? 'N/A');
        } else {
            $templateProcessor->setValue('equipo_ram', 'N/A');
            $templateProcessor->setValue('equipo_procesador', 'N/A');
        }

        // Para celulares
        if (strtolower($asset->category->name) === 'celular') {
            $templateProcessor->setValue('celular_linea', $asset->phone ?? '');
            $templateProcessor->setValue('celular_imei', $asset->imei ?? '');
        } else {
            $templateProcessor->setValue('celular_linea', '');
            $templateProcessor->setValue('celular_imei', '');
        }

        // Fechas
        $templateProcessor->setValue('fecha_entrega', $assignment->assigned_date->format('d/m/Y'));
        $templateProcessor->setValue('fecha_devolucion', $assignment->returned_date->format('d/m/Y'));
        $templateProcessor->setValue('fecha_actual', date('d/m/Y'));
        $templateProcessor->setValue('dias_uso', $assignment->usage_days);

        // Condición del equipo al entregar y al devolver
        $condiciones = [
            'new' => 'Nuevo',
            'good' => 'Bueno',
            'fair' => 'Regular',
            'poor' => 'Malo',
            'damaged' => 'Dañado'
        ];
        $templateProcessor->setValue('condicion_entrega', $condiciones[$assignment->condition_on_assignment] ?? 'Bueno');
        $templateProcessor->setValue('condicion_devolucion', $condiciones[$assignment->condition_on_return] ?? 'Bueno');

        // Observaciones
        $templateProcessor->setValue('observaciones_entrega', $assignment->assignment_observations ?? 'Ninguna');
        $templateProcessor->setValue('observaciones_devolucion', $assignment->return_observations ?? 'Ninguna');

        // Generar nombre del archivo
        $fileName = 'Acta_Devolucion_' . $asset->code . '_' . date('Ymd') . '.docx';
        $tempFile = storage_path('app/temp/' . $fileName);

        // Crear directorio temp si no existe
        if (!file_exists(storage_path('app/temp'))) {
            mkdir(storage_path('app/temp'), 0755, true);
        }

        // Guardar archivo
        $templateProcessor->saveAs($tempFile);

        // Descargar
        return response()->download($tempFile, $fileName)->deleteFileAfterSend(true);
    }

    /**
     * Subir documento de responsabilidad después de crear la asignación
     */
    public function uploadDocument(Request $request, Assignment $assignment)
    {
        $validated = $request->validate([
            'document_type' => 'required|in:delivery,return',
            'document_number' => 'required|string',
            'document_file' => 'required|file|mimes:pdf|max:5120',
            'signed_date' => 'required|date',
            'document_notes' => 'nullable|string',
        ]);

        try {
            // Verificar que no tenga documento de este tipo ya
            $existingDoc = ResponsibilityDocument::where('assignment_id', $assignment->id)
                ->where('document_type', $validated['document_type'])
                ->first();

            if ($existingDoc) {
                return back()->with('error', 'Esta asignación ya tiene un documento de ' .
                    ($validated['document_type'] === 'delivery' ? 'entrega' : 'devolución') . ' adjunto.');
            }

            $path = $request->file('document_file')->store('responsibility_documents', 'public');

            ResponsibilityDocument::create([
                'assignment_id' => $assignment->id,
                'document_type' => $validated['document_type'],
                'document_number' => $validated['document_number'],
                'document_path' => $path,
                'signed_date' => $validated['signed_date'],
                'notes' => $validated['document_notes'] ?? null,
            ]);

            $docTypeName = $validated['document_type'] === 'delivery' ? 'entrega' : 'devolución';
            return redirect()->route('assignments.show', $assignment)
                ->with('success', 'Documento de ' . $docTypeName . ' subido exitosamente.');
        } catch (\Exception $e) {
            return back()->with('error', 'Error al subir el documento: ' . $e->getMessage());
        }
    }
}
