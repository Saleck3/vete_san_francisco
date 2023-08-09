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
            if ($_ENV["DEBUG"]) {
                $this->logger->logError($e->getMessage());
            }
            die;
        }

        $this->db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        $this->db->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);
    }

    /**
     * Inicia una transaccion en la base de datos
     *
     * @return void
     */
    public function transactionBegin(): void
    {
        $this->db->beginTransaction();
    }

    /**
     * Commitea una transaccion
     *
     * @return void
     */
    public function transactionCommit(): void
    {
        $this->db->commit();
    }

    /**
     * Hace rollback de una transaccion
     *
     * @return void
     */
    public function transactionRollback(): void
    {
        $this->db->rollback();
    }

    /**
     * Hace una consulta SELECT a la tabla
     *
     * @param string $tabla
     * @param array|null $campos Si esta seteado, completa el array con los valores
     * @param string|null $where Condiciones por las que filtrar
     * @param array|null $toBind Array a pasar por parametro en los where
     * @param string|null $columnaID Nombre de la columna ID, Si se setea acomoda el array para que sea [id]=>valores
     *
     * @return array|null
     */
    public function buscar(string $tabla, array $campos = null, string $where = null, array $toBind = null, string $columnaID = null): ?array
    {
        if (!empty($where)) {
            if (!str_contains(strtoupper($where), "WHERE")) {
                $where = " WHERE " . $where;
            }
        } else {
            $where = " ";
        }
        $camposSelect = " * ";

        if (!empty($campos)) {
            if (!empty($columnaID) && !in_array($columnaID, $campos)) {
                $campos[] = $columnaID;
            }
            $camposSelect = implode(" ,", $campos);
        }

        $query = "SELECT " . $camposSelect . " FROM " . $tabla . $where;
        $datosCrudos = $this->consulta($query, $toBind);
        if (!empty($columnaID)) {
            $this->acomodarID($datosCrudos, $columnaID);
        }

        return $datosCrudos;
    }

    public function acomodarID(array &$datosCrudos, string $columnaID): void
    {
        $resultAcomodado = array();
        foreach ($datosCrudos as $linea) {
            $id = $linea[$columnaID];
            unset($linea[$columnaID]);
            $resultAcomodado[$id] = $linea;
        }
        $datosCrudos = $resultAcomodado;
    }

    /**
     * Hace una consulta SELECT a la tabla y devuelve los datos formateados en filas y columnas
     *
     * @param string $tabla
     * @param array|null $campos Si esta seteado, completa el array con los valores
     * @param string|null $where Condiciones por las que filtrar
     * @param array|null $toBind Array a pasar por parametro en los where
     *
     * @return array|null [columnas]=> Encabezados [filas] => datos
     */
    public function buscarListado(string $tabla, array $campos = null, string $where = null, array $toBind = null): ?array
    {
        $datosCrudos = $this->buscar($tabla, $campos, $where, $toBind);
        if (empty($datosCrudos)) return null;

        $resultado["filas"] = $datosCrudos;
        $resultado["columnas"] = array_keys($datosCrudos[0]);
        return $resultado;
    }

    /**
     * Hace una consulta SELECT a la tabla y devuelve los datos formateados en filas y columnas
     *
     * @param string $tabla
     * @param string|null $campo Si esta seteado, completa el array con los valores
     * @param string|null $where Condiciones por las que filtrar
     * @param array|null $toBind Array a pasar por parametro en los where
     * @param string $columnaId
     *
     * @return array|null [columnas]=> Encabezados [filas] => datos
     */
    public function buscarOpciones(string $tabla, string $campo = null, string $where = null, array $toBind = null, string $columnaId = "id"): ?array
    {
        if (empty($campo))
            $campo = "nombre";

        $datosCrudos = $this->buscar($tabla, array($campo), $where, $toBind, $columnaId);
        if (empty($datosCrudos)) return null;
        $resultado = array();
        foreach ($datosCrudos as $id => $valor) {
            $resultado[$id] = $valor[$campo];
        }
        return $resultado;
    }

    /**
     * Convierte un array en listable
     *
     * @param array $datosCrudos
     *
     * @return array [columnas]=> Encabezados [filas] => datos
     */
    public function armarListado(array $datosCrudos): array
    {
        $resultado["filas"] = $datosCrudos;
        $resultado["columnas"] = array_keys($datosCrudos[array_key_first($datosCrudos)]);
        return $resultado;
    }

    /**
     * Modifica un registro en la base
     *
     * @param string $tabla
     * @param array $datos
     * @param int $id
     *
     * @return bool|int|string|null Retorna la cantidad de filas que modifico,si hay error retorna false y si no modificó retorna true
     */
    function modificar(string $tabla, array $datos, int $id): bool|int|string|null
    {
        // Control de campos
        if (!is_numeric($id) || empty($datos)) {
            return null; // Si no llega el id retorno NULL
        }
        $id_tbl = null;

        if (isset($datos['id_tbl']) && !empty($datos['id_tbl'])) {
            $id_tbl = $datos['id_tbl'];
            unset($datos['id_tbl']);
        }

        $sentencias = array();
        // Obtengo las claves y valores del array y los separo
        foreach ($datos as $clave => $valor) {
            if ($clave != "") {
                $sentencias[] = $this->sentenciaCamposQuery($valor, $clave);
            }
            $toBind[$clave] = $valor;
        }
        $sentencias = join(",", $sentencias);
        // Creo la consulta SQL
        $query = "UPDATE " . $tabla . " ";
        $query .= "SET $sentencias ";

        if (!is_null($id_tbl)) {
            $query .= " WHERE id = $id_tbl";
        } else {
            $query .= "WHERE id = $id";
        }

        try {
            $stmt = $this->db->prepare($query);
            if (!empty($toBind)) {
                $this->binds($stmt, $toBind);
            }
            $stmt->execute();
        } catch (Exception $e) {
            if ($_ENV["DEBUG"]) {
                error_log("Query " . print_r($query, true));
                $this->logger->logError($e->getMessage());
            }
            return false;
        }

        if ($stmt->rowCount()) {
            return $stmt->rowCount();
        } else {
            return true;
        }
    }

    /**
     * Ejecuta una consulta base
     *
     * @param string $query
     * @param array|null $toBind
     *
     * @return array|false
     */
    public function consulta(string $query, array $toBind = null): bool|array
    {
        try {
            $stmt = $this->db->prepare($query);
            if (!empty($toBind)) {
                $this->binds($stmt, $toBind);
            }
            $stmt->execute();
        } catch (PDOException $e) {
            if ($_ENV["DEBUG"]) {
                $this->logger->logError($e->getMessage());
            }
        }
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
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
     *
     * @return integer|false (false en caso de error)
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
     *
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
     *
     * @return array ["values","binds"]
     */
    private function valoresInsert(array &$datos): array
    {
        $campos = array();
        $binds = array();
        // Obtengo las claves y valores del array y los separo
        if ($datos) foreach ($datos as $clave => $valor) {
            $value = $this->sentenciaCamposQuery($valor, $clave, false);
            if ($value == ":$clave") {
                $binds[$clave] = $valor;
            }
            $campos[] = $value;
        }
        return array('values' => "VALUES (" . join(",", $campos) . " )", "binds" => $binds);
    }


    /**
     * Resuelvo que retornar en la sentencia del campo del query, si $update es false retorna solo el valor
     *
     * @param string $valor
     * @param string $clave
     * @param bool $update
     *
     * @return int|string|void
     */
    public function sentenciaCamposQuery(string &$valor, string &$clave, bool $update = true)
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
                        return "$clave = NULL ";
                    } else {
                        return "null";
                    }
                } elseif ($valor === "") {
                    // Vacio
                    if ($update) {
                        return "$clave = '' ";
                    } else {
                        return "''";
                    }
                } elseif ($valor == 0) {
                    // Cero (0)
                    if ($update) {
                        return "$clave = 0 ";
                    } else {
                        return 0;
                    }
                }
                break;
            case "_autoincrement":
                if ($update) {
                    return "$clave = ($clave +1 ) ";
                } else {
                    return "1";
                }
            case "curdate()":
                if ($update) {
                    return "$clave = CURDATE() ";
                } else {
                    return "CURDATE() ";
                }
            case "now()":
                if ($update) {
                    return "$clave = NOW() ";
                } else {
                    return "NOW() ";
                }
            default:
                if ($update) {
                    return "$clave = :$clave ";
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
     *
     * @return void
     */
    private function binds(PDOStatement $stmt, array $datos): void
    {
        // Obtengo las claves y valores del array y los separo
        if ($datos) foreach ($datos as $clave => $valor) {
            if (is_numeric($valor)) {
                $type = PDO::PARAM_INT;
            } else {
                $type = PDO::PARAM_STR;
            }
            $stmt->bindValue($clave, $valor, $type);
        }
    }

    /**
     * Resuelve la parte del "ON DUPLICATE KEY" de una query
     *
     * @param array $datos
     *
     * @return string
     */
    private function getDuplicateInsert(array $datos): string
    {
        $campos = array();
        // Obtengo las claves y valores del array y los separo
        if ($datos) foreach ($datos as $clave => $valor) {
            $value = $this->sentenciaCamposQuery($valor, $clave);
            $campos[] = $value;
        }
        return "ON DUPLICATE KEY UPDATE " . join(",", $campos);
    }


    /**
     * Elimina los elementos de una tabla
     *
     * @param string $tabla
     * @param array|int|null $id
     * @param string|null $where
     *
     * @return bool|null TRUE si pudo eliminar FALSE si hubo un error y NULL si no se paso ningun where
     * @noinspection SqlWithoutWhere
     */
    public function borrar(string $tabla, array|int $id = null, string $where = null): ?bool
    {
        //TODO Reemplazar por eliminado logico
        if (empty($id) && empty($where)) {
            return null;
        }

        if (!empty($where)) {
            if (!str_contains(strtoupper($where), "WHERE")) {
                $where = " WHERE " . $where;
            }
        } else {
            if (is_array($id)) {
                $where = " WHERE id IN (" . join(" ,", $id) . ")";
            } else {
                $where = " WHERE id = $id";
            }
        }

        $query = "DELETE FROM " . $tabla . $where;
        try {
            $stmt = $this->db->prepare($query);
            return $stmt->execute();
        } catch (Exception $e) {
            if ($_ENV["DEBUG"]) {
                error_log("Query " . print_r($query, true));
                $this->logger->logError($e->getMessage());
            }
            return false;
        }

    }


    /**
     * Este metodo devolvera el where correspondiente, si se pasa el campo desde y el campo hasta
     * devolvera el where con el between entre ambos, si se pasa solo el campo hasta devolvera para que filtre por los menores
     * de ese valor, si se pasa solo desde se devolvera para que filtre por los valores mayores a ese valor.
     *
     * @param mixed $campo_base_de_datos campo con el cual se comparara los valores desde hasta
     * @param mixed|null $desde valor desde el cual quieres comparar por default null
     * @param mixed|null $hasta valor hasta el cual quieres comparar por default null
     *
     * @return string
     */
    public function whereDesdeHasta(mixed $campo_base_de_datos, mixed $desde = null, mixed $hasta = null): string
    {
        $this->escaparMysql($desde);
        $this->escaparMysql($hasta);
        $where = "";
        if ($desde && $hasta && $desde != $hasta) {
            $where .= "and $campo_base_de_datos BETWEEN '" . $desde . "' and '" . $hasta . "' ";


        } elseif ($desde && $hasta && $desde == $hasta) {
            $where .= "and $campo_base_de_datos  = '" . $desde . "' ";
        } elseif ($desde && !$hasta) {
            $where .= "and $campo_base_de_datos  >= '" . $desde . "' ";
        } elseif (!$desde && $hasta) {
            $where .= "and $campo_base_de_datos  <= '" . $hasta . "' ";
        }
        return $where;
    }

    /**
     * Sanitiza una variable para que sea pasable a MySQL
     *
     * @param array|string $string
     *
     * @return void
     */
    public function escaparMysql(array|string &$string): void
    {
        if (is_array($string)) {
            foreach ($string as $k => $v) {
                $this->escaparMysql($v);
                $string[$k] = $v;
            }
        } else {
            $string = str_replace("\'", "''", filter_var($string, FILTER_SANITIZE_ADD_SLASHES));
        }
    }
}