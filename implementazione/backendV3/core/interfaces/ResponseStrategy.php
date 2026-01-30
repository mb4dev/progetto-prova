<?php

namespace core\interfaces;
use core\http\Response;

interface ResponseStrategy {
	public function response(Response $response): void;
}