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
                $fila["acciones"] = $this->view->boton(href: "/" . _MODULO . "/editar?usuario_id=" . $id, icono: "fa-pencil", claseExtra: "w3-blue");
                if ($id != $_SESSION["usuario"]["id"]) {
                    $fila["acciones"] .= $this->view->boton(href: "/" . _MODULO . "/eliminar?usuario_id=" . $id, icono: "fa-trash", claseExtra: "w3-red");
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
                if ($this->model->crearUsuario($_POST)) {
                    mensaje_al_usuario("Usuario creado con exito!", "exito");
                    redireccionar(_MODULO);
                }
                mensaje_al_usuario("Fallo al crear usuario", "error");
            }
        }

        $roles = $this->model->buscarOpciones("roles", "nombre");
        echo $this->view->nuevo_usuario($roles);
    }

    #[NoReturn] public function eliminar(): void
    {
        $id_usuario = validarNumero($_REQUEST["usuario_id"]);
        if (!esAdmin()) {
            mensaje_al_usuario("No tenes permisos para eliminar usuarios", "error");
        } else {
            if ($this->model->borrarUsuario($id_usuario)) {
                mensaje_al_usuario("Se elimino el usuario con exito", "exito");
            } else {
                mensaje_al_usuario("No se pudo eliminar el usuario", "error");
            }
        }
        redireccionar(_MODULO);
    }

    /**
     * @throws Exception
     */
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

                if (!empty($_FILES)) {
                    $foto_perfil = $this->archivoSubido("foto_perfil", "fotos_perfil/" . $datos["nombre"], "imagen")[0];
                    if ($foto_perfil !== false) {
                        $datos["imagen_perfil"] = $foto_perfil;
                    } else {
                        mensaje_al_usuario("Error al subir la foto de perfil");
                    }
                }

                if ($this->model->editarUsuario($datos, $usuario_id)) {
                    mensaje_al_usuario("Usuario actualizado con exito!", "exito");
                    redireccionar(_MODULO);
                }
                mensaje_al_usuario("No se pudo actualizar el usuario", "error");
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
        sanitizar_array($_POST);
        if (!validarMail($_POST["mail"])) {
            $controles = false;
            mensaje_al_usuario("El mail no es valido", "error");
        }
        if ($_POST["password"] != $_POST["password_reintento"]) {
            $controles = false;
            mensaje_al_usuario("Las contraseñas no coinciden", "error");
        }
        if (empty($_POST["nombre"]) || empty($_POST["mail"]) || empty($_POST["rol_id"])) {
            $controles = false;
            mensaje_al_usuario("Falta un campo", "error");
        }
        return $controles;
    }
}