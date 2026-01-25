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

$autoloader->addDirectory("./bookings");
$autoloader->addDirectory("./bookings/interfaces");

$autoloader->addDirectory("./campi");
$autoloader->addDirectory("./campi/interfaces");

$autoloader->addDirectory("./sport");
$autoloader->addDirectory("./sport/interfaces");

$autoloader->addDirectory("./utility");

$autoloader->register();
if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    header("Access-Control-Allow-Origin: http://localhost:8080");
    header("Access-Control-Allow-Methods: POST, GET, OPTIONS, DELETE, PUT");
    header("Access-Control-Allow-Headers: Content-Type, Authorization, X-Requested-With");
    http_response_code(204);
    exit;
}
    

try{
    $connection = new PDO("pgsql:host=localhost;port=5432;dbname=postgres;", "postgres", "postgres");
    $connection->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    
}
catch(Exception $e){
    echo "errore connessione database";
}


$jwtManager = new MockJwtTokenManager();
$middlewareFactory = new MiddlewareFactory();
$router = new DefaultRouter(new TempMiddlewareChain(), new DefaultURLParser(), new ControllerFactory($connection), new JsonResponseStrategy(), $middlewareFactory);
$router -> dispatch();



