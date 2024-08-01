<?php

namespace Microservices\Reserva;

use Database\Database;
use JsonException;
use Router\Router;

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
        $app->get('/reservas', function() {
            return $this->handleAllReservas();
        });

        $app->get('/reserva/{id}', function($id) {
            return $this->handleReserva($id);
        });

        $app->get('/reservasForMesa/{id}', function($id) {
            return $this->handleReservasForMesa($id);
        });

        $app->get('/reservasForCliente/{id}', function($id) {
            return $this->handleReservasForCliente($id);
        });

        $app->post('/reserva', function() {
            return $this->handleStoreReserva($this->input());
        });
    }

    private function handleAllReservas() {
        $controller = new Controller($this->reserva);
        try {
            $data = json_encode($controller->index(), JSON_THROW_ON_ERROR);
            return $this->createResponse(200, $data);
        } catch (JsonException $e) {
            return $this->createResponse(500, json_encode(['error' => $e->getMessage()]));
        }
    }

    private function handleReserva($id) {
        $controller = new Controller($this->reserva);
        try {
            $data = json_encode($controller->show($id), JSON_THROW_ON_ERROR);
            return $this->createResponse(200, $data);
        } catch (JsonException $e) {
            return $this->createResponse(500, json_encode(['error' => $e->getMessage()]));
        }
    }

    private function handleReservasForMesa($id) {
        $controller = new Controller($this->reserva);
        try {
            $data = json_encode($controller->showForMesa($id), JSON_THROW_ON_ERROR);
            return $this->createResponse(200, $data);
        } catch (JsonException $e) {
            return $this->createResponse(500, json_encode(['error' => $e->getMessage()]));
        }
    }

    private function handleReservasForCliente($id) {
        $controller = new Controller($this->reserva);
        try {
            $data = json_encode($controller->showForCliente($id), JSON_THROW_ON_ERROR);
            return $this->createResponse(200, $data);
        } catch (JsonException $e) {
            return $this->createResponse(500, json_encode(['error' => $e->getMessage()]));
        }
    }

    private function handleStoreReserva(array $input) {
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

            return $this->createResponse(201, $success);
        } catch (JsonException $e) {
            return $this->createResponse(500, json_encode(['error' => $e->getMessage()]));
        }
    }

    private function createResponse($status, $body) {
        http_response_code($status);
        header('Content-Type: application/json');
        echo $body;
    }
}
