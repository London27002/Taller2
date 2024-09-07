<?php
include_once '../src/Controllers/EmpleadoController.php';
include_once '../src/Models/Empleado.php'; // Asegúrate de incluir el modelo de empleado

use Empleados\Controllers\EmpleadoController;
use Empleados\Models\Empleado;

// Instanciar el controlador y leer los datos
$empleadosController = new EmpleadoController('../empleados.json');
$empleados = $empleadosController->readJsonFile();
?>

<!doctype html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Lista Empleados</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
</head>
<body>
    <div class="container mt-4">
        <table class="table table-bordered table-striped">
            <thead>
                <tr>
                    <th scope="col">#</th>
                    <th scope="col">Name</th>
                    <th scope="col">Phone</th>
                    <th scope="col">Email</th>
                    <th scope="col">Weight</th>
                    <th scope="col">Height</th>
                    <th scope="col">IMC</th>
                    <th scope="col">Hire Date</th>
                    <th scope="col">Time in Company</th>
                
                </tr>
            </thead>
            <tbody>
            <?php
foreach ($empleados as $index => $empleadoData) {
    // Crear instancia del empleado para usar los métodos de cálculo
    $empleado = new Empleado(
        $empleadoData['name'],
        $empleadoData['phone'],
        $empleadoData['email'],
        $empleadoData['weight'],
        $empleadoData['height'],
        new DateTime($empleadoData['hire_date'])
    );

    echo "<tr>";
    echo "<th scope='row'>" . ($index + 1) . "</th>";
    echo "<td>" . htmlspecialchars($empleado->getName()) . "</td>";
    echo "<td>" . htmlspecialchars($empleado->getPhone()) . "</td>";
    echo "<td>" . htmlspecialchars($empleado->getEmail()) . "</td>";
    echo "<td>" . htmlspecialchars($empleado->getWeight()) . "</td>";
    echo "<td>" . htmlspecialchars($empleado->getHeight()) . "</td>";

    // Mostrar IMC
    echo "<td>" . number_format($empleado->calculateIMC(), 4) . "</td>";

    // Verificar y formatear la fecha
    $hireDate = $empleado->getHireDate()->format('Y-m-d');
    echo "<td>" . htmlspecialchars($hireDate) . "</td>";

    // Mostrar tiempo en la empresa
    echo "<td>" . htmlspecialchars($empleado->timeInCompany()) . "</td>";



    echo "</tr>";
}
?>
            </tbody>
        </table>

        <a href="add.php" class="btn btn-success">Create new Employee</a>
       
        <a href="view_pdf.php" class="btn btn-primary" target="_blank">Contenido</a>

        
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
</body>
</html>
