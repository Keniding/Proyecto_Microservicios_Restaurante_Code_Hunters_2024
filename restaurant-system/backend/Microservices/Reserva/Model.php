<?php

namespace Microservices\Reserva;

use Database\Database;
use Model\BaseModel;
use PDOException;

class Model extends BaseModel
{
    public function __construct(Database $database) {
        parent::__construct($database);
    }

    public function getAll() {
        return $this->db->query("SELECT * FROM reservas")->fetchAll(\PDO::FETCH_ASSOC);
    }

    public function getById($id) {
        $stmt = $this->db->prepare("SELECT * FROM reservas WHERE id_reserva  = :id");
        $stmt->bindParam(':id', $id, \PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetch(\PDO::FETCH_ASSOC);
    }

    public function getByCategory($id)
    {
        $stmt = $this->db->prepare("SELECT * FROM reservas WHERE id_reserva=:id");
        $stmt->bindParam(':id', $id, \PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetchAll(\PDO::FETCH_ASSOC);
    }

    public function getByMesa($id)
    {
        $stmt = $this->db->prepare("SELECT * FROM reservas WHERE id_mesa=:id");
        $stmt->bindParam(':id', $id, \PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetchAll(\PDO::FETCH_ASSOC);
    }

    public function getByCliente($id)
    {
        $stmt = $this->db->prepare("SELECT * FROM reservas WHERE dni_cliente=:id");
        $stmt->bindParam(':id', $id, \PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetchAll(\PDO::FETCH_ASSOC);
    }

    public function create(array $data): int
    {
        try {
            $this->db->beginTransaction();

            $query = "INSERT INTO reservas (id_mesa, dni_cliente, hora_reserva, numero_invitados, estado) VALUES (:mesa, :cliente, :hora, :invitados, :estado)";
            $stmt = $this->db->prepare($query);

            $estado =  'pendiente';

            $stmt->bindParam(':mesa', $data['id_mesa'], \PDO::PARAM_INT);
            $stmt->bindParam(':cliente', $data['dni_cliente'], \PDO::PARAM_STR);
            $stmt->bindParam(':hora', $data['hora_reserva'], \PDO::PARAM_STR);
            $stmt->bindParam(':invitados', $data['numero_invitados'], \PDO::PARAM_INT);
            $stmt->bindParam(':estado', $estado, \PDO::PARAM_STR);

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
