<?php

namespace Microservices\User;

require_once __DIR__ . '/../../vendor/autoload.php';
require '../../Conexion/Database.php';
require '../../Auth/Middleware.php';

use Auth\Middleware;
use Database\Database;
use JsonException;
use Router\Router;

class Routes extends Router
{
    private Model $user;

    public function __construct() {
        session_start();
        parent::__construct();
        $this->user = new Model(new Database());
        $this->run();
        $this->header();
        $this->request();
    }

    public function run(): void
    {

        $this->addRoute("GET", "/allUsers", function() {
            $this->handleAllUsers();
        }, [Middleware::class, 'checkAuth']);

        $this->get("/users", function() {
            $this->handleAllUsers();
        }, [Middleware::class, 'checkAuth']);

        $this->get("/user/{id}", function($id) {
            $this->handleUser($id);
        }, [Middleware::class, 'checkAuth']);

        $this->get("/userForRol/{id}", function($id) {
            $this->handleUserForRol($id);
        }, [Middleware::class, 'checkAuth']);

        $this->post("/user", function() {
            $this->handleStoreUser();
        }, [Middleware::class, 'checkAuth']);

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

    private function handleUserForRol($id): void
    {
        $controller = new Controller($this->user);
        try {
            echo json_encode($controller->showForCategory($id), JSON_THROW_ON_ERROR);
        } catch (JsonException $e) {
            echo json_encode(['error' => $e->getMessage()]);
        }
    }

    private function handleStoreUser(): void
    {
        $controller = new Controller($this->user);

        $input = $this->input();

        $this->error();

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