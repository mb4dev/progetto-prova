<?php

namespace core\factory;

use core\di\Container;
use core\http\CommandController;
use PDO;

final class ControllerFactory {
	public function __construct(private PDO $dbConnection, private ControllerCreatorRegistry $registry) {}
	public function create($type, Container $container) : CommandController{
		
		$controllerCreator = $this->registry->get($type);

		return $container->get($type);//$controllerCreator->create($this->dbConnection);
		
	}
}