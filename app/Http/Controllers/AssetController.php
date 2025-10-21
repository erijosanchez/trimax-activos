<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Asset;
use App\Models\Category;

class AssetController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $assets = Asset::with(['category', 'currentAssignment.employee'])
            ->latest()
            ->paginate(15);

        return view('assets.index', compact('assets'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $categories = Category::all();
        return view('assets.create', compact('categories'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'category_id' => 'required|exists:categories,id',
            'code' => 'required|unique:assets',
            'brand' => 'required|string|max:255',
            'model' => 'required|string|max:255',
            'serial_number' => 'nullable|unique:assets|string|max:255',
            'specifications' => 'nullable|string',
            'purchase_date' => 'nullable|date',
            'purchase_price' => 'nullable|numeric|min:0',
            'status' => 'required|in:available,assigned,maintenance,damaged,retired',
            'observations' => 'nullable|string',
        ]);

        Asset::create($validated);

        return redirect()->route('assets.index')
            ->with('success', 'Activo creado exitosamente');
    }

    /**
     * Display the specified resource.
     */
    public function show(Asset $asset)
    {
        $asset->load(['category', 'assignmentHistory.employee', 'assignmentHistory.responsibilityDocument']);

        return view('assets.show', compact('asset'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Asset $asset)
    {
        $categories = Category::all();
        return view('assets.edit', compact('asset', 'categories'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Asset $asset)
    {
        $validated = $request->validate([
            'category_id' => 'required|exists:categories,id',
            'code' => 'required|unique:assets,code,' . $asset->id,
            'brand' => 'required|string|max:255',
            'model' => 'required|string|max:255',
            'serial_number' => 'nullable|unique:assets,serial_number,' . $asset->id,
            'specifications' => 'nullable|string',
            'purchase_date' => 'nullable|date',
            'purchase_price' => 'nullable|numeric|min:0',
            'status' => 'required|in:available,assigned,maintenance,damaged,retired',
            'observations' => 'nullable|string',
        ]);

        $asset->update($validated);

        return redirect()->route('assets.show', $asset)
            ->with('success', 'Activo actualizado exitosamente');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Asset $asset)
    {
        if ($asset->currentAssignment) {
            return back()->with('error', 'No se puede eliminar un activo que está asignado');
        }

        $asset->delete();

        return redirect()->route('assets.index')
            ->with('success', 'Activo eliminado exitosamente');
    }
}
