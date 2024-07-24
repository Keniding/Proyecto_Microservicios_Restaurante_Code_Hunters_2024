<?php

namespace Microservices\Modifications;

use Database\Database;
use Model\BaseModel;
use PDOException;

class Model extends BaseModel
{
    public function __construct(Database $database) {
        parent::__construct($database);
    }

    public function getAll() {
        return $this->db->query("SELECT * FROM modificaciones_plato")->fetchAll(\PDO::FETCH_ASSOC);
    }

    public function getById($id) {
        $stmt = $this->db->prepare("SELECT * FROM modificaciones_plato WHERE id = :id");
        $stmt->bindParam(':id', $id, \PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetch(\PDO::FETCH_ASSOC);
    }

    public function create(array $data): bool
    {
        try {
            switch ($data['category']) {
                case 'quitar':
                    $data['color'] = '#ff0000';
                    break;
                case 'agregar':
                    $data['color'] = '#00ff00';
                    break;
                case 'modificar':
                    $data['color'] = '#0000ff';
                    break;
                default:
                    $data['color'] = '#000000';
                    break;
            }

            $query = "INSERT INTO modificaciones_plato (name, category, color) VALUES (:name, :category, :color)";
            $stmt = $this->db->prepare($query);

            $stmt->bindParam(':name', $data['name'], \PDO::PARAM_STR);
            $stmt->bindParam(':category', $data['category'], \PDO::PARAM_STR);
            $stmt->bindParam(':color', $data['color'], \PDO::PARAM_STR);

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
            $query = "DELETE FROM modificaciones_plato WHERE id = :id";
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