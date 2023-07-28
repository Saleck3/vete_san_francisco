<?php

class UsuariosModel extends CommonModel
{
    public function listado(): ?array
    {
        $sql = "
            SELECT
                u.nombre
                ,u.mail
                ,u.matricula
                ,rol.nombre rol
            FROM usuarios u
                INNER JOIN roles rol ON u.rol_id = rol.id";

        $usuarios = $this->consulta($sql);
        foreach ($usuarios as &$usuario) {
            $usuario["nombre"] = ucwords($usuario["nombre"]);
            $usuario["rol"] = ucwords($usuario["rol"]);
        }
        return $this->armar_listado($usuarios);
    }

}