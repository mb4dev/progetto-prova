<?php

namespace core\utility;
class ParsedURL {
	public function __construct(
		public string $controller,
		public string $action,
	) {}
}