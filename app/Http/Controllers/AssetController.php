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
        $rules = [
            'category_id' => 'required|exists:categories,id',
            'code' => 'required|unique:assets',
            'brand' => 'required|string|max:255',
            'model' => 'required|string|max:255',
            'serial_number' => 'nullable|unique:assets|string|max:255',
            'purchase_date' => 'nullable|date',
            'purchase_price' => 'nullable|numeric|min:0',
            'status' => 'required|in:available,assigned,maintenance,damaged,retired',
            'observations' => 'nullable|string',
        ];

        // Obtener categoría para validaciones condicionales
        $category = Category::find($request->category_id);
        $categoryName = strtolower($category->name);

        // Validaciones específicas según categoría
        switch ($categoryName) {
            case 'celular':
                $rules['imei'] = 'required|unique:assets|string|max:15';
                $rules['imei_2'] = 'nullable|unique:assets|string|max:15';
                $rules['phone'] = 'required|unique:assets|string|max:20';
                $rules['operator_name'] = 'required|string|max:255';
                break;

            case 'pc':
            case 'laptop':
                $rules['processor'] = 'required|string|max:255';
                $rules['ram'] = 'required|string|max:50';
                $rules['storage'] = 'required|string|max:50';
                $rules['storage_type'] = 'required|string|max:50';
                $rules['graphics_card'] = 'nullable|string|max:255';
                $rules['operating_system'] = 'required|string|max:100';
                if ($categoryName === 'laptop') {
                    $rules['screen_size'] = 'required|string|max:50';
                }
                break;

            case 'monitor':
                $rules['screen_size_monitor'] = 'required|string|max:50';
                $rules['resolution'] = 'required|string|max:50';
                $rules['panel_type'] = 'nullable|string|max:50';
                $rules['refresh_rate'] = 'nullable|string|max:50';
                break;

            case 'mouse':
            case 'teclado':
                $rules['connection_type'] = 'required|string|max:50';
                $rules['is_wireless'] = 'required|boolean';
                break;

            case 'audífonos':
                $rules['connection_type'] = 'required|string|max:50';
                $rules['is_wireless'] = 'required|boolean';
                $rules['audio_type'] = 'required|string|max:50';
                $rules['has_microphone'] = 'required|boolean';
                break;
        }

        $validated = $request->validate($rules);

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
        $rules = [
            'category_id' => 'required|exists:categories,id',
            'code' => 'required|unique:assets,code,' . $asset->id,
            'brand' => 'required|string|max:255',
            'model' => 'required|string|max:255',
            'serial_number' => 'nullable|unique:assets,serial_number,' . $asset->id,
            'purchase_date' => 'nullable|date',
            'purchase_price' => 'nullable|numeric|min:0',
            'status' => 'required|in:available,assigned,maintenance,damaged,retired',
            'observations' => 'nullable|string',
        ];

        // Obtener categoría para validaciones condicionales
        $category = Category::find($request->category_id);
        $categoryName = strtolower($category->name);

        // Validaciones específicas según categoría
        switch ($categoryName) {
            case 'celular':
                $rules['imei'] = 'required|unique:assets,imei,' . $asset->id . '|string|max:15';
                $rules['imei_2'] = 'nullable|unique:assets,imei_2,' . $asset->id . '|string|max:15';
                $rules['phone'] = 'required|unique:assets,phone,' . $asset->id . '|string|max:20';
                $rules['operator_name'] = 'required|string|max:255';
                break;

            case 'pc':
            case 'laptop':
                $rules['processor'] = 'required|string|max:255';
                $rules['ram'] = 'required|string|max:50';
                $rules['storage'] = 'required|string|max:50';
                $rules['storage_type'] = 'required|string|max:50';
                $rules['graphics_card'] = 'nullable|string|max:255';
                $rules['operating_system'] = 'required|string|max:100';
                if ($categoryName === 'laptop') {
                    $rules['screen_size'] = 'required|string|max:50';
                }
                break;

            case 'monitor':
                $rules['screen_size_monitor'] = 'required|string|max:50';
                $rules['resolution'] = 'required|string|max:50';
                $rules['panel_type'] = 'nullable|string|max:50';
                $rules['refresh_rate'] = 'nullable|string|max:50';
                break;

            case 'mouse':
            case 'teclado':
                $rules['connection_type'] = 'required|string|max:50';
                $rules['is_wireless'] = 'required|boolean';
                break;

            case 'audífonos':
                $rules['connection_type'] = 'required|string|max:50';
                $rules['is_wireless'] = 'required|boolean';
                $rules['audio_type'] = 'required|string|max:50';
                $rules['has_microphone'] = 'required|boolean';
                break;
        }

        $validated = $request->validate($rules);

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
