<?php
session_start();
require_once "../classes/database.php";
require_once "../classes/movements.php";

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

$database = new Database();
$db = $database->connect();
$user_id = $_SESSION['user_id'];

$movements = new Movements($db);
$entradas = $movements->getEntradas($user_id);
$salidas = $movements->getSalidas($user_id);

$totalEntradas = 0;
$totalSalidas = 0;

foreach ($entradas as $entrada) {
    $totalEntradas += $entrada['amount'];
}

foreach ($salidas as $salida) {
    $totalSalidas += $salida['amount'];
}

$balance = $totalEntradas - $totalSalidas;
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Reporte de Balance</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/html2canvas/1.4.1/html2canvas.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf/2.5.1/jspdf.umd.min.js"></script>
</head>
<body class="flex flex-col items-center p-6">

    <h1 class="text-3xl font-bold mb-6">Reporte Mensual</h1>

    <div class="p-6 w-3/4">
        <h2 class="text-xl font-bold mb-4"><?php echo date('Y-m-01'); ?> / <?php echo date('Y-m-t'); ?></h2>
        <div class="grid grid-cols-2 gap-6">
            <!--entradas -->
            <div>
                <h3 class="text-lg font-bold">Entradas</h3>
                <table class="w-full border">
                    <thead>
                        <tr>
                            <th class="p-2 border">Tipo</th>
                            <th class="p-2 border">Monto</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($entradas as $entrada): ?>
                            <tr class="border-b">
                                <td class="p-2 border"><?php echo $entrada['type']; ?></td>
                                <td class="p-2 border">$<?php echo $entrada['amount']; ?></td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                    <tfoot>
                        <tr>
                            <td class="p-2 font-bold border">TOTAL</td>
                            <td class="p-2 font-bold border">$<?php echo $totalEntradas; ?></td>
                        </tr>
                    </tfoot>
                </table>
            </div>

            <!-- salidas -->
            <div>
                <h3 class="text-lg font-bold">Salidas</h3>
                <table class="w-full border">
                    <thead>
                        <tr>
                            <th class="p-2 border">Tipo</th>
                            <th class="p-2 border">Monto</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($salidas as $salida): ?>
                            <tr class="border-b">
                                <td class="p-2 border"><?php echo$salida['type']; ?></td>
                                <td class="p-2 border">$<?php echo $salida['amount']; ?></td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                    <tfoot>
                        <tr>
                            <td class="p-2 border font-bold">TOTAL</td>
                            <td class="p-2 border font-bold">$<?php echo $totalSalidas; ?></td>
                        </tr>
                    </tfoot>
                </table>
            </div>
        </div>

        <h3 class="text-lg font-bold mt-4">Balance Mensual: 
            <span>
                $<?php echo $balance; ?>
            </span>
        </h3>
    </div>

    <!--Grafico-->
    <div class="bg-white p-6 rounded-lg shadow-md w-3/4 mt-6">
        <h2 class="text-xl font-bold mb-4">Gráfico de Balance</h2>
        <canvas id="balanceChart"></canvas>
    </div>
    

    
    <button id="exportPDF" class="mt-6 bg-green-500 text-white px-4 py-2 ">Exportar a PDF</button>
    <a href="dashboard.php" class="mt-3 text-blue-500">Volver al Dashboard</a>

    <script>
        const ctx = document.getElementById("balanceChart").getContext("2d");
        new Chart(ctx, {
            type: 'doughnut',
            data: {
                labels: ["Entradas", "Salidas"],
                datasets: [{
                    data: [<?php echo $totalEntradas; ?>, <?php echo $totalSalidas; ?>],
                    backgroundColor: ["#0000ff", "#ff3333"],
                }]
            }
        });

        document.getElementById("exportPDF").addEventListener("click", function () {
        const { jsPDF } = window.jspdf;
        html2canvas(document.body, { scale: 2 }).then(canvas => {
            const pdf = new jsPDF("p", "mm", "a4");
            const imgData = canvas.toDataURL("image/png");
            const imgWidth = 210;
            const imgHeight = (canvas.height * imgWidth) / canvas.width;
            
            pdf.addImage(imgData, "PNG", 0, 0, imgWidth, imgHeight);
            pdf.save("Reporte_Balance.pdf");
        });
    });
    </script>

</body>
</html>
