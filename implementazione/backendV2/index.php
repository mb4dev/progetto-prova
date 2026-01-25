<?php

require_once("./autoload.php");

use core\http\ControllerFactory;
use core\http\DefaultRouter;
use core\http\interfaces\ResponseStrategy;
use core\http\Response;
use core\utility\DefaultURLParser;
use core\http\HttpResponse;

try{
    $connection = new PDO("pgsql:host=localhost;port=5432;dbname=postgres;", "postgres", "postgres");
    $connection->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
}
catch(Exception $e){
    echo "errore connessione database";
}

class TempResponseStrategy implements ResponseStrategy{
    public function response(Response $response): void{
    }
}

$router = new DefaultRouter(
    new DefaultURLParser(),
    new ControllerFactory($connection),
    new HttpResponse() 
);


$router->dispatch();