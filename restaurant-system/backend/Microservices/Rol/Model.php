<?php

namespace Microservices\Rol;

use Database\Database;
use Model\BaseModel;
use PDOException;

class Model extends BaseModel
{
    public function __construct(Database $database) {
        parent::__construct($database);
    }

    public function getAll() {
        return $this->db->query("SELECT * FROM roles")->fetchAll(\PDO::FETCH_ASSOC);
    }

    public function getById($id) {
        $stmt = $this->db->prepare("SELECT * FROM roles WHERE id = :id");
        $stmt->bindParam(':id', $id, \PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetch(\PDO::FETCH_ASSOC);
    }

    public function create(array $data): bool
    {
        try {
            $query = "INSERT INTO roles (nombre, descripcion, fecha_creacion) VALUES (:nombre, :descripcion, :fecha_creacion)";
            $stmt = $this->db->prepare($query);

            $fecha_creacion = date('Y-m-d H:i:s');

            $stmt->bindParam(':nombre', $data['nombre'], \PDO::PARAM_STR);
            $stmt->bindParam(':descripcion', $data['descripcion'], \PDO::PARAM_STR);
            $stmt->bindParam(':fecha_creacion', $fecha_creacion, \PDO::PARAM_STR);

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
        // TODO: Implement getByCategory() method.
    }

    public function delete($id): bool
    {
        // TODO: Implement delete() method.
    }
}