<?php

namespace Microservices\Costumer;

use Controller\BaseController;

class Controller extends BaseController
{
    public function __construct(Model $food) {
        parent::__construct($food);
    }
}
