<?php

namespace App\Http\Controllers;

use App\Models\MaintenanceSchedule;
use App\Models\Asset;
use Illuminate\Http\Request;

class MaintenanceScheduleController extends Controller
{
    /**
     * Display a listing of maintenance schedules.
     */
    public function index()
    {
        $schedules = MaintenanceSchedule::with('asset')
            ->where('is_active', true)
            ->orderBy('next_maintenance_date')
            ->paginate(20);

        return view('maintenance-schedules.index', compact('schedules'));
    }

    /**
     * Show the form for creating a new maintenance schedule.
     */
    public function create()
    {
        // Obtener activos que no tienen schedule activo
        $assets = Asset::whereDoesntHave('maintenanceSchedule', function($query) {
            $query->where('is_active', true);
        })->orderBy('code')->get();

        return view('maintenance-schedules.create', compact('assets'));
    }

    /**
     * Store a newly created maintenance schedule in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'asset_id' => 'required|exists:assets,id',
            'frequency' => 'required|in:mensual,trimestral,semestral,anual',
            'last_maintenance_date' => 'nullable|date',
            'notes' => 'nullable|string',
        ]);

        $schedule = new MaintenanceSchedule($validated);
        
        // Calcular próxima fecha de mantenimiento
        $schedule->next_maintenance_date = $schedule->calculateNextMaintenanceDate(
            $validated['last_maintenance_date'] ? now()->parse($validated['last_maintenance_date']) : now()
        );
        
        $schedule->is_active = true;
        $schedule->save();

        return redirect()->route('maintenance-schedules.index')
            ->with('success', 'Programa de mantenimiento creado exitosamente.');
    }

    /**
     * Display the specified maintenance schedule.
     */
    public function show(MaintenanceSchedule $maintenanceSchedule)
    {
        $maintenanceSchedule->load(['asset', 'maintenances.technician']);
        return view('maintenance-schedules.show', compact('maintenanceSchedule'));
    }

    /**
     * Show the form for editing the specified maintenance schedule.
     */
    public function edit(MaintenanceSchedule $maintenanceSchedule)
    {
        $assets = Asset::orderBy('code')->get();
        return view('maintenance-schedules.edit', compact('maintenanceSchedule', 'assets'));
    }

    /**
     * Update the specified maintenance schedule in storage.
     */
    public function update(Request $request, MaintenanceSchedule $maintenanceSchedule)
    {
        $validated = $request->validate([
            'frequency' => 'required|in:mensual,trimestral,semestral,anual',
            'is_active' => 'boolean',
            'notes' => 'nullable|string',
        ]);

        $maintenanceSchedule->update($validated);

        // Recalcular próxima fecha si cambió la frecuencia
        if ($maintenanceSchedule->wasChanged('frequency')) {
            $maintenanceSchedule->updateNextMaintenanceDate();
        }

        return redirect()->route('maintenance-schedules.show', $maintenanceSchedule)
            ->with('success', 'Programa de mantenimiento actualizado exitosamente.');
    }

    /**
     * Remove the specified maintenance schedule from storage.
     */
    public function destroy(MaintenanceSchedule $maintenanceSchedule)
    {
        $maintenanceSchedule->update(['is_active' => false]);

        return redirect()->route('maintenance-schedules.index')
            ->with('success', 'Programa de mantenimiento desactivado exitosamente.');
    }

    /**
     * Reactivate a maintenance schedule
     */
    public function reactivate(MaintenanceSchedule $maintenanceSchedule)
    {
        $maintenanceSchedule->update([
            'is_active' => true,
            'next_maintenance_date' => $maintenanceSchedule->calculateNextMaintenanceDate(),
        ]);

        return back()->with('success', 'Programa de mantenimiento reactivado exitosamente.');
    }
}
