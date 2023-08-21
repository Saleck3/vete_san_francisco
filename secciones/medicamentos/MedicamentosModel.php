<?php

class MedicamentosModel extends CommonModel
{
    public function listado(): ?array
    {
        $medicamentos = $this->buscar("medicamentos",null,null,null,"id");
        return $this->armarListado($medicamentos);
    }

}