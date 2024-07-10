<?php

namespace Microservices\User;

use Auth\Middleware;
use Database\Database;
use Exception;
use JsonException;
use Router\Router;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Slim\Routing\RouteCollectorProxy;

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

    public function registerRoutes(RouteCollectorProxy $group): void
    {
        $group->get('/allUsers', [$this, 'handleAllUsers'])->add(new Middleware());
        $group->get('/users', [$this, 'handleAllUsers'])->add(new Middleware());
        $group->get('/user/{id}', [$this, 'handleUser'])->add(new Middleware());
        $group->get('/userForRol/{id}', [$this, 'handleUserForRol'])->add(new Middleware());
        $group->post('/user', [$this, 'handleStoreUser'])->add(new Middleware());
    }

    public function handleAllUsers(Request $request, Response $response): Response
    {
        $controller = new Controller($this->user);
        try {
            $data = json_encode($controller->index(), JSON_THROW_ON_ERROR);
            $response->getBody()->write($data);
            return $response->withHeader('Content-Type', 'application/json');
        } catch (JsonException $e) {
            $error = json_encode(['error' => $e->getMessage()]);
            $response->getBody()->write($error);
            return $response->withHeader('Content-Type', 'application/json');
        }
    }

    public function handleUser(Request $request, Response $response, array $args): Response
    {
        $controller = new Controller($this->user);
        try {
            $data = json_encode($controller->show($args['id']), JSON_THROW_ON_ERROR);
            $response->getBody()->write($data);
            return $response->withHeader('Content-Type', 'application/json');
        } catch (JsonException $e) {
            $error = json_encode(['error' => $e->getMessage()]);
            $response->getBody()->write($error);
            return $response->withHeader('Content-Type', 'application/json');
        }
    }

    public function handleUserForRol(Request $request, Response $response, array $args): Response
    {
        $controller = new Controller($this->user);
        try {
            $data = json_encode($controller->showForCategory($args['id']), JSON_THROW_ON_ERROR);
            $response->getBody()->write($data);
            return $response->withHeader('Content-Type', 'application/json');
        } catch (JsonException $e) {
            $error = json_encode(['error' => $e->getMessage()]);
            $response->getBody()->write($error);
            return $response->withHeader('Content-Type', 'application/json');
        }
    }

    public function handleStoreUser(Request $request, Response $response): Response
    {
        $controller = new Controller($this->user);
        $input = $request->getParsedBody();

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
            $success = json_encode(['status' => 'success', 'message' => 'Registro exitoso', 'data' => $result], JSON_THROW_ON_ERROR);
            $response->getBody()->write($success);
            return $response->withHeader('Content-Type', 'application/json');
        } catch (JsonException $e) {
            $error = json_encode(['status' => 'error', 'message' => $e->getMessage()]);
            $response->getBody()->write($error);
            return $response->withHeader('Content-Type', 'application/json');
        } catch (Exception $e) {
            $error = json_encode(['status' => 'error', 'message' => 'Error inesperado: ' . $e->getMessage()]);
            $response->getBody()->write($error);
            return $response->withHeader('Content-Type', 'application/json');
        }
    }
}