<?php

namespace core\http\interfaces;
use core\http\Response;

interface ResponseStrategy {
	public function response(Response $response): void;
}