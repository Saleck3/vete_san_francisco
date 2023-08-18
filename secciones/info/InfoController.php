<?php

class InfoController extends CommonController
{
    public function inicio()
    {
        if (esAdmin()) {
            phpinfo();
        }
    }


}