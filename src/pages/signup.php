<?php
session_start();
include_once '../classes/database.php';
include_once '../classes/user.php';

$database = new Database();
$db = $database->connect();
$user = new User($db);

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $user->fullname = $_POST["fullname"];
    $user->email = $_POST["email"];
    $user->password = $_POST["password"];

    if ($user->create()) {
        header("Location: dashboard.php");//dirigir al dashboard
        exit();
    } else {
        $error = "Hubo un problema al registrar usuario.";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Registro</title>
    <link href="../output.css" rel="stylesheet">
</head>
<body class="bg-blue-800 flex items-center justify-center h-screen">
    <div class="bg-white p-8 rounded-lg shadow-lg w-96">
        <h2 class="text-2xl font-bold mb-4 text-center">Registro</h2>
        <?php if (isset($error)): ?>
            <div class="alert alert-danger"><?php echo $error; ?></div>
        <?php endif; ?>
        <form action="signup.php" method="POST">
            <div class="mb-4">
                <label class="block text-black">Nombre</label>
                <input type="text" name="fullname" id="fullname" class="w-full p-2 border" required>
            </div>
            <div class="mb-4">
                <label class="block text-black">Email</label>
                <input type="email" name="email" id="email" class="w-full p-2 border" required>
            </div>
            <div class="mb-4">
                <label class="block text-black">Contraseña</label>
                <input type="password" name="password" id="password" class="w-full p-2 border" required>
            </div>
            <button type="submit" class="w-full bg-yellow-400 text-white py-2 hover:bg-yellow-500">Ingresar</button>
        </form>
        <div class="pt-2 !important">Ya tienes una cuenta? <a class="text-blue-600 underline" href="login.php">Ir a login</a></div>
    </div>
</body>
</html>
