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
            $this->db->beginTransaction();

            $query = "INSERT INTO facturas (id, Fecha, Total, dni_cliente) VALUES (:id, :fecha, :total, :dni)";
            $stmt = $this->db->prepare($query);

            $fecha_creacion = date('Y-m-d H:i:s');

            $stmt->bindParam(':id', $data['id'], \PDO::PARAM_STR);
            $stmt->bindParam(':fecha', $fecha_creacion, \PDO::PARAM_STR);
            $stmt->bindParam(':total', $data['total'], \PDO::PARAM_STR);
            $stmt->bindParam(':dni', $data['dni'], \PDO::PARAM_STR);

            if (!$stmt->execute()) {
                $this->db->rollBack();
                return false;
            }

            $query = "SELECT incrementar_cantidad_compras(:dni)";
            $stmt = $this->db->prepare($query);
            $stmt->bindParam(':dni', $data['dni'], \PDO::PARAM_STR);

            if (!$stmt->execute()) {
                $this->db->rollBack();
                return false;
            }

            $this->db->commit();
            return true;
        } catch(PDOException $exception) {
            $this->db->rollBack();
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