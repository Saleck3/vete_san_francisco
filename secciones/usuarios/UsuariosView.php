<?php

class UsuariosView extends CommonView
{
    public function nuevo_usuario($roles): bool|string
    {
        ob_start(); ?>
        <h1>Nuevo usuario</h1>

        <form method="post" name="nuevoUsuario" id="nuevoUsuario" class="w3-card-4 w3-padding w3-purple "
              style="width: 70%; margin: auto">
            <?= $this->campoFormTexto("nombre", "Nombre y apellido", "Ingrese nombre y apellido", true); ?>
            <?= $this->campoFormMail(); ?>
            <?= $this->campoFormTexto("matricula", "Matricula (opcional)", "Ingrese la matricula"); ?>
            <?= $this->campoFormPassword(); ?>
            <?= $this->campoFormPassword("password_reintento", "Re ingrese clave", "Vuelva a ingresar la clave"); ?>
            <?= $this->campoSelect("rol_id", "Rol", $roles, 2, true); ?>
            <?= $this->botonesForm("Crear usuario", "nuevoUsuario"); ?>
        </form>

        <?php return $this->pagina(ob_get_clean());
    }

    public function editar($roles): bool|string
    {
        ob_start(); ?>
        <h1>Editar</h1>

        <form method="post" name="editarUsuario" id="editarUsuario" class="w3-card-4 w3-padding w3-purple " enctype="multipart/form-data"
              style="width: 70%; margin: auto">
            <?= $this->campoFormTexto("nombre", "Nombre y apellido", "Ingrese nombre y apellido", true); ?>
            <?= $this->campoFormMail(); ?>
            <?= $this->campoFormTexto("matricula", "Matricula (opcional)", "Ingrese la matricula"); ?>
            <?= $this->campoFormPassword(requerido: false); ?>
            <?= $this->campoFormPassword("password_reintento", "Re ingrese clave", "Vuelva a ingresar la clave", false); ?>
            <?= $this->campoSelect("rol_id", "Rol", $roles, 2, true); ?>
            <?= $this->campoArchivo("Foto de perfil", "foto_perfil"); ?>
            <?= $this->campoHidden("usuario_id", $_POST["id"]); ?>
            <?= $this->botonesForm("Editar usuario", "editarUsuario"); ?>
        </form>

        <?php return $this->pagina(ob_get_clean());
    }


}