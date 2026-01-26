<?php

namespace core\http;

use core\exceptions\ValidationException;
use core\http\interfaces\ResponseStrategy;
use core\http\interfaces\Router;
use core\utility\ControllerFactory;
use core\utility\interfaces\URLParser;

final class DefaultRouter extends Router {
	public function __construct(
		URLParser $urlParser, 
		ControllerFactory $controllerFactory, 
		ResponseStrategy $responseStrategy) {
		parent::__construct($urlParser, $controllerFactory, $responseStrategy);
	}

	public function dispatch(): void {
		$parsedURL = $this->urlParser->parse();
		$controllerType = ControllerTypes::tryFrom(strtolower($parsedURL->controller));

		if ($controllerType === null) 
			throw new ValidationException("controller non trovato", 404);

		$controller = $this->controllerFactory->create($controllerType);
		$response = $controller->resolveAction($parsedURL->action);
		$this->sendResponse($response);
	}
}