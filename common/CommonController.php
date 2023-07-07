<?php

use JetBrains\PhpStorm\NoReturn;

class CommonController
{
    protected CommonView $view;
    protected CommonModel $model;

    public function __construct()
    {
        if (!$this->estaLogueado() && !in_array(mb_strtolower(_MODULO), _PAGINAS_PERMITIDAS_SIN_LOGIN)) {
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
        return false;
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
     */
    public function esAdmin(): bool
    {
        return $_SESSION["admin"] == _ROL_ADMIN;
    }

    /**
     * Devuelve el nombre de la vista a declarar
     *
     * @return CommonView
     */
    private function createView(): CommonView
    {
        if (file_exists($_SERVER["DOCUMENT_ROOT"] . "/secciones/" . _MODULO . "/" . _MODULO . "View.php")) {
            require_once($_SERVER["DOCUMENT_ROOT"] . "/secciones/" . _MODULO . "/" . _MODULO . "View.php");
            $vista = _MODULO . "View";
            return new $vista();
        }
        return new CommonView();
    }

    private function createModel()
    {
        if (file_exists($_SERVER["DOCUMENT_ROOT"] . "/secciones/" . _MODULO . "/" . _MODULO . "Model.php")) {
            require_once($_SERVER["DOCUMENT_ROOT"] . "/secciones/" . _MODULO . "/" . _MODULO . "Model.php");
            $vista = _MODULO . "Model";
            return new $vista();
        }
        return new CommonModel();
    }


}