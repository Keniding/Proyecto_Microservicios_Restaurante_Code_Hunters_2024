<?php
require '../vendor/autoload.php';
require 'Dbase.php';
require '../includes/helpers.php';

use Dotenv\Dotenv;

// Ruta al archivo .env
$envPath = __DIR__ . '/../';

// Cargar las variables de entorno desde el archivo .env
$dotenv = Dotenv::createImmutable($envPath);
$dotenv->load();

// Debugging: Verificar las variables de entorno
echo 'DB_HOST: ' . $_ENV['DB_HOST'] . '<br>';
echo 'DB_NAME: ' . $_ENV['DB_NAME'] . '<br>';
echo 'DB_USERNAME: ' . $_ENV['DB_USERNAME'] . '<br>';
echo 'DB_PASSWORD: ' . $_ENV['DB_PASSWORD'] . '<br>';

$db = new DatabaseConnection;
$conn = $db->getConnection();

if ($conn) {
    echo "Conexión exitosa a la base de datos.<br>";
} else {
    echo "Error al conectar a la base de datos.<br>";
}

loadView('backend', 'auth.login');
?>
