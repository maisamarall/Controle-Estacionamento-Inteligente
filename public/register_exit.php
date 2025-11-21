<?php
require __DIR__ . '/../vendor/autoload.php';

use App\Application\Services\ParkingService;
use App\Infra\Repositories\ParkingRepository;

$repository = new ParkingRepository();
$service = new ParkingService($repository);

$message = null;

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $ok = $service->registerExit($_POST['plate']);

    if ($ok) {
        $message = "Saída registrada com sucesso!";
    } else {
        $message = "Veículo não encontrado.";
    }
}
?>

<!DOCTYPE html>
<html lang="pt-BR">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Registrar Saída</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>

<body class="bg-gray-100">

    <div class="min-h-screen flex items-center justify-center px-4">
        <div class="bg-white shadow-xl rounded-2xl p-8 max-w-md w-full">

            <h1 class="text-2xl font-bold text-gray-800 text-center mb-6">
                Registrar Saída
            </h1>

            <?php if ($message): ?>
                <div class="mb-4 text-center p-3 rounded-lg 
                <?php echo ($ok ? 'bg-green-100 text-green-700' : 'bg-red-100 text-red-700'); ?>">
                    <?= $message ?>
                </div>
            <?php endif; ?>

            <form method="POST" class="space-y-5">

                <div>
                    <label class="block font-semibold">Tipo de veículo:</label>
                    <select id="vehicleType" name="type" class="border p-2 rounded w-full" required>
                        <option value="">Selecione...</option>
                        <option value="carro">Carro</option>
                        <option value="moto">Moto</option>
                        <option value="caminhao">Caminhão</option>
                    </select>
                </div>

                <div>
                    <label class="block text-gray-700 font-medium mb-1">Placa</label>
                    <input type="text" name="plate" required
                        class="w-full px-4 py-2 border rounded-lg focus:ring-2 focus:ring-blue-500 outline-none">
                </div>

                <button type="submit"
                    class="w-full bg-red-600 hover:bg-red-700 text-white font-semibold py-3 rounded-lg transition">
                    Registrar Saída
                </button>

            </form>

            <div class="text-center mt-6">
                <a href="index.php" class="text-blue-600 hover:text-blue-800 font-medium">
                    ← Voltar para o início
                </a>
            </div>

        </div>
    </div>

</body>

</html>