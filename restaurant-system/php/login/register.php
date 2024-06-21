<?php
require_once '../connection/db.php';

class Register {
    private $conn;

    public function __construct($db) {
        $this->conn = $db->getConnection();
    }

    public function registerUser($dni, $username, $password, $email, $telefono, $rol) {
        try {
            $query = "INSERT INTO users (dni, username, password, email, telefono, rol) VALUES (:dni, :username, :password, :email, :telefono, :rol)";
            $stmt = $this->conn->prepare($query);

            // Encriptar la contraseña
            $hashed_password = password_hash($password, PASSWORD_DEFAULT);

            // Vincular los parámetros
            $stmt->bindParam(':dni', $dni);
            $stmt->bindParam(':username', $username);
            $stmt->bindParam(':password', $hashed_password);
            $stmt->bindParam(':email', $email);
            $stmt->bindParam(':telefono', $telefono);
            $stmt->bindParam(':rol', $rol);

            if ($stmt->execute()) {
                echo "Usuario registrado exitosamente.<br>";
            } else {
                echo "Error al registrar el usuario.<br>";
            }
        } catch(PDOException $exception) {
            echo "Error: " . $exception->getMessage() . "<br>";
        }
    }
}
?>

