<?php

class UsuariosModel extends CommonModel
{
    public function listado(): ?array
    {
        $sql = "
            SELECT
                u.id
                ,u.nombre
                ,u.mail
                ,u.matricula
                ,rol.nombre rol
            FROM usuarios u
                INNER JOIN roles rol ON u.rol_id = rol.id";

        $usuarios = $this->consulta($sql);
        $this->acomodarID($usuarios, "id");
        foreach ($usuarios as &$usuario) {
            $usuario["nombre"] = ucwords($usuario["nombre"]);
            $usuario["rol"] = ucwords($usuario["rol"]);
        }
        return $this->armarListado($usuarios);
    }

    public function crearUsuario(array $datos): int
    {
        return $this->insertar("usuarios", $datos);
    }

    public function borrarUsuario(int $id_usuario): ?bool
    {
        return $this->borrar("usuarios", $id_usuario);
    }

    public function buscarUsuario(int $usuario_id): ?array
    {
        return $this->buscar("usuarios", array("id", "nombre", "mail", "matricula", "rol_id"), " WHERE id = $usuario_id")[0];
    }

    public function editarUsuario(array $datos, int $usuario_id): int
    {
        return $this->modificar("usuarios", $datos, $usuario_id);
    }
}