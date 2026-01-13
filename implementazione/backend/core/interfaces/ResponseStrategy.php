<?php

interface ResponseStrategy {
	public function response(Response $response): void;
}