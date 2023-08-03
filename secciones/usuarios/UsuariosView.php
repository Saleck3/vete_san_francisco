<?php

class UsuariosView extends CommonView
{
    public function nuevo_usuario($roles): bool|string
    {
        ob_start(); ?>
        <h1>Nuevo usuario</h1>

        <form method="post" name="nuevoUsuario" id="nuevoUsuario" class="w3-card-4 w3-padding w3-purple "
              style="width: 70%; margin: auto">
            <?= $this->campo_form_texto("nombre", "Nombre y apellido", "Ingrese nombre y apellido", true); ?>
            <?= $this->campo_form_mail(); ?>
            <?= $this->campo_form_texto("matricula", "Matricula (opcional)", "Ingrese la matricula"); ?>
            <?= $this->campo_form_password(); ?>
            <?= $this->campo_form_password("password_reintento", "Re ingrese clave", "Vuelva a ingresar la clave"); ?>
            <?= $this->campo_select("rol", "Rol", $roles,2,true); ?>
            <?= $this->botones_form("Crear usuario","nuevoUsuario"); ?>
        </form>

        <?php return $this->pagina(ob_get_clean());
    }




}