<?php

class HomeView extends CommonView
{

    /**
     * Pagina de inicio de la pagina para usuarios que no esten logueados
     *
     * @return bool|string
     */
    public function inicio(): bool|string
    {
        return $this->pagina("Bienvenido a la vete! <br>");
    }

    public function login(): bool|string
    {
        ob_start(); ?>
        <h1>Login</h1>

        <form method="post" name="login" id="login" class="w3-card-4 w3-padding w3-purple " action="/home/login"
              style="width: 70%; margin: auto">
            <?= $this->campoFormMail(); ?>
            <?= $this->campoFormPassword(); ?>
            <?= $this->botonesForm("Ingresar", "login", "/"); ?>
        </form>

        <?php return $this->pagina(ob_get_clean());
    }
}