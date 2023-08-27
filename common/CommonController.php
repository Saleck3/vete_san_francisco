<?php

use JetBrains\PhpStorm\NoReturn;

class CommonController
{
    protected CommonView $view;
    protected CommonModel $model;
    protected Logger $logger;

    public function __construct()
    {
        $this->logger = new Logger();

        if (!estaLogueado() && !in_array(strtolower(_MODULO), explode(",", $_ENV["PAGINAS_PERMITIDAS_SIN_LOGIN"]))) {
            redireccionar();
        }
        $this->model = $this->createModel();
        $this->view = $this->createView();
    }

    /**
     * Devuelve el nombre de la vista a declarar
     *
     * @return CommonView
     */
    private function createView(): CommonView
    {
        if (file_exists($_SERVER["DOCUMENT_ROOT"] . "/secciones/" . strtolower(_MODULO) . "/" . _MODULO . "View.php")) {
            require_once($_SERVER["DOCUMENT_ROOT"] . "/secciones/" . strtolower(_MODULO) . "/" . _MODULO . "View.php");
            $vista = _MODULO . "View";
            return new $vista();
        }
        return new CommonView();
    }

    private function createModel()
    {
        if (file_exists($_SERVER["DOCUMENT_ROOT"] . "/secciones/" . strtolower(_MODULO) . "/" . _MODULO . "Model.php")) {
            require_once($_SERVER["DOCUMENT_ROOT"] . "/secciones/" . strtolower(_MODULO) . "/" . _MODULO . "Model.php");
            $model = _MODULO . "Model";
            return new $model();
        }
        return new CommonModel();
    }

    public function info(): void
    {
        if (esAdmin()) {
            phpinfo();
            die();
        }
        $this->inicio();
    }

    public function inicio(): void
    {
        echo $this->view->pagina("", _MODULO);
    }

    /**
     * Resuelve la subida de un archivo al filesystem
     *
     * @param string $nombre_form
     * @param string|null $nombre_a_guardar
     * @param string $formatos_aceptados
     * @param int $tamano_maximo_MB
     *
     * @return bool|array
     * @throws Exception
     */
    public function archivoSubido(string $nombre_form, string $nombre_a_guardar = null, string $formatos_aceptados = "imagen,pdf,word", int $tamano_maximo_MB = 5): bool|array
    {

        // Si el archivo no se subio o se subio con errores
        if (empty($_FILES[$nombre_form])) {
            mensaje_al_usuario("Error al subir el archivo ", "error");
            return false;
        }

        //una vez que ya terminamos de definir si está tod0 en orden, chequeamos que la carpeta de guardado exista
        if (!file_exists($_SERVER["DOCUMENT_ROOT"] . $_ENV['PATH_GUARDADO_FILES'])) {
            throw new Exception("El path de guardado no esta creado (" . $_SERVER["DOCUMENT_ROOT"] . $_ENV['PATH_GUARDADO_FILES'] . ")");
        }

        // Chequeo si se quiere guardar en un subdirectorio y creo la carpeta
        if (!empty($nombre_a_guardar) && str_contains($nombre_a_guardar, "/")) {
            $directorio = dirname($_SERVER["DOCUMENT_ROOT"] . $_ENV['PATH_GUARDADO_FILES'] . $nombre_a_guardar);
            if (!file_exists($directorio)) {
                mkdir($directorio, 0777, true);
            }
        }

        $input[0] = $_FILES[$nombre_form];
        if (is_array($_FILES[$nombre_form]["name"])) {
            for ($i = 0; $i < count($_FILES[$nombre_form]["name"]); $i++) {
                foreach ($_FILES[$nombre_form] as $key => $value) {
                    $input[$i][$key] = $value[$i];
                }
            }
        }

        foreach ($input as $file) {

            if (!empty($file["error"])) {
                mensaje_al_usuario("Error al subir el archivo " . $file["name"] . ": " . $file["error"], "error");
                continue;
            }

            // Verify mime
            $ext = array_search(mime_content_type($file["tmp_name"]), $this->tiposArchivoSegunMime($formatos_aceptados));
            if (!$ext) {
                mensaje_al_usuario("El documento ingresado como tiene un formato no aceptado. Por favor, elija otro archivo de un formato admitido.", "error");
                return false;
            }

            // Chequeo tamaño de imagen
            if ($file["size"] > $tamano_maximo_MB * 1024 * 1024) {
                mensaje_al_usuario("El documento ingresado es demasiado grande. Por favor, ingrese un archivo más pequeño.", "error");
                return false;
            }

            $nombre = $nombre_a_guardar ?: time();

            // Chequeo si hay un archivo subido con el mismo nombre y acomodar
            if (file_exists($_SERVER["DOCUMENT_ROOT"] . $_ENV['PATH_GUARDADO_FILES'] . $nombre . "." . $ext)) {
                $nombre = $nombre . "_" . time();
            }

            $nombre_archivo = $nombre . "." . $ext;

            if (move_uploaded_file($file["tmp_name"], $_SERVER["DOCUMENT_ROOT"] . $_ENV['PATH_GUARDADO_FILES'] . $nombre_archivo)) {
                $datos_guardar = array();
                if (strripos($nombre, "/")) {
                    $datos_guardar["nombre"] = substr($nombre, strripos($nombre, "/") + 1);
                } else {
                    $datos_guardar["nombre"] = $nombre;
                }

                $datos_guardar["path_absoluto"] = $_SERVER["DOCUMENT_ROOT"] . $_ENV['PATH_GUARDADO_FILES'] . $nombre_archivo;
                $datos_guardar["path_relativo"] = $_ENV['PATH_GUARDADO_FILES'] . $nombre_archivo;
                $datos_guardar["extension"] = $ext;
                $archivos[] = $this->model->insertar("archivos", $datos_guardar);
            }
        }

        return $archivos ?: false;
    }

    /**
     * Define los tipos de archivos permitidos
     *
     * @param string $tipos_archivo_permitidos
     *
     * @return array|string[]
     */
    public function tiposArchivoSegunMime(string $tipos_archivo_permitidos): array
    {

        $tipos_archivo_especifico = array();

        if (str_contains($tipos_archivo_permitidos, 'imagen')) {
            $imagen = array(
                "jpg" => "image/jpg",
                "jpeg" => "image/jpeg",
                "gif" => "image/gif",
                "png" => "image/png"
            );
            $tipos_archivo_especifico = array_merge($tipos_archivo_especifico, $imagen);
        }

        if (str_contains($tipos_archivo_permitidos, 'pdf')) {
            $pdf = array(
                'pdf' => 'application/pdf'
            );
            $tipos_archivo_especifico = array_merge($tipos_archivo_especifico, $pdf);
        }

        if (str_contains($tipos_archivo_permitidos, 'word')) {
            $word = array(
                'doc' => 'application/msword',
                'odt' => 'application/vnd.oasis.opendocument.text',
                '.dot' => 'application/msword',
                '.docx' => 'application/vnd.openxmlformats-officedocument.wordprocessingml.document',
                '.dotx' => 'application/vnd.openxmlformats-officedocument.wordprocessingml.template',
                '.docm' => 'application/vnd.ms-word.document.macroEnabled.12',
                '.dotm' => 'application/vnd.ms-word.template.macroEnabled.12'
            );
            $tipos_archivo_especifico = array_merge($tipos_archivo_especifico, $word);
        }

        return $tipos_archivo_especifico;
    }

}