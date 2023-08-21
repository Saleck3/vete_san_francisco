<?php

class MedicamentosView extends CommonView
{
    public function nuevo_medicamento()
    {
        ob_start(); ?>
        <h1>Nuevo medicamento</h1>

        <form method="post" name="nuevoMedicamento" id="nuevoMedicamento" class="w3-card-4 w3-padding w3-purple "
              style="width: 70%; margin: auto">
            <?= $this->campoFormTexto("nombre", "Nombre", "Ingrese nombre de medicamento", true); ?>
            <?= $this->campoFormTexto("presentacion", "Presentacion", "Ingrese la presentacion"); ?>
            <?= $this->botonesForm("Crear medicamento", "nuevoMedicamento"); ?>
        </form>

        <?php return $this->pagina(ob_get_clean());
    }

    public function editar_medicamento()
    {
        ob_start(); ?>
        <h1>Nuevo medicamento</h1>

        <form method="post" name="nuevoMedicamento" id="nuevoMedicamento" class="w3-card-4 w3-padding w3-purple "
              style="width: 70%; margin: auto">
            <?= $this->campoFormTexto("nombre", "Nombre", "Ingrese nombre de medicamento", true); ?>
            <?= $this->campoFormTexto("presentacion", "Presentacion", "Ingrese la presentacion"); ?>
            <?= $this->campoHidden("medicamento_id", $_POST["id"]); ?>

            <?= $this->botonesForm("Crear medicamento", "editarMedicamento"); ?>
        </form>

        <?php return $this->pagina(ob_get_clean());
    }

}