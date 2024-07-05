<?php

namespace Microservices\Food;

use Controller\BaseController;

require '../../Controller/BaseController.php';

class Controller extends BaseController
{
    public function __construct(Model $food) {
        parent::__construct($food);
    }
}
