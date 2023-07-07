<?php

class CommonModel
{
    protected mixed $db;

    public function __construct()
    {
        require_once($_SERVER["DOCUMENT_ROOT"] . "/common/" . _DB_CLASE . ".php");
        $this->db = _DB_CLASE;
        $this->db = new $this->db();
    }
}