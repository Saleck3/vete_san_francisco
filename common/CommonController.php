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

        if (!$this->estaLogueado() && !in_array(strtolower(_MODULO), explode(",",$_ENV["PAGINAS_PERMITIDAS_SIN_LOGIN"]))) {
            $this->redireccionarAInicio();
        }
        $this->model = $this->createModel();
        $this->view = $this->createView();
    }

    /**
     * Valida si el usuario inicio sesion
     *
     * @return bool
     */
    public function estaLogueado(): bool
    {
        return false;//TODO Reemplazar cuando se haga el login
    }

    /**
     * Redirecciona al modulo default y corta la ejecucion
     *
     * @return void
     */

    #[NoReturn] public function redireccionarAInicio(): void
    {
        header("Location: " . $_SERVER["SERVER_NAME"] . "/");
        die();
    }

    /**
     * Valida si un usuario es administrador
     *
     * @return bool
     */
    public function esAdmin(): bool
    {
        $_SESSION["rol"] = "admin";//TODO Reemplazar cuando se haga el login
        return $_SESSION["rol"] == $_ENV["ROL_ADMIN"];
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

    public function info()
    {
        if ($this->esAdmin()) {
            phpinfo();
            die();
        }
        $this->inicio();
    }


}