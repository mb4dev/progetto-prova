<?php

namespace resources;

use core\factory\interfaces\ControllerCreator;
use PDO;
use core\http\CommandController;

final class ResourceControllerCreator implements ControllerCreator {
	public function create(PDO $dbConnection): CommandController{
		$repository = new PostgreResourceRepository($dbConnection);
		$service = new StandardResourceService($repository);
		return new ResourceController($service);
	}
}