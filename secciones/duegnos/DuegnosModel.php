<?php

class DuegnosModel extends CommonModel
{
    public function listado(): ?array
    {
        $sql = "
            SELECT
                d.id
                ,d.nombre
                ,d.apellido
                ,d.numero_tel
                ,d.mail
                ,d.direccion 
            FROM duegnos d";

        $duegnos = $this->consulta($sql);
        if (empty($duegnos)) {
            return $this->armarListado($duegnos);
        }
        $this->acomodarID($duegnos, "id");
        foreach ($duegnos as &$duegno) {
            $duegno["nombre"] = ucwords($duegno["nombre"]);
            $duegno["apellido"] = ucwords($duegno["apellido"]);
        }
        return $this->armarListado($duegnos);

    }
}