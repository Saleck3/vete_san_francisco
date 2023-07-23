<?php
set_include_path(dirname(__FILE__,2));

require_once('vendor/autoload.php');
$dotenv = Dotenv\Dotenv::createImmutable(__DIR__);
$dotenv->safeLoad();
const _MODULO = "test";

include("common/Logger.php");
include("common/CommonController.php");
include("common/CommonView.php");
include("common/CommonModel.php");