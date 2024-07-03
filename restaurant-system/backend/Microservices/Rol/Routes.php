<?php

namespace Microservices\Rol;

require_once __DIR__ . '/../../vendor/autoload.php';
require '../../Conexion/Database.php';

use Database\Database;
use JsonException;
use Router\Router;

class Routes
{
    private Router $router;
    private Model $rol;

    public function __construct() {
        $this->router = new Router();
        $this->rol = new Model(new Database());
        $this->initializeRoutes();
        $this->router->header();
        $this->router->request();
    }

    private function initializeRoutes(): void
    {
        $this->router->addRoute("GET", "/allRoles", function() {
            $this->handleAllUsers();
        });

        $this->router->get("/roles", function() {
            $this->handleAllUsers();
        });

        $this->router->get("/rol/{id}", function($id) {
            $this->handleUser($id);
        });

        $this->router->post("/rol", function() {
            $this->handleStoreUser();
        });
    }

    private function handleAllUsers(): void
    {
        $controller = new Controller($this->rol);
        try {
            echo json_encode($controller->index(), JSON_THROW_ON_ERROR);
        } catch (JsonException $e) {
            echo json_encode(['error' => $e->getMessage()]);
        }
    }

    private function handleUser($id): void
    {
        $controller = new Controller($this->rol);
        try {
            echo json_encode($controller->show($id), JSON_THROW_ON_ERROR);
        } catch (JsonException $e) {
            echo json_encode(['error' => $e->getMessage()]);
        }
    }

    private function handleStoreUser(): void
    {
        $controller = new Controller($this->rol);
        $input = $this->router->input();
        $this->router->error();

        try {
            $data = [
                'nombre' => $input['nombre'],
                'descripcion' => $input['descripcion']
            ];
            $result = $controller->store($data);
            echo json_encode(['success' => $result], JSON_THROW_ON_ERROR);
        } catch (JsonException $e) {
            echo json_encode(['error' => $e->getMessage()]);
        }
    }
}
