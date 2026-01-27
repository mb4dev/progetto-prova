<?php

namespace core\factory;

use core\http\CommandController;
use PDO;

final class ControllerFactory {
	public function __construct(private PDO $dbConnection, private ControllerCreatorRegistry $registry) {}
	public function create($type) : CommandController{
		$controllerCreator = $this->registry->get($type);

		return $controllerCreator->create($this->dbConnection);
	}
}