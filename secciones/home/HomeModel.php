<?php

class HomeModel extends CommonModel
{

    public function login($user = null, $password = null): bool
    {
        if (empty($user) || empty($password)) {
            mensaje_al_usuario("Error al ingresar, valide su usuario y clave", "error");
            return false;
        }

        $datos_usuario = $this->buscar("usuarios", null, " WHERE mail = :mail", array("mail" => $user))[0];

        if (empty($datos_usuario) || !password_verify($password, $datos_usuario["password"])) {
            mensaje_al_usuario("Usuario o clave invalidos", "error");
            return false;
        }

        unset($datos_usuario["password"]);
        $_SESSION["usuario"] = $datos_usuario;
        return true;

    }
}