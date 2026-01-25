<?php

namespace core\utility\interfaces;

use core\utility\ParsedURL;

interface URLParser {
	public function parse(): ParsedURL;
}