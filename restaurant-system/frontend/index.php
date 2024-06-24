<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="css/index.css">
    <link rel="icon" href="assets/icons/restaurant.png" type="image/x-icon"> 
    <title>Sistema de Gestión de Restaurante</title>
</head>
<body>
    <header>
        <nav>
            <ul>
                <li><a href="#">Inicio</a></li>
                <li><a href="#">Sobre Nosotros</a></li>
                <li><a href="#">Servicios</a></li>
                <li><a href="#">Contacto</a></li>
                <li><a href="app/login/login.php">Login</a></li>
            </ul>
        </nav>
    </header>

    <div class="container">
        <div class="hero features">
            <h1 class="hero-text feature"><img src="assets/icons/tomato.png" alt="Tomato Mascot" class="mascot">Bienvenido a Tomato</h1>
            <div class="feature">
                <p>El sistema de gestión interna para tu restaurante.</p>
                <a href="app/login/login.php" class="btn-primary">Login</a>
                <a href="app/register/registro.php" class="btn-secondary">Registrar</a>
            </div>
        </div>

        <section class="features">
            <div class="feature">
                <h2>Fácil de Usar</h2>
                <p>Interfaz intuitiva y fácil de navegar.</p>
            </div>
            <div class="feature">
                <h2>Gestión Eficiente</h2>
                <p>Optimiza la administración de tu restaurante.</p>
            </div>
            <div class="feature">
                <h2>Soporte 24/7</h2>
                <p>Estamos aquí para ayudarte en cualquier momento.</p>
            </div>
        </section>
    </div>
    
    <?php include('includes/footer.php'); ?>
</body>
</html>