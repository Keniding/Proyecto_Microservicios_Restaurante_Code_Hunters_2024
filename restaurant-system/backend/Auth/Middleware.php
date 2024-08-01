<?php

namespace Auth;

use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
class Middleware
{
    public function __invoke(Request $request, $handler): Response
    {
        if (!isset($_SESSION['user_id']) || !isset($_SESSION['token'])) {
            $response = Response::class;
            $response->getBody()->write(json_encode(['status' => 'error', 'message' => 'No autorizado']));
            return $response->withHeader('Content-Type', 'application/json')->withStatus(401);
        }

        return $handler->handle($request);
    }
}
