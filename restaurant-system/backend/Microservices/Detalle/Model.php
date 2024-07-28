<?php

namespace Microservices\Detalle;

use Database\Database;
use Model\BaseModel;
use PDOException;

class Model extends BaseModel
{
    public function __construct(Database $database) {
        parent::__construct($database);
    }

    public function getAll() {
        return $this->db->query("SELECT * FROM ordenes")->fetchAll(\PDO::FETCH_ASSOC);
    }

    public function getById($id) {
        $stmt = $this->db->prepare("SELECT * FROM ordenes WHERE id  = :id");
        $stmt->bindParam(':id', $id, \PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetch(\PDO::FETCH_ASSOC);
    }

    public function getByCategory($id)
    {
        $stmt = $this->db->prepare("SELECT * FROM ordenes WHERE FacturaID=:id");
        $stmt->bindParam(':id', $id, \PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetchAll(\PDO::FETCH_ASSOC);
    }

    public function getByFood($id)
    {
        $stmt = $this->db->prepare("SELECT * FROM ordenes WHERE ComidaID=:id");
        $stmt->bindParam(':id', $id, \PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetchAll(\PDO::FETCH_ASSOC);
    }

    public function create(array $data): int
    {
        try {
            $this->db->beginTransaction();

            $query = "INSERT INTO ordenes (FacturaID, ComidaID, Cantidad, Precio) VALUES (:factura, :comida, :cantidad, :precio)";
            $stmt = $this->db->prepare($query);

            $stmt->bindParam(':factura', $data['factura'], \PDO::PARAM_STR);
            $stmt->bindParam(':comida', $data['comida'], \PDO::PARAM_INT);
            $stmt->bindParam(':cantidad', $data['cantidad'], \PDO::PARAM_INT);
            $stmt->bindParam(':precio', $data['precio'], \PDO::PARAM_STR);

            if ($stmt->execute()) {
                $newDetalleId = (int)$this->db->lastInsertId();
                $this->db->commit();
                return $newDetalleId;
            } else {
                $this->db->rollBack();
                return false;
            }
        } catch(PDOException $exception) {
            $this->db->rollBack();
            echo "Error: " . $exception->getMessage() . "<br>";
            return false;
        }
    }

    public function delete($id): bool
    {
        // TODO: Implement delete() method.
    }
}
