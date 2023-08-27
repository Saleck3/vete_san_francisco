<?php

class MedicamentosController extends CommonController
{
    public function inicio(): void
    {
        $medicamentos = $this->model->listado();
        $this->acciones($medicamentos);
        $botonNuevoMedicamento = $this->view->boton(etiqueta: "Nuevo medicamento", href: _MODULO . "/nuevo", icono: "fa-user", claseExtra: "w3-blue");
        echo $this->view->tabla($medicamentos, "Medicamentos", "Listado de todos los medicamentos", $botonNuevoMedicamento);
    }

    /**
     * Agrega la columna de acciones
     *
     * @param array|null $array $array
     *
     * @return void
     */
    private function acciones(?array &$array = null): void
    {
        if (empty($array)) return;
        $array["columnas"][] = "acciones";
        foreach ($array["filas"] as $id => &$fila) {
            $fila["acciones"] = $this->view->boton(href: "/" . _MODULO . "/editar?medicamento_id=" . $id, icono: "fa-pencil", claseExtra: "w3-blue");
            $fila["acciones"] .= $this->view->boton(href: "/" . _MODULO . "/eliminar?medicamento_id=" . $id, icono: "fa-trash", claseExtra: "w3-red");
        }
    }

    public function nuevo(): void
    {
        if (!empty($_POST["nuevoMedicamento"])) {
            $datos["nombre"] = $_POST["nombre"];
            $datos["presentacion"] = $_POST["presentacion"];
            $this->model->insertar("medicamentos", $datos);
            mensaje_al_usuario("Medicamento creado con exito!", "exito");
            redireccionar(_MODULO);
        }

        echo $this->view->nuevo_medicamento();
    }

    public function editar()
    {
        if (!empty($_POST["editarMedicamento"])) {
            $medicamento_id = validarNumero($_POST["medicamento_id"]);
            $datos = array();
            $datos["nombre"] = $_POST["nombre"];
            $datos["presentacion"] = $_POST["presentacion"];

            if ($this->model->modificar("medicamentos", $datos, $medicamento_id)) {
                mensaje_al_usuario("Medicamento actualizado con exito!", "exito");
                redireccionar(_MODULO);
            }
            mensaje_al_usuario("No se pudo actualizar el Medicamento", "error");
        }

        $_POST = $this->model->buscar(tabla: "medicamentos", where: " WHERE id = " . validarNumero($_REQUEST["medicamento_id"]))[0];
        echo $this->view->editar_medicamento();
    }

    #[NoReturn] public function eliminar(): void
    {
        $medicamento_id = validarNumero($_REQUEST["medicamento_id"]);
        if ($this->model->borrar("medicamentos", $medicamento_id)) {
            mensaje_al_usuario("Se elimino el medicamento con exito", "exito");
        } else {
            mensaje_al_usuario("No se pudo eliminar el medicamento", "error");
        }
        redireccionar(_MODULO);
    }

}