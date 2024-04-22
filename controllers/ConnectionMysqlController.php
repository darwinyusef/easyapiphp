<?php



class ConnectionMysqlController
{
    public static function connection()
    {
        $host = 'localhost';
        $db_name = 'productrestpr';
        $username = 'root';
        $password = '';

        try {
            $connection = new PDO(
                "mysql:host=$host;dbname=$db_name",
                $username,
                $password
            );
            $connection->setAttribute(
                PDO::ATTR_ERRMODE,
                PDO::ERRMODE_EXCEPTION
            );
            return $connection;
        } catch (PDOException $e) {
            die('Error de conexión: ' . $e->getMessage());
        }
    }
}
