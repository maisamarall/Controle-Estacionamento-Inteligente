<?php
require __DIR__ . '/../vendor/autoload.php';

use App\Application\Services\ParkingService;
use App\Infra\Repositories\ParkingRepository;

$service = new ParkingService(new ParkingRepository());
$data = $service->listAll();
$summary = $service->summaryByType();

?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Relatório Diário</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>

<body class="bg-gray-100">

<div class="min-h-screen flex items-center justify-center px-4 py-10">
    <div class="bg-white shadow-xl rounded-2xl p-8 max-w-5xl w-full">

        <h1 class="text-2xl font-bold text-gray-800 text-center mb-6">
            Relatório Diário de Movimentação
        </h1>

        <div class="overflow-x-auto">
            <table class="min-w-full border border-gray-200 rounded-lg overflow-hidden">
                <thead>
                    <tr class="bg-blue-600 text-white">
                        <th class="px-4 py-3 text-left">ID</th>
                        <th class="px-4 py-3 text-left">Placa</th>
                        <th class="px-4 py-3 text-left">Tipo</th>
                        <th class="px-4 py-3 text-left">Entrada</th>
                        <th class="px-4 py-3 text-left">Saída</th>
                    </tr>
                </thead>

                <tbody>
                    <?php foreach ($data as $v): ?>
                        <tr class="border-b hover:bg-gray-50 <?= ($v->leaveTime ? '' : 'bg-yellow-50') ?>">
                            <td class="px-4 py-3"><?= $v->id ?></td>
                            <td class="px-4 py-3"><?= $v->plate ?></td>
                            <td class="px-4 py-3 capitalize"><?= $v->type ?></td>
                            <td class="px-4 py-3">
                                <?= $v->entryTime?->format('d/m/Y H:i') ?>
                            </td>
                            <td class="px-4 py-3">
                                <?= $v->leaveTime?->format('d/m/Y H:i') ?? '<span class="text-red-600 font-semibold">Ainda no pátio</span>' ?>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>

        <div class="text-center mt-6">
            <a href="index.php" class="text-blue-600 hover:text-blue-800 font-medium">
                ← Voltar
            </a>
        </div>

    </div>
</div>

</body>
</html>
