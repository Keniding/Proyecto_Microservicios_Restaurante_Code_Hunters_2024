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
        return $this->db->query("SELECT * FROM detalleorden")->fetchAll(\PDO::FETCH_ASSOC);
    }

    public function getById($id) {
        $stmt = $this->db->prepare("SELECT * FROM detalleorden WHERE FacturaID  = :id");
        $stmt->bindParam(':id', $id, \PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetch(\PDO::FETCH_ASSOC);
    }

    public function getByCategory($id)
    {
        $stmt = $this->db->prepare("SELECT * FROM detalleorden WHERE ComidaID=:id");
        $stmt->bindParam(':id', $id, \PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetchAll(\PDO::FETCH_ASSOC);
    }

    public function create(array $data): bool
    {
        try {
            $query = "INSERT INTO detalleorden (FacturaID, ComidaID, Cantidad, Precio) VALUES (:factura, :comida, :cantidad, :precio)";
            $stmt = $this->db->prepare($query);

            $stmt->bindParam(':factura', $data['factura'], \PDO::PARAM_STR);
            $stmt->bindParam(':comida', $data['comida'], \PDO::PARAM_INT);
            $stmt->bindParam(':cantidad', $data['cantidad'], \PDO::PARAM_INT);
            $stmt->bindParam(':precio', $data['precio'], \PDO::PARAM_STR);

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
