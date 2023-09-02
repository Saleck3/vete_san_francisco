<?php

class DuegnosView extends CommonView
{
    public function nuevo_duegno(): bool|string
    {
        ob_start(); ?>
        <h1>Nuevo dueño</h1>

        <form method="post" name="nuevoDuegno" id="nuevoDuegno" class="w3-card-4 w3-padding w3-purple "
              style="width: 70%; margin: auto">
            <?= $this->campoFormTexto("nombre", "Nombre y apellido", "Ingrese nombre y apellido", true); ?>
            <?= $this->campoFormTexto("numero_tel", "Teléfono (opcional)", "Ingrese el numero de teléfono"); ?>
            <?= $this->campoFormMail(); ?>
            <?= $this->campoFormTexto("direccion", "Dirección (opcional)", "Ingrese la dirección"); ?>
            <?= $this->botonesForm("Crear dueño", "nuevoDuegno"); ?>
        </form>

        <?php return $this->pagina(ob_get_clean());
    }
    public function editar(): bool|string
    {
        ob_start(); ?>
        <h1>Editar</h1>

        <form method="post" name="editarDuegno" id="editarDuegno" class="w3-card-4 w3-padding w3-purple "
              style="width: 70%; margin: auto">

            <?= $this->campoFormTexto("nombre", "Nombre y apellido", "Ingrese nombre y apellido", true); ?>
            <?= $this->campoFormTexto("numero_tel", "Teléfono (opcional)", "Ingrese el numero de teléfono"); ?>
            <?= $this->campoFormMail(); ?>
            <?= $this->campoFormTexto("direccion", "Dirección (opcional)", "Ingrese la dirección"); ?>
            <?= $this->campoHidden("duegno_id", $_POST["id"]); ?>

            <?= $this->botonesForm("Editar dueño", "editarDuegno"); ?>
        </form>

        <?php return $this->pagina(ob_get_clean());
    }

}