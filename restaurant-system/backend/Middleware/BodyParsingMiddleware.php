<?php

namespace Middleware;

class BodyParsingMiddleware
{
    public function __invoke(): void
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST' || $_SERVER['REQUEST_METHOD'] === 'PUT') {
            if (str_contains($_SERVER['CONTENT_TYPE'], 'application/json')) {
                $input = json_decode(file_get_contents('php://input'), true);
                if (json_last_error() === JSON_ERROR_NONE) {
                    $_POST = $input;
                } else {
                    http_response_code(400);
                    echo json_encode(['error' => 'Invalid JSON input']);
                    exit;
                }
            }
        }
    }
}
