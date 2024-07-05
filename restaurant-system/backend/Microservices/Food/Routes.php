<?php

namespace Microservices\Food;

require_once __DIR__ . '/../../vendor/autoload.php';
require '../../Conexion/Database.php';
require '../../Auth/Middleware.php';

use Database\Database;
use JsonException;
use Router\Router;
use Auth\Middleware;

class Routes extends Router
{
    private Model $food;

    public function __construct() {
        session_start();
        parent::__construct();
        $this->food = new Model(new Database());
        $this->initializeRoutes();
        $this->header();
        $this->request();
    }

    private function initializeRoutes(): void
    {
        // Rutas protegidas con middleware
        $this->addRoute("GET", "/allFoods", function() {
            $this->handleAllFoods();
        }, [Middleware::class, 'checkAuth']);

        $this->get("/foods", function() {
            $this->handleAllFoods();
        }, [Middleware::class, 'checkAuth']);

        $this->get("/food/{id}", function($id) {
            $this->handleFood($id);
        }, [Middleware::class, 'checkAuth']);

        $this->get("/foodForCategory/{id}", function($id) {
            $this->handleFoodForCategory($id);
        }, [Middleware::class, 'checkAuth']);

        $this->post("/food", function() {
            $this->handleStoreFood();
        }, [Middleware::class, 'checkAuth']);
    }

    private function handleAllFoods(): void
    {
        $controller = new Controller($this->food);
        try {
            echo json_encode($controller->index(), JSON_THROW_ON_ERROR);
        } catch (JsonException $e) {
            echo json_encode(['error' => $e->getMessage()]);
        }
    }

    private function handleFood($id): void
    {
        $controller = new Controller($this->food);
        try {
            echo json_encode($controller->show($id), JSON_THROW_ON_ERROR);
        } catch (JsonException $e) {
            echo json_encode(['error' => $e->getMessage()]);
        }
    }

    private function handleFoodForCategory($id): void
    {
        $controller = new Controller($this->food);
        try {
            echo json_encode($controller->showForCategory($id), JSON_THROW_ON_ERROR);
        } catch (JsonException $e) {
            echo json_encode(['error' => $e->getMessage()]);
        }
    }

    private function handleStoreFood(): void
    {
        $controller = new Controller($this->food);
        $input = $this->input();
        $this->error();

        try {
            $data = [
                'nombre' => $input['nombre'],
                'descripcion' => $input['descripcion'],
                'precio' => $input['precio'],
                'disponibilidad' => $input['disponibilidad'],
                'categoria_id' => $input['categoria_id']
            ];
            $result = $controller->store($data);
            echo json_encode(['success' => $result], JSON_THROW_ON_ERROR);
        } catch (JsonException $e) {
            echo json_encode(['error' => $e->getMessage()]);
        }
    }
}
