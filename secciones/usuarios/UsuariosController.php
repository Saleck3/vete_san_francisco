<?php

class UsuariosController extends CommonController
{
    public function __construct()
    {
        parent::__construct();
    }

    public function inicio()
    {
        $usuarios = $this->model->listado();
        echo $this->view->tabla($usuarios,"Usuarios","Listado de todos los usuarios");
    }
}