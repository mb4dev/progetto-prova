<?php

namespace resources;

use core\factory\interfaces\ControllerCreator;
use PDO;
use core\http\CommandController;

final class ResourceControllerCreator implements ControllerCreator {
	public function create(PDO $dbConnection): CommandController{
		$fieldRepo = new PostgreFieldsRepository($dbConnection);
		$coursesRepo = new PostgreCoursesRepository($dbConnection);
		$service = new StandardResourceService($fieldRepo, $coursesRepo);
		return new ResourceController($service);
	}
}