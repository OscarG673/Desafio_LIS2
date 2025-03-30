<?php
session_start();
require_once "../classes/database.php";
require_once "../classes/user.php";

$database = new Database();
$db = $database->connect();

$usuario = new User($db);

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $usuario->email = $_POST["email"];
    $usuario->password = $_POST["password"];

    if ($usuario->login()) {
        header("Location: dashboard.php"); //dirigir al dasboard
        exit();
    } else {
        $error = "Error, verifique sus credenciales";
    }
}

?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>
    <link href="../output.css" rel="stylesheet">
</head>
<body class="bg-blue-800 flex items-center justify-center h-screen">
    <div class="bg-white p-8 rounded-lg shadow-lg w-96">
        <h2 class="text-2xl font-bold mb-4 text-center">Login</h2>
        <?php if (isset($error)): ?>
            <div class="text-red-600"><?php echo $error; ?></div>
        <?php endif; ?>
        <form action="login.php" method="POST">
            <div class="mb-4">
                <label class="block">Email</label>
                <input type="email" name="email" id="email" class="w-full p-2 border" required>
            </div>
            <div class="mb-4">
                <label class="block">Contraseña</label>
                <input type="password" name="password" id="password" class="w-full p-2 border" required>
            </div>
            <button type="submit" class="w-full bg-yellow-400 text-white py-2 hover:bg-yellow-500">Ingresar</button>
        </form>
        <div class="pt-2 !important">No tienes una cuenta? <a class="text-blue-600 underline" href="signup.php">Ir a registro</a></div>
    </div>
</body>
</html>
