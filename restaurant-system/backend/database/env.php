<?php
require_once __DIR__ . '/../vendor/autoload.php'; 

use Dotenv\Dotenv;

$envPath = __DIR__ . '/../';

$dotenv = Dotenv::createImmutable($envPath);
$dotenv->load();

