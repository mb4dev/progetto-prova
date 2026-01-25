<?php

interface MiddlewareChain {
	public function addMiddleware(Middleware $middleware): void;
	public function execute(): ?Response;
}
