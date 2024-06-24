<?php
require_once '../db/register.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $dni = filter_input(INPUT_POST, 'dni', FILTER_SANITIZE_FULL_SPECIAL_CHARS);
    $username = filter_input(INPUT_POST, 'username', FILTER_SANITIZE_FULL_SPECIAL_CHARS);
    $password = filter_input(INPUT_POST, 'password', FILTER_SANITIZE_FULL_SPECIAL_CHARS);
    $email = filter_input(INPUT_POST, 'email', FILTER_VALIDATE_EMAIL);
    $telefono = filter_input(INPUT_POST, 'telefono', FILTER_SANITIZE_FULL_SPECIAL_CHARS);
    $rol = filter_input(INPUT_POST, 'rol', FILTER_SANITIZE_FULL_SPECIAL_CHARS);

    if (!$dni || !$username || !$password || !$email || !$telefono || !$rol) {
        echo "<h1>Error: Todos los campos son obligatorios.</h1>";
        exit();
    }

    $database = new Database();
    $register = new Register($database);

    $result = $register->registerUser($dni, $username, $password, $email, $telefono, $rol);

    if ($result === false) {
        echo "<h1>Error al registrar el usuario.</h1>";
    } else {
        header('Location: ../../../../frontend/app/login/login.php');
        exit();
    }
} else {
    echo "<h1>Solicitud no válida.</h1>";
}
?>
