<?php

namespace Microservices\User;

use Auth\Middleware;
use Database\Database;
use Exception;
use JsonException;
use Router\Router;

class Routes extends Router
{
    private Model $user;

    public function __construct() {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
        parent::__construct();
        $this->user = new Model(new Database());
        $this->header();
    }

    public function registerRoutes($app): void
    {
        $app->get('/allUsers', function() {
            return $this->handleAllUsers();
        });

        $app->get('/users', function() {
            return $this->handleAllUsers();
        });

        $app->get('/user/{id}', function($id) {
            return $this->handleUser($id);
        });

        $app->get('/userForDni/{id}', function($id) {
            return $this->handleUserDni($id);
        });

        $app->get('/userForRol/{id}', function($id) {
            return $this->handleUserForRol($id);
        }); //Auth midd

        $app->post('/user', function() {
            return $this->handleStoreUser($this->input());
        });
    }

    private function handleAllUsers() {
        $controller = new Controller($this->user);
        try {
            $data = json_encode($controller->index(), JSON_THROW_ON_ERROR);
            return $this->createResponse(200, $data);
        } catch (JsonException $e) {
            return $this->createResponse(500, json_encode(['error' => $e->getMessage()]));
        }
    }

    private function handleUser($id) {
        $controller = new Controller($this->user);
        try {
            $data = json_encode($controller->show($id), JSON_THROW_ON_ERROR);
            return $this->createResponse(200, $data);
        } catch (JsonException $e) {
            return $this->createResponse(500, json_encode(['error' => $e->getMessage()]));
        }
    }

    private function handleUserDni($id) {
        $controller = new Controller($this->user);
        try {
            $data = json_encode($controller->showForDni($id), JSON_THROW_ON_ERROR);
            return $this->createResponse(200, $data);
        } catch (JsonException $e) {
            return $this->createResponse(500, json_encode(['error' => $e->getMessage()]));
        }
    }

    private function handleUserForRol($id) {
        $controller = new Controller($this->user);
        try {
            $data = json_encode($controller->showForCategory($id), JSON_THROW_ON_ERROR);
            return $this->createResponse(200, $data);
        } catch (JsonException $e) {
            return $this->createResponse(500, json_encode(['error' => $e->getMessage()]));
        }
    }

    private function handleStoreUser(array $input) {
        $controller = new Controller($this->user);

        error_log('Received data: ' . print_r($input, true));

        try {
            $data = [
                'dni' => $input['dni'] ?? null,
                'username' => $input['username'] ?? null,
                'password' => $input['password'] ?? null,
                'email' => $input['email'] ?? null,
                'telefono' => $input['telefono'] ?? null,
                'rol' => $input['rol'] ?? null
            ];

            foreach ($data as $key => $value) {
                if ($value === null) {
                    throw new Exception("Missing required field: $key");
                }
            }

            $result = $controller->store($data);
            $success = json_encode(['status' => 'success', 'message' => 'Registro exitoso', 'data' => $result], JSON_THROW_ON_ERROR);
            return $this->createResponse(201, $success);
        } catch (JsonException $e) {
            return $this->createResponse(500, json_encode(['status' => 'error', 'message' => $e->getMessage()]));
        } catch (Exception $e) {
            return $this->createResponse(500, json_encode(['status' => 'error', 'message' => 'Error inesperado: ' . $e->getMessage()]));
        }
    }

    private function createResponse($status, $body) {
        http_response_code($status);
        header('Content-Type: application/json');
        echo $body;
    }
}