<?php

namespace Auth;

class Middleware
{
    public static function checkAuth(): void
    {
        if (!isset($_SESSION['user_id']) || !isset($_SESSION['token'])) {
            http_response_code(401);
            echo json_encode(['status' => 'error', 'message' => 'No autorizado']);
            exit();
        }
    }
}