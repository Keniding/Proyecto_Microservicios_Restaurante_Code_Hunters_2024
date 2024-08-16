<?php

namespace Microservices\User;

use Controller\BaseController;

class Controller extends BaseController
{
    public function __construct(Model $user) {
        parent::__construct($user);
    }

    public function showForDni($id)
    {
        return $this->model->getByDni($id);
    }
}
