<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Asset;

class TestCodeGeneration extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'test:code-generation';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Prueba la generación automática de códigos de activos';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info('========================================');
        $this->info('  Test de Generación de Códigos');
        $this->info('========================================');
        $this->newLine();

        // Obtener el próximo código que se generaría
        $nextCode = Asset::getNextCode();
        $this->info("📋 Próximo código a generar: {$nextCode}");
        $this->newLine();

        // Mostrar estadísticas
        $totalAssets = Asset::count();
        $currentYear = date('Y');
        $assetsThisYear = Asset::where('code', 'like', "GMSAC{$currentYear}%")->count();

        $this->info("📊 Estadísticas:");
        $this->line("   - Total de activos: {$totalAssets}");
        $this->line("   - Activos creados en {$currentYear}: {$assetsThisYear}");
        $this->newLine();

        // Simular generación de códigos
        $this->info("🔮 Simulación de próximos 5 códigos:");
        
        for ($i = 0; $i < 5; $i++) {
            $simulatedNumber = $assetsThisYear + $i + 1;
            $simulatedCode = 'GMSAC' . $currentYear . str_pad($simulatedNumber, 5, '0', STR_PAD_LEFT);
            $this->line("   " . ($i+1) . ". {$simulatedCode}");
        }

        $this->newLine();
        $this->info('✅ Test completado exitosamente!');
        $this->info('========================================');

        return 0;
    }
}
