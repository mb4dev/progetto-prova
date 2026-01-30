<?php

namespace core\http;

use core\exceptions\CustomException;

final class StandardRouter extends Router {
	public function dispatch(){
		$requestUri = $_SERVER["REQUEST_URI"] ?? throw new CustomException("request uri mancate", 400);

		$parserdUrl = $this->urlParser->parse($requestUri);

		$controllerClass = ControllerType::tryFrom	($parserdUrl->controller)->getClass();
		$controller = $this->factory->get($controllerClass);

		$response= $controller->resolveAction($parserdUrl->action);

		$this->sendResponse($response);
	}
}