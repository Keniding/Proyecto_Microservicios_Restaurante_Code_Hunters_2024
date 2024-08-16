<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tabla de Categorías</title>
    <link rel="stylesheet" href="../../css/styles.css">
    <link rel="stylesheet" href="styles.css">
</head>
<body>
<div class="container">
    <h1>Lista de Platos de Comida</h1>
    <div class="agregar">
        <div>
            <label for="foodName"></label><input class="campos" id="foodName" type="text" placeholder="Nombre de la comida" required>
        </div>
        <div>
            <label for="foodDescription"></label><textarea class="campos" id="foodDescription" placeholder="Descripción de la comida" required></textarea>
        </div>
        <div>
            <label for="foodPrice"></label><input class="campos" id="foodPrice" type="number" placeholder="Precio" step="0.01" required>
        </div>
        <div>
            <label for="foodAvailability"></label><select class="campos" id="foodAvailability" required>
                <option class="campos" value="1">Disponible</option>
                <option class="campos" value="0">No Disponible</option>
            </select>
        </div>
        <div>
            <label for="categoryFood"></label><select class="campos" id="categoryFood" required>
                <option class="campos">Seleccione categoria</option>
            </select>
        </div>
        <button id="addButton">Agregar Categoría</button>
    </div>
    <div class="tabla">
        <div id="pagination"></div>
        <table border="1">
            <thead>
            <tr>
                <th>ID</th>
                <th>Nombre</th>
                <th>Descripción</th>
                <th>Precio</th>
                <th>Disponibilidad</th>
                <th>Acciones</th>
            </tr>
            </thead>
            <tbody id="foodTableBody">
            </tbody>
        </table>
    </div>
</div>
<div class="image-container">
    <img src="/assets/dist/images/tomato.png" alt="Tomato">
</div>
<script src="/assets/dist/comidaCRUD.bundle.js"></script>
</body>
</html>