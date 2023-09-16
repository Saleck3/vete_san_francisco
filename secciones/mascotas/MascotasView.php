<?php

class MascotasView extends CommonView
{
    public function nueva_mascota($raza, $especie): bool|string
    {
        ob_start(); ?>
        <h1>Nueva mascota</h1>

        <form method="post" name="nuevaMascota" id="nuevaMascota" class="w3-card-4 w3-padding w3-purple "
              style="width: 70%; margin: auto">
            <?= $this->campoFormTexto("nombre", "Nombre", "Ingrese nombre de la mascota", true); ?>
            <?= $this->campoSelect("raza_id", "Raza", $raza, 2, true); ?>
            <?= $this->campoSelect("especie_id", "Especie", $especie, 2, true); ?>
            <?= $this->campoFormTexto("color", "Color (opcional)", "Ingrese el color"); ?>
            <?= $this->campoFormTexto("peso", "Peso (opcional)", "Ingrese el peso"); ?>
            <?=$this->campoFormDate("fnac", "Fecha de Nacimiento", "Ingrese la fecha")?>

            <?= $this->botonesForm("Crear mascota", "nuevaMascota"); ?>
        </form>



        <?php return $this->pagina(ob_get_clean());
    }

    public function editar($raza, $especie): bool|string
    {
        ob_start(); ?>
        <h1>Editar</h1>

        <form method="post" name="editarMascota" id="editarMascota" class="w3-card-4 w3-padding w3-purple "
              style="width: 70%; margin: auto">

            <?= $this->campoFormTexto("nombre", "Nombre", "Ingrese nombre de la mascota", true); ?>
            <?= $this->campoSelect("raza_id", "Raza", $raza, null, true); ?>
            <?= $this->campoSelect("especie_id", "Especie", $especie, null, true); ?>
            <?= $this->campoFormTexto("color", "Color (opcional)", "Ingrese el color"); ?>
            <?= $this->campoFormTexto("peso", "Peso (opcional)", "Ingrese el peso"); ?>
            <?=$this->campoFormDate("fnac", "Fecha de Nacimiento", "Ingrese la fecha")?>
            <?=$this->campoFormCheck("muerto", "Murió?")?>

            <?= $this->campoHidden("mascota_id", $_POST["id"]); ?>



            <?= $this->botonesForm("Editar mascota", "editarMascota"); ?>
        </form>

        <?php return $this->pagina(ob_get_clean());
    }

}

