<?php

namespace core\http\interfaces;

use core\di\Container;
use core\factory\ControllerFactory;
use core\http\Response;
use core\utility\interfaces\URLParser;

abstract class Router {
	public function __construct(
		protected URLParser $urlParser,
		protected Container $container,
		protected ControllerFactory $factory,
		protected ResponseStrategy $responseStrategy) {}
	abstract public function dispatch();

	protected function sendResponse(Response $response): void{
		$this->responseStrategy->response($response);
	}
}
