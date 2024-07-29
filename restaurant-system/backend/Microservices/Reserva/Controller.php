<?php

namespace Microservices\Reserva;

use Controller\BaseController;

class Controller extends BaseController
{
    public function __construct(Model $order) {
        parent::__construct($order);
    }

    public function showForCliente($id)
    {
        return $this->model->getByCliente($id);
    }

    public function showForMesa($id)
    {
        return $this->model->getByMesa($id);
    }
}
