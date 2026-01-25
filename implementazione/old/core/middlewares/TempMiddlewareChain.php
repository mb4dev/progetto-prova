<?php

final class TempMiddlewareChain implements MiddlewareChain {

	public function __construct(private array $middlewares = []) {}
	public function addMiddleware(Middleware $middleware): void {
		$this->middlewares[] = $middleware;
	}

	public function execute(): ?Response {
		foreach ($this->middlewares as $middleware) {
			$middleware->handle();
		}
		return null;
	}
}