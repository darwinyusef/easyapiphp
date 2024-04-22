# Este es codigo PHP Disponible para gestionar Apis Rapidas

Es posible crear api rest usando solo php sin intermediarios ni frameworks si requieres algo utra rapido

```php
class InicioController
{
    public function index()
    {
        // Captura el body tipo { "info": "info", "email": "sender@aquicreamos.com" }
        $body = file_get_contents('php://input');
        $datosJson = json_decode($body, true);
        $info = isset($datosJson['info']) ? $datosJson['info'] : '';
        $email = isset($datosJson['email']) ? $datosJson['email'] : '';
        
        //Exceptions  throw new Exception('División por cero..', 500);
        //HTML  echo '<h1>hola a todos</h1>';
        
        // Objects
        $phpObject = (object) [
            "info" => $info,
            "email" => $email,
            'name' => 'foo',
            'age' => 42,
        ];

        // lanzar http status with json
        header('HTTP/1.1 200 OK');
        echo json_encode($phpObject);

        // Redirect
        // header('Location: contacto');
        // exit();
    }
}
```

