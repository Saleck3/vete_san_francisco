<?php

class DuegnosController extends CommonController
{
    public function inicio(): void
    {
        $duegnos = $this->model->listado();
        echo $this->view->tabla($duegnos, "Dueños", "Listado de todos los dueños", );
    }
}