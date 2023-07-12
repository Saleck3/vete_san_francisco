<?php
session_start();

include_once("config/entorno.php");

if(_DEBUG){
    ini_set('display_errors', 1);
    ini_set('display_startup_errors', 1);
    error_reporting(E_ALL);
}

define("_MODULO", !empty($_GET["modulo"]) ? htmlspecialchars($_GET["modulo"]): _MODULO_DEFAULT);
define("_ACCION", !empty($_GET["accion"]) ? htmlspecialchars($_GET["accion"]): _ACCION_DEFAULT);


$controller = ucfirst(_MODULO) . "Controller";
$accion = _ACCION;

require_once($_SERVER["DOCUMENT_ROOT"] . "/common/CommonController.php" );
require_once($_SERVER["DOCUMENT_ROOT"] . "/common/CommonModel.php" );
require_once($_SERVER["DOCUMENT_ROOT"] . "/common/CommonView.php" );
require_once($_SERVER["DOCUMENT_ROOT"] . "/common/Logger.php" );

if (file_exists($_SERVER["DOCUMENT_ROOT"] . "secciones/" . strtolower(_MODULO) . "/$controller.php")) {
    require_once($_SERVER["DOCUMENT_ROOT"] . "secciones/" . strtolower(_MODULO) . "/$controller.php");
    $controller = new $controller();
} else {
    require_once(_PAGINA_404);
    die;
}


if(method_exists($controller,$accion)) {
    $controller->$accion();
}else{
    require_once(_PAGINA_404);
    die;
}