<?php
session_start();

//Traemos las variables de entorno
require_once('vendor/autoload.php');
/**
 * @var Dotenv\Dotenv $dotenv Variable que contiene el parser de .env
 * https://github.com/vlucas/phpdotenv
 */
$dotenv = Dotenv\Dotenv::createImmutable(__DIR__);
$dotenv->safeLoad();

$dotenv->required('DEBUG')->isBoolean();
$_ENV["DEBUG"] = boolval($_ENV["DEBUG"]);

if ($_ENV["DEBUG"]) {
    ini_set('display_errors', 1);
    ini_set('display_startup_errors', 1);
    error_reporting(E_ALL);
}

define("_MODULO", !empty($_GET["modulo"]) ? htmlspecialchars($_GET["modulo"]) : $_ENV["MODULO_DEFAULT"]);
define("_ACCION", !empty($_GET["accion"]) ? htmlspecialchars($_GET["accion"]) : $_ENV["ACCION_DEFAULT"]);


$controller = ucfirst(_MODULO) . "Controller";
$accion = _ACCION;

require_once($_SERVER["DOCUMENT_ROOT"] . "/common/CommonController.php");
require_once($_SERVER["DOCUMENT_ROOT"] . "/common/CommonModel.php");
require_once($_SERVER["DOCUMENT_ROOT"] . "/common/CommonView.php");
require_once($_SERVER["DOCUMENT_ROOT"] . "/common/Logger.php");

if (file_exists($_SERVER["DOCUMENT_ROOT"] . "secciones/" . strtolower(_MODULO) . "/$controller.php")) {
    require_once($_SERVER["DOCUMENT_ROOT"] . "secciones/" . strtolower(_MODULO) . "/$controller.php");
    $controller = new $controller();
} else {
    require_once($_ENV["PAGINA_404"]);
    die;
}


if (method_exists($controller, $accion)) {
    $controller->$accion();
} else {
    require_once($_ENV["PAGINA_404"]);
    die;
}