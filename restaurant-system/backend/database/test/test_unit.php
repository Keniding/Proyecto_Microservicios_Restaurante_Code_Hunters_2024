<?php
require_once __DIR__ . '/../../vendor/autoload.php'; 

use Dotenv\Dotenv;
//use App\Database\Database;
require '../db.php';

// Ruta al archivo .env
$envPath = __DIR__ . '/../../';

// Cargar las variables de entorno desde el archivo .env
$dotenv = Dotenv::createImmutable($envPath);
$dotenv->load();

// Debugging: Verificar las variables de entorno
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
