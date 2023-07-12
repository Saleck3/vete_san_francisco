<?php

class CommonModel
{
    protected mixed $db;
    protected Logger $logger;

    public function __construct()
    {
        $this->logger = new Logger();

        try {
            $this->db = new PDO('mysql:host=' . _DB_HOST . ';dbname=' . _DB_NOMBRE . ';port=' . _DB_PUERTO . ';charset=' . _DB_CHARSET, _DB_USUARIO, _DB_CLAVE);
        } catch (PDOException $e) {
            $this->logger->logError($e->getMessage());
            die;
        }

        $this->db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        $this->db->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);
    }

    /**
     * Hace una consulta SELECT a la tabla
     *
     * @param string $tabla
     * @param array|null $campos Si esta seteado, completa el array con los valores
     * @param string|null $where Condiciones por las que filtrar
     * @param array|null $toBind Array a pasar por parametro en los where
     * @param string|null $columnaID Nombre de la columna ID, Si se setea acomoda el array para que sea [id]=>valores
     * @return array|null
     */
    public function buscar(string $tabla, array $campos = null, string $where = null, array $toBind = null, string $columnaID = null): ?array
    {
        if (!empty($where)) {
            if (!str_contains($where, "where")) {
                $where = " where " . $where;
            }
        } else {
            $where = " ";
        }
        $camposSelect = " * ";

        if (!empty($campos)) {
            $camposSelect = implode(" ,", $campos);
        }

        $query = "SELECT " . $camposSelect . " FROM " . $tabla . $where;
        var_dump($query);
        $result = null;
        try {
            $stmt = $this->db->prepare($query);

            if (!empty($toBind) && !empty($where)) {
                foreach ($toBind as $key => $value) {
                    $stmt->bindParam($key, $value);
                }
            }

            $stmt->execute();
            $result = $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (Exception $e) {
            if (_DEBUG) {
                $this->logger->logError($e->getMessage());
            }
        }

        if (!empty($columnaID)) {
            $resultAcomodado = array();
            foreach ($result as $linea) {
                $id = $linea[$columnaID];
                unset($linea[$columnaID]);
                $resultAcomodado[$id] = $linea;
            }
            $result = $resultAcomodado;
        }

        return $result;
    }

    public function actualizar()
    {

    }

    public function insertar(string $tabla, array $datos)
    {
        if (sizeof($datos) == 1) {
            $campos = implode(",", array_keys($datos));
            var_dump($campos);
            $sql = "INSERT INTO $tabla ($campos)  VALUES (" . implode(" ,", $datos[0]) . ")";
        }

        try {
            $res = $this->db->exec($sql);
        } catch (PDOException $e) {
            echo $sql . "<br>" . $e->getMessage();
        }

        return $res;

    }

    public function borrar()
    {

    }
}