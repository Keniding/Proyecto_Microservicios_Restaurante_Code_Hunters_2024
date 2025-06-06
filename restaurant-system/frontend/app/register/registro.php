<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="css/registro.css">
    <title>Registro de Trabajadores</title>
</head>
<body>
<div class="container">
    <div class="login-container">
        <div class="avatar">
            <img src="https://img.icons8.com/ios-filled/50/ffffff/user-male-circle.png" alt="Avatar">
        </div>
        <h2>Formulario de Registro</h2>
        <form id="registerForm">
            <input type="text" id="dni" name="dni" placeholder="DNI" required>
            <input type="text" id="username" name="username" placeholder="Nombre de usuario" required>
            <input type="password" id="password" name="password" placeholder="Contraseña" required>
            <input type="email" id="email" name="email" placeholder="Correo electrónico" required>
            <input type="text" id="telefono" name="telefono" placeholder="Teléfono">
            <label for="rol">Seleccione un Rol:</label>
            <select name="rol" id="rol" required>
                <option value="">Elegir rol</option>
            </select>
            <button type="submit" class="login-btn">Registrarse</button>
        </form>
        <a class="register-btn" href="../login/login.php">Iniciar Sesión</a>
    </div>
    <div class="image-container">
        <img src="../../assets/icons/tomato.png" alt="Tomato Mascot" class="mascot">
    </div>
</div>

<script src="/assets/dist/getroles.bundle.js"></script>
<script src="/assets/dist/register.bundle.js"></script>
</body>
</html>
