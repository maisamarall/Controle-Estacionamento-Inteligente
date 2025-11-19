<?php
require __DIR__ . '/../../vendor/autoload.php';

use App\Application\Services\ReportService;
use App\Infra\Repositories\ParkingRepositorySQLite;

$repo = new ParkingRepositorySQLite(__DIR__ . '/../src/Application/database/parking.db');
$service = new ReportService($repo);

$report = $service->generateDailyReport();

require __DIR__ . '/header.php';
?>

<body class="bg-gray-100 p-8">

<h1 class="text-2xl font-bold mb-6">Relatório Diário</h1>

<table class="w-full bg-white shadow rounded">
    <tr class="bg-gray-200 font-semibold">
        <th class="p-3">ID</th>
        <th class="p-3">Placa</th>
        <th class="p-3">Tipo</th>
        <th class="p-3">Entrada</th>
    </tr>

    <?php foreach ($report as $row): ?>
    <tr class="border-t">
        <td class="p-3"><?= $row['id'] ?></td>
        <td class="p-3"><?= $row['plate'] ?></td>
        <td class="p-3"><?= $row['type'] ?></td>
        <td class="p-3"><?= $row['entry_time'] ?></td>
    </tr>
    <?php endforeach; ?>
</table>

</body>
</html>
