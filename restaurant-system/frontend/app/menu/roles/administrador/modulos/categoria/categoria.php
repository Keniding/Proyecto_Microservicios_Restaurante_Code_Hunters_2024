<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tabla de Categorías</title>
</head>
<body>
<h1>Lista de Categorías</h1>
<input id="categoryName" type="text" placeholder="Nombre de la categoría">
<button id="addButton">Agregar Categoría</button>
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

<script src="/assets/dist/categoria.bundle.js"></script>
</body>
</html>