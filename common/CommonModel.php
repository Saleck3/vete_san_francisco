<?php

class CommonModel
{
    protected mixed $db;
    protected Logger $logger;

    public function __construct()
    {
        $this->logger = new Logger();

        try {
            $this->db = new PDO('mysql:host=' . $_ENV["DB_HOST"] . ';dbname=' . $_ENV["DB_NOMBRE"] . ';port=' . $_ENV["DB_PUERTO"] . ';charset=' . $_ENV["DB_CHARSET"], $_ENV["DB_USUARIO"], $_ENV["DB_CLAVE"]);
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
            if ($_ENV["DEBUG"]) {
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


    /**
     * Agrega un registro en una tabla. Si llega onDuplicateKey, entonces actualiza el registro, si es que ya existe en la base de datos.
     * Si agrega un registro, devuelve el id del registro agregado.
     * Si actualiza un registro, devuelve la cantidad de filas afectadas.
     * Si no se realizaron cambios, devuelve 0.
     * Si ocurrieron errores, devuelve false.
     *
     * @param string $tabla
     * @param array $datos
     * @param boolean $onDuplicateKey (true|false)
     * @return integer|false (false en caso de error)
     * @throws Exception
     * @example insert("noticias",array("denominacion"=>"Noticia","copete"=>"Copete"));
     */
    public function insertar(string $tabla, array &$datos, bool $onDuplicateKey = false): bool|int
    {

        // Creo la consulta SQL
        $query = "INSERT  INTO `{$tabla}`";
        $query .= $this->camposInsertar($datos);
        // obtengo datos y posibles binds
        $values = $this->valoresInsert($datos);
        $query .= $values['values'];

        if ($onDuplicateKey) {
            $query .= $this->getDuplicateInsert($datos);
        }

        try {
            $stmt = $this->db->prepare($query);
            $this->binds($stmt, $values['binds']);
            $stmt->execute();
            $affected = $stmt->rowCount();
            $last_insert_id = $this->db->lastInsertId();
            // Chequeo si hubo filas afectadas
            if ($affected) {
                //Hubo filas afectadas
                if ($onDuplicateKey) {
                    //Si es onDuplicateKey, devuelve la cantidad de filas afectadas
                    return $affected;
                }
                //Si no es onDuplicateKey, devuelve el id del registro insertado
                if ($last_insert_id) {
                    return $last_insert_id;
                }
            }
            //No hubo filas afectadas - Devuelve cero
            return 0;
        } catch (Exception $e) {
            if ($_ENV["DEBUG"]) {
                $this->logger->logError($e->getMessage());
            }
        }

        return false;
    }

    /**
     * Convierte el primer nivel de un array en string para insertar
     *
     * @param array $datos
     * @return string
     */
    private function camposInsertar(array &$datos): string
    {
        // Obtengo las claves y valores del array y los separo
        return "(" . join(",", array_keys($datos)) . ")";
    }

    /**
     * Convierte los campos en la parte de "VALUES" de la query pero tambien genera los binds para despues pasarle a binds
     *
     * @param array $datos
     * @return array ["values","binds"]
     */
    private function valoresInsert(array &$datos): array
    {
        $campos = array();
        $binds = array();
        // Obtengo las claves y valores del array y los separo
        if ($datos) foreach ($datos as $clave => $valor) {
            $value = $this->sentencia_campos_query($valor, $clave, false);
            if ($value == ":$clave") {
                $binds[$clave] = $valor;
            }
            $campos[] = $value;
        }
        return array('values' => "VALUES (" . join(",", $campos) . " )", "binds" => $binds);
    }


    /**
     *
     * Resuelvo que retornar en la sentencia del campo del query, si $update es false retorna solo el valor
     *
     * @param string $valor
     * @param string $clave
     * @param bool $update
     * @return int|string|void
     */
    public function sentencia_campos_query(string &$valor, string &$clave, bool $update = true)
    {
        if (!empty($valor)) {
            $valor = strtolower($valor);
        }

        switch ($valor) {
            case "":
            case null:
                if ($valor === null) {
                    // Valor null
                    if ($update) {
                        return "$clave = NULL";
                    } else {
                        return "null";
                    }
                } elseif ($valor === "") {
                    // Vacio
                    if ($update) {
                        return "$clave = ''";
                    } else {
                        return "''";
                    }
                } elseif ($valor == 0) {
                    // Cero (0)
                    if ($update) {
                        return "$clave = 0";
                    } else {
                        return 0;
                    }
                }
                break;
            case "_autoincrement":
                if ($update) {
                    return "$clave = ($clave +1 )";
                } else {
                    return "1";
                }
            case "curdate()":
                if ($update) {
                    return "$clave = CURDATE()";
                } else {
                    return "CURDATE()";
                }
            case "now()":
                if ($update) {
                    return "$clave = NOW()";
                } else {
                    return "NOW()";
                }
            default:
                // Con datos
                if ($update) {
                    return "$clave = :$clave";
                } else {
                    return ":$clave";
                }
        }
    }

    /**
     * Resuelve los binds para las sentencias preparadas
     *
     * @param PDOStatement $stmt
     * @param array $datos
     * @return int
     */
    private function binds(PDOStatement $stmt, array $datos): int
    {
        // Obtengo las claves y valores del array y los separo
        $binds = 0;
        if ($datos) foreach ($datos as $clave => $valor) {
            if (is_numeric($valor)) {
                $type = PDO::PARAM_INT;
            } else {
                $type = PDO::PARAM_STR;
            }
            $stmt->bindValue($clave, $valor, $type);
            $binds++;
        }
        return $binds;
    }

    /**
     * Resuelve la parte del "ON DUPLICATE KEY" de una query
     *
     * @param array $datos
     * @return string
     */
    private function getDuplicateInsert(array $datos): string
    {
        $campos = array();
        // Obtengo las claves y valores del array y los separo
        if ($datos) foreach ($datos as $clave => $valor) {
            $value = $this->sentencia_campos_query($valor, $clave);
            $campos[] = $value;
        }
        return "ON DUPLICATE KEY UPDATE " . join(",", $campos);
    }


    public
    function borrar()
    {

    }
}