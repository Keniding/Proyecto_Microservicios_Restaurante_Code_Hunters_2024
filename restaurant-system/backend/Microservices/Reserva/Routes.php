<?php

namespace Microservices\Reserva;

use Database\Database;
use JsonException;
use Router\Router;
use Auth\Middleware;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;

class Routes extends Router
{
    private Model $reserva;

    public function __construct() {
        parent::__construct();
        $this->reserva = new Model(new Database());
        $this->header();
    }

    public function registerRoutes($app): void
    {
        $app->get('/reservas', function(Request $request, Response $response) {
            return $this->handleAllReservas($response);
        });

        $app->get('/reserva/{id}', function(Request $request, Response $response, array $args) {
            return $this->handleReserva($response, $args['id']);
        });

        $app->get('/reservasForMesa/{id}', function(Request $request, Response $response, array $args) {
            return $this->handleReservasForMesa($response, $args['id']);
        });

        $app->get('/reservasForCliente/{id}', function(Request $request, Response $response, array $args) {
            return $this->handleReservasForCliente($response, $args['id']);
        });

        $app->post('/reserva', function(Request $request, Response $response) {
            return $this->handleStoreReserva($response, $request->getParsedBody());
        });
    }

    private function handleAllReservas(Response $response): Response
    {
        $controller = new Controller($this->reserva);
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

    private function handleReserva(Response $response, $id): Response
    {
        $controller = new Controller($this->reserva);
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

    private function handleReservasForMesa(Response $response, $id): Response
    {
        $controller = new Controller($this->reserva);
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

    private function handleReservasForCliente(Response $response, $id): Response
    {
        $controller = new Controller($this->reserva);
        try {
            $data = json_encode($controller->showForCliente($id), JSON_THROW_ON_ERROR);
            $response->getBody()->write($data);
            return $response->withHeader('Content-Type', 'application/json');
        } catch (JsonException $e) {
            $error = json_encode(['error' => $e->getMessage()]);
            $response->getBody()->write($error);
            return $response->withHeader('Content-Type', 'application/json');
        }
    }

    private function handleStoreReserva(Response $response, array $input): Response
    {
        $controller = new Controller($this->reserva);

        try {
            $data = [
                'id_mesa' => $input['id_mesa'],
                'dni_cliente' => $input['dni_cliente'],
                'hora_reserva' => $input['hora_reserva'],
                'fecha_reserva' => $input['fecha_reserva'],
                'numero_invitados' => $input['numero_invitados']
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
