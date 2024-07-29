<?php

namespace Microservices\UsoMesa;

use Database\Database;
use Model\BaseModel;
use PDOException;

class Model extends BaseModel
{
    public function __construct(Database $database) {
        parent::__construct($database);
    }

    public function getAll() {
        return $this->db->query("SELECT * FROM uso_mesa")->fetchAll(\PDO::FETCH_ASSOC);
    }

    public function getById($id) {
        $stmt = $this->db->prepare("SELECT * FROM uso_mesa WHERE id_uso  = :id");
        $stmt->bindParam(':id', $id, \PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetch(\PDO::FETCH_ASSOC);
    }

    public function getByCategory($id)
    {
        $stmt = $this->db->prepare("SELECT * FROM uso_mesa WHERE id_uso=:id");
        $stmt->bindParam(':id', $id, \PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetchAll(\PDO::FETCH_ASSOC);
    }

    public function getByMesa($id)
    {
        $stmt = $this->db->prepare("SELECT * FROM uso_mesa WHERE id_mesa=:id");
        $stmt->bindParam(':id', $id, \PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetchAll(\PDO::FETCH_ASSOC);
    }

    public function getByFactura($id)
    {
        $stmt = $this->db->prepare("SELECT * FROM uso_mesa WHERE id_factura=:id");
        $stmt->bindParam(':id', $id, \PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetchAll(\PDO::FETCH_ASSOC);
    }

    public function create(array $data): int
    {
        try {
            $this->db->beginTransaction();

            $query = "INSERT INTO uso_mesa (id_factura, id_mesa, hora_inicio, hora_fin) VALUES (:fac, :mesa, :ini, :fin)";
            $stmt = $this->db->prepare($query);

            $hora_inicio = new \DateTime('now', new \DateTimeZone('America/Lima'));
            $hora_inicio_str = $hora_inicio->format('Y-m-d H:i:s');

            $stmt->bindParam(':fac', $data['factura_id'], \PDO::PARAM_INT);
            $stmt->bindParam(':mesa', $data['mesa_id'], \PDO::PARAM_INT);
            $stmt->bindParam(':ini', $hora_inicio_str, \PDO::PARAM_STR);
            $stmt->bindParam(':fin', $data['hora_fin'], \PDO::PARAM_STR);

            if ($stmt->execute()) {
                $newDetalleId = (int)$this->db->lastInsertId();
                $this->db->commit();
                return $newDetalleId;
            } else {
                $this->db->rollBack();
                return 0;
            }
        } catch(PDOException $exception) {
            $this->db->rollBack();
            error_log("Error en create: " . $exception->getMessage());
            return 0;
        } catch (\Exception $e) {
            $this->db->rollBack();
            error_log("Error en create: " . $e->getMessage());
            return 0;
        }
    }

    public function delete($id): bool
    {
        // TODO: Implement delete() method.
    }
}
