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
        echo $this->view->inicio();
    }

    public function homeLogueado()
    {
        echo $this->view->inicio();
    }
}