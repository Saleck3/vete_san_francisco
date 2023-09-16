<?php

class MascotasModel extends CommonModel
{
    public function listado(): ?array
    {
        $sql = "
            SELECT
                m.id
                ,m.nombre
                ,r.nombre raza
                ,e.nombre especie
                ,m.color
                ,concat(m.peso , 'kg') peso
                ,m.fnac 'fecha de nacimiento'
                ,m.muerto
            FROM mascotas m
                INNER JOIN razas r ON m.raza_id = r.id
                INNER JOIN especies e ON m.especie_id = e.id";


        $mascotas = $this->consulta($sql);
        if (empty($mascotas)) {
            return $this->armarListado($mascotas);
        }
        $this->acomodarID($mascotas, "id");
        foreach ($mascotas as &$mascota) {
            $mascota["nombre"] = ucwords($mascota["nombre"]);
        }
        return $this->armarListado($mascotas);

    }
    public function crearMascota(array $datos): int
    {
        return $this->insertar("mascotas", $datos);
    }
    public function borrarMascota(int $mascota_id): ?bool
    {
        return $this->borrar("mascotas", $mascota_id);
    }

    public function buscarMascota(int $mascota_id): ?array
    {
        return $this->buscar("mascotas", array("id", "nombre", "raza_id", "especie_id", "color", "peso", "fnac", "muerto"), " WHERE id = $mascota_id")[0];
    }

    public function editarMascota(array $datos, int $mascota_id): int
    {
        return $this->modificar("mascotas", $datos, $mascota_id);
    }
}