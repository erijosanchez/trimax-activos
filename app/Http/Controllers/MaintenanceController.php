<?php

namespace App\Http\Controllers;

use App\Models\Maintenance;
use App\Models\MaintenanceSchedule;
use App\Models\MaintenanceChecklist;
use App\Models\Asset;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Barryvdh\DomPDF\Facade\Pdf;

class MaintenanceController extends Controller
{
    /**
     * Display a listing of maintenances.
     */
    public function index(Request $request)
    {
        $query = Maintenance::with(['asset', 'technician', 'user'])
            ->orderBy('scheduled_date', 'desc');

        // Filtros
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        if ($request->filled('type')) {
            $query->where('type', $request->type);
        }

        if ($request->filled('asset_id')) {
            $query->where('asset_id', $request->asset_id);
        }

        if ($request->filled('date_from')) {
            $query->where('scheduled_date', '>=', $request->date_from);
        }

        if ($request->filled('date_to')) {
            $query->where('scheduled_date', '<=', $request->date_to);
        }

        $maintenances = $query->paginate(15);

        // Estadísticas
        $stats = [
            'total' => Maintenance::count(),
            'programado' => Maintenance::where('status', 'programado')->count(),
            'en_proceso' => Maintenance::where('status', 'en_proceso')->count(),
            'completado' => Maintenance::where('status', 'completado')->count(),
            'vencidos' => Maintenance::where('status', 'programado')
                ->where('scheduled_date', '<', now())
                ->count(),
        ];

        $assets = Asset::orderBy('code')->get();

        return view('maintenances.index', compact('maintenances', 'stats', 'assets'));
    }

    /**
     * Show the form for creating a new maintenance.
     */
    public function create()
    {
        $assets = Asset::with('maintenanceSchedule')->orderBy('code')->get();
        $technicians = User::where('role', User::ROLE_TI)
            ->orWhere('role', User::ROLE_ADMIN)
            ->where('is_active', true)
            ->orderBy('name')
            ->get();
        $users = User::where('is_active', true)->orderBy('name')->get();

        return view('maintenances.create', compact('assets', 'technicians', 'users'));
    }

    /**
     * Store a newly created maintenance in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'asset_id' => 'required|exists:assets,id',
            'technician_id' => 'required|exists:users,id',
            'user_id' => 'nullable|exists:users,id',
            'type' => 'required|in:preventivo,correctivo,predictivo',
            'scheduled_date' => 'required|date',
            'description' => 'required|string',
            'observations' => 'nullable|string',
            'checklist_items' => 'nullable|array',
        ]);

        DB::beginTransaction();
        try {
            $maintenance = Maintenance::create([
                ...$validated,
                'status' => 'programado',
            ]);

            // Crear checklist items si existen
            if ($request->filled('checklist_items')) {
                foreach ($request->checklist_items as $item) {
                    if (!empty($item)) {
                        MaintenanceChecklist::create([
                            'maintenance_id' => $maintenance->id,
                            'item' => $item,
                        ]);
                    }
                }
            }

            DB::commit();

            return redirect()->route('maintenances.show', $maintenance)
                ->with('success', 'Mantenimiento programado exitosamente.');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Error al crear el mantenimiento: ' . $e->getMessage())
                ->withInput();
        }
    }

    /**
     * Display the specified maintenance.
     */
    public function show(Maintenance $maintenance)
    {
        $maintenance->load(['asset', 'technician', 'user', 'checklists', 'maintenanceSchedule']);
        return view('maintenances.show', compact('maintenance'));
    }

    /**
     * Show the form for editing the specified maintenance.
     */
    public function edit(Maintenance $maintenance)
    {
        if ($maintenance->status === 'completado') {
            return back()->with('error', 'No se puede editar un mantenimiento completado.');
        }

        $assets = Asset::with('maintenanceSchedule')->orderBy('code')->get();
        $technicians = User::where('role', User::ROLE_TI)
            ->orWhere('role', User::ROLE_ADMIN)
            ->where('is_active', true)
            ->orderBy('name')
            ->get();
        $users = User::where('is_active', true)->orderBy('name')->get();

        return view('maintenances.edit', compact('maintenance', 'assets', 'technicians', 'users'));
    }

    /**
     * Update the specified maintenance in storage.
     */
    public function update(Request $request, Maintenance $maintenance)
    {
        if ($maintenance->status === 'completado') {
            return back()->with('error', 'No se puede editar un mantenimiento completado.');
        }

        $validated = $request->validate([
            'asset_id' => 'required|exists:assets,id',
            'technician_id' => 'required|exists:users,id',
            'user_id' => 'nullable|exists:users,id',
            'type' => 'required|in:preventivo,correctivo,predictivo',
            'status' => 'required|in:programado,en_proceso,completado,cancelado',
            'scheduled_date' => 'required|date',
            'completed_date' => 'nullable|date',
            'description' => 'required|string',
            'activities_performed' => 'nullable|string',
            'observations' => 'nullable|string',
            'recommendations' => 'nullable|string',
            'cost' => 'nullable|numeric|min:0',
            'duration_minutes' => 'nullable|integer|min:0',
        ]);

        $maintenance->update($validated);

        return redirect()->route('maintenances.show', $maintenance)
            ->with('success', 'Mantenimiento actualizado exitosamente.');
    }

