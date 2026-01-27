<?php

namespace core\factory;

use core\http\CommandController;
use PDO;

final class ControllerFactory {
	public function __construct(private PDO $dbConnection, private ControllerCreatorRegistry $registry) {}
	public function create($type) : CommandController{
		return $this->registry->get($type)->create($this->dbConnection);
	}
}