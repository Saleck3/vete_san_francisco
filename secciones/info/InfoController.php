<?php

class InfoController extends \CommonController
{
    public function inicio()
    {
        if ($this->esAdmin()) {
            phpinfo();
        }
    }


}