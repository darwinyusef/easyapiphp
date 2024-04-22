<?php

include_once 'ConnectionMysqlController.php';

class ProductoController
{
    private $connection;

    public function __construct()
    {
        $this->connection = ConnectionMysqlController::connection();
    }

    public function getAllProducts()
    {
        $sql = 'SELECT * FROM products';
        $selProduct = $this->connection->prepare($sql);
        $selProduct->execute();
        header('HTTP/1.1 200 OK');
        echo json_encode($selProduct->fetchAll(PDO::FETCH_ASSOC));
    }

    public function getOnlyProduct($id)
    {
        $sql = 'SELECT * FROM products WHERE id = :id';
        $stmt = $this->connection->prepare($sql);
        $stmt->bindParam(':id', $id, PDO::PARAM_INT);
        $stmt->execute();
        header('HTTP/1.1 200 OK');
        echo json_encode($stmt->fetch(PDO::FETCH_ASSOC));
    }

    public function postProduct()
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $body = file_get_contents('php://input');
            $datosJson = json_decode($body, true);
            $name = isset($datosJson['name']) ? $datosJson['name'] : '';
            $description = isset($datosJson['description'])
                ? $datosJson['description']
                : '';
            $price = isset($datosJson['price']) ? $datosJson['price'] : '';
            $sql =
                'INSERT INTO products (name, description, price) VALUES (:name, :description, :price); SELECT LAST_INSERT_ID() AS nid;';
            $dbh = $this->connection;
            $stmt = $dbh->prepare($sql);

            // $dbh->beginTransaction();
            $stmt->bindParam(':name', $name, PDO::PARAM_STR);
            $stmt->bindParam(':description', $description, PDO::PARAM_STR);
            $stmt->bindParam(':price', $price, PDO::PARAM_INT);
            if ($stmt->execute()) {
                echo json_encode((object) ['response' => true]);
            } else {
                echo json_encode((object) ['response' => false]);
            }
            // $dbh->commit();
            // echo $dbh->lastInsertId();
        } else {
            throw new Exception('Debe solicitar este servicio por POST', 400);
        }
    }
}
