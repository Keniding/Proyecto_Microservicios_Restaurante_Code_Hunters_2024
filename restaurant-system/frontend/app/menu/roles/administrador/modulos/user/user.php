<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gestión de Usuarios</title>
    <link rel="stylesheet" href="../../css/styles.css">
    <link rel="stylesheet" href="styles.css">
</head>
<body>
<div class="container">
    <h1>Lista de Usuarios</h1>
    <!--
    <div class="agregar">
        <div>
            <label for="dni">DNI:</label>
            <input class="campos" id="dni" type="text" placeholder="DNI" required>
        </div>
        <div>
            <label for="username">Nombre de Usuario:</label>
            <input class="campos" id="username" type="text" placeholder="Nombre de Usuario" required>
        </div>
        <div>
            <label for="password">Contraseña:</label>
            <input class="campos" id="password" type="password" placeholder="Contraseña" required>
        </div>
        <div>
            <label for="email">Email:</label>
            <input class="campos" id="email" type="email" placeholder="Email" required>
        </div>
        <div>
            <label for="telefono">Teléfono:</label>
            <input class="campos" id="telefono" type="tel" placeholder="Teléfono" required>
        </div>
        <div>
            <label for="rol_id">Rol:</label>
            <select class="campos" id="rol_id" required>
                <option class="campos">Seleccione rol</option>
            </select>
        </div>
        <div>
            <label for="estado">Estado:</label>
            <select class="campos" id="estado" required>
                <option class="campos" value="1">Activo</option>
                <option class="campos" value="0">Inactivo</option>
            </select>
        </div>
        <button id="addButton">Agregar Usuario</button>
    </div>
    -->
    <div class="tabla">
        <div id="pagination"></div>
        <table border="1">
            <thead>
            <tr>
                <th>DNI</th>
                <th>Nombre de Usuario</th>
                <th>Email</th>
                <th>Teléfono</th>
                <th>Rol</th>
                <th>Estado</th>
                <th>Acciones</th>
            </tr>
            </thead>
            <tbody id="userTableBody">
            </tbody>
        </table>
    </div>
</div>

<script src="/assets/dist/usuarioCRUD.bundle.js"></script>
<div class="image-container">
    <img src="/assets/dist/images/tomato.png" alt="Tomato">
</div>
</body>
</html>