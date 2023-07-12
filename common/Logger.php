<?php

class Logger
{
    /**
     * @param array|string $mensaje
     * @return void
     */
    public function logError(array|string $mensaje): void
    {
        if (is_array($mensaje)) {
            error_log(print_r($mensaje, true));
        } else {
            error_log($mensaje);
        }
    }
}