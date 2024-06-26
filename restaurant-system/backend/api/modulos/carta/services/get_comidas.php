<?php
require_once '../../../../database/db.php';

header('Content-Type: application/json');

$db = new Database();
$conn = $db->getConnection();

$sql = "SELECT c.id AS categoria_id, c.nombre AS categoria_nombre, 
               f.id AS comida_id, f.nombre AS comida_nombre, 
               f.descripcion, f.precio 
        FROM categorias c
        LEFT JOIN comidas f ON c.id = f.categoria_id";

$result = $conn->query($sql);

$comidasPorCategoria = [];

while ($row = $result->fetch()) {
    $categoriaId = $row['categoria_id'];
    $categoriaNombre = $row['categoria_nombre'];
    
    if (!isset($comidasPorCategoria[$categoriaId])) {
        $comidasPorCategoria[$categoriaId] = [
            'categoria_nombre' => $categoriaNombre,
            'comidas' => []
        ];
    }
    
    $comidasPorCategoria[$categoriaId]['comidas'][] = [
        'id' => $row['comida_id'],
        'nombre' => $row['comida_nombre'],
        'descripcion' => $row['descripcion'],
        'precio' => $row['precio']
    ];
}

echo json_encode(array_values($comidasPorCategoria));
?>
