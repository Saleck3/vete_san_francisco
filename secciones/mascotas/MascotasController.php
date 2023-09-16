<?php

use JetBrains\PhpStorm\NoReturn;

class MascotasController extends CommonController
{
    public function inicio(): void
    {
        $mascotas = $this->model->listado();
        $this->acciones($mascotas);
        $botonNuevaMascota = $this->view->boton("Nueva mascota", _MODULO . "/nuevo", "", "fa-user", "w3-blue");
        echo $this->view->tabla($mascotas, "Mascotas", "Listado de todas las mascotas", $botonNuevaMascota);
    }

    private function acciones(?array &$datos): void
    {
        if ($datos == null) {
            return;
        }
        foreach ($datos["filas"] as $id => &$fila) {
            $fila["acciones"] = $this->view->boton("", "/" . _MODULO . "/editar?mascota_id=" . $id, "", "fa-pencil", "w3-blue");
            $fila["acciones"] .= $this->view->boton("", "/" . _MODULO . "/eliminar?mascota_id=" . $id, "", "fa-trash", "w3-red");
            $fila["muerto"] = $fila["muerto"] ?  'muerto':'vivo';
        }
        $datos["columnas"][] = "acciones";

    }

    public function nuevo(): void
    {
        if (!empty($_POST["nuevaMascota"])) {
            unset($_POST["nuevaMascota"]);
            if ($this->controlesFormMascota()) {
                if ($this->model->crearMascota($_POST)) {
                    mensaje_al_usuario("Mascota creada con exito!", "exito");
                    redireccionar(_MODULO);
                }
                mensaje_al_usuario("Fallo al crear mascota", "error");
            }
        }

        $raza = $this->model->buscarOpciones("razas", "nombre");
        $especie = $this->model->buscarOpciones("especies", "nombre");
        echo $this->view->nueva_mascota($raza, $especie);
    }

    #[NoReturn] public function eliminar(): void
    {
        $id_mascota = validarNumero($_REQUEST["mascota_id"]);

        if ($this->model->borrarMascota($id_mascota)) {
            mensaje_al_usuario("Se elimino la mascota con exito", "exito");
        } else {
            mensaje_al_usuario("No se pudo eliminar la mascota", "error");
        }

        redireccionar(_MODULO);
    }

    public function editar()
    {
        if (!empty($_POST["editarMascota"])) {
            if ($this->controlesFormMascota()) {
                $mascota_id = $_POST["mascota_id"];
                $datos = array();
                var_dump($_POST);

                $datos["nombre"] = $_POST["nombre"];
                $datos["raza_id"] = $_POST["raza_id"];
                $datos["especie_id"] = $_POST["especie_id"];
                $datos["color"] = $_POST["color"];
                $datos["peso"] = $_POST["peso"];
                $datos["fnac"] = $_POST["fnac"];
                $datos["muerto"] = !empty($_POST["muerto"]);



                if ($this->model->editarMascota($datos, $mascota_id)) {
                    mensaje_al_usuario("Mascota actualizada con exito!", "exito");
                    redireccionar(_MODULO);
                }
                mensaje_al_usuario("No se pudo actualizar la mascota", "error");
            }
        }
        $_POST = $this->model->buscarMascota(validarNumero($_REQUEST["mascota_id"]));
        $raza = $this->model->buscarOpciones("razas", "nombre");
        $especie = $this->model->buscarOpciones("especies", "nombre");
        echo $this->view->editar($raza, $especie);

    }

    public function controlesFormMascota(): bool
    {
        $controles = true;
        sanitizar_array($_POST);

        if (empty($_POST["nombre"])) {
            $controles = false;
            mensaje_al_usuario("Falta un campo", "error");
        }
        return $controles;
    }

}