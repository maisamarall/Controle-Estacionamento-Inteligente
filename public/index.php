<!DOCTYPE html>
<html lang="pt-BR">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Estacionamento Inteligente</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>

<body class="bg-gray-100">

    <div class="min-h-screen flex items-center justify-center px-4">
        <div class="bg-white shadow-xl rounded-2xl p-8 max-w-md w-full">

            <h1 class="text-3xl font-bold text-center text-gray-800 mb-6">
                🚗 Estacionamento Inteligente
            </h1>

            <p class="text-center text-gray-600 mb-8">
                Escolha uma opção para continuar
            </p>

            <ul class="space-y-4">
                <li>
                    <a href="register_entry.php"
                        class="block w-full text-center bg-blue-600 hover:bg-blue-700 text-white py-3 rounded-xl font-semibold transition">
                        Registrar Entrada
                    </a>
                </li>

                <li>
                    <a href="register_exit.php"
                        class="block w-full text-center bg-green-600 hover:bg-green-700 text-white py-3 rounded-xl font-semibold transition">
                        Registrar Saída
                    </a>
                </li>

                <li>
                    <a href="report.php"
                        class="block w-full text-center bg-gray-700 hover:bg-gray-900 text-white py-3 rounded-xl font-semibold transition">
                        Relatório Diário
                    </a>
                </li>
            </ul>

        </div>
    </div>

</body>

</html>

<script>
document.getElementById("vehicleType").addEventListener("change", function() {
    const type = this.value;

    fetch("list_by_type.php?type=" + type)
        .then(res => res.json())
        .then(data => {
            const plateSelect = document.getElementById("plateSelect");
            plateSelect.innerHTML = "<option value=''>Selecione...</option>";

            data.forEach(v => {
                plateSelect.innerHTML += `<option value="${v.plate}">${v.plate}</option>`;
            });
        });
});
</script>