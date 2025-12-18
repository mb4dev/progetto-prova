<?php 

require_once "Autoloader.php";

$autoloader = new Autoloader();

$autoloader->addDirectory("core");
$autoloader->addDirectory("controller");
$autoloader->addDirectory("interfaces");
$autoloader->addDirectory("interfaces/services");
$autoloader->addDirectory("interfaces/repository");
$autoloader->addDirectory("model");
$autoloader->addDirectory("utility");
$autoloader->addDirectory("services");
$autoloader->addDirectory("repository");
$autoloader->register();

$_SERVER["REQUEST_URI"] = "http://localhost:8008/auth/register";
$_SERVER["REQUEST_METHOD"] = "POST";
$_POST = [
    "name" => "johndoe",
    "email" => "john.doe@example.com",
    "password" => "123",
];


$connection = new PDO("sqlite::memory:");

$router = new DefaultRouter(new DefaultURLParser(), new ControllerFactory($connection), new ConsoleResponseStrategy());
$router -> dispatch();
