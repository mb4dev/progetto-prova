<?php

require_once __DIR__ . "/../Autoloader.php";

$autoloader = new Autoloader();
$autoloader->addDirectory(__DIR__ . "/../core");
$autoloader->addDirectory(__DIR__ . "/../core/exceptions");
$autoloader->addDirectory(__DIR__ . "/../core/interfaces");
$autoloader->addDirectory(__DIR__ . "/../core/model");
$autoloader->addDirectory(__DIR__ . "/../auth");
$autoloader->addDirectory(__DIR__ . "/../auth/interfaces");

$autoloader->addDirectory(__DIR__ . "/../profile");
$autoloader->addDirectory(__DIR__ . "/../profile/interfaces");

$autoloader->addDirectory(__DIR__ . "/../fields");
$autoloader->addDirectory(__DIR__ . "/../fields/interfaces");

$autoloader->addDirectory(__DIR__ . "/../utility");


$autoloader->register();
