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

$_SERVER["REQUEST_URI"] = "http://localhost:8008/auth/register";
$_SERVER["REQUEST_METHOD"] = "POST";
$_POST = [
    "email" => "johndoe@example.com",
    "password" => "123",
    "name" => "John Doe"
];

$connection = new PDO("sqlite:database.db");
$connection->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

$router = new DefaultRouter(new DefaultURLParser(), new ControllerFactory($connection), new ConsoleResponseStrategy());
$router -> dispatch();
