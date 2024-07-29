<?php

namespace Microservices\EstadoMesa;

use Database\Database;
use Model\BaseModel;
use PDOException;

class Model extends BaseModel
{
    public function __construct(Database $database) {
        parent::__construct($database);
    }

    public function getAll() {
        return $this->db->query("SELECT * FROM estados_mesa")->fetchAll(\PDO::FETCH_ASSOC);
    }

    public function getById($id) {
        $stmt = $this->db->prepare("SELECT * FROM estados_mesa WHERE id = :id");
        $stmt->bindParam(':id', $id, \PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetch(\PDO::FETCH_ASSOC);
    }

    public function create(array $data): bool
    {
        try {
            $query = "INSERT INTO estados_mesa (nombre, descripcion) VALUES (:nombre, :descripcion)";
            $stmt = $this->db->prepare($query);

            $stmt->bindParam(':nombre', $data['nombre'], \PDO::PARAM_STR);
            $stmt->bindParam(':descripcion', $data['descripcion'], \PDO::PARAM_STR);

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

    public function getByCategory($id)
    {
        // TODO: Implement getByCategory() method.
    }

    public function delete($id): bool
    {
        try {
            $query = "DELETE FROM estados_mesa WHERE id_estado_mesa = :id";
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