<?php

namespace Microservices\Costumer;

use Database\Database;
use Model\BaseModel;
use PDOException;

class Model extends BaseModel
{
    public function __construct(Database $database) {
        parent::__construct($database);
    }

    public function getAll() {
        return $this->db->query("SELECT * FROM clientes")->fetchAll(\PDO::FETCH_ASSOC);
    }

    public function getById($id) {
        $stmt = $this->db->prepare("SELECT * FROM clientes WHERE Dni = :id");
        $stmt->bindParam(':id', $id, \PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetch(\PDO::FETCH_ASSOC);
    }

    public function getByCategory($id)
    {
        $stmt = $this->db->prepare("SELECT * FROM clientes WHERE TipoClienteID=:id");
        $stmt->bindParam(':id', $id, \PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetchAll(\PDO::FETCH_ASSOC);
    }

    public function getByDni($id)
    {
        $stmt = $this->db->prepare("SELECT * FROM clientes WHERE Dni=:id");
        $stmt->bindParam(':id', $id, \PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetch(\PDO::FETCH_ASSOC);
    }

    public function create(array $data): bool
    {
        try {
            $query = "INSERT INTO clientes (Dni, Nombre, Email, Telefono, Direccion, CantidadCompras, TipoClienteID) 
            VALUES (:dni, :name, :email, :telefono, :direccion, :cantidad, :tipo)";
            $stmt = $this->db->prepare($query);

            $cantidadcomprada = 0;
            $tipo = 1;

            $stmt->bindParam(':dni', $data['dni'], \PDO::PARAM_STR);
            $stmt->bindParam(':name', $data['name'], \PDO::PARAM_STR);
            $stmt->bindParam(':email', $data['email'], \PDO::PARAM_STR);
            $stmt->bindParam(':telefono', $data['telefono'], \PDO::PARAM_STR);
            $stmt->bindParam(':direccion', $data['direccion'], \PDO::PARAM_STR);
            $stmt->bindParam(':cantidad', $cantidadcomprada, \PDO::PARAM_INT);
            $stmt->bindParam(':tipo', $tipo, \PDO::PARAM_INT);

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
