<?php

/**
 * Clase por default cuando se entra a la pagina
 */
class HomeController extends CommonController
{
    public function __construct()
    {
        parent::__construct();
    }

    public function inicio()
    {
        if ($this->estaLogueado()) {
            $this->homeLogueado();
        } else {
            $this->view->inicio();
        }
    }

    public function homeLogueado()
    {

        if (!$this->estaLogueado()) {
            $this->inicio();

        } else {
            echo "Estas logueado! <br>";
        }
    }
}