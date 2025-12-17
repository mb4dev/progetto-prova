<?php

final class ControllerFactory {
	public function __construct() {}
	public function create($type): Controller {
		return match($type){
			ControllerTypes::AUTH => new AuthController(null),
			default => throw new InvalidArgumentException("Controller $type non esistente")
		};
	}
}