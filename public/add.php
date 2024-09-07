<?php 
require_once '../vendor/autoload.php';
use Empleados\Controllers\EmpleadoController;
use Empleados\Models\Empleado;

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    try {
        // Validar y sanitizar entradas
        $name = filter_input(INPUT_POST, 'name', FILTER_SANITIZE_STRING);
        $phone = filter_input(INPUT_POST, 'phone', FILTER_SANITIZE_STRING);
        $email = filter_input(INPUT_POST, 'email', FILTER_SANITIZE_EMAIL);
        $weight = filter_input(INPUT_POST, 'weight', FILTER_VALIDATE_FLOAT);
        $height = filter_input(INPUT_POST, 'height', FILTER_VALIDATE_FLOAT);
        $hireDate = new \DateTime(filter_input(INPUT_POST, 'hireDate', FILTER_SANITIZE_STRING));

        if ($name === false || $phone === false || $email === false || $weight === false || $height === false) {
            throw new \Exception('Invalid input data');
        }

        // Crear instancia del controlador y del empleado
        $contactController = new EmpleadoController('../empleados.json');
        $empleado = new Empleado($name, $phone, $email, $weight, $height, $hireDate);

        // Añadir empleado y redireccionar
        $contactController->add($empleado);
        header('Location: index.php');
        exit; // Asegurarse de que no se ejecute código adicional
    } catch (\Exception $e) {
        echo 'Error: ' . htmlspecialchars($e->getMessage());
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Formulario</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-1s9qT6ldM4rWuj7J9WsJoOtPAWz6IMpGOSApmH8DOzghxz9mEkPxD7F6E63TxAA5" crossorigin="anonymous">
</head>
<body>
<div class="container mt-5">
    <h1 class="mb-4">Add New Employee</h1>
    <form method="POST">
        <div class="mb-3">
            <label for="name" class="form-label">Name</label>
            <input type="text" name="name" id="name" class="form-control" required>
        </div>
        <div class="mb-3">
            <label for="phone" class="form-label">Phone</label>
            <input type="text" name="phone" id="phone" class="form-control" required>
        </div>
        <div class="mb-3">
            <label for="email" class="form-label">Email</label>
            <input type="email" name="email" id="email" class="form-control" required>
        </div>
        <div class="mb-3">
            <label for="weight" class="form-label">Weight (kg)</label>
            <input type="number" step="0.1" name="weight" id="weight" class="form-control" required>
        </div>
        <div class="mb-3">
            <label for="height" class="form-label">Height (m)</label>
            <input type="number" step="0.01" name="height" id="height" class="form-control" required>
        </div>
        <div class="mb-3">
            <label for="hireDate" class="form-label">Hire Date</label>
            <input type="date" name="hireDate" id="hireDate" class="form-control" required>
        </div>
        <button type="submit" class="btn btn-primary">Add</button>
    </form>
</div>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
</body>
</html>
    