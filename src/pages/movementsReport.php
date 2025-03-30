<?php
session_start();
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}
require_once "../classes/database.php";
require_once "../classes/movements.php";

// Verificar si el usuario está autenticado
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

// Conectar a la base de datos
$database = new Database();
$db = $database->connect();
$user_id = $_SESSION['user_id'];

// Instanciar Movements y obtener datos
$movements = new Movements($db);
$entradas = $movements->getEntradas($user_id);
$salidas = $movements->getSalidas($user_id);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Reporte de Movimientos</title>
    <link href="../output.css" rel="stylesheet">
</head>
<body class="flex flex-col items-center p-6">

    <h1 class="text-3xl font-bold mb-6">Reporte de Movimientos</h1>

    <div class="flex gap-6">
        <!--Entradas -->
        <div class="p-6 w-96">
            <h2 class="text-xl font-bold  mb-4">Entradas</h2>
            <table class="w-full border-collapse">
                <thead>
                    <tr>
                        <th class="p-2 border">Fecha</th>
                        <th class="p-2 border">Tipo</th>
                        <th class="p-2 border">Monto</th>
                        <th class="p-2 border">Factura</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($entradas as $entrada): ?>
                        <tr class="border-b">
                            <td class="p-2 border"><?php echo htmlspecialchars($entrada['date']); ?></td>
                            <td class="p-2 border"><?php echo htmlspecialchars($entrada['type']); ?></td>
                            <td class="p-2 border">$<?php echo $entrada['amount']; ?></td>
                            <td class="p-2 border text-center">
                                <?php if (!empty($entrada["bill"])): ?>
                                    <img src="../uploads/<?php echo htmlspecialchars($entrada["bill"]); ?>" 
                                         class="w-16 h-16 object-cover cursor-pointer rounded border" 
                                         onclick="showImage('../uploads/<?php echo htmlspecialchars($entrada['bill']); ?>')">
                                <?php else: ?>
                                    <span class="text-red-400">No disponible</span>
                                <?php endif; ?>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>

        <!--Salidas -->
        <div class="p-6 w-96">
            <h2 class="text-xl font-bold mb-4">Salidas</h2>
            <table class="w-full border-collapse">
                <thead>
                    <tr>
                        <th class="p-2 border">Fecha</th>
                        <th class="p-2 border">Tipo</th>
                        <th class="p-2 border">Monto</th>
                        <th class="p-2 border">Factura</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($salidas as $salida): ?>
                        <tr class="border-b">
                            <td class="p-2 border"><?php echo htmlspecialchars($salida['date']); ?></td>
                            <td class="p-2 border"><?php echo htmlspecialchars($salida['type']); ?></td>
                            <td class="p-2 border">$<?php echo $salida['amount']; ?></td>
                            <td class="p-2 border text-center">
                                <?php if (!empty($salida['bill'])): ?>
                                    <img src="../uploads/<?php echo htmlspecialchars($salida['bill']); ?>" 
                                         class="w-16 h-16 object-cover cursor-pointer rounded border" 
                                         onclick="showImage('../uploads/<?php echo htmlspecialchars($salida['bill']); ?>')">
                                <?php else: ?>
                                    <span class="text-gray-400">No disponible</span>
                                <?php endif; ?>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>
    <a href="dashboard.php" class="mt-3 text-blue-500">Volver al Dashboard</a>

    <!--mostrar la imagen en grande -->
    <div id="imageModal" class="fixed inset-0 bg-black bg-opacity-75 flex justify-center items-center hidden">
        <div class="relative">
            <img id="modalImage" class="max-w-full max-h-[90vh] rounded-lg">
            <button onclick="closeImage()" class="absolute top-2 right-2 bg-white text-black px-3 py-1 rounded-full">✖</button>
        </div>
    </div>

    <script>
        function showImage(src) {
            document.getElementById("modalImage").src = src;
            document.getElementById("imageModal").classList.remove("hidden");
        }

        function closeImage() {
            document.getElementById("imageModal").classList.add("hidden");
        }
    </script>

</body>
</html>
