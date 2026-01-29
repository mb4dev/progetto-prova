<?php

namespace core\http;

use core\di\Container;
use core\exceptions\CustomException;
use core\http\interfaces\ResponseStrategy;
use core\http\interfaces\Router;
use core\utility\interfaces\URLParser;

/**
 * Router che gestisce le request HTTP
 * 
 * Usa il Container per ottenere i controller (registrati tramite Factory Method)
 */
final class DefaultRouter extends Router {
	
	public function __construct(
		URLParser $urlParser, 
		private Container $container,  // Container DI
		ResponseStrategy $responseStrategy) {
		parent::__construct($urlParser, $responseStrategy);
	}
	
	public function dispatch(): void {
		$parsedURL = $this->urlParser->parse();
		$controllerType = ControllerTypes::tryFrom(strtolower($parsedURL->controller));

		if ($controllerType === null) 
			throw new CustomException("controller non trovato", 404);

		// Ottiene il controller dal Container
		// (registrato tramite il Factory Method del controller)
		$controller = $this->container->get($controllerType->value);
		$response = $controller->resolveAction($parsedURL->action);
		$this->sendResponse($response);
	}
}