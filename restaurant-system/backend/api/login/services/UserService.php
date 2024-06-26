<?php
require_once '../../../database/db.php';
require '../../../interface/iUserService.php';

class UserService implements iUserService{
    private $conn;

    public function __construct() {
        $database = new Database();
        $this->conn = $database->getConnection();
    }

    public function getUserByUsername($username) {
        $sql = "SELECT u.dni, u.username, u.password, r.nombre as rol 
                FROM users u
                JOIN roles r ON u.rol_id = r.id
                WHERE u.username = :username";
        $stmt = $this->conn->prepare($sql);
        $stmt->bindParam(':username', $username);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function getUserById($dni) {
        $sql = "SELECT u.dni, u.username, u.password, r.nombre as rol 
                FROM users u
                JOIN roles r ON u.rol_id = r.id
                WHERE u.dni = :dni";
        $stmt = $this->conn->prepare($sql);
        $stmt->bindParam(':dni', $dni);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function __destruct() {
        $this->conn = null;
    }
}
?>
