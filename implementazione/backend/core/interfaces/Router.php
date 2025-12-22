<?php

abstract class Router {
	public function __construct(
		protected URLParser $urlParser,
		protected ControllerFactory $controllerFactory,
		protected ResponseStrategy $responseStrategy) {}
	abstract public function dispatch();

	protected function sendResponse(Response $response): void{
		$this->responseStrategy->response($response);
	}
}