<?php 

namespace core\factory\interfaces;

use core\http\CommandController;
use PDO;

interface ControllerCreator{
	public function create(PDO $dbConnection) : CommandController;
}