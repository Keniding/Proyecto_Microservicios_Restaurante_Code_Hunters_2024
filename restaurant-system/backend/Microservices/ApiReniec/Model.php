<?php

namespace Microservices\ApiReniec;

use ApiReniecDni\PersonaReniec;
use Exception;

class Model
{
    /**
     * @throws Exception
     */
    public function getByDni($id): array
    {
        try {
            $persona = new PersonaReniec();
            if ($persona->obtenerDatosPorDNI($id)) {
                return $persona->obtenerTodosDatos();
            } else {
                throw new Exception("No se encontraron datos para el DNI proporcionado.");
            }
        } catch (Exception $e) {
            throw new Exception($e->getMessage());
        }
    }
}
