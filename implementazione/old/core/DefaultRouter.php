<?php

final class DefaultRouter extends Router {
	public function __construct(
		MiddlewareChain $chain, 
		URLParser $urlParser, 
		ControllerFactory $controllerFactory, 
		ResponseStrategy $responseStrategy, 
		private MiddlewareFactory $middlewareFactory) {
		parent::__construct($chain, $urlParser, $controllerFactory, $responseStrategy);
	}

	public function dispatch(): void {
		$parsedURL = $this->urlParser->parse();
		$controllerType = ControllerTypes::tryFrom(strtolower($parsedURL->controller));
		if ($controllerType === null) {
			$this->sendResponse(new Response(404, false, ["error" => "Controller non trovato"]));
			return;
		}
		
		$controller = $this->controllerFactory->create($controllerType);

		foreach ($controller->getMiddlewares() as $middlewareClass) {
			$this->chain->addMiddleware($this->middlewareFactory->create($middlewareClass));
		}

		$response = $this->chain->execute();
		if ($response !== null) {
			$this->sendResponse($response);
			return;
		}


		$response = $controller->resolveAction($parsedURL->action);
		$this->sendResponse($response);
	}
}