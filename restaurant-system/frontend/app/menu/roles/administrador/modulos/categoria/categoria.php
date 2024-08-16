<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tabla de Categorías</title>
    <link rel="stylesheet" href="../../css/styles.css">
</head>
<body>
<div class="container">
    <h1>Lista de Categorías</h1>
    <div class="agregar">
        <label for="Name"></label><input id="categoryName" type="text" placeholder="Nombre de la categoría">
        <button id="addButton">Agregar Categoría</button>
    </div>
    <div class="tabla">
        <div id="pagination"></div>
        <table border="1">
            <thead>
            <tr>
                <th>ID</th>
                <th>Nombre</th>
                <th>Acciones</th>
            </tr>
            </thead>
            <tbody id="categoryTableBody">
            </tbody>
        </table>
    </div>
</div>
<div class="image-container">
    <img src="/assets/dist/images/tomato.png" alt="Tomato">
</div>
<script src="/assets/dist/categoria.bundle.js"></script>
</body>
</html>