<?php

namespace Model;

use Database\Database;

abstract class BaseModel
{
    protected Database $db;

    public function __construct(Database $database) {
        $this->db = $database;
    }

    abstract public function getAll();
    abstract public function getById($id);
    abstract public function getByCategory($id);
    abstract public function create(array $data);
    abstract public function delete($id): bool;
    //abstract public function edit($id, array $data);
}
