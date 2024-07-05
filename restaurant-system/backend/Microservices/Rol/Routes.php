<?php

namespace Microservices\Rol;

require_once __DIR__ . '/../../vendor/autoload.php';
require '../../Conexion/Database.php';
require '../../Auth/Middleware.php';

use Auth\Middleware;
use Database\Database;
use JsonException;
use Router\Router;

class Routes extends Router
{
    private Model $rol;

    public function __construct() {
        session_start();
        parent::__construct();
        $this->rol = new Model(new Database());
        $this->initializeRoutes();
        $this->header();
        $this->request();
    }

    private function initializeRoutes(): void
    {
        // Rutas protegidas con middleware
        $this->addRoute("GET", "/allRoles", function() {
            $this->handleAllRoles();
        }, [Middleware::class, 'checkAuth']);

        $this->get("/roles", function() {
            $this->handleAllRoles();
        }, [Middleware::class, 'checkAuth']);

        $this->get("/rol/{id}", function($id) {
            $this->handleRol($id);
        }, [Middleware::class, 'checkAuth']);

        $this->post("/rol", function() {
            $this->handleStoreRol();
        }, [Middleware::class, 'checkAuth']);
    }

    private function handleAllRoles(): void
    {
        $controller = new Controller($this->rol);
        try {
            echo json_encode($controller->index(), JSON_THROW_ON_ERROR);
        } catch (JsonException $e) {
            echo json_encode(['error' => $e->getMessage()]);
        }
    }

    private function handleRol($id): void
    {
        $controller = new Controller($this->rol);
        try {
            echo json_encode($controller->show($id), JSON_THROW_ON_ERROR);
        } catch (JsonException $e) {
            echo json_encode(['error' => $e->getMessage()]);
        }
    }

    private function handleStoreRol(): void
    {
        $controller = new Controller($this->rol);
        $input = $this->input();
        $this->error();

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
