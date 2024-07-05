<?php

namespace Microservices\Food;

require_once __DIR__ . '/../../vendor/autoload.php';
require '../../Conexion/Database.php';

use Database\Database;
use JsonException;
use Router\Router;

class Routes
{

    private Router $router;
    private Model $food;

    public function __construct() {
        $this->router = new Router();
        $this->food = new Model(new Database());
        $this->run();
        $this->router->header();
        $this->router->request();
    }

    public function run(): void
    {

        $this->router->addRoute("GET", "/allFoods", function() {
            $this->handleAllFoods();
        });

        $this->router->get("/foods", function() {
            $this->handleAllFoods();
        });

        $this->router->get("/food/{id}", function($id) {
            $this->handleFood($id);
        });

        $this->router->get("/foodForCategory/{id}", function ($id) {
            $this->handleFoodForCategory($id);
        });

        $this->router->post("/food", function() {
            $this->handleStoreFood();
        });

        if (!isset($_SERVER['REQUEST_URI'])) {
            $_SERVER['REQUEST_URI'] = '/allFoods';
        }
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

        $input = $this->router->input();

        $this->router->error();

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