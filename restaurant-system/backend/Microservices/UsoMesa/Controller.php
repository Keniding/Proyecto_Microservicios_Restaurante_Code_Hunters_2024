<?php

namespace Microservices\UsoMesa;

use Controller\BaseController;

class Controller extends BaseController
{
    public function __construct(Model $order) {
        parent::__construct($order);
    }

    public function showForFactura($id)
    {
        return $this->model->getByFactura($id);
    }

    public function showForMesa($id)
    {
        return $this->model->getByMesa($id);
    }
}
