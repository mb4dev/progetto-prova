<?php

namespace core\http;

use core\exceptions\CustomException;

final class StandardRouter extends Router {
	public function dispatch(){
		$requestUri = $_SERVER["REQUEST_URI"] ?? throw new CustomException("request uri mancate", 400);

		$parserdUrl = $this->urlParser->parse($requestUri);


		$this->sendResponse(new Response(200, true, [$parserdUrl]));
	}
}
/*
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

*/

/*
final class DefaultRouter extends Router {
	public function __construct(
		URLParser $urlParser, 
		Container $container, 
		ControllerFactory $factory,
		ResponseStrategy $responseStrategy) {
		parent::__construct($urlParser, $container, $factory, $responseStrategy);
	}
	public function dispatch(): void {
		$parsedURL = $this->urlParser->parse();
		$controllerType = ControllerTypes::tryFrom(strtolower($parsedURL->controller));

		if ($controllerType === null) 
			throw new ValidationException("controller non trovato", 404);

		$controller = $this->factory->create($controllerType, $this->container);
		$response = $controller->resolveAction($parsedURL->action);
		$this->sendResponse($response);
	}
}
	*/