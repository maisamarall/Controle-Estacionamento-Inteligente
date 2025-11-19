<?php
require __DIR__ . '/../vendor/autoload.php';

use App\Application\Services\ParkingService;
use App\Infra\Repositories\ParkingRepositorySQLite;

$repo = new ParkingRepositorySQLite(__DIR__ . '/../src/Application/database/parking.db');
$service = new ParkingService($repo);

$message = "";

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $plate = $_POST['plate'];
    $type = $_POST['type'];

    if ($service->registerEntry($plate, $type)) {
        $message = "Entrada registrada com sucesso!";
    } else {
        $message = "Erro ao registrar entrada.";
    }
}

require __DIR__ . '/header.php';
?>

<body class="bg-gray-100 p-8">

    <h1 class="text-2xl font-bold mb-6">Registrar Entrada</h1>

    <?php if ($message): ?>
        <div class="bg-green-200 border border-green-700 p-3 rounded mb-4">
            <?= $message ?>
        </div>
    <?php endif; ?>

    <form method="POST" class="bg-white p-6 rounded shadow w-96">

        <label class="font-semibold">Placa:</label>
        <input type="text" name="plate" required class="w-full border p-2 rounded mb-3">

        <label class="font-semibold">Tipo:</label>
        <select name="type" class="w-full border p-2 rounded mb-3">
            <option value="car">Carro</option>
            <option value="motorcycle">Moto</option>
            <option value="truck">Caminhão</option>
        </select>

        <button class="bg-blue-600 text-white px-4 py-2 rounded w-full">Registrar</button>
    </form>

</body>

</html>