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
$autoloader->addDirectory("exceptions");
$autoloader->register();

$_SERVER["REQUEST_URI"] = "http://localhost:8008/auth/login";
$_SERVER["REQUEST_METHOD"] = "POST";
$_POST = [
    "email" => "johndoe@example.com",
    "password" => "123",
];


$connection = new PDO("sqlite:database.db");
$connection->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

$router = new DefaultRouter(new DefaultURLParser(), new ControllerFactory($connection), new ConsoleResponseStrategy());
$router -> dispatch();
