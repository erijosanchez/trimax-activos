<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Asset;
use App\Models\Category;
use App\Models\Assignment;

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
            'code' => 'nullable|unique:assets',
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

        return redirect()->route('asset.index')
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

        return redirect()->route('asset.show', $asset)
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

        return redirect()->route('asset.index')
            ->with('success', 'Activo eliminado exitosamente');
    }

    /**
     * Generar código de barras para un activo
     */
    public function generateBarcode(Asset $asset)
    {
        // Generar código de barras usando una librería
        $generator = new \Picqer\Barcode\BarcodeGeneratorPNG();
        $barcode = $generator->getBarcode($asset->code, $generator::TYPE_CODE_128);

        return response($barcode)
            ->header('Content-Type', 'image/png');
    }

    /**
     * Descargar código de barras con formato para imprimir
     */
    public function downloadBarcode(Asset $asset)
    {
        $generator = new \Picqer\Barcode\BarcodeGeneratorPNG();
        $barcode = $generator->getBarcode($asset->code, $generator::TYPE_CODE_128, 3, 100);

        // Crear una imagen con el código de barras y el texto
        $barcodeImage = imagecreatefromstring($barcode);
        $width = imagesx($barcodeImage);
        $height = imagesy($barcodeImage);

        // Crear imagen con espacio para texto
        $finalHeight = $height + 60;
        $finalImage = imagecreatetruecolor($width + 40, $finalHeight);

        // Fondo blanco
        $white = imagecolorallocate($finalImage, 255, 255, 255);
        $black = imagecolorallocate($finalImage, 0, 0, 0);
        imagefill($finalImage, 0, 0, $white);

        // Copiar código de barras
        imagecopy($finalImage, $barcodeImage, 20, 10, 0, 0, $width, $height);

        // Agregar texto del código
        $font = 5;
        $textWidth = imagefontwidth($font) * strlen($asset->code);
        $x = (($width + 40) - $textWidth) / 2;
        imagestring($finalImage, $font, $x, $height + 20, $asset->code, $black);

        // Agregar información adicional
        $info = $asset->brand . ' - ' . $asset->model;
        $infoWidth = imagefontwidth($font) * strlen($info);
        $infoX = (($width + 40) - $infoWidth) / 2;
        imagestring($finalImage, $font, $infoX, $height + 40, $info, $black);

        // Generar imagen
        ob_start();
        imagepng($finalImage);
        $imageData = ob_get_clean();

        imagedestroy($barcodeImage);
        imagedestroy($finalImage);

        return response($imageData)
            ->header('Content-Type', 'image/png')
            ->header('Content-Disposition', 'attachment; filename="barcode-' . $asset->code . '.png"');
    }

    /**
     * Descargar múltiples códigos de barras en PDF
     */
    public function downloadBarcodesPDF(Request $request)
    {
        $assetIds = $request->input('asset_ids', []);

        if (empty($assetIds)) {
            return back()->with('error', 'Seleccione al menos un activo');
        }

        $assets = Asset::whereIn('id', $assetIds)->get();

        $pdf = \PDF::loadView('assets.barcodes-pdf', compact('assets'));

        return $pdf->download('codigos-barras-' . date('Y-m-d') . '.pdf');
    }

    /**
     * Obtener el siguiente código que se generaría
     */
    public function getNextCode()
    {
        return response()->json([
            'code' => Asset::getNextCode()
        ]);
    }
}
