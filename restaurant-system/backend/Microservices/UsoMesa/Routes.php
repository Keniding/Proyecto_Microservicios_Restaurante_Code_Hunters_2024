<?php

namespace Microservices\UsoMesa;

use Database\Database;
use JsonException;
use Router\Router;
use Auth\Middleware;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;

class Routes extends Router
{
    private Model $usoMesa;

    public function __construct() {
        parent::__construct();
        $this->usoMesa = new Model(new Database());
        $this->header();
    }

    public function registerRoutes($app): void
    {
        $app->get('/usosMesa', function(Request $request, Response $response) {
            return $this->handleAllUsosMesa($response);
        });

        $app->get('/usoMesa/{id}', function(Request $request, Response $response, array $args) {
            return $this->handleUsoMesa($response, $args['id']);
        });

        $app->get('/usoMesaForMesa/{id}', function(Request $request, Response $response, array $args) {
            return $this->handleUsoMesaForMesa($response, $args['id']);
        });

        $app->get('/usoMesaForFecha/{id}', function(Request $request, Response $response, array $args) {
            return $this->handleUsoMesaForFactura($response, $args['id']);
        });

        $app->post('/usoMesa', function(Request $request, Response $response) {
            return $this->handleStoreUsoMesa($response, $request->getParsedBody());
        });
    }

    private function handleAllUsosMesa(Response $response): Response
    {
        $controller = new Controller($this->usoMesa);
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

    private function handleUsoMesa(Response $response, $id): Response
    {
        $controller = new Controller($this->usoMesa);
        try {
            $data = json_encode($controller->show($id), JSON_THROW_ON_ERROR);
            $response->getBody()->write($data);
            return $response->withHeader('Content-Type', 'application/json');
        } catch (JsonException $e) {
            $error = json_encode(['error' => $e->getMessage()]);
            $response->getBody()->write($error);
            return $response->withHeader('Content-Type', 'application/json');
        }
    }

    private function handleUsoMesaForMesa(Response $response, $id): Response
    {
        $controller = new Controller($this->usoMesa);
        try {
            $data = json_encode($controller->showForMesa($id), JSON_THROW_ON_ERROR);
            $response->getBody()->write($data);
            return $response->withHeader('Content-Type', 'application/json');
        } catch (JsonException $e) {
            $error = json_encode(['error' => $e->getMessage()]);
            $response->getBody()->write($error);
            return $response->withHeader('Content-Type', 'application/json');
        }
    }

    private function handleUsoMesaForFactura(Response $response, $fecha): Response
    {
        $controller = new Controller($this->usoMesa);
        try {
            $data = json_encode($controller->showForFactura($fecha), JSON_THROW_ON_ERROR);
            $response->getBody()->write($data);
            return $response->withHeader('Content-Type', 'application/json');
        } catch (JsonException $e) {
            $error = json_encode(['error' => $e->getMessage()]);
            $response->getBody()->write($error);
            return $response->withHeader('Content-Type', 'application/json');
        }
    }

    private function handleStoreUsoMesa(Response $response, array $input): Response
    {
        $controller = new Controller($this->usoMesa);

        try {
            $data = [
                'factura_id' => $input['factura_id'],
                'mesa_id' => $input['mesa_id']
            ];

            $result = $controller->store($data);

            if (is_int($result)) {
                $success = json_encode(['success' => true, 'id' => $result], JSON_THROW_ON_ERROR);
            } else {
                $success = json_encode(['success' => false], JSON_THROW_ON_ERROR);
            }

            $response->getBody()->write($success);
            return $response->withHeader('Content-Type', 'application/json');
        } catch (JsonException $e) {
            $error = json_encode(['error' => $e->getMessage()]);
            $response->getBody()->write($error);
            return $response->withHeader('Content-Type', 'application/json');
        }
    }
}
