<?php

class DuegnosController extends CommonController
{
    public function inicio(): void
    {
        $duegnos = $this->model->listado();
        $this->acciones($duegnos);
        $botonNuevoDuegno = $this->view->boton("Nuevo dueño", _MODULO . "/nuevo", "", "fa-user", "w3-blue");
        echo $this->view->tabla($duegnos, "Dueños", "Listado de todos los dueños", $botonNuevoDuegno);
    }

    private function acciones(array &$array): void
    {
        foreach ($array["filas"] as $id => &$fila) {
            $fila["acciones"] = $this->view->boton("", "/" . _MODULO . "/editar?duegno_id=" . $id, "", "fa-pencil", "w3-blue");
            $fila["acciones"] .= $this->view->boton("", "/" . _MODULO . "/eliminar?duegno_id=" . $id, "", "fa-trash", "w3-red");
        }
        $array["columnas"][] = "acciones";

    }

    public function nuevo(): void
    {
        if (!empty($_POST["nuevoDuegno"])) {
            unset($_POST["nuevoDuegno"]);
            if ($this->controlesFormDuegno()) {
                if ($this->model->crearDuegno($_POST)) {
                    mensajeAlUsuario("Dueño creado con exito!", "exito");
                    redireccionar(_MODULO);
                }
                mensajeAlUsuario("Fallo al crear dueño", "error");
            }
        }

        echo $this->view->nuevo_duegno();
    }


    public function controlesFormDuegno(): bool
    {
        $controles = true;
        sanitizarArray($_POST);
        if (!validarMail($_POST["mail"])) {
            $controles = false;
            mensajeAlUsuario("El mail no es valido", "error");
        }
        if (empty($_POST["nombre"]) || empty($_POST["mail"] || empty($_POST["telefono"]))) {
            $controles = false;
            mensajeAlUsuario("Falta un campo", "error");
        }
        return $controles;
    }


}