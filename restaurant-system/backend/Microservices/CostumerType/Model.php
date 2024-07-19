<?php

namespace Microservices\CostumerType;

use Database\Database;
use Model\BaseModel;
use PDOException;

class Model extends BaseModel
{
    public function __construct(Database $database) {
        parent::__construct($database);
    }

    public function getAll() {
        return $this->db->query("SELECT * FROM tiposclientes")->fetchAll(\PDO::FETCH_ASSOC);
    }

    public function getById($id) {
        $stmt = $this->db->prepare("SELECT * FROM tiposclientes WHERE id = :id");
        $stmt->bindParam(':id', $id, \PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetch(\PDO::FETCH_ASSOC);
    }

    public function create(array $data): bool
    {
        try {
            $query = "INSERT INTO tiposclientes (Descripcion, MinCompras, MaxCompras) VALUES (:descripcion, :min, :max)";
            $stmt = $this->db->prepare($query);

            $stmt->bindParam(':descripcion', $data['descripcion'], \PDO::PARAM_STR);
            $stmt->bindParam(':min', $data['min'], \PDO::PARAM_STR);
            $stmt->bindParam(':max', $data['max'], \PDO::PARAM_STR);

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