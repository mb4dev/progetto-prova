<?php 

abstract class Controller {
	public function __construct() {}
	
	abstract public function resolveAction(string $action) : Response;

	protected function getBody() : array {
		$input = file_get_contents("php://input");
		$body = json_decode($input, true);
		return $body ?? [];
	}

}