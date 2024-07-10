<?php

namespace Microservices\Food;

use Database\Database;
use Model\BaseModel;
use PDOException;

class Model extends BaseModel
{
    public function __construct(Database $database) {
        parent::__construct($database);
    }

    public function getAll() {
        return $this->db->query("SELECT * FROM comidas")->fetchAll(\PDO::FETCH_ASSOC);
    }

    public function getById($id) {
        $stmt = $this->db->prepare("SELECT * FROM comidas WHERE id = :id");
        $stmt->bindParam(':id', $id, \PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetch(\PDO::FETCH_ASSOC);
    }

    public function getByCategory($id)
    {
        $stmt = $this->db->prepare("SELECT * FROM comidas WHERE categoria_id=:id");
        $stmt->bindParam(':id', $id, \PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetchAll(\PDO::FETCH_ASSOC);
    }

    public function create(array $data): bool
    {
        try {
            $query = "INSERT INTO comidas (nombre, descripcion, precio, disponibilidad, categoria_id) VALUES (:nombre, :descripcion, :precio, :disponibilidad, :categoria_id)";
            $stmt = $this->db->prepare($query);

            $stmt->bindParam(':nombre', $data['nombre'], \PDO::PARAM_STR);
            $stmt->bindParam(':descripcion', $data['descripcion'], \PDO::PARAM_STR);
            $stmt->bindParam(':precio', $data['precio'], \PDO::PARAM_STR);
            $stmt->bindParam(':disponibilidad', $data['disponibilidad'], \PDO::PARAM_INT);
            $stmt->bindParam(':categoria_id', $data['categoria_id'], \PDO::PARAM_INT);

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
}
