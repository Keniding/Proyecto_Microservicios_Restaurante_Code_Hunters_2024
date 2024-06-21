<?php
require_once '../db/register.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $dni = $_POST['dni'];
    $username = $_POST['username'];
    $password = $_POST['password'];
    $email = $_POST['email'];
    $telefono = $_POST['telefono'];
    $rol = $_POST['rol'];

    $database = new Database();
    $register = new Register($database);

    $register->registerUser($dni, $username, $password, $email, $telefono, $rol);

    header('Location: ../../../../frontend/app/login/login.php');
    exit();
}
?>
