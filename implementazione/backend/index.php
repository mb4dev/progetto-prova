<?php 

require_once "Autoloader.php";

$autoloader = new Autoloader();

$autoloader->addDirectory("./core");
$autoloader->addDirectory("./core/exceptions");
$autoloader->addDirectory("./core/middlewares");
$autoloader->addDirectory("./core/interfaces");
$autoloader->addDirectory("./core/model");

$autoloader->addDirectory("./auth");
$autoloader->addDirectory("./auth/interfaces");

$autoloader->addDirectory("./profile");
$autoloader->addDirectory("./profile/interfaces");

$autoloader->addDirectory("./fields");
$autoloader->addDirectory("./fields/interfaces");

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

$connection->exec("CREATE TABLE IF NOT EXISTS users (
    id INTEGER PRIMARY KEY AUTOINCREMENT,
    name TEXT NOT NULL,
    email TEXT NOT NULL UNIQUE,
    password TEXT NOT NULL
)");

$connection->exec("CREATE TABLE IF NOT EXISTS fields (
    id INTEGER PRIMARY KEY AUTOINCREMENT,
    sport TEXT NOT NULL,
    pricePerHour REAL NOT NULL
)");

$connection->exec("CREATE TABLE IF NOT EXISTS booking (
    id INTEGER PRIMARY KEY AUTOINCREMENT,
    fieldId INTEGER NOT NULL,
    userId INTEGER NOT NULL,
    date TEXT NOT NULL,
    slot TEXT NOT NULL UNIQUE,
    FOREIGN KEY (fieldId) REFERENCES fields(id),
    FOREIGN KEY (userId) REFERENCES users(id)
)");

$router = new DefaultRouter(new TempMiddlewareChain(), new DefaultURLParser(), new ControllerFactory($connection), new JsonResponseStrategy());
$router -> dispatch();


