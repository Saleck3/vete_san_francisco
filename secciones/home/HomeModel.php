<?php

class HomeModel extends CommonModel
{

    public function login($user = null, $password = null)
    {
        if (empty($user) || empty($password)) {
            mensajeAlUsuario("Error al ingresar, valide su usuario y clave", "error");
            return false;
        }

        $datos_usuario = $this->buscar("usuarios", null, " WHERE mail = :mail", array("mail" => $user))[0];

        if (empty($datos_usuario) || !password_verify($password, $datos_usuario["password"])) {
            mensajeAlUsuario("Usuario o clave invalidos", "error");
            return false;
        }

        unset($datos_usuario["password"]);
        $_SESSION["usuario"] = $datos_usuario;
        return true;

    }
}