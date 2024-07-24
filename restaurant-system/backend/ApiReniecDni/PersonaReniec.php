<?php

namespace ApiReniecDni;

require 'Env.php';

use Exception;

class PersonaReniec {
    private $nombres;
    private $apellidoPaterno;
    private $apellidoMaterno;
    private $tipoDocumento;
    private $numeroDocumento;
    private $token;
    private $apiUrl = 'https://api.apis.net.pe/v2/reniec/dni?numero=';

    public function __construct() {
        $env = new Env();
        $env->load();
        $this->token = $_ENV['API_TOKEN'];
        $this->inicializarDatos();
    }

    private function inicializarDatos(): void
    {
        $this->nombres = '';
        $this->apellidoPaterno = '';
        $this->apellidoMaterno = '';
        $this->tipoDocumento = '';
        $this->numeroDocumento = '';
    }

    /**
     * @throws Exception
     */
    public function obtenerDatosPorDNI($dni): bool
    {
        $curl = curl_init();

        curl_setopt_array($curl, array(
            CURLOPT_URL => $this->apiUrl . $dni,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_SSL_VERIFYPEER => 0,
            CURLOPT_ENCODING => '',
            CURLOPT_MAXREDIRS => 2,
            CURLOPT_TIMEOUT => 0,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_CUSTOMREQUEST => 'GET',
            CURLOPT_HTTPHEADER => array(
                'Authorization: Bearer ' . $this->token
            ),
        ));

        $response = curl_exec($curl);
        $error = curl_error($curl);
        curl_close($curl);

        if ($error) {
            throw new Exception("Error en la llamada a la API: " . $error);
        }

        $persona = json_decode($response);

        if (isset($persona->numeroDocumento)) {
            $this->nombres = $persona->nombres ?? '';
            $this->apellidoPaterno = $persona->apellidoPaterno ?? '';
            $this->apellidoMaterno = $persona->apellidoMaterno ?? '';
            $this->tipoDocumento = $persona->tipoDocumento ?? '';
            $this->numeroDocumento = $persona->numeroDocumento ?? '';
            return true;
        }

        return false;
    }

    public function getNombres() {
        return $this->nombres;
    }

    public function getApellidoPaterno() {
        return $this->apellidoPaterno;
    }

    public function getApellidoMaterno() {
        return $this->apellidoMaterno;
    }

    public function getTipoDocumento() {
        return $this->tipoDocumento;
    }

    public function getNumeroDocumento() {
        return $this->numeroDocumento;
    }

    public function getDigitoVerificador() {
        return $this->digitoVerificador;
    }

    public function obtenerTodosDatos(): array
    {
        return [
            'nombres' => $this->nombres,
            'apellidoPaterno' => $this->apellidoPaterno,
            'apellidoMaterno' => $this->apellidoMaterno,
            'tipoDocumento' => $this->tipoDocumento,
            'numeroDocumento' => $this->numeroDocumento
        ];
    }
}
