<?php

class ExceptionsController
{
    public $email = 'wsgestor@gmail.com';

    public function index()
    {
        // Cargar la plantilla
        $plantilla = file_get_contents('views/404.html');

        // Reemplazar variables dinámicas
        $nombre = $this->email;
        $plantilla = str_replace('{{nombre}}', $nombre, $plantilla);

        // Generar la salida HTML final
        echo $plantilla;
    }

    public function errorquinientos()
    {
        // Cargar la plantilla
        $plantilla = file_get_contents('views/500.html');

        // Reemplazar variables dinámicas
        $nombre = $this->email;
        $plantilla = str_replace('{{nombre}}', $nombre, $plantilla);

        // Generar la salida HTML final
        echo $plantilla;
    }
}
