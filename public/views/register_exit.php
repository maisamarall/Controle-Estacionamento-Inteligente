<?php
require __DIR__ . '/../../vendor/autoload.php';

use App\Application\Services\ParkingService;
use App\Infra\Repositories\ParkingRepositorySQLite;

$repo = new ParkingRepositorySQLite(__DIR__ . '/../src/Application/database/parking.db');
$service = new ParkingService($repo);

$message = "";

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $plate = $_POST['plate'];

    if ($service->registerLeave($plate)) {
        $message = "Saída registrada com sucesso!";
    } else {
        $message = "Veículo não encontrado.";
    }
}

require __DIR__ . '/header.php';
?>

<body class="bg-gray-100 p-8">

    <h1 class="text-2xl font-bold mb-6">Registrar Saída</h1>

    <?php if ($message): ?>
        <div class="bg-yellow-200 border border-yellow-700 p-3 rounded mb-4">
            <?= $message ?>
        </div>
    <?php endif; ?>

    <form method="POST" class="bg-white p-6 rounded shadow w-96">

        <label class="font-semibold">Placa:</label>
        <input type="text" name="plate" required class="w-full border p-2 rounded mb-3">

        <button class="bg-red-600 text-white px-4 py-2 rounded w-full">Registrar Saída</button>
    </form>

</body>

</html>