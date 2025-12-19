<?php 

require_once "Autoloader.php";

$autoloader = new Autoloader();

$autoloader->addDirectory("core");
$autoloader->addDirectory("core/controller");
$autoloader->addDirectory("core/services");
$autoloader->addDirectory("core/repository");

$autoloader->addDirectory("interfaces");
$autoloader->addDirectory("interfaces/services");
$autoloader->addDirectory("interfaces/repository");
$autoloader->addDirectory("model");
$autoloader->addDirectory("utility");
$autoloader->addDirectory("exceptions");
$autoloader->register();


if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    header("Access-Control-Allow-Origin: http://localhost:8080");
    header("Access-Control-Allow-Methods: POST, GET, OPTIONS, DELETE, PUT");
    header("Access-Control-Allow-Headers: Content-Type, Authorization, X-Requested-With");
    http_response_code(204);
    exit;
}

$connection = new PDO("sqlite:database.db");
$connection->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

$router = new DefaultRouter(new DefaultURLParser(), new ControllerFactory($connection), new JsonResponseStrategy());
$router -> dispatch();
