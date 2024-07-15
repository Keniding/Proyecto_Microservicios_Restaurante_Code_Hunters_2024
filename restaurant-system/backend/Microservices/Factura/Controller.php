<?php

namespace Microservices\Factura;

use Controller\BaseController;

class Controller extends BaseController
{
    public function __construct(Model $rol) {
        parent::__construct($rol);
    }
}