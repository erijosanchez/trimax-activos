<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Códigos de Barras - Activos</title>
    <style>
        @page {
            margin: 0.5cm;
        }
        
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
        }
        
        .barcode-container {
            display: inline-block;
            width: 4.5cm;
            height: 2cm;
            border: 1px solid #ddd;
            margin: 0.15cm;
            padding: 0.15cm;
            text-align: center;
            vertical-align: top;
            page-break-inside: avoid;
            box-sizing: border-box;
        }
        
        .barcode-image {
            max-width: 100%;
            height: auto;
            margin: 2px 0;
        }
        
        .code-text {
            font-size: 10px;
            font-weight: bold;
            margin: 2px 0;
            letter-spacing: 0.3px;
        }
        
        .asset-info {
            font-size: 8px;
            color: #333;
            margin: 1px 0;
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
        }
        
        .category {
            font-size: 7px;
            color: #666;
            font-style: italic;
        }
        
        h1 {
            text-align: center;
            font-size: 16px;
            margin-bottom: 8px;
            color: #333;
        }
        
        .page-break {
            page-break-after: always;
        }
    </style>
</head>
<body>
    <h1>Códigos de Barras - Activos Trimax</h1>
    
    @php
        $generator = new \Picqer\Barcode\BarcodeGeneratorPNG();
    @endphp
    
    @foreach($assets as $index => $asset)
        <div class="barcode-container">
            <img src="data:image/png;base64,{{ base64_encode($generator->getBarcode($asset->code, $generator::TYPE_CODE_128, 1.5, 35)) }}" 
                 alt="{{ $asset->code }}" 
                 class="barcode-image">
            
            <div class="code-text">{{ $asset->code }}</div>
            <div class="asset-info">{{ $asset->brand }} - {{ $asset->model }}</div>
        </div>
        
        @if(($index + 1) % 16 == 0 && !$loop->last)
            <div class="page-break"></div>
        @endif
    @endforeach
</body>
</html>