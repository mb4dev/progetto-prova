<?php

namespace core\http\interfaces;

use core\http\Response;
use core\utility\interfaces\URLParser;

abstract class Router {
	public function __construct(
		protected URLParser $urlParser,
		protected ResponseStrategy $responseStrategy) {}
		
	abstract public function dispatch();

	protected function sendResponse(Response $response): void{
		$this->responseStrategy->response($response);
	}
}
