<?php

final class DefaultRouter extends Router {
	public function __construct(URLParser $urlParser, ControllerFactory $controllerFactory, ResponseStrategy $responseStrategy) {
		parent::__construct($urlParser, $controllerFactory, $responseStrategy);
	}

	public function dispatch(): void {
		$parsedURL = $this->urlParser->parse();
		$controllerType = ControllerTypes::tryFrom(strtolower($parsedURL->controller));
		if ($controllerType === null) {
			$this->sendResponse(new Response(404, false, ["error" => "Controller non trovato"]));
			return;
		}
		
		$controller = $this->controllerFactory->create($controllerType);
		$response = $controller->resolveAction($parsedURL->action);

		$this->sendResponse($response);
	}
}