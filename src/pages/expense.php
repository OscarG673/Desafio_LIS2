<?php
session_start();
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

require_once "../classes/database.php";
require_once "../classes/movements.php";

// Conectar a la base de datos
$database = new Database();
$db = $database->connect();
$user_id = $_SESSION["user_id"];

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

$error = ""; 

// Verificar si se envió el formulario
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $salida = new Movements($db, "salida"); //enviar salida como argumento

    $salida->type = $_POST["type"];
    $salida->amount = floatval($_POST["amount"]); // Convertir a número
    $salida->date = $_POST["date"];
    $salida->user_id = $_SESSION["user_id"];

    $amount = $salida->amount;

    if ($amount < 0) {
        $error = "No puede ingresar un numero negativo.";
    } else {
        if ($amount > $balance) {
            $error = "No tiene saldo suficiente.";
        } else {
            //Manejo de la imagen de la factura
            if (isset($_FILES["bill"]) && $_FILES["bill"]["error"] == 0) {
                $target_dir = "../uploads/";
                $target_file = $target_dir . basename($_FILES["bill"]["name"]);
                $imageFileType = strtolower(pathinfo($target_file, PATHINFO_EXTENSION));
    
                // Solo permitir ciertos formatos
                $allowed_types = ['jpg', 'jpeg', 'png'];
                if (in_array($imageFileType, $allowed_types) && move_uploaded_file($_FILES["bill"]["tmp_name"], $target_file)) {
                    $salida->bill = basename($_FILES["bill"]["name"]); // Guardar el nombre del archivo
                } else {
                    $error = "Error al subir la imagen.";
                }
            }
    
            // Si no hay errores, registrar la salida
            if (empty($error) && $salida->registrar()) {
                header("Location: dashboard.php?success=salida");
                exit();
            }
        }
    }

}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Registrar Salida</title>
    <link href="../output.css" rel="stylesheet">
</head>
<body class="bg-gray-100 flex flex-col items-center justify-center h-screen">
    <h1 class="text-2xl font-bold mb-5">Registrar Salida</h1>

    <div class="bg-white p-4 rounded shadow-md mb-4">
        <p class="text-lg font-semibold">Saldo Actual: <span class="text-green-600">$<?php echo $balance; ?></span></p>
    </div>

    <?php if (!empty($error)): ?>
        <p class="text-red-500 font-semibold"><?php echo $error; ?></p>
    <?php endif; ?>

    <form method="POST" enctype="multipart/form-data" class="bg-white p-6 shadow-md w-96">
        <label class="block mb-2">Tipo:</label>
        <select name="type" class="w-full p-2 border ">
            <option value="comida">Comida</option>
            <option value="servicios">Servicios</option>
            <option value="transporte">Transporte</option>
            <option value="otros">Otros</option>
        </select>

        <label class="block mt-3">Monto:</label>
        <input type="number" name="amount" class="w-full p-2 border" required>

        <label class="block mt-3">Fecha:</label>
        <input type="date" name="date" class="w-full p-2 border" required>

        <label class="block mt-3">Factura (Solo imágenes JPG, JPEG, PNG):</label>
        <input type="file" name="bill" accept=".jpg, .jpeg, .png" class="w-full p-2 border">

        <button type="submit" name="submit" class="cursor-pointer mt-4 bg-red-500 text-white p-2 cur w-full">Registrar</button>
    </form>

    <a href="dashboard.php" class="mt-3 text-blue-500">Volver al Dashboard</a>
</body>
</html>
