<?php

namespace Microservices\Category;

require_once __DIR__ . '/../../vendor/autoload.php';
require '../../Conexion/Database.php';

use Database\Database;
use JsonException;
use Router\Router;

class Routes
{
    private Router $router;
    private Model $category;

    public function __construct() {
        $this->router = new Router();
        $this->category = new Model(new Database());
        $this->initializeRoutes();
        $this->router->header();
        $this->router->request();
    }

    private function initializeRoutes(): void
    {
        $this->router->addRoute("GET", "/allCategories", function() {
            $this->handleAllUsers();
        });

        $this->router->get("/categories", function() {
            $this->handleAllUsers();
        });

        $this->router->get("/category/{id}", function($id) {
            $this->handleUser($id);
        });

        $this->router->post("/category", function() {
            $this->handleStoreUser();
        });
    }

    private function handleAllUsers(): void
    {
        $controller = new Controller($this->category);
        try {
            echo json_encode($controller->index(), JSON_THROW_ON_ERROR);
        } catch (JsonException $e) {
            echo json_encode(['error' => $e->getMessage()]);
        }
    }

    private function handleUser($id): void
    {
        $controller = new Controller($this->category);
        try {
            echo json_encode($controller->show($id), JSON_THROW_ON_ERROR);
        } catch (JsonException $e) {
            echo json_encode(['error' => $e->getMessage()]);
        }
    }

    private function handleStoreUser(): void
    {
        $controller = new Controller($this->category);
        $input = $this->router->input();
        $this->router->error();

        try {
            $data = [
                'nombre' => $input['nombre']
            ];
            $result = $controller->store($data);
            echo json_encode(['success' => $result], JSON_THROW_ON_ERROR);
        } catch (JsonException $e) {
            echo json_encode(['error' => $e->getMessage()]);
        }
    }
}
