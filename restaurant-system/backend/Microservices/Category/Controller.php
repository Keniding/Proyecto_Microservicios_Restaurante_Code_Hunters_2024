<?php

namespace Microservices\Category;

use Controller\BaseController;

class Controller extends BaseController
{
    public function __construct(Model $rol) {
        parent::__construct($rol);
    }

    public function edit(mixed $id, array $data)
    {
        return $this->model->update($id, $data);
    }
}