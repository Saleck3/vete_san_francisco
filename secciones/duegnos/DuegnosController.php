<?php

use JetBrains\PhpStorm\NoReturn;

class DuegnosController extends CommonController
{
    public function inicio(): void
    {
        $duegnos = $this->model->listado();
        $this->acciones($duegnos);
        $botonNuevoDuegno = $this->view->boton("Nuevo dueño", _MODULO . "/nuevo", "", "fa-user", "w3-blue");
        echo $this->view->tabla($duegnos, "Dueños", "Listado de todos los dueños", $botonNuevoDuegno);
    }

    private function acciones(?array &$datos): void
    {
        if ($datos == null) {
            return;
        }
        foreach ($datos["filas"] as $id => &$fila) {
            $fila["acciones"] = $this->view->boton("", "/" . _MODULO . "/editar?duegno_id=" . $id, "", "fa-pencil", "w3-blue");
            $fila["acciones"] .= $this->view->boton("", "/" . _MODULO . "/eliminar?duegno_id=" . $id, "", "fa-trash", "w3-red");
        }
        $datos["columnas"][] = "acciones";

    }

    public function nuevo(): void
    {
        if (!empty($_POST["nuevoDuegno"])) {
            unset($_POST["nuevoDuegno"]);
            if ($this->controlesFormDuegno()) {
                if ($this->model->crearDuegno($_POST)) {
                    mensaje_al_usuario("Dueño creado con exito!", "exito");
                    redireccionar(_MODULO);
                }
                mensaje_al_usuario("Fallo al crear dueño", "error");
            }
        }

        echo $this->view->nuevo_duegno();
    }

    #[NoReturn] public function eliminar(): void
    {
        $id_duegno = validarNumero($_REQUEST["duegno_id"]);

        if ($this->model->borrarDuegno($id_duegno)) {
            mensaje_al_usuario("Se elimino el dueño con exito", "exito");
        } else {
            mensaje_al_usuario("No se pudo eliminar el dueño", "error");
        }

        redireccionar(_MODULO);
    }

    public function editar()
    {
        if (!empty($_POST["editarDuegno"])) {
            if ($this->controlesFormDuegno()) {
                $duegno_id = $_POST["duegno_id"];
                $datos = array();


                $datos["nombre"] = $_POST["nombre"];
                $datos["numero_tel"] = $_POST["numero_tel"];
                $datos["mail"] = $_POST["mail"];
                $datos["direccion"] = $_POST["direccion"];

                if ($this->model->editarDuegno($datos, $duegno_id)) {
                    mensaje_al_usuario("Dueño actualizado con exito!", "exito");
                    redireccionar(_MODULO);
                }
                mensaje_al_usuario("No se pudo actualizar el dueño", "error");
            }
        }
        $_POST = $this->model->buscarDuegno(validarNumero($_REQUEST["duegno_id"]));
        echo $this->view->editar();

    }


    public function controlesFormDuegno(): bool
    {
        $controles = true;
        sanitizar_array($_POST);
        if (!validarMail($_POST["mail"])) {
            $controles = false;
            mensaje_al_usuario("El mail no es valido", "error");
        }
        if (empty($_POST["nombre"]) || empty($_POST["mail"] || empty($_POST["telefono"]))) {
            $controles = false;
            mensaje_al_usuario("Falta un campo", "error");
        }
        return $controles;
    }


}