<?php

namespace Microservices\User;

use Database\Database;
use Model\BaseModel;
use PDOException;

class Model extends BaseModel
{
    public function __construct(Database $database) {
        parent::__construct($database);
    }

    public function getAll() {
        return $this->db->query("SELECT * FROM users")->fetchAll(\PDO::FETCH_ASSOC);
    }

    public function getById($id) {
        $stmt = $this->db->prepare("SELECT * FROM users WHERE id = :id");
        $stmt->bindParam(':id', $id, \PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetch(\PDO::FETCH_ASSOC);
    }

    public function getByDni($id) {
        $stmt = $this->db->prepare("SELECT * FROM users WHERE dni = :id");
        $stmt->bindParam(':id', $id, \PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetch(\PDO::FETCH_ASSOC);
    }

    public function create(array $data): bool
    {
        try {
            $query = "INSERT INTO users (dni, username, password, email, telefono, rol_id, estado) VALUES (:dni, :username, :password, :email, :telefono, :rol_id, :estado)";
            $stmt = $this->db->prepare($query);

            $hashed_password = password_hash($data['password'], PASSWORD_DEFAULT);
            $estado = 0;

            $stmt->bindParam(':dni', $data['dni'], \PDO::PARAM_STR);
            $stmt->bindParam(':username', $data['username'], \PDO::PARAM_STR);
            $stmt->bindParam(':email', $data['email'], \PDO::PARAM_STR);
            $stmt->bindParam(':telefono', $data['telefono'], \PDO::PARAM_STR);
            $stmt->bindParam(':password', $hashed_password, \PDO::PARAM_STR);
            $stmt->bindParam(':rol_id', $data['rol'], \PDO::PARAM_INT);
            $stmt->bindParam(':estado', $estado, \PDO::PARAM_INT);

            if ($stmt->execute()) {
                return true;
            } else {
                return false;
            }
        } catch(PDOException $exception) {
            echo "Error: " . $exception->getMessage() . "<br>";
            return false;
        }
    }

    public function getByCategory($id)
    {
        $stmt = $this->db->prepare("SELECT * FROM users WHERE rol_id = :id");
        $stmt->bindParam(':id', $id, \PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetchAll(\PDO::FETCH_ASSOC);
    }

    public function delete($id): bool
    {
        // TODO: Implement delete() method.
    }
}
