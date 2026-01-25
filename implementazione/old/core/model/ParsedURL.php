<?php

class ParsedURL {
	public function __construct(
		public string $controller,
		public string $action,
		//public array $params
	) {}
}