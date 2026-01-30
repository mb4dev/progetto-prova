<?php

namespace core\http;

use core\factory\Factory;
use core\http\Response;
use core\interfaces\ResponseStrategy;
use core\interfaces\URLParser;

abstract class Router {

	public function __construct(
		protected Factory $factory,
		protected URLParser $urlParser,
		protected ResponseStrategy $responseStrategy) {}
	abstract public function dispatch();


	protected function sendResponse(Response $response): void{
		$this->responseStrategy->response($response);
	}
}