<?php
require_once 'env.php';

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

//require_once __DIR__ . '/../vendor/autoload.php'; 

//use Dotenv\Dotenv;

//$envPath = __DIR__ . '/../';
echo 'Ruta del archivo .env: ' . $envPath . '.env<br>';

if (!file_exists($envPath . '.env')) {
    echo 'El archivo .env no existe en la ruta especificada<br>';
} else {
    echo 'El archivo .env existe en la ruta especificada<br>';
}

// putenv('DB_HOST=localhost');
// putenv('DB_NAME=restaurant_db');
// putenv('DB_USERNAME=root');
// putenv('DB_PASSWORD=');

// Cargar las variables de entorno
//$dotenv = Dotenv::createImmutable($envPath);
//$dotenv->load();
