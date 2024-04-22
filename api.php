<?php

// Definir rutas
$rutas = [
    '/' => 'InicioController@index',
    '/acerca-de' => 'AcercaDeController@mostrar',
    '/contacto' => 'ContactoController@contacto',
];

// Obtener la URL actual
$uri = str_replace(
    '/prCrud/api.php',
    '',
    parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH)
);

// Validar si la URL existe en las rutas
if (array_key_exists($uri, $rutas)) {
    // Extraer el controlador y el método
    $controladorYMetodo = $rutas[$uri];
    $partes = explode('@', $controladorYMetodo);
    $controlador = $partes[0];
    $metodo = $partes[1];
    // Incluir el controlador y llamar al método
    if (file_exists("controllers/$controlador.php")) {
        getController($controlador, $metodo);
    } else {
        echo "Error: Controlador $controlador no encontrado";
    }
} else {
    $controlador = 'ExceptionsController';
    $metodo = 'index';
    http_response_code(404);
    getController($controlador, $metodo);
}

function getController($controlador, $metodo)
{
    try {
        include_once "controllers/$controlador.php";
        if (method_exists($controlador, $metodo)) {
            try {
                call_user_func([new $controlador(), $metodo]);
            } catch (Exception $e) {
                $codigoError = $e->getCode();
                $mensajeError = $e->getMessage();

                switch ($codigoError) {
                    // Mostrar un código de error HTTP renderizando una view 404
                    case 404:
                        header('HTTP/1.1 404 Not Found');
                        http_response_code(404);
                        $controlador = 'ExceptionsController';
                        $metodo = 'index';
                        getController($controlador, $metodo);
                        break;
                    // Mostrar un código de error HTTP renderizando una view 500
                    case 500:
                        header('HTTP/1.1 501 Internal Server Error');
                        http_response_code(500);
                        $controlador = 'ExceptionsController';
                        $metodo = 'errorquinientos';
                        getController($controlador, $metodo);
                        break;
                    default:
                        // Mostrar un código de error HTTP lanzando un json
                        header('HTTP/1.1' . $codigoError . $mensajeError);
                        echo json_encode([
                            'error' => $e->getMessage(),
                            'codigo' => $codigoError,
                            'mensaje' => $mensajeError,
                        ]);
                }

                exit(); // Detener la ejecución del script
            }
        } else {
            echo "Error: Método $metodo no encontrado en el controlador $controlador";
        }
    } catch (Exception $e) {
        // Capturar la excepción

        // Mostrar mensaje de error personalizado según el código
    }
}
