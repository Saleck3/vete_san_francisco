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

}