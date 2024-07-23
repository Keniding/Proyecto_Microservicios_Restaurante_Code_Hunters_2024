<?php

namespace Microservices\Modifications;

use Controller\BaseController;

class Controller extends BaseController
{
    public function __construct(Model $model) {
        parent::__construct($model);
    }
}