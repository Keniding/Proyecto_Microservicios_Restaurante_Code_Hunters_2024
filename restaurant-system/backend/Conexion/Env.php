<?php

namespace Database;

require_once __DIR__ . '/../vendor/autoload.php';

use Dotenv\Dotenv;

class Env
{
    private $envPath;

    public function __construct($envPath = null) {
        $this->envPath = $envPath ?: __DIR__ . '/../';
    }

    public function load() {
        $dotenv = Dotenv::createImmutable($this->envPath);
        $dotenv->load();
    }
}