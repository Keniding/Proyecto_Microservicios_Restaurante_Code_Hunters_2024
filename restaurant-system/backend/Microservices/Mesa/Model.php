<?php

namespace Microservices\Mesa;

use Database\Database;
use Model\BaseModel;
use PDOException;

class Model extends BaseModel
{
    public function __construct(Database $database) {
        parent::__construct($database);
    }

    public function getAll() {
        return $this->db->query("SELECT * FROM mesas")->fetchAll(\PDO::FETCH_ASSOC);
    }

    public function getById($id) {
        $stmt = $this->db->prepare("SELECT * FROM mesas WHERE id_mesa = :id");
        $stmt->bindParam(':id', $id, \PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetch(\PDO::FETCH_ASSOC);
    }

    public function getByCategory($id)
    {
        $stmt = $this->db->prepare("SELECT * FROM mesas WHERE id_estado_mesa=:id");
        $stmt->bindParam(':id', $id, \PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetchAll(\PDO::FETCH_ASSOC);
    }

    public function create(array $data): bool
    {
        try {
            $query = "INSERT INTO mesas (capacidad, id_estado_mesa) 
            VALUES (:capacidad, :estado)";
            $stmt = $this->db->prepare($query);

            $stmt->bindParam(':capacidad', $data['capacidad'], \PDO::PARAM_INT);
            $stmt->bindParam(':estado', $data['estado'], \PDO::PARAM_INT);

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

    public function delete($id): bool
    {
        // TODO: Implement delete() method.
        return false;
    }
}
