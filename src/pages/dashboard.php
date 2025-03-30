<?php
session_start();
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}


require_once "../classes/database.php";
require_once "../classes/movements.php";

$database = new Database();
$db = $database->connect();

$user_id = $_SESSION['user_id']; // get id del usuario en sesión
$user_name = $_SESSION['user_name']; // get name del usuario en sesión

$movements = new Movements($db);
$entradas = $movements->getEntradas($user_id);
$salidas = $movements->getSalidas($user_id);

$totalEntradas = 0;
$totalSalidas = 0;

foreach ($entradas as $entrada) {
    $totalEntradas = $totalEntradas + $entrada["amount"];
}

foreach ($salidas as $salida) {
    $totalSalidas = $totalSalidas + $salida["amount"];
}

$balance = $totalEntradas - $totalSalidas;

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard</title>
    <link href="../output.css" rel="stylesheet">
</head>
<body>
    <div class="flex flex-col items-center justify-center h-screen">
        <h1 class="text-3xl font-bold">Bienvenido, <?php echo $user_name; ?></h1>
        <p class="mt-2 text-black">Dashboard de gastos</p>

        <div class="mt-5 grid grid-cols-3 gap-5">
            <div class="p-5 bg-green-500 text-white rounded shadow">
                <h2 class="text-xl font-bold">Saldo</h2>
                <p class="text-2xl">$<?php echo $balance; ?></p>
            </div>
            <div class="p-5 bg-blue-500 text-white rounded shadow">
                <h2 class="text-xl font-bold">Total Entradas</h2>
                <p class="text-2xl">$<?php echo $totalEntradas; ?></p>
            </div>
            <div class="p-5 bg-red-500 text-white rounded shadow">
                <h2 class="text-xl font-bold">Total Salidas</h2>
                <p class="text-2xl">$<?php echo $totalSalidas; ?></p>
            </div>
        </div>

        <div class="mt-5">
            <a href="income.php" class="bg-blue-700 text-white p-3 rounded">Registrar Entrada</a>
            <a href="expense.php" class="bg-blue-700 text-white p-3 rounded">Registrar Salida</a>
            <a href="movementsReport.php" class="bg-blue-700 text-white p-3 rounded">Ver Entradas y Salidas</a>
            <a href="balance.php" class="bg-blue-700 text-white p-3 rounded">Ver Balance</a>
            <a href="closeSession.php" class="bg-red-700 text-white p-3 rounded">Cerrar sesion</a>
        </div>
    </div>
</body>
</html>
