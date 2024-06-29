<?php
use Conexion\Database;

require '../vendor/autoload.php';
require 'Database.php';

echo 'DB_HOST: ' . $_ENV['DB_HOST'] . '<br>';
echo 'DB_NAME: ' . $_ENV['DB_NAME'] . '<br>';
echo 'DB_USERNAME: ' . $_ENV['DB_USERNAME'] . '<br>';
echo 'DB_PASSWORD: ' . $_ENV['DB_PASSWORD'] . '<br>';

$db = new Database();
$conn = $db->getConnection();

if ($conn) {
    echo "Conexión exitosa a la base de datos.<br>";
} else {
    echo "Error al conectar a la base de datos.<br>";
}
?>
