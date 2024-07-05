<?php

namespace Auth;

use Random\RandomException;

session_start();

class Controller
{
    private Model $userModel;

    public function __construct($userModel)
    {
        $this->userModel = $userModel;
    }

    /**
     * @throws RandomException
     */
    public function login($dni, $password): bool|string
    {
        $user = $this->userModel->findByDNI($dni);
        if ($user && password_verify($password, $user['password'])) {
            $token = bin2hex(random_bytes(16));
            $_SESSION['user_id'] = $user['id'];
            $_SESSION['token'] = $token;
            return json_encode(['status' => 'success', 'token' => $token]);
        } else {
            return json_encode(['status' => 'error', 'message' => 'Credenciales inválidas']);
        }
    }

    public function logout(): bool|string
    {
        session_destroy();
        return json_encode(['status' => 'success', 'message' => 'Sesión cerrada']);
    }

    public function checkAuth(): bool
    {
        if (isset($_SESSION['user_id']) && isset($_SESSION['token'])) {
            return true;
        }
        return false;
    }
}