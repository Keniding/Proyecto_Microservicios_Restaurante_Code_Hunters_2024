<?php

namespace Microservices\Food;

use Controller\BaseController;

class Controller extends BaseController
{
    public function __construct(Model $food) {
        parent::__construct($food);
    }

    public function edit(mixed $id, array $data)
    {
        return $this->model->update($id, $data);
    }
}
