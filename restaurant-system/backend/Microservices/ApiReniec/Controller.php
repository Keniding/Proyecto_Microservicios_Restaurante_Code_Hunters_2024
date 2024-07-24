<?php

namespace Microservices\ApiReniec;

use Exception;

class Controller
{
    protected Model $model;

    public function __construct(Model $model) {
        $this->model = $model;
    }

    /**
     * @throws Exception
     */
    public function show($id)
    {
        return $this->model->getByDni($id);
    }
}
