<?php

abstract class Router {
	public function __construct(
		protected URLParser $urlParser,
		protected ControllerFactory $controllerFactory) {}
	abstract public function dispatch();

	protected function sendResponse(Response $response): void{
		//header("HTTP/1.1 " . $response->code);
		//header("Content-Type: application/json");
		//echo $response->jsonData;
		print_r($response);
	}
}