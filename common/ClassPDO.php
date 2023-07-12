<?php

class ClassPDO
{

    public function __construct($host = _DB_HOST, $name = _DB_NOMBRE, $user = _DB_USUARIO, $password = _DB_CLAVE, $port = _DB_PUERTO, $charset = _DB_CHARSET)
    {
        try {
            $pdo = new PDO( 'mysql:host=' . $host . ';dbname=' . $name . ';port=' . $port . ';charset=' . $charset, $user, $password);
        } catch (PDOException $e) {
            if (_DEBUG) {
                echo "ERROR de conexion a la base de datos: " . $e->getMessage();
            } else {
                echo "ERROR de conexion a la base de datos";
            }
            die;
        }

        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        $pdo->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);

        return $pdo;
    }
}