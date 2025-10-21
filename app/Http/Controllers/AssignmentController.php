<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Assignment;
use App\Models\Asset;
use App\Models\Employee;
use App\Models\ResponsibilityDocument;
use Illuminate\Support\Facades\DB;

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
            'document_number' => 'required|string|unique:responsibility_documents',
            'document_file' => 'required|file|mimes:pdf|max:5120',
            'signed_date' => 'required|date',
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

            // Guardar documento de responsabilidad
            if ($request->hasFile('document_file')) {
                $path = $request->file('document_file')->store('responsibility_documents', 'public');
                
                ResponsibilityDocument::create([
                    'assignment_id' => $assignment->id,
                    'document_number' => $validated['document_number'],
                    'document_path' => $path,
                    'signed_date' => $validated['signed_date'],
                    'notes' => $validated['document_notes'] ?? null,
                ]);
            }

            DB::commit();

            return redirect()->route('assignments.show', $assignment)
                ->with('success', 'Asignación creada exitosamente');
                
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
        $assignment->load(['asset.category', 'employee', 'responsibilityDocument']);
        
        return view('assignments.show', compact('assignment'));
    }

    public function returnAsset(Request $request, Assignment $assignment)
    {
        $validated = $request->validate([
            'returned_date' => 'required|date|after_or_equal:' . $assignment->assigned_date,
            'return_observations' => 'nullable|string',
            'condition_on_return' => 'required|in:good,fair,poor,damaged',
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

            DB::commit();

            return redirect()->route('assignments.show', $assignment)
                ->with('success', 'Devolución registrada exitosamente');
                
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Error al registrar devolución: ' . $e->getMessage());
        }
    }
}
