<?php 

require_once "Autoloader.php";

$autoloader = new Autoloader();

$autoloader->addDirectory("core");
$autoloader->addDirectory("controller");
$autoloader->addDirectory("interfaces");
$autoloader->addDirectory("model");
$autoloader->addDirectory("utility");
$autoloader->register();

$_SERVER["REQUEST_URI"] = "http://localhost:8008/auth/crea";

$router = new DefaultRouter(new DefaultURLParser(), new ControllerFactory());
$router -> dispatch();


