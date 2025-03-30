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
$movements = new Movements($db);
$user_id = $_SESSION["user_id"];

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

if (isset($_POST["submit"])) {
    $entrada = new Movements($db, "entrada");

    $entrada->type = $_POST["type"];
    $entrada->amount = $_POST["amount"];
    $entrada->date = $_POST["date"];
    $entrada->user_id = $_SESSION["user_id"]; //tomar el usuario desde la variable de sesión

    $target_dir = "../uploads/";
    $target_file = $target_dir . basename($_FILES["bill"]["name"]);
    $uploadOk = 1;
    $imageFileType = strtolower(pathinfo($target_file, PATHINFO_EXTENSION));

    //verificar si es imagen
    $check = getimagesize($_FILES["bill"]["tmp_name"]);
    if ($check !== false) {
        echo "El archivo es una imagen - " . $check["mime"] . ".";
        $uploadOk = 1;
    } else {
        echo "El archivo no es una imagen.";
        $uploadOk = 0;
    }

    $allowed_types = ['jpg', 'jpeg', 'png'];
    if (!in_array($imageFileType, $allowed_types)) {
        echo "Solo se permiten archivos JPG, JPEG y PNG.";
        $uploadOk = 0;
    }

    if ($uploadOk == 1) {
        if (move_uploaded_file($_FILES["bill"]["tmp_name"], $target_file)) {
            echo "El archivo " . htmlspecialchars(basename($_FILES["bill"]["name"])) . " se ha subido correctamente.";
            $entrada->bill = basename($_FILES["bill"]["name"]);
        } else {
            echo "Hubo un error al subir la imagen";
            $uploadOk = 0;
        }
    }

    if ($uploadOk == 1 && $entrada->registrar()) {
        header("Location: dashboard.php");
        exit();
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Registrar Entrada</title>
    <link href="../output.css" rel="stylesheet">
</head>
<body class="bg-gray-100 flex flex-col items-center justify-center h-screen">
    <h1 class="text-2xl font-bold mb-5">Registrar Entrada</h1>
    <div class="bg-white p-4 rounded shadow-md mb-4">
        <p class="text-lg font-semibold">Saldo Actual: <span class="text-green-600">$<?php echo $balance; ?></span></p>
    </div>
    <form method="POST" enctype="multipart/form-data" class="bg-white p-6 rounded shadow-md w-96">
        <label class="block mb-2">Tipo:</label>
        <select name="type" class="w-full p-2 border">
            <option value="salario">Salario</option>
            <option value="bono">Bono</option>
            <option value="regalo">Regalo</option>
            <option value="regalo">Otro</option>
        </select>
        <label class="block mt-3">Monto:</label>
        <input type="number" step="0.01" name="amount" class="w-full p-2 border" required>
        <label class="block mt-3">Fecha:</label>
        <input type="date" name="date" class="w-full p-2 border rounded" required>
        <label class="block mt-3">Factura (Solo imágenes JPG, JPEG, PNG):</label>
        <input type="file" name="bill" accept=".jpg, .jpeg, .png" class="w-full p-2 border">
        <button type="submit" name="submit" class="mt-4 bg-blue-500 cursor-pointer text-white p-2 w-full">Registrar</button>
    </form>

    <a href="dashboard.php" class="mt-3 text-blue-500">Volver al Dashboard</a>
</body>
</html>
