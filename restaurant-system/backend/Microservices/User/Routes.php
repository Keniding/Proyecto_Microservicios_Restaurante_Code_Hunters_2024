<?php

namespace Microservices\User;

require_once __DIR__ . '/../../vendor/autoload.php';
require '../../Conexion/Database.php';

use Database\Database;
use JsonException;
use Router\Router;

class Routes
{

    private Router $router;
    private Model $user;

    public function __construct() {
        $this->router = new Router();
        $this->user = new Model(new Database());
        $this->run();
        $this->router->header();
        $this->router->request();
    }

    public function run(): void
    {

        $this->router->addRoute("GET", "/allUsers", function() {
            $this->handleAllUsers();
        });

        $this->router->get("/users", function() {
            $this->handleAllUsers();
        });

        $this->router->get("/user/{id}", function($id) {
            $this->handleUser($id);
        });

        $this->router->post("/user", function() {
            $this->handleStoreUser();
        });

        if (!isset($_SERVER['REQUEST_URI'])) {
            $_SERVER['REQUEST_URI'] = '/allUsers';
        }
    }

    private function handleAllUsers(): void
    {
        $controller = new Controller($this->user);
        try {
            echo json_encode($controller->index(), JSON_THROW_ON_ERROR);
        } catch (JsonException $e) {
            echo json_encode(['error' => $e->getMessage()]);
        }
    }

    private function handleUser($id): void
    {
        $controller = new Controller($this->user);
        try {
            echo json_encode($controller->show($id), JSON_THROW_ON_ERROR);
        } catch (JsonException $e) {
            echo json_encode(['error' => $e->getMessage()]);
        }
    }

    private function handleStoreUser(): void
    {
        $controller = new Controller($this->user);

        $input = $this->router->input();

        $this->router->error();

        try {
            $data = [
                'dni' => $input['dni'],
                'username' => $input['username'],
                'password' => $input['password'],
                'email' => $input['email'],
                'telefono' => $input['telefono'],
                'rol' => $input['rol']
            ];
            $result = $controller->store($data);
            echo json_encode(['success' => $result], JSON_THROW_ON_ERROR);
        } catch (JsonException $e) {
            echo json_encode(['error' => $e->getMessage()]);
        }
    }
}