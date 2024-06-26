 <?php
session_start();
require_once '../services/UserService.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = filter_input(INPUT_POST, 'username', FILTER_SANITIZE_FULL_SPECIAL_CHARS);
    $password = $_POST['password'];

    if (empty($username) || empty($password)) {
        echo "Por favor, complete todos los campos.";
        exit();
    }

    $userService = new UserService();
    $result = $userService->getUserByUsername($username);

    if ($result && password_verify($password, $result['password'])) {
        $_SESSION['username'] = $result['username'];
        $_SESSION['role'] = $result['rol'];

        if ($result['rol'] == 'Mesero') {
            header('Location: ../../../../frontend/app/menu/roles/mesero/menu_mesero.php');
        } else {
            header('Location: ../../../../frontend/app/menu/menu.php');
        }
        exit();
    } else {
        echo "Usuario o contraseña incorrectos";
    }
}
?>
