<?php

namespace Microservices\ModificationsOrders;

use Database\Database;
use Model\BaseModel;
use PDOException;

class Model extends BaseModel
{
    public function __construct(Database $database) {
        parent::__construct($database);
    }

    public function getAll() {
        return $this->db->query("SELECT * FROM ordenes_modificaciones")->fetchAll(\PDO::FETCH_ASSOC);
    }

    public function getById($id) {
        $stmt = $this->db->prepare("SELECT * FROM ordenes_modificaciones WHERE id = :id");
        $stmt->bindParam(':id', $id, \PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetch(\PDO::FETCH_ASSOC);
    }

    public function create(array $data): bool
    {
        try {
            $this->db->beginTransaction();

            $query = "INSERT INTO ordenes_modificaciones (orden_id, modificacion_id) VALUES (:order, :modification)";
            $stmt = $this->db->prepare($query);

            $stmt->bindParam(':order', $data['order'], \PDO::PARAM_STR);
            $stmt->bindParam(':modification', $data['modification'], \PDO::PARAM_STR);

            if ($stmt->execute()) {
                $modificationId = $data['modification'];
                $incrementQuery = "SELECT incrementar_contador_modificador(:modification) AS nuevo_contador";
                $incrementStmt = $this->db->prepare($incrementQuery);
                $incrementStmt->bindParam(':modification', $modificationId, \PDO::PARAM_INT);

                if ($incrementStmt->execute()) {
                    $this->db->commit();
                    return true;
                } else {
                    $this->db->rollBack();
                    return false;
                }
            } else {
                $this->db->rollBack();
                return false;
            }
        } catch (PDOException $exception) {
            $this->db->rollBack();
            error_log("Error: " . $exception->getMessage());
            return false;
        }
    }

    public function getByCategory($id)
    {
        // TODO: Implement getByCategory() method.
    }

    public function delete($id): bool
    {
        try {
            $query = "DELETE FROM ordenes_modificaciones WHERE id = :id";
            $stmt = $this->db->prepare($query);

            $stmt->bindParam(':id', $id, \PDO::PARAM_INT);

            if ($stmt->execute()) {
                return true;
            } else {
                return false;
            }
        } catch(PDOException $exception) {
            error_log("Error: " . $exception->getMessage());
            return false;
        }
    }
}