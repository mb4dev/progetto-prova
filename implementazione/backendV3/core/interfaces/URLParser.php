<?php

namespace core\interfaces;

use core\utility\ParsedURL;

interface URLParser {
	public function parse(string $requestUri): ParsedURL ;
}