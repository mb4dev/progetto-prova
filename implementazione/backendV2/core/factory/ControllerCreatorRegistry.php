<?php

namespace core\factory;

use core\factory\interfaces\ControllerCreator;
use core\http\ControllerTypes;
use InvalidArgumentException;

final class ControllerCreatorRegistry {
	private array $creators = [];

	public function __construct(){
	}

	public function register(ControllerTypes $type, ControllerCreator $controller){
		$this->creators[$type->value] = $controller;
	}

	public function get(ControllerTypes $type):  ControllerCreator {
		$key = $type->value;
		if (!isset($this->creators[$key])) throw new InvalidArgumentException("Controller {$type->value} non esistente");

		return $this->creators[$key];
	}
}