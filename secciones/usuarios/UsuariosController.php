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
        $botonNuevoUsuario = $this->view->boton("Nuevo usuario", _MODULO . "/nuevo", "fa-user", "w3-blue");
        echo $this->view->tabla($usuarios, "Usuarios", "Listado de todos los usuarios", $botonNuevoUsuario);
    }

    public function nuevo()
    {
        //TODO Generar usuario cuando se complete el form
        echo $this->view->nuevo_usuario($this->model->buscar("roles",null,null,null,"id"));
    }
}