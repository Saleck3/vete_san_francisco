<?php

class DuegnosModel extends CommonModel
{
    public function listado(): ?array
    {
        $sql = "
            SELECT
                d.id
                ,d.nombre
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
        }
        return $this->armarListado($duegnos);

    }

    public function crearDuegno(array $datos): int
    {
        return $this->insertar("duegnos", $datos);

    }

    public function borrarDuegno(int $id_duegno): ?bool
    {
        return $this->borrar("duegnos", $id_duegno);
    }

    public function buscarDuegno(int $duegno_id): ?array
    {
        return $this->buscar("duegnos", array("id", "nombre", "numero_tel", "mail", "direccion"), " WHERE id = $duegno_id")[0];
    }

    public function editarDuegno(array $datos, int $duegno_id): int
    {
        return $this->modificar("duegnos", $datos, $duegno_id);
    }

}