    /**
     * Remove the specified maintenance from storage.
     */
    public function destroy(Maintenance $maintenance)
    {
        if ($maintenance->status === 'completado') {
            return back()->with('error', 'No se puede eliminar un mantenimiento completado.');
        }

        $maintenance->delete();

        return redirect()->route('maintenances.index')
            ->with('success', 'Mantenimiento eliminado exitosamente.');
    }

    /**
     * Complete a maintenance
     */
    public function complete(Request $request, Maintenance $maintenance)
    {
        $validated = $request->validate([
            'activities_performed' => 'required|string',
            'observations' => 'nullable|string',
            'recommendations' => 'nullable|string',
            'parts_replaced' => 'nullable|array',
            'cost' => 'nullable|numeric|min:0',
            'duration_minutes' => 'nullable|integer|min:0',
            'checklist' => 'nullable|array',
        ]);

        DB::beginTransaction();
        try {
            $maintenance->update([
                'status' => 'completado',
                'completed_date' => now(),
                'activities_performed' => $validated['activities_performed'],
                'observations' => $validated['observations'] ?? null,
                'recommendations' => $validated['recommendations'] ?? null,
                'parts_replaced' => $validated['parts_replaced'] ?? null,
                'cost' => $validated['cost'] ?? null,
                'duration_minutes' => $validated['duration_minutes'] ?? null,
            ]);

            // Actualizar checklist
            if ($request->filled('checklist')) {
                foreach ($request->checklist as $checklistId => $checked) {
                    $checklistItem = MaintenanceChecklist::find($checklistId);
                    if ($checklistItem) {
                        $checklistItem->update(['is_checked' => $checked]);
                    }
                }
            }

            // Actualizar schedule si existe
            if ($maintenance->maintenanceSchedule) {
                $maintenance->maintenanceSchedule->last_maintenance_date = $maintenance->completed_date;
                $maintenance->maintenanceSchedule->updateNextMaintenanceDate();
            }

            DB::commit();

            return redirect()->route('maintenances.show', $maintenance)
                ->with('success', 'Mantenimiento completado exitosamente.');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Error al completar el mantenimiento: ' . $e->getMessage());
        }
    }

    /**
     * Generate maintenance document (Acta de Mantenimiento)
     */
    public function generateDocument(Maintenance $maintenance)
    {
        if ($maintenance->status !== 'completado') {
            return back()->with('error', 'Solo se pueden generar actas de mantenimientos completados.');
        }

        $maintenance->load(['asset.category', 'technician', 'user', 'checklists']);

        $pdf = PDF::loadView('maintenances.document', compact('maintenance'));
        $pdf->setPaper('a4', 'portrait');

        return $pdf->download('acta_mantenimiento_' . $maintenance->id . '.pdf');
    }

    /**
     * Sign maintenance document (technician)
     */
    public function signAsTechnician(Maintenance $maintenance)
    {
        if ($maintenance->status !== 'completado') {
            return back()->with('error', 'El mantenimiento debe estar completado para firmar.');
        }

        if (auth()->id() !== $maintenance->technician_id && !auth()->user()->isAdmin()) {
            return back()->with('error', 'Solo el técnico asignado puede firmar.');
        }

        $maintenance->update([
            'technician_signed_at' => now(),
        ]);

        return back()->with('success', 'Firmado como técnico exitosamente.');
    }

    /**
     * Sign maintenance document (user)
     */
    public function signAsUser(Maintenance $maintenance)
    {
        if ($maintenance->status !== 'completado') {
            return back()->with('error', 'El mantenimiento debe estar completado para firmar.');
        }

        if (!$maintenance->user_id || (auth()->id() !== $maintenance->user_id && !auth()->user()->isAdmin())) {
            return back()->with('error', 'Solo el usuario asignado puede firmar.');
        }

        $maintenance->update([
            'user_signed_at' => now(),
        ]);

        return back()->with('success', 'Firmado como usuario exitosamente.');
    }

    /**
     * Dashboard de mantenimientos
     */
    public function dashboard()
    {
        $stats = [
            'programados' => Maintenance::where('status', 'programado')->count(),
            'en_proceso' => Maintenance::where('status', 'en_proceso')->count(),
            'completados_mes' => Maintenance::where('status', 'completado')
                ->whereMonth('completed_date', now()->month)
                ->count(),
            'vencidos' => Maintenance::where('status', 'programado')
                ->where('scheduled_date', '<', now())
                ->count(),
        ];

        $upcomingMaintenances = Maintenance::with(['asset', 'technician'])
            ->where('status', 'programado')
            ->where('scheduled_date', '>=', now())
            ->orderBy('scheduled_date')
            ->limit(10)
            ->get();

        $overdueMaintenances = Maintenance::with(['asset', 'technician'])
            ->where('status', 'programado')
            ->where('scheduled_date', '<', now())
            ->orderBy('scheduled_date')
            ->get();

        $assetsNeedingMaintenance = MaintenanceSchedule::with('asset')
            ->where('is_active', true)
            ->where('next_maintenance_date', '<=', now()->addDays(30))
            ->orderBy('next_maintenance_date')
            ->get();

        return view('maintenances.dashboard', compact(
            'stats',
            'upcomingMaintenances',
            'overdueMaintenances',
            'assetsNeedingMaintenance'
        ));
    }
}
