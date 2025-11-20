<?php
require __DIR__ . '/../vendor/autoload.php';

use App\Application\Services\ParkingService;
use App\Application\Services\ReportService;
use App\Infra\Repositories\ParkingRepository;
use App\Infra\Repositories\ParkingRecordFileRepository;
use App\Infra\Repositories\VehicleRepository;


$parkingRepo = new ParkingRepository();
$recordRepo = new ParkingRecordFileRepository();
$vehicleRepo = new VehicleRepository();

$parkingService = new ParkingService($parkingRepo, $recordRepo); 
$reportService = new ReportService($recordRepo, $vehicleRepo); 

$data = $parkingService->listAll(); 
$summary = $reportService->generateReportByType();
$totalRevenue = $summary['total']['revenue'] ?? 0.0;

unset($summary['total']);

$totalEntries = count($data);
$totalExits = 0;
foreach ($data as $v) {
    if ($v->leaveTime !== null) {
        $totalExits++;
    }
}
$vehiclesInParking = $totalEntries - $totalExits;


$vehicleTypes = ['carro', 'moto', 'caminhao'];

?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Relatório Diário</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>

<body class="bg-gray-100 font-sans">

<div class="min-h-screen flex items-start justify-center px-4 py-10">
    <div class="bg-white shadow-2xl rounded-2xl p-8 max-w-6xl w-full">

        <h1 class="text-3xl font-extrabold text-gray-800 text-center mb-10 border-b pb-4">
            Relatório Diário de Movimentação
        </h1>

        <h2 class="text-2xl font-bold text-gray-800 mb-4 border-t pt-6">Histórico de Registros Detalhado</h2>

        <div class="overflow-x-auto">
            <table class="min-w-full border border-gray-200 rounded-lg overflow-hidden">
                <thead>
                    <tr class="bg-blue-600 text-white">
                        <th class="px-4 py-3 text-left">Placa</th>
                        <th class="px-4 py-3 text-left">Tipo</th>
                        <th class="px-4 py-3 text-left">Entrada</th>
                        <th class="px-4 py-3 text-left">Saída</th>
                        <th class="px-4 py-3 text-left">Faturamento (R$)</th>
                    </tr>
                </thead>

                <tbody>
                    <?php foreach ($data as $v): ?>
                        <tr class="border-b hover:bg-gray-50 <?= ($v->leaveTime ? '' : 'bg-yellow-100 font-semibold') ?>">
                            <td class="px-4 py-3 font-semibold"><?= $v->plate ?></td>
                            <td class="px-4 py-3 capitalize"><?= $v->type ?></td>
                            <td class="px-4 py-3">
                                <?= $v->entryTime?->format('d/m/Y H:i') ?>
                            </td>
                            <td class="px-4 py-3">
                                <?= $v->leaveTime?->format('d/m/Y H:i') ?? '<span class="text-red-600 font-semibold">Ainda no pátio</span>' ?>
                            </td>
                            <td class="px-4 py-3 text-left font-bold text-green-700">
                                R$ <?= number_format($v->price ?? 0.0, 2, ',', '.') ?>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                    
                    <?php if (empty($data)): ?>
                        <tr class="text-center">
                            <td colspan="5" class="p-4 text-gray-500">Nenhum registro de movimentação encontrado.</td>
                        </tr>
                    <?php endif; ?>
                </tbody>

                <tfoot class="border-2 border-blue-600">
                    <tr class="font-extrabold text-lg text-gray-900 bg-blue-100 border-b-2 border-gray-600">
                        <td class="px-4 py-3" colspan="2">Total Geral</td>
                        <td class="px-4 py-3 text-left border-l border-r border-blue-200">
                            <?= $totalEntries ?> Entradas
                        </td>
                        <td class="px-4 py-3 text-left border-r border-blue-200">
                            <?= $totalExits ?> Saídas
                        </td>
                        <td class="px-4 py-3 text-left text-green-700">
                            R$ <?= number_format($totalRevenue, 2, ',', '.') ?>
                        </td>
                    </tr>

        
                    <tr class="font-bold text-md text-gray-900 bg-gray-100 border-b-2 border-gray-600">
                        <td class="px-4 py-2 font-bold text-gray-800" colspan="5">Resumo de Faturamento por Tipo de Veículo:</td>
                    </tr>
                    
                    <?php foreach ($vehicleTypes as $type): ?>
                        <?php 
                            $revenue = $summary[$type]['revenue'] ?? 0.0;
                            $count = $summary[$type]['count'] ?? 0;
                        ?>
                        <tr class="font-semibold text-sm hover:bg-gray-50">
                            <td class="px-4 py-2 capitalize text-gray-600" colspan="4">
                                &nbsp;&nbsp;&nbsp;Faturamento de **<?= $type ?>** (<?= $count ?> Movimentações)
                            </td>
                            
                            <td class="px-4 py-2 text-left font-semibold text-indigo-700">
                                R$ <?= number_format($revenue, 2, ',', '.') ?>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tfoot>
            </table>
        </div>

        <div class="text-center mt-8 pt-4 border-t">
            <a href="index.php" class="text-blue-600 hover:text-blue-800 font-medium transition duration-150">
                ← Voltar para o Painel Principal
            </a>
        </div>
    </div>
</div>

</body>
</html>