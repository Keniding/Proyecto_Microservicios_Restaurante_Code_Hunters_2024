<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="css/styles.css">
    <link rel="icon" href="assets/icons/restaurant.png" type="image/x-icon"> <!-- Ruta al favicon -->
    <title>Sistema de Gestión de Restaurante</title>
</head>
<body>
    <?php include('views/header.php'); ?>
    
    <div class="container  view-container">
        <h1>Bienvenido al Sistema de Gestión de Restaurante</h1>
        <div class="card-container">
            <a href="views/menu.php" class="card">Gestionar Menú</a>
            <a href="views/orders.php" class="card">Gestionar Pedidos</a>
            <a href="views/reservations.php" class="card">Gestionar Reservas</a>
            <a href="views/employees.php" class="card">Gestionar Empleados</a>
            <a href="views/reports.php" class="card">Ver Reportes</a>
        </div>
    </div>
    
    <?php include('views/footer.php'); ?>
</body>
</html>
