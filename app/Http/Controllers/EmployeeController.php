<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Employee;

class EmployeeController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $employees = Employee::latest()->paginate(15);
        return view('employees.index', compact('employees'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('employees.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'dni' => 'required|unique:employees',
            'first_name' => 'required|string|max:255',
            'last_name' => 'required|string|max:255',
            'email' => 'required|email|unique:employees',
            'phone' => 'nullable|string|max:20',
            'department' => 'nullable|string|max:255',
            'position' => 'nullable|string|max:255',
            'active' => 'required|boolean',
        ]);

        Employee::create($validated);

        return redirect()->route('employees.index')
            ->with('success', 'Empleado creado exitosamente');
    }

    /**
     * Display the specified resource.
     */
    public function show(Employee $employee)
    {
        $employee->load(['activeAssignments.asset', 'assignments']);
        return view('employees.show', compact('employee'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        return view('employees.edit', compact('employee'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Employee $employee)
    {
        $validated = $request->validate([
            'dni' => 'required|unique:employees,dni,' . $employee->id,
            'first_name' => 'required|string|max:255',
            'last_name' => 'required|string|max:255',
            'email' => 'required|email|unique:employees,email,' . $employee->id,
            'phone' => 'nullable|string|max:20',
            'department' => 'nullable|string|max:255',
            'position' => 'nullable|string|max:255',
            'active' => 'required|boolean',
        ]);

        $employee->update($validated);

        return redirect()->route('employees.show', $employee)
            ->with('success', 'Empleado actualizado exitosamente');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Employee $employee)
    {
        if ($employee->activeAssignments()->count() > 0) {
            return back()->with('error', 'No se puede eliminar un empleado con asignaciones activas');
        }

        $employee->delete();

        return redirect()->route('employees.index')
            ->with('success', 'Empleado eliminado exitosamente');
    }
}
