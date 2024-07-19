<?php

namespace Microservices\Detalle;

use Controller\BaseController;

class Controller extends BaseController
{
    public function __construct(Model $order) {
        parent::__construct($order);
    }

    public function showForFood($id)
    {
        return $this->model->getByFood($id);
    }
}
