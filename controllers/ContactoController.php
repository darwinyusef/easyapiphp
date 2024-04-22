<?php

class ContactoController
{
    public function contacto()
    {
        // get Queries
        $queries = $_GET;
        $pagina = isset($queries['page']) ? $queries['page'] : 1;
        $limite = isset($queries['limit']) ? $queries['limit'] : 10;
        var_dump($pagina);

        // get body JSON
        $body = file_get_contents('php://input');
        $datosJson = json_decode($body, true);
        $nombre = isset($datosJson['nombre']) ? $datosJson['nombre'] : '';
        $email = isset($datosJson['email']) ? $datosJson['email'] : '';
        var_dump($nombre, $email);
        echo '<h1>Formulario de contacto</h1>';
    }
}
