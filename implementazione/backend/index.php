<?php 

require_once "Autoloader.php";

$autoloader = new Autoloader();

$autoloader->addDirectory("./core");
$autoloader->addDirectory("./core/exceptions");
$autoloader->addDirectory("./core/interfaces");
$autoloader->addDirectory("./core/model");
$autoloader->addDirectory("./auth");
$autoloader->addDirectory("./auth/interfaces");

$autoloader->addDirectory("./profile");
$autoloader->addDirectory("./profile/interfaces");

$autoloader->addDirectory("./utility");

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
