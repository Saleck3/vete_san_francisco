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

    public function info()
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

}