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
        $botonNuevoUsuario = $this->view->boton("Nuevo usuario", _MODULO . "/nuevo", "", "fa-user", "w3-blue");
        echo $this->view->tabla($usuarios, "Usuarios", "Listado de todos los usuarios", $botonNuevoUsuario);
    }

    public function nuevo()
    {
        if (!empty($_POST["nuevoUsuario"])) {
            $controles = true;
            sanitizar_array($_POST);
            if (!validar_mail($_POST["mail"])) {
                $controles = false;
                mensaje_al_usuario("El mail no es valido", "error");
            }
            if ($_POST["password"] != $_POST["password_reintento"]) {
                $controles = false;
                mensaje_al_usuario("Las contraseñas no coinciden", "error");
            }
            if (empty($_POST["nombre"]) || empty($_POST["mail"]) || empty($_POST["password"]) || empty($_POST["password_reintento"]) || empty($_POST["rol"])) {
                $controles = false;
                mensaje_al_usuario("Falta un campo", "error");
            }
            if ($controles) {
                $_POST["rol_id"] = $_POST["rol"];
                unset($_POST["rol"]);
                unset($_POST["password_reintento"]);
                unset($_POST["nuevoUsuario"]);
                $this->model->crearUsuario($_POST);
                mensaje_al_usuario("Usuario creado con exito!","exito");
                redireccionar(_MODULO);
            }
        }


        $roles = $this->model->buscar_opciones("roles", "nombre");
        echo $this->view->nuevo_usuario($roles);

    }
}