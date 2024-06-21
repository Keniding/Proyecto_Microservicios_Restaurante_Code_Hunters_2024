<!DOCTYPE html>
<html>
<head>
    <title>Registro de Trabajadores</title>
</head>
<body>
    <h2>Formulario de Registro</h2>
    <form action="../../../backend/api/register/controller/register_user.php" method="post">
        <label for="dni">DNI:</label><br>
        <input type="text" id="dni" name="dni" required><br>
        <label for="username">Nombre de usuario:</label><br>
        <input type="text" id="username" name="username" required><br>
        <label for="password">Contraseña:</label><br>
        <input type="password" id="password" name="password" required><br>
        <label for="email">Correo electrónico:</label><br>
        <input type="email" id="email" name="email" required><br>
        <label for="telefono">Teléfono:</label><br>
        <input type="text" id="telefono" name="telefono"><br>
        <label for="rol">Rol:</label><br>
        <input type="text" id="rol" name="rol" required><br><br>
        <input type="submit" value="Registrarse">
    </form>
</body>
</html>
