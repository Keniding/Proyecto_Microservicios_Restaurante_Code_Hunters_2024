<?php

namespace Microservices\Factura;

use Database\Database;
use Model\BaseModel;
use PDOException;

class Model extends BaseModel
{
    public function __construct(Database $database) {
        parent::__construct($database);
    }

    public function getAll() {
        return $this->db->query("SELECT * FROM facturas")->fetchAll(\PDO::FETCH_ASSOC);
    }

    public function getById($id) {
        $stmt = $this->db->prepare("SELECT * FROM facturas WHERE id = :id");
        $stmt->bindParam(':id', $id, \PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetch(\PDO::FETCH_ASSOC);
    }

    public function create(array $data): bool
    {
        try {
            $query = "INSERT INTO facturas (id, Fecha, Total) VALUES (:id, :fecha, :total)";
            $stmt = $this->db->prepare($query);

            $fecha_creacion = date('Y-m-d H:i:s');

            $stmt->bindParam(':id', $data['id'], \PDO::PARAM_STR);
            $stmt->bindParam(':fecha', $fecha_creacion, \PDO::PARAM_STR);
            $stmt->bindParam(':total', $data['total'], \PDO::PARAM_STR);

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
}