<?php
class InicioController
{
    public function index()
    {
        $body = file_get_contents('php://input');
        $datosJson = json_decode($body, true);
        $info = isset($datosJson['info']) ? $datosJson['info'] : '';
        // throw new Exception('División por cero..', 500);
        // echo '<h1>hola a todos</h1>';
       
        $phpObject = (object) [
            "info" => $info,
            'name' => 'foo',
            'age' => 42,
        ];
        header('HTTP/1.1 200 OK');
        echo json_encode($phpObject);
        // header('Location: contacto');
        // exit();
    }
}
