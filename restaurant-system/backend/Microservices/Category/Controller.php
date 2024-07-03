<?php

namespace Microservices\Category;

use Controller\BaseController;

require '../../Controller/BaseController.php';

class Controller extends BaseController
{
    public function __construct(Model $rol) {
        parent::__construct($rol);
    }
}