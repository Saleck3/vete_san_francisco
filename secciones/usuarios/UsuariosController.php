<?php

use JetBrains\PhpStorm\NoReturn;

class UsuariosController extends CommonController
{
    public function __construct()
    {
        parent::__construct();
    }

    public function inicio(): void
    {
        $usuarios = $this->model->listado();
        $this->acciones($usuarios);
        $botonNuevoUsuario = $this->view->boton("Nuevo usuario", _MODULO . "/nuevo", "", "fa-user", "w3-blue");
        echo $this->view->tabla($usuarios, "Usuarios", "Listado de todos los usuarios", $botonNuevoUsuario);
    }

    /**
     * Agrega la columna de acciones
     *
     * @param array $array
     *
     * @return void
     */
    private function acciones(array &$array): void
    {
        if (esAdmin()) {
            foreach ($array["filas"] as $id => &$fila) {
                $fila["acciones"] = $this->view->boton("", "/" . _MODULO . "/editar?usuario_id=" . $id, "", "fa-pencil", "w3-blue");
                if ($id != $_SESSION["usuario"]["id"]) {
                    $fila["acciones"] .= $this->view->boton("", "/" . _MODULO . "/eliminar?usuario_id=" . $id, "", "fa-trash", "w3-red");
                }
            }
            $array["columnas"][] = "acciones";
        }
    }

    public function nuevo(): void
    {
        if (!empty($_POST["nuevoUsuario"])) {
            if ($this->controlesFormUsuario()) {
                $_POST["password"] = password_hash($_POST["password"], PASSWORD_DEFAULT);
                unset($_POST["password_reintento"]);
                unset($_POST["nuevoUsuario"]);
                $this->model->crearUsuario($_POST);
                mensajeAlUsuario("Usuario creado con exito!", "exito");
                redireccionar(_MODULO);
            }
        }

        $roles = $this->model->buscarOpciones("roles", "nombre");
        echo $this->view->nuevo_usuario($roles);
    }

    #[NoReturn] public function eliminar(): void
    {
        $id_usuario = validarNumero($_REQUEST["usuario_id"]);
        if (!esAdmin()) {
            mensajeAlUsuario("No tenes permisos para eliminar usuarios", "error");
        } else {
            if ($this->model->borrarUsuario($id_usuario)) {
                mensajeAlUsuario("Se elimino el usuario con exito", "exito");
            } else {
                mensajeAlUsuario("No se pudo eliminar el usuario", "error");
            }
        }
        redireccionar(_MODULO);
    }

    public function editar()
    {
        if (!empty($_POST["editarUsuario"])) {
            if ($this->controlesFormUsuario()) {
                $usuario_id = $_POST["usuario_id"];
                $datos = array();

                if (!empty($_POST["password"])) {
                    $datos["password"] = password_hash($_POST["password"], PASSWORD_DEFAULT);
                }

                $datos["nombre"] = $_POST["nombre"];
                $datos["mail"] = $_POST["mail"];
                $datos["matricula"] = $_POST["matricula"];
                $datos["rol_id"] = $_POST["rol_id"];

                if ($this->model->editarUsuario($datos, $usuario_id)) {
                    mensajeAlUsuario("Usuario actualizado con exito!", "exito");
                    redireccionar(_MODULO);
                }
                mensajeAlUsuario("No se pudo actualizar el usuario", "error");
            }
        }

        $_POST = $this->model->buscarUsuario(validarNumero($_REQUEST["usuario_id"]));
        $roles = $this->model->buscarOpciones("roles", "nombre");
        echo $this->view->editar($roles);
    }

    /**
     * Controla los campos de los forms de creacion y modificacion de usuario
     *
     * @return bool
     */
    public function controlesFormUsuario(): bool
    {
        $controles = true;
        sanitizarArray($_POST);
        if (!validarMail($_POST["mail"])) {
            $controles = false;
            mensajeAlUsuario("El mail no es valido", "error");
        }
        if ($_POST["password"] != $_POST["password_reintento"]) {
            $controles = false;
            mensajeAlUsuario("Las contraseñas no coinciden", "error");
        }
        if (empty($_POST["nombre"]) || empty($_POST["mail"]) || empty($_POST["password"]) || empty($_POST["password_reintento"]) || empty($_POST["rol_id"])) {
            $controles = false;
            mensajeAlUsuario("Falta un campo", "error");
        }
        return $controles;
    }
}