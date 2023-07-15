<?php

class Logger
{
    /**
     * Agrega una linea en el error seteado en apache
     *
     * @param string|array $mensaje Si es string lo loguea tal cual, si es array hace un var_dump
     * @return void
     */
    public function logError(string|array $mensaje): void
    {
        if (is_array($mensaje)) {
            error_log(print_r($mensaje, true));
        } else {
            error_log($mensaje);
        }
    }
}