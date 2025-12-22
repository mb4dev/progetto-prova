<?php 

abstract class Controller {
	public function __construct() {}
	
	abstract public function resolveAction(string $action) : Response;
	
	protected function getBody(): array {
		if ($_SERVER['CONTENT_TYPE'] ?? '' === 'application/json') {
			$input = file_get_contents('php://input');
			return json_decode($input, true) ?? [];
		}
		
		return $_POST;
	}
}