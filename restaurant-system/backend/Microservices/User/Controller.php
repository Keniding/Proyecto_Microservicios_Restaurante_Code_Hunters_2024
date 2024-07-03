<?php

namespace Microservices\User;

use Controller\BaseController;

require '../../Controller/BaseController.php';

class Controller extends BaseController
{
    public function __construct(Model $user) {
        parent::__construct($user);
    }
}
