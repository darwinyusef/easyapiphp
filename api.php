<?php

// Definir rutas
$rutas = [
    '/' => 'InicioController@index',
    '/acerca-de' => 'AcercaDeController@mostrar',
    '/contacto' => 'ContactoController@contacto',
    '/productos/todos' => 'ProductoController@getAllProducts',
    '/producto' => 'ProductoController@getOnlyProduct',
    '/crear-producto' => 'ProductoController@postProduct',
];

$actualfolder = 'prCrud';
// Obtener la URL actual se debe incluir la carpeta base
$uri = str_replace(
    '/' . $actualfolder . '/api.php',
    '',
    parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH)
);

// obtiene los parametros de ruta basicos de tipo int o string para agregar a los metodos
$param = null;
$paramExplode = explode('/', $uri);
// Capturar el parámetro de la URL
$paramCount = count($paramExplode);
if ($paramCount > 2) {
    if ($paramExplode[2] != 'todos') {
        $param = $paramExplode[2];
        $uri = '/' . $paramExplode[1];
    }
}

// Validar si la URL existe en las rutas
if (array_key_exists($uri, $rutas)) {
    // Extraer el controlador y el método
    $controladorYMetodo = $rutas[$uri];
    $partes = explode('@', $controladorYMetodo);
    $controlador = $partes[0];
    $metodo = $partes[1];

    // Capturar el parámetro de la URL
    // Incluir el controlador y llamar al método
    if (file_exists("controllers/$controlador.php")) {
        getController($controlador, $metodo, $param);
    } else {
        echo "Error: Controlador $controlador no encontrado";
    }
} else {
    $controlador = 'ExceptionsController';
    $metodo = 'index';
    http_response_code(404);
    $id = null;
    getController($controlador, $metodo, $id);
}

function getController($controlador, $metodo, $param)
{
    include_once "controllers/$controlador.php";

    if (
        /* `method_exists` is a PHP function that checks if a method exists in a certain class. It
    takes two parameters: the class name and the method name. If the method exists in the class,
    it returns `true`; otherwise, it returns `false`. In the provided code, `method_exists` is
    used to check if a specific method exists in the controller class before calling it. */
        method_exists($controlador, $metodo)
    ) {
        try {
            // var_dump($controlador, $metodo, $param);
            call_user_func([new $controlador(), $metodo], $param);
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
                    $id = null;
                    getController($controlador, $metodo, $id);
                    break;
                // Mostrar un código de error HTTP renderizando una view 500
                case 500:
                    header('HTTP/1.1 501 Internal Server Error');
                    http_response_code(500);
                    $controlador = 'ExceptionsController';
                    $metodo = 'errorquinientos';
                    $id = null;
                    getController($controlador, $metodo, $id);
                    break;
                default:
                    // Mostrar un código de error HTTP lanzando un json
                    http_response_code($codigoError);
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
}
