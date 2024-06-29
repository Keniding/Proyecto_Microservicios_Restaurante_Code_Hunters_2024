<?php
namespace apiRest\gestionUser;

use \apiRest\gestionUser\UserModel as UserModel;

class UserController
{
    private UserModel $userModel;

    public function __construct(UserModel $user) {
        $this->userModel = $user;
    }

    public function index() {
        return $this->userModel->getAll();
    }

    public function show($id) {
        return $this->userModel->getById($id);
    }

    public function store($dni, $name, $email, $tel, $pass) {
        return $this->userModel->create($dni, $name, $email, $tel, $pass);
    }
}