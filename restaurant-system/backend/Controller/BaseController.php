<?php

namespace Controller;

use Model\BaseModel;

abstract class BaseController
{
    protected BaseModel $model;

    public function __construct($model)
    {
        $this->model = $model;
    }

    public function index()
    {
        return $this->model->getAll();
    }

    public function show($id)
    {
        return $this->model->getById($id);
    }

    public function showForCategory($id)
    {
        return $this->model->getByCategory($id);
    }

    public function store(array $data)
    {
        return $this->model->create($data);
    }

    public function destroy(mixed $id)
    {
        return $this->model->delete($id);
    }

    /*
    public function edit(mixed $id, array $data)
    {
        return $this->model->update($id, $data);
    }
    */

}
