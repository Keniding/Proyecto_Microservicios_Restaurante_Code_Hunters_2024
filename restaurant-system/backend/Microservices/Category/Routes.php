<?php

namespace Microservices\Category;

require_once __DIR__ . '/../../vendor/autoload.php';
require '../../Conexion/Database.php';
require '../../Auth/Middleware.php';

use Database\Database;
use JsonException;
use Router\Router;
use Auth\Middleware;

class Routes extends Router
{
    private Model $category;

    public function __construct() {
        session_start();
        parent::__construct();
        $this->category = new Model(new Database());
        $this->initializeRoutes();
        $this->header();
        $this->request();
    }

    private function initializeRoutes(): void
    {
        // Rutas protegidas
        $this->addRoute("GET", "/allCategories", function() {
            $this->handleAllCategories();
        }, [Middleware::class, 'checkAuth']);

        $this->get("/categories", function() {
            $this->handleAllCategories();
        }, [Middleware::class, 'checkAuth']);

        $this->get("/category/{id}", function($id) {
            $this->handleCategory($id);
        }, [Middleware::class, 'checkAuth']);

        $this->post("/category", function() {
            $this->handleStoreCategory();
        }, [Middleware::class, 'checkAuth']);
    }

    private function handleAllCategories(): void
    {
        $controller = new Controller($this->category);
        try {
            echo json_encode($controller->index(), JSON_THROW_ON_ERROR);
        } catch (JsonException $e) {
            echo json_encode(['error' => $e->getMessage()]);
        }
    }

    private function handleCategory($id): void
    {
        $controller = new Controller($this->category);
        try {
            echo json_encode($controller->show($id), JSON_THROW_ON_ERROR);
        } catch (JsonException $e) {
            echo json_encode(['error' => $e->getMessage()]);
        }
    }

    private function handleStoreCategory(): void
    {
        $controller = new Controller($this->category);
        $input = $this->input();
        $this->error();

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